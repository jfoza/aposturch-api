#!/bin/bash -x

if [ "$BUILD_APP_ENV" == "local" ]; then
    pecl install xdebug
    mv /tmp/xdebug.ini /usr/local/etc/php/conf.d/
else
    rm /tmp/xdebug.ini
fi
