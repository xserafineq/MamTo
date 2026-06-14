#!/bin/bash
set -e

# Copy .env.example to .env if it does not exist
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Dependencies must be installed before any artisan command (package discovery cache).
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Drop stale provider cache after dependency changes (e.g. new packages after merge).
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php

# Ensure APP_KEY is set in .env
if ! grep -q "APP_KEY=base64:" .env || [ -z "$(grep APP_KEY= .env | cut -d'=' -f2)" ]; then
    echo "Generating Laravel application key..."
    php artisan key:generate --no-interaction --force
fi

# Wait for PostgreSQL database connection
echo "Waiting for PostgreSQL database to become available..."
php -r '
$host = getenv("DB_HOST") ?: "127.0.0.1";
$port = getenv("DB_PORT") ?: "5432";
$db   = getenv("DB_DATABASE") ?: "laravel";
$user = getenv("DB_USERNAME") ?: "root";
$pass = getenv("DB_PASSWORD") ?: "";

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

for ($i = 0; $i < 30; $i++) {
    try {
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "Database connected successfully!\n";
        exit(0);
    } catch (PDOException $e) {
        echo "Waiting for database connection... (" . $e->getMessage() . ")\n";
        sleep(2);
    }
}
echo "Could not connect to database after 60 seconds!\n";
exit(1);
'

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Public link to storage (uploaded images)
php artisan storage:link --force

# Execute the main command (e.g. apache2-foreground)
exec "$@"
