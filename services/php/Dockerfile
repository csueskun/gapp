FROM php:7.2-fpm-alpine

ARG DEBUG_HOST

RUN apk update && apk add libpng-dev memcached

# Install CUPS client and dependencies linux only
RUN apk add --no-cache \
  cups \
  cups-client \
  avahi \
  dbus \
  bash
RUN echo "ServerName cups" > /etc/cups/client.conf

# Lumen packages
RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql gd zip

# Install dependencies and SOAP
RUN apk add --no-cache libxml2-dev && docker-php-ext-install soap

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Install required dependencies for wkhtmltopdf
RUN apk add --no-cache \
    wkhtmltopdf \
    xvfb \
    fontconfig \
    ttf-dejavu \
    ttf-freefont \
    ttf-liberation \
    ttf-ubuntu-font-family \
    libxrender \
    libjpeg-turbo \
    libxext

# Install Xdebug
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-2.9.0 \
    && docker-php-ext-enable xdebug

RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9005" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.profiler_output_dir="/var/www/html"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.cli_color=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=tdplusapi" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.remote_host="192.168.1.9"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini