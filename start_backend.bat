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
cd /d "D:\PRO224\DEV\lavishstay-backend"
php artisan serve --port=8888
echo.
echo !line!
echo [!] Backend Server stopped. Press any key to close...
pause > nul
