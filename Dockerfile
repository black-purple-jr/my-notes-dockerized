FROM composer:2 AS composer
FROM php:8.2.12-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html/
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader