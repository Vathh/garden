FROM php:8.3-apache

# Apache + mod_rewrite + public jako DocumentRoot
RUN a2enmod rewrite && \
    sed -i 's|AllowOverride None|AllowOverride All|' /etc/apache2/apache2.conf && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|' /etc/apache2/sites-available/000-default.conf

# PDO do MySQL
RUN docker-php-ext-install pdo_mysql mysqli

# Xdebug przez PECL
RUN pecl install xdebug && docker-php-ext-enable xdebug

# msmtp
RUN apt-get update && \
    apt-get install -y unzip zip git msmtp ca-certificates && \
    rm -rf /var/lib/apt/lists/*

# Konfiguracja Xdebug, maila i PHP
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY php.ini /usr/local/etc/php/php.ini
COPY msmtprc /etc/msmtprc
RUN chmod 600 /etc/msmtprc && chown www-data:www-data /etc/msmtprc
RUN touch /var/log/msmtp.log && chown www-data:www-data /var/log/msmtp.log

# Ustawienie ścieżki roboczej
WORKDIR /var/www

# Instalacja Composera i zależności
COPY composer.json composer.lock ./

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php && \
    composer install --no-interaction --prefer-dist

