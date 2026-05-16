@echo off
echo.
echo  IoT Smart Home Dashboard - Windows Setup
echo  ==========================================
echo.

where php >nul 2>&1 || (echo PHP not found. Install PHP 8.2+ first. & pause & exit)
where composer >nul 2>&1 || (echo Composer not found. Download from https://getcomposer.org & pause & exit)

echo [1/6] Installing PHP dependencies...
call composer install --no-interaction --prefer-dist

echo [2/6] Creating .env file...
if not exist .env copy .env.example .env

echo [3/6] Generating app key...
php artisan key:generate

echo.
echo  Configure your database in .env before continuing!
echo  Open .env and set: DB_DATABASE, DB_USERNAME, DB_PASSWORD
echo.
pause

echo [4/6] Running migrations...
php artisan migrate --force

echo [5/6] Seeding sample data...
php artisan db:seed --force

echo [6/6] Creating storage link...
php artisan storage:link

echo.
echo  ==========================================
echo  Setup complete!
echo.
echo  Credentials:
echo    Admin : admin@iot.com  / password
echo    User  : alice@iot.com  / password
echo    User  : bob@iot.com    / password
echo.
echo  Run: php artisan serve
echo  Open: http://localhost:8000
echo  ==========================================
pause
