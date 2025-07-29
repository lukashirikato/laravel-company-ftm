# Gunakan image PHP + Apache
FROM php:8.2-apache

# Install ekstensi yang diperlukan Laravel
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file project ke dalam container
COPY . /var/www/html

# Pindah ke direktori project
WORKDIR /var/www/html

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Generate key Laravel
RUN php artisan key:generate

# Atur permission storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Aktifkan mod_rewrite untuk Apache
RUN a2enmod rewrite

# Copy konfigurasi Apache untuk Laravel
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
