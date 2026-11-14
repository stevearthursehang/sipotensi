#!/bin/sh
set -e

# Default PORT ke 80 jika Render tidak mendefinisikannya
PORT="${PORT:-80}"

# Pastikan Apache mendengarkan port dinamis dari Render
sed -ri -e "s!Listen [0-9]+!Listen ${PORT}!g" /etc/apache2/ports.conf
sed -ri -e "s!<VirtualHost \*:[0-9]+>!<VirtualHost *:${PORT}>!g" /etc/apache2/sites-available/000-default.conf

# Buat storage symlink jika belum ada
if [ ! -L /var/www/html/public/storage ]; then
    echo "Membuat storage link..."
    php artisan storage:link || true
fi

# Jalankan migrasi otomatis jika RUN_MIGRATIONS=true di environment variable Render
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Menjalankan migrasi database ke Supabase..."
    php artisan migrate --force
fi

# Optimasi konfigurasi & cache Laravel
echo "Mengoptimalkan cache Laravel..."
php artisan config:cache || php artisan config:clear
php artisan route:cache || php artisan route:clear
php artisan view:cache || php artisan view:clear

# Jalankan Apache Web Server
echo "Menjalankan Apache di port ${PORT}..."
exec apache2-foreground
