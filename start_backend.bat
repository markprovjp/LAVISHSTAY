@echo off
REM filepath: d:\PRO224\DU_AN_TOT_NGHIEP\start_backend.bat
REM Minimal hacker-style colored startup for LavishStay Backend
color 0C
cls

REM Hacker-style output
setlocal enabledelayedexpansion
for /l %%i in (1,1,40) do set "line=!line!="
echo !line!
echo [ LAVISHSTAY BACKEND DEV MODE ]
echo !line!
echo.
echo [*] Directory: %CD%
echo [*] URL: http://localhost:8888
echo [*] Stack: Laravel
 echo.
cd /d "C:\Users\ADMIN\DEV2\LAVISHSTAY\lavishstay-backend"
REM Start scheduler daemon in separate window (runs schedule:run every 60s)
start "Scheduler Daemon" cmd /c "php artisan scheduler:daemon --interval=60 > storage/logs/scheduler-daemon.log 2>&1"

REM Start Laravel dev server
php artisan serve --port=8888
echo.
echo !line!
echo [!] Backend Server stopped. Press any key to close...
pause > nul
