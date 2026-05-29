#!/usr/bin/env bash
set -o errexit

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations on your Aiven Database
php artisan migrate --force

# Start Nginx & PHP-FPM (required by the base image to keep the server running)
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf