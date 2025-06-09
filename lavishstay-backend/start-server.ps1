# Laravel Backend Server Starter Script
# Run this script to start the backend server

Write-Host "Starting Laravel Backend Server..." -ForegroundColor Green
Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Yellow
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

# Change to backend directory
Set-Location -Path $PSScriptRoot

# Start PHP built-in server with router
Write-Host "Using PHP built-in server with custom router..." -ForegroundColor Cyan
php -S localhost:8000 -t public router.php
