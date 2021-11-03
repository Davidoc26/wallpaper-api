FROM php:8.0.12-fpm
RUN usermod -u 1000 www-data

WORKDIR "/application"

RUN docker-php-ext-install pdo pdo_mysql
