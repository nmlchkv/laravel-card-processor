FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    postgresql-dev \
    libxml2-dev \
    icu-dev \
    bash

RUN docker-php-ext-install pdo pdo_pgsql zip intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm", "-F"]


