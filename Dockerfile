FROM php:8.2-apache

# Instalar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurações do Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
