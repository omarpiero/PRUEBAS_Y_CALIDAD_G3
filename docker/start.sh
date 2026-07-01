#!/bin/bash

# Replace Nginx port with Railway's PORT env variable
sed -i "s/\${PORT:-8080}/${PORT:-8080}/g" /etc/nginx/sites-available/default

# Create log directory for supervisor
mkdir -p /var/log/supervisor

# Run Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (safe for production with --force)
php artisan migrate --force 2>/dev/null || true

# Create storage link
php artisan storage:link 2>/dev/null || true

# Start Supervisor (manages PHP-FPM, Nginx, and Queue)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
