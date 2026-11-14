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
# Stage 2: Production Web Server (PHP 8.3 FPM + Nginx)
# ==============================================================================
FROM php:8.3-fpm

WORKDIR /var/www/html

# Port default untuk Hugging Face Spaces
ENV PORT=7860

# 1. Install sistem dependensi, PostgreSQL (Supabase / PostGIS), dan Nginx
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    nginx \
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

# 3. Konfigurasi Nginx & PHP
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# 4. Entrypoint script
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

# 9. Atur permissions untuk Hugging Face Spaces & Laravel
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/log/nginx /var/lib/nginx /run

# Port khusus Hugging Face Spaces
EXPOSE 7860

# Jalankan entrypoint script saat container start
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
