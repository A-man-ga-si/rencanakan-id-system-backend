# Gunakan official image PHP dengan ekstensi yang diperlukan
FROM php:8.2-fpm

# Set working directory di dalam container
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project Laravel ke dalam container
COPY . .

# Berikan permission agar storage dan cache bisa diakses
RUN chmod -R 777 storage bootstrap/cache

# Jalankan perintah untuk menginstal dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Ubah user ke www-data agar lebih aman
RUN chown -R www-data:www-data /var/www
USER www-data

# Expose port yang digunakan
EXPOSE 9000

CMD ["php-fpm"]
