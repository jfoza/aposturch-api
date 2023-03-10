#!/bin/bash

if [ "$APP_ENV" != "local" ]; then

    # Caches
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache
    php /var/www/html/artisan view:cache
fi

# Horizon
php /var/www/html/artisan horizon
