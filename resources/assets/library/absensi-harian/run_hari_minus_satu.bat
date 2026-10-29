@echo off
echo "Mulai penarikan data Absensi untuk H-1"
cd D:\wwwroot\absensi_laravel\
TIMEOUT 1
php71 artisan sync:mobile "yesterday"
TIMEOUT 1
php71 artisan sync:socket
TIMEOUT 1
php71 artisan summary:daily "yesterday"
exit
