@echo off
echo "Mulai penarikan data Telkomsel"
cd D:\wwwroot\absensi_laravel\
TIMEOUT 1
php71 artisan summary:today
TIMEOUT 2
exit