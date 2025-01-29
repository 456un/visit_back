FROM php:8.3-fpm

# Устанавливаем необходимые пакеты и расширения PHP
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libmcrypt-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем конфигурационный файл Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Создаем рабочую директорию для приложения
WORKDIR /var/www

# Устанавливаем права на рабочую директорию
RUN chown -R www-data:www-data /var/www

# Устанавливаем bash как команду по умолчанию
CMD ["php-fpm"]

# Открываем порты
EXPOSE 9000