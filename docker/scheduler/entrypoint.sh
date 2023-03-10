#!/bin/bash

if [ "$APP_ENV" != "local" ]; then

    # Caches
    php /var/www/html/artisan config:cache
    php /var/www/html/artisan route:cache
    php /var/www/html/artisan view:cache
fi

# Run scheduler
while [ true ]
do
  php /var/www/html/artisan schedule:run
  sleep 60
done
