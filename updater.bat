@echo off
Title Application Updater
echo.
git pull
composer update
php artisan migrate
pause
