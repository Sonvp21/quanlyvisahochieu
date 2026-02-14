@echo off
cd /d F:\project\quanlyvisahochieu
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
