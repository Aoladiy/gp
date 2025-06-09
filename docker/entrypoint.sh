#!/usr/bin/env bash

# Назначить права владельца и группы для Laravel
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Запустить PHP-FPM
exec php-fpm -y /usr/local/etc/php-fpm.conf -R
