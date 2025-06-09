FROM php:8.2-fpm

# Установим нужные зависимости для PHP и Node.js
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libmagickwand-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql zip intl mysqli mbstring gd pcntl \
    && pecl install imagick redis \
    && docker-php-ext-enable imagick redis

# Установим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установим Laravel Installer глобально
RUN composer global require laravel/installer

# Добавляем Composer bin в PATH
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Настраиваем PHP-FPM на работу через сокет
RUN sed -i 's|listen = 9000|listen = /var/run/php/php-fpm.sock|' /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen.mode = 0666" >> /usr/local/etc/php-fpm.d/www.conf

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Права для storage и cache
RUN mkdir -p /var/run/php && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Точка входа
ENTRYPOINT ["/var/www/docker/entrypoint.sh"]
