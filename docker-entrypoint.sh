#!/bin/bash
set -e

echo "Running database migrations..."
php artisan migrate --force --verbose

echo "Clearing configuration cache..."
php artisan config:clear

echo "Starting Laravel server..."
php artisan serve --host 0.0.0.0 --port ${PORT:-10000}
