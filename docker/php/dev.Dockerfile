FROM php:8.1-fpm-alpine
RUN apk update \
 && apk upgrade \
 && apk add linux-headers \
 && apk add $PHPIZE_DEPS \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug \
 && docker-php-ext-install pdo_mysql \
 && docker-php-ext-enable pdo_mysql \
 && apk del $PHPIZE_DEPS \
 && rm -rf /var/cache/apk/*
