FROM php:8.0-apache

USER root

COPY msmtprc /etc/msmtprc

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -q -y                                     \
    zlib1g-dev msmtp msmtp-mta mailutils libpng-dev libjpeg-dev openssl         \
    && docker-php-ext-configure gd --with-jpeg                                  \
    && docker-php-ext-install gd pdo pdo_mysql                                  \
    && echo "sendmail_path=/usr/sbin/sendmail -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini \
    && echo "sendmail_from=camagru69@outlook.com" >> /usr/local/etc/php/conf.d/php-sendmail.ini \
    && openssl req -x509 -nodes -days 365 -newkey rsa:2048                      \
        -keyout /etc/ssl/private/ssl-cert-snakeoil.key                          \
        -out /etc/ssl/certs/ssl-cert-snakeoil.pem                               \
        -subj "/C=AT/ST=Helsinki/L=Helsinki/O=Security/OU=Development/CN=localhost" \
    && chmod 600 /etc/msmtprc                                                   \
    && chmod a+r /etc/ssl/private/ssl-cert-snakeoil.key                         \
    && chmod a+rx /etc/ssl/private                                              \
    && a2enmod rewrite                                                          \
    && a2ensite default-ssl                                                     \
    && a2enmod ssl

