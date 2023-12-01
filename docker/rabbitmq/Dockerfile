FROM composer:2 as vendor

COPY ./composer.json ./composer.lock ./

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

FROM php:8.2-cli

WORKDIR /src/app

ENV TZ=America/Sao_Paulo

RUN docker-php-ext-install bcmath \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY --chown=www-data:www-data --from=vendor /app/vendor/ /src/app/vendor/

ADD ./docker/rabbitmq/script.php .

CMD php -f /src/app/script.php
