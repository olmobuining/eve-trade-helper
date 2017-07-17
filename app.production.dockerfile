FROM php:7.1-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev git \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql

# Enable Reporting
RUN echo "php_flag[display_errors] = On">>/usr/local/etc/php-fpm.conf
RUN echo "php_admin_flag[log_errors] = On">>/usr/local/etc/php-fpm.conf
RUN echo "php_admin_value[display_errors] = 'stderr'">>/usr/local/etc/php-fpm.conf

WORKDIR /var/www/

COPY . /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts

RUN php artisan optimize

RUN chown -R www-data:www-data /var/www
