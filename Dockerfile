FROM php:7.4-fpm-alpine

#Copy composer.lock and composer.json
COPY ./src/composer.lock* ./src/composer.json* /var/www/html/

WORKDIR /var/www/html

#PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

#Enable bash
RUN apk add --update bash && rm -rf /var/cache/apk/*

# Copy existing application directory contents
COPY ./src /var/www/html