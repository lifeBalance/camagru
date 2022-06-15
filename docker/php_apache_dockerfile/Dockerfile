FROM php:8.1-rc-apache-buster

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update && apt-get install -y msmtp msmtp-mta    \
    && docker-php-ext-install pdo pdo_mysql                 \
    && docker-php-ext-enable pdo pdo_mysql                  \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf   \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf  \
    && a2enmod rewrite

# COPY docker-php-entrypoint /docker-php-entrypoint
# RUN chmod u+x /docker-php-entrypoint
# CMD ["/docker-php-entrypoint"]