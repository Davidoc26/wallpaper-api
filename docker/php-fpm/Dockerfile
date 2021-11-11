FROM php:8.0.12-fpm
RUN usermod -u 1000 www-data

WORKDIR "/application"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd pdo_mysql
