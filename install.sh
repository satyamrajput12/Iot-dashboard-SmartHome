#!/bin/bash
# ============================================================
# IoT Dashboard — Automated Setup Script
# Run: bash install.sh
# ============================================================

set -e

echo ""
echo "🏠  IoT Smart Home Dashboard — Setup"
echo "======================================"
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo "❌  PHP not found. Install PHP 8.2+ first."
    exit 1
fi

PHP_VER=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo "✅  PHP $PHP_VER found"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "❌  Composer not found. Install from https://getcomposer.org"
    exit 1
fi
echo "✅  Composer found"

# Install dependencies
echo ""
echo "📦  Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

# Copy .env
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅  .env file created"
fi

# Generate key
php artisan key:generate
echo "✅  App key generated"

# Prompt for DB config
echo ""
echo "🗄️   Database Configuration"
echo "----------------------------"
read -p "DB Host [127.0.0.1]: " DB_HOST
DB_HOST=${DB_HOST:-127.0.0.1}
read -p "DB Port [3306]: " DB_PORT
DB_PORT=${DB_PORT:-3306}
read -p "DB Name [iot_dashboard]: " DB_DATABASE
DB_DATABASE=${DB_DATABASE:-iot_dashboard}
read -p "DB Username [root]: " DB_USERNAME
DB_USERNAME=${DB_USERNAME:-root}
read -s -p "DB Password [leave empty]: " DB_PASSWORD
echo ""

# Update .env
sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

echo "✅  Database config updated"

# Run migrations
echo ""
echo "🔄  Running database migrations..."
php artisan migrate --force

# Seed
echo ""
echo "🌱  Seeding sample data..."
php artisan db:seed --force

# Storage link
php artisan storage:link 2>/dev/null || true

echo ""
echo "======================================"
echo "✅  Setup complete!"
echo ""
echo "🔑  Login Credentials:"
echo "   Admin → admin@iot.com   / password"
echo "   User  → alice@iot.com   / password"
echo "   User  → bob@iot.com     / password"
echo ""
echo "🚀  Start the server:"
echo "   php artisan serve"
echo ""
echo "   Then open: http://localhost:8000"
echo "======================================"
