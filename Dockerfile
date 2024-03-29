# Image
FROM php:7.4-fpm

# Starting from scratch
RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Dependencies
RUN apt-get update

# Zip
RUN apt-get install -y libzip-dev zip && docker-php-ext-configure zip && docker-php-ext-install zip

# Git
RUN apt-get install -y git

# Curl
RUN apt-get install -y libcurl3-dev curl && docker-php-ext-install curl

# GD
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

#PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# BC Math
RUN docker-php-ext-install bcmath

# Human Language and Character Encoding Support
RUN apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# PHP Redis extension
RUN pecl install redis
RUN docker-php-ext-enable redis

# Install Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Composer installation
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Clean up
RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#Copy composer.lock and composer.json
COPY ./src/composer.lock* ./src/composer.json* /var/www/html/

# Set up default directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY ./src /var/www/html