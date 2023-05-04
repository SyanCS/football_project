FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libicu-dev \
    g++ \
    libzip-dev \
    zip \
    && docker-php-ext-install -j$(nproc) intl opcache pdo_mysql zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]

EXPOSE 9000
