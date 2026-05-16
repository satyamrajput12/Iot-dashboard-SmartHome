🏠 IoT Smart Home Dashboard

A modern IoT Device Management Dashboard built with Laravel 11, PHP 8.2, MySQL, and Bootstrap 5.
It includes secure authentication, admin/user roles, real-time device control, analytics, approval workflows, device logs, AJAX updates, and responsive UI support.

✨ Features
🔐 Authentication & Role-Based Access (Admin/User)
📱 Smart device management
⚡ Real-time AJAX device controls
📊 Dashboard analytics with Chart.js
🛡️ Device approval & monitoring system
👥 User management for admins
🔍 Advanced filtering & search
📜 Activity logs & troubleshooting tools
🚀 Quick Setup
composer install
cp .env.example .env
php artisan key:generate

Configure database in .env:

DB_DATABASE=iot_dashboard
DB_USERNAME=root
DB_PASSWORD=

Run:

php artisan migrate --seed
php artisan storage:link
php artisan serve

Open:

http://localhost:8000
🔑 Demo Login
Role	Email	Password
Admin	admin@iot.com	password
User	alice@iot.com	password
🛠️ Tech Stack
Laravel 11
PHP 8.2
MySQL 8
Bootstrap 5
Chart.js
Fetch API (AJAX)
📂 Core Modules
User Dashboard
Admin Panel
Device Management
Device Logs
Analytics & Charts
Authentication System
📌 Useful Commands
php artisan migrate:fresh --seed
php artisan optimize:clear
php artisan route:list
