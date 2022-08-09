FROM php:7.4.3-apache
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Xdebug
RUN pecl install xdebug-3.1.2
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
