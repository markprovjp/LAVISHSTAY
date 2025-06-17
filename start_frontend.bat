@echo off
REM filepath: d:\PRO224\DU_AN_TOT_NGHIEP\start_frontend.bat
REM Minimal hacker-style colored startup for LavishStay Frontend
color 0A
cls

REM Hacker-style output
setlocal enabledelayedexpansion
for /l %%i in (1,1,40) do set "line=!line!="
echo !line!
echo [ LAVISHSTAY FRONTEND DEV MODE ]
echo !line!
echo.
echo [*] Directory: %CD%
echo [*] URL: http://localhost:5173
echo [*] Stack: React + Vite + TypeScript
echo.
cd /d "D:\PRO224\DU_AN_TOT_NGHIEP\lavishstay-frontend"
npm run dev
echo.
echo !line!
echo [!] Frontend Server stopped. Press any key to close...
pause > nul
