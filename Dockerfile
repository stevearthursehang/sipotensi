# ==============================================================================
# Stage 1: Build Frontend Assets (Vite & Tailwind CSS)
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Salin package manifest
COPY package*.json ./
RUN npm ci || npm install

# Salin konfigurasi dan resource frontend
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

# Build aset production (menghasilkan public/build)
RUN npm run build

# ==============================================================================
# Stage 2: Production Web Server (PHP 8.3 Apache)
# ==============================================================================
FROM php:8.3-apache

WORKDIR /var/www/html

# Port default (akan di-override otomatis oleh Render saat runtime)
ENV PORT=80

# 1. Install ekstensi sistem & dependensi PostgreSQL (Supabase / PostGIS)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        bcmath \
        intl \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Ambil Composer versi 2 resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Aktifkan modul rewrite Apache
RUN a2enmod rewrite

# 4. Salin konfigurasi Apache, PHP, dan script entrypoint
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# 5. Salin composer file dan install vendor (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 6. Salin source code proyek
COPY . .

# 7. Salin hasil compile Vite dari Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# 8. Dump autoloader composer
RUN composer dump-autoload --optimize --no-scripts

# 9. Atur permissions direktori storage dan cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Port container
EXPOSE 80

# Jalankan entrypoint script saat container start
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
