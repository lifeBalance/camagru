FROM phpdockerio/php:8.1-fpm

RUN apt-get update && apt-get install -q -y ssmtp mailutils \
    && usermod -aG mail root \
    && openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 \
        -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=www.example.com" \
        -keyout www.example.com.key  -out www.example.com.cert   \
    && echo "hostname=localhost.localdomain" > /etc/ssmtp/ssmtp.conf \
    && echo "UseSTARTTLS=YES" >> /etc/ssmtp/ssmtp.conf \
    && echo "TLS_CA_File=/root/certs/MyCertificate.crt" >> /etc/ssmtp/ssmtp.conf \
    && echo "RewriteDomain=localhost" >> /etc/ssmtp/ssmtp.conf \
    && echo "Hostname=localhost" >> /etc/ssmtp/ssmtp.conf \
    && echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf
RUN apt-get -y --no-install-recommends install php8.1-gd php8.1-mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# TLS certificate has to be created manually inside the container
#mkdir /root/certs && openssl req -new -newkey rsa:4096 -x509 -sha256 -days 365 -nodes -out /root/certs/MyCertificate.crt -keyout /root/certs/MyKey.key