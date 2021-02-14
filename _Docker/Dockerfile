FROM php:7.4.15-apache

COPY . /srv/app
COPY _Docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install mysqli

RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite