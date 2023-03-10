#!/bin/bash

if [ "$APP_ENV" != "staging" ]; then

    php /var/www/html/artisan custom:create-queue-rabbitmq
fi

if [ "$APP_ENV" != "local" ]; then

    # Caches
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache
    php /var/www/html/artisan view:cache
fi

# PHP FPM
docker-php-entrypoint php-fpm
