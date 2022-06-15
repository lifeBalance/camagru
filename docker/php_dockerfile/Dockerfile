FROM php:8.1-rc-apache-buster

RUN apt-get update && apt-get install -y sendmail   \
    && docker-php-ext-install pdo pdo_mysql         \
    && docker-php-ext-enable pdo pdo_mysql          \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf   \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf  \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

