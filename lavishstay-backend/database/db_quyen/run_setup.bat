@echo off
echo Running LavishStay Hotel Database Setup...
echo Please make sure MySQL is running and you have the correct credentials.
echo.

REM Change to the database directory
cd /d "%~dp0"

REM Run the master setup file with Laragon MySQL path
"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe" -u root -p < 00_master_setup.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ===================================
    echo Database da duoc thiet lap thanh cong!
    echo ===================================
) else (
    echo.
    echo ===================================
    echo Database khong duoc thiet lap!
    echo ===================================
)

echo.
pause
