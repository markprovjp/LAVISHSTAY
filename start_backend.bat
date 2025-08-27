@echo off
cd /d "%~dp0\lavishstay-backend"
echo Starting Laravel backend (built-in server)...
start "Laravel" cmd /c "php artisan serve --host=127.0.0.1 --port=8000"
echo Starting scheduler daemon in background...
start "Scheduler" cmd /c "php artisan scheduler:daemon"
echo Done.