#
# PHP Dependencies Stage
#
FROM composer:1.7 as vendor
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist
#
# Final Stage
#
FROM php:8.1-fpm-alpine
RUN apk update \
 && apk upgrade \
 && docker-php-ext-install pdo_mysql \
 && rm -rf /var/cache/apk/*
COPY --chown=www-data --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=www-data . /var/www/html/
