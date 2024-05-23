FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache oniguruma-dev \
    && docker-php-ext-install mbstring pdo_mysql
#    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /usr/share/nginx/html/backend

CMD ["php-fpm"]
