# Базовый образ с nginx и php
FROM richarvey/nginx-php-fpm

# Добавляем наше веб приложение
ADD app /var/www/app
ADD build/conf/nginx/site.conf /etc/nginx/sites-available/site.conf

RUN apk add --no-cache --update libmemcached-libs zlib \
    && set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps zlib-dev libmemcached-dev cyrus-sasl-dev \
    && pecl install memcached \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/20_memcached.ini \
    && rm -rf /usr/share/php7 \
    && rm -rf /tmp/* \
    && rm -Rf /etc/nginx/sites-enabled/* \
    && ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf \
# forward request and error logs to docker log collector
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

RUN chmod -R 777 /var/www/app/runtime && chmod -R 777 /var/www/app/web/assets
# setUp entrypoint to performe database migrations
ADD ./build/wait-for-it.sh /
ADD ./build/docker-entrypoint.sh /
RUN chmod +x /wait-for-it.sh
RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]
