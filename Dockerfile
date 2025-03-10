# Gunakan official PHP image
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project Laravel ke dalam container
COPY . .

# Berikan permission agar storage bisa diakses
RUN chmod -R 777 storage bootstrap/cache

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Expose port Laravel
EXPOSE 8000

# Jalankan Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
