# #
# # Application
# #
FROM nginx:1.22.0 as nginx

ENV TZ=America/Sao_Paulo \
    APP_HOME=/var/www/html

COPY ./docker/nginx/app.conf /etc/nginx/conf.d/default.conf

WORKDIR $APP_HOME

COPY --chown=www-data:www-data . $APP_HOME/

# support running as arbitrary user which belogs to the root group
RUN chgrp -R 0 /var/cache/nginx /var/run /var/log/nginx /etc/nginx $WORKDIR \
    && chmod -R g=u /var/cache/nginx /var/run /var/log/nginx /etc/nginx $WORKDIR \
    && ln -sf /var/www/html/storage/app/public /var/www/html/public/storage

EXPOSE 8080
