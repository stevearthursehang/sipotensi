#!/bin/sh
set -e

# Default PORT 7860 (Standar Hugging Face Spaces)
PORT="${PORT:-7860}"

# Sesuaikan port Nginx secara dinamis jika ada variabel PORT
sed -i -E "s/listen (\[::\]:)?[0-9]+/listen \1${PORT}/g" /etc/nginx/sites-available/default

# Buat storage symlink jika belum ada
if [ ! -L /var/www/html/public/storage ]; then
    echo "Membuat storage link..."
    php artisan storage:link || true
fi

# Jalankan migrasi database otomatis jika RUN_MIGRATIONS=true di Environment Secrets
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Menjalankan migrasi database ke Supabase..."
    php artisan migrate --force
fi

# Optimasi cache Laravel
echo "Mengoptimalkan cache Laravel..."
php artisan config:cache || php artisan config:clear
php artisan route:cache || php artisan route:clear
php artisan view:cache || php artisan view:clear

# Jalankan PHP-FPM di background
echo "Memulai PHP-FPM..."
php-fpm -D

# Jalankan Nginx di foreground pada port ${PORT}
echo "Memulai Nginx pada port ${PORT}..."
exec nginx -g "daemon off;"
