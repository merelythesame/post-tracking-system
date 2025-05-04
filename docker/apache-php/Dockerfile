FROM php:8.4-apache

RUN apt-get update && apt-get install -y iputils-ping

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

# COPY ./my-apache-config.conf /etc/apache2/sites-available/000-default.conf
