# 🏠 IoT Smart Home Dashboard
### Laravel 11 + PHP 8.2 + MySQL + Bootstrap 5

A complete IoT Device Management Dashboard for Smart Homes with Role-Based Access Control (Admin/User), real-time device control via AJAX, device approval workflow, logs/troubleshooting, charts, and more.

---

## 📋 Table of Contents
1. [Requirements](#requirements)
2. [Quick Setup](#quick-setup)
3. [Database Schema](#database-schema)
4. [Login Credentials](#login-credentials)
5. [Features Overview](#features-overview)
6. [Project Structure](#project-structure)
7. [Routes Reference](#routes-reference)

---

## ✅ Requirements

| Tool        | Version     |
|-------------|-------------|
| PHP         | >= 8.2      |
| Composer    | >= 2.x      |
| MySQL       | >= 8.0      |
| Node.js     | >= 18 (optional, no frontend build needed) |

---

## 🚀 Quick Setup (Step by Step)

### Step 1 — Clone / Extract the project
```bash
cd /your/projects/folder
# If from zip:
unzip iot-dashboard.zip
cd iot-dashboard
```

### Step 2 — Install PHP dependencies
```bash
composer install
```
> If Composer is not installed: https://getcomposer.org/download/

### Step 3 — Create environment file
```bash
cp .env.example .env
```

### Step 4 — Generate application key
```bash
php artisan key:generate
```

### Step 5 — Configure your database in `.env`
Open `.env` and update these lines:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iot_dashboard      # <-- your database name
DB_USERNAME=root               # <-- your MySQL username
DB_PASSWORD=                   # <-- your MySQL password
```

### Step 6 — Create the MySQL database
```sql
-- In MySQL / phpMyAdmin / TablePlus:
CREATE DATABASE iot_dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 7 — Run migrations (creates all tables)
```bash
php artisan migrate
```

### Step 8 — Seed sample data
```bash
php artisan db:seed
```
This creates:
- 1 Admin user
- 2 Regular users (Alice, Bob)
- 9 sample devices (thermostats, lights, alarms)
- Device logs and activity history

### Step 9 — Create storage symlink
```bash
php artisan storage:link
```

### Step 10 — Start the development server
```bash
php artisan serve
```
Open your browser: **http://localhost:8000**

---

## 🔑 Login Credentials

| Role  | Email           | Password   |
|-------|-----------------|------------|
| Admin | admin@iot.com   | password   |
| User  | alice@iot.com   | password   |
| User  | bob@iot.com     | password   |

---

## 🗄️ Database Schema

### `users` table
| Column       | Type      | Notes                        |
|--------------|-----------|------------------------------|
| id           | bigint    | Primary key                  |
| name         | string    | Full name                    |
| email        | string    | Unique, used for login       |
| password     | string    | Hashed (bcrypt)              |
| role         | enum      | `admin` or `user`            |
| is_active    | boolean   | Account enabled/disabled     |
| created_at   | timestamp |                              |

### `devices` table
| Column            | Type      | Notes                              |
|-------------------|-----------|------------------------------------|
| id                | bigint    | Primary key                        |
| user_id           | bigint    | Foreign key → users                |
| name              | string    | Device display name                |
| type              | enum      | `thermostat`, `light`, `alarm`     |
| location          | string    | Room/area name                     |
| status            | enum      | `on` or `off`                      |
| temperature       | decimal   | For thermostats (°C)               |
| approval_status   | enum      | `pending`, `approved`, `rejected`  |
| rejection_reason  | text      | Admin note when rejected           |
| device_id         | string    | Unique hardware ID (e.g. IOT-XXXX) |
| description       | text      | Optional notes                     |
| last_seen         | timestamp | Last activity time                 |

### `device_logs` table
| Column      | Type      | Notes                                  |
|-------------|-----------|----------------------------------------|
| id          | bigint    | Primary key                            |
| device_id   | bigint    | Foreign key → devices                  |
| user_id     | bigint    | Who triggered this event               |
| log_type    | enum      | `info`, `warning`, `error`, `control`  |
| action      | string    | Short action name                      |
| message     | text      | Full description                       |
| ip_address  | string    | Source IP address                      |
| metadata    | json      | Extra data (temperatures, etc.)        |
| created_at  | timestamp |                                        |

---

## 🎯 Features Overview

### 🔐 Authentication
- User registration and login
- Secure logout with session invalidation
- Remember Me functionality
- Account deactivation enforcement

### 👤 User Dashboard
- Overview stats (total, approved, pending, active devices)
- Device grid with live toggle controls
- Filter by type, room, search by name/ID
- AJAX toggle ON/OFF without page reload
- Real-time status polling every 30 seconds
- Thermostat temperature control modal
- Recent activity log feed

### 📱 Device Management (User)
- Register new devices (name, type, location, description)
- Auto-generated unique Device ID (e.g. IOT-A1B2C3D4)
- Edit and delete devices
- View full device log history
- Devices start as **pending** until admin approves

### 🛡️ Admin Panel
- Overview dashboard with Chart.js analytics
  - Donut chart: device type distribution
  - Bar chart: approval status breakdown
- Pending approvals list with one-click Approve/Reject
- View ALL devices across all users
- Simulate device errors for testing
- Full device log viewer with type/device filters

### 👥 User Management (Admin)
- View all registered users
- Activate/deactivate user accounts
- Delete users (cascades to their devices)

### 🔧 Troubleshooting Module
- Logs for every device action (control, info, warning, error)
- Admin can simulate random errors on any device
- Filter logs by type, device, or keyword
- Log stats summary (counts per type)

### 🔍 Filtering & Search
- Filter devices by: type, location, approval status, owner
- Full-text search on name, device ID, location
- Paginated results with preserved query strings

---

## 📁 Project Structure

```
iot-dashboard/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminController.php       # Admin panel logic
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php        # Login/Register/Logout
│   │   │   └── User/
│   │   │       ├── DeviceController.php       # Device CRUD + AJAX toggle
│   │   │       └── UserDashboardController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php            # Block non-admins
│   │       └── CheckActiveUser.php            # Block deactivated accounts
│   ├── Models/
│   │   ├── Device.php
│   │   ├── DeviceLog.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/                            # 5 migration files
│   └── seeders/
│       └── DatabaseSeeder.php                 # Sample data
├── resources/views/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── devices/index.blade.php
│   │   ├── users/index.blade.php
│   │   └── logs.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── layouts/
│   │   ├── app.blade.php                      # Main sidebar layout
│   │   └── auth.blade.php                     # Login/register layout
│   └── user/
│       ├── dashboard.blade.php
│       └── devices/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php
└── routes/
    └── web.php                                # All routes
```

---

## 🌐 Routes Reference

### Public
| Method | URL        | Action         |
|--------|------------|----------------|
| GET    | /login     | Show login form |
| POST   | /login     | Authenticate   |
| GET    | /register  | Show register  |
| POST   | /register  | Create account |
| POST   | /logout    | Logout         |

### User (auth required)
| Method | URL                                  | Action                    |
|--------|--------------------------------------|---------------------------|
| GET    | /dashboard                           | User dashboard            |
| GET    | /dashboard/devices                   | List user's devices       |
| GET    | /dashboard/devices/create            | Add device form           |
| POST   | /dashboard/devices                   | Save new device           |
| GET    | /dashboard/devices/{id}/edit         | Edit form                 |
| PUT    | /dashboard/devices/{id}              | Update device             |
| DELETE | /dashboard/devices/{id}              | Delete device             |
| GET    | /dashboard/devices/{id}              | Device logs (show)        |
| POST   | /dashboard/devices/{id}/toggle       | AJAX toggle ON/OFF        |
| POST   | /dashboard/devices/{id}/temperature  | AJAX set temperature      |
| GET    | /dashboard/devices/statuses/live     | AJAX poll all statuses    |

### Admin (admin role required)
| Method | URL                                  | Action                    |
|--------|--------------------------------------|---------------------------|
| GET    | /admin                               | Admin dashboard           |
| GET    | /admin/devices                       | All devices               |
| PATCH  | /admin/devices/{id}/approve          | Approve device            |
| PATCH  | /admin/devices/{id}/reject           | Reject device             |
| DELETE | /admin/devices/{id}                  | Delete device             |
| POST   | /admin/devices/{id}/simulate-error   | Simulate error log        |
| GET    | /admin/users                         | Manage users              |
| PATCH  | /admin/users/{id}/toggle             | Activate/deactivate user  |
| DELETE | /admin/users/{id}                    | Delete user               |
| GET    | /admin/logs                          | View all logs             |

---

## 🛠️ Common Commands

```bash
# Reset database and reseed
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# View all routes
php artisan route:list

# Run with custom port
php artisan serve --port=8080
```

---

## 💡 Tech Stack

- **Backend:** Laravel 11, PHP 8.2
- **Database:** MySQL 8 with Eloquent ORM
- **Frontend:** Blade Templates, Bootstrap 5.3, Bootstrap Icons
- **Charts:** Chart.js 4
- **AJAX:** Vanilla Fetch API (no jQuery needed)
- **Auth:** Laravel built-in session authentication
- **Middleware:** Custom Admin + ActiveUser middleware

---

*Built with ❤️ as a beginner-friendly IoT dashboard project.*
#   I o t - d a s h b o a r d - S m a r t H o m e  
 #   I o t - d a s h b o a r d - S m a r t H o m e  
 