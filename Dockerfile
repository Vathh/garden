FROM php:8.3-apache
RUN docker-php-ext-install pdo_mysql

RUN apt-get update && \
    apt-get install -y msmtp ca-certificates && \
    rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/php.ini
COPY msmtprc   /etc/msmtprc
RUN chmod 600 /etc/msmtprc && chown www-data:www-data /etc/msmtprc

RUN touch /var/log/msmtp.log && chown www-data:www-data /var/log/msmtp.log