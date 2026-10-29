@echo off
echo "Mulai penarikan data Telkomsel"
cd D:\wwwroot\absensi_laravel\
TIMEOUT 1
php71 artisan sync:today
TIMEOUT 1
php71 artisan sync:socket
TIMEOUT 1
php artisan summary:today
exit
