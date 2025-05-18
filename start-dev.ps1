# Script khoi dong tu dong LavishStay Frontend va Backend
# Su dung: Chay file nay trong PowerShell bang lenh ./start-dev.ps1

Write-Host "
==================================================
       KHOI DONG LAVISHSTAY - MOI TRUONG DEV
==================================================
" -ForegroundColor Cyan

# Kiem tra xem da co npm chua
if (!(Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "Khong tim thay npm. Hay cai dat Node.js truoc khi chay script nay." -ForegroundColor Red
    exit
}

# Kiem tra xem da co php chua
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "Khong tim thay PHP. Hay cai dat PHP truoc khi chay script nay." -ForegroundColor Red
    exit
}

# Duong dan tuyet doi den cac thu muc
$rootPath = $PSScriptRoot
$frontendPath = Join-Path -Path $rootPath -ChildPath "lavishstay-frontend"
$backendPath = Join-Path -Path $rootPath -ChildPath "lavishstay-backend"

# Ham kiem tra va cai dat cac dependency neu chua co
function Install-Dependencies {
    param (
        [string]$path,
        [string]$name
    )
    
    if (Test-Path -Path "$path\node_modules") {
        Write-Host "Da tim thay cac dependency cho $name..." -ForegroundColor Green
    } else {
        Write-Host "Dang cai dat cac dependency cho $name..." -ForegroundColor Yellow
        Push-Location $path
        npm install
        Pop-Location
    }
}

# Kiem tra va cai dat cac dependency cho Frontend
if (Test-Path -Path $frontendPath) {
    Write-Host "Chuan bi khoi dong Frontend..." -ForegroundColor Cyan
    Install-Dependencies -path $frontendPath -name "Frontend"
} else {
    Write-Host "Khong tim thay thu muc Frontend tai $frontendPath" -ForegroundColor Red
    exit
}

# Kiem tra va cai dat cac dependency cho Backend
if (Test-Path -Path $backendPath) {
    Write-Host "Chuan bi khoi dong Backend..." -ForegroundColor Cyan
    
    # Kiem tra file .env
    if (!(Test-Path -Path "$backendPath\.env")) {
        if (Test-Path -Path "$backendPath\.env.example") {
            Write-Host "Tao file .env tu .env.example..." -ForegroundColor Yellow
            Copy-Item "$backendPath\.env.example" "$backendPath\.env"
        } else {
            Write-Host "Khong tim thay file .env hoac .env.example" -ForegroundColor Red
            Write-Host "Tao file .env tu dong..." -ForegroundColor Yellow
            
            @"
APP_NAME=LavishStay
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=$backendPath\database\database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
"@ | Out-File -FilePath "$backendPath\.env" -Encoding utf8
        }
        
        # Tao khoa ung dung
        Push-Location $backendPath
        Write-Host "Tao khoa ung dung Laravel..." -ForegroundColor Yellow
        php artisan key:generate
        Pop-Location
    }
    
    # Kiem tra database SQLite
    if (!(Test-Path -Path "$backendPath\database\database.sqlite")) {
        Write-Host "Tao file database SQLite..." -ForegroundColor Yellow
        New-Item -ItemType File -Path "$backendPath\database\database.sqlite" -Force | Out-Null
        
        # Chay cac migration
        Push-Location $backendPath
        Write-Host "Chay cac migration co so du lieu..." -ForegroundColor Yellow
        php artisan migrate --seed
        Pop-Location
    }
    
    # Kiem tra composer dependencies
    if (!(Test-Path -Path "$backendPath\vendor")) {
        Write-Host "Cai dat cac dependency composer cho Backend..." -ForegroundColor Yellow
        Push-Location $backendPath
        composer install
        Pop-Location
    }
} else {
    Write-Host "Khong tim thay thu muc Backend tai $backendPath" -ForegroundColor Red
    exit
}

# Khoi dong ca hai dich vu trong cac cua so PowerShell rieng biet
Write-Host "Dang khoi dong LavishStay..." -ForegroundColor Green

# Khoi dong Backend
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$backendPath'; Write-Host 'Khoi dong Laravel Backend tai http://localhost:8000' -ForegroundColor Cyan; php artisan serve"

# Cho mot chut de dam bao backend da khoi dong
Start-Sleep -Seconds 2

# Khoi dong Frontend
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$frontendPath'; Write-Host 'Khoi dong React Frontend tai http://localhost:5173' -ForegroundColor Cyan; npm run dev"

# Mo trinh duyet web (tu chon)
$openBrowser = Read-Host "Ban co muon mo trinh duyet den trang Frontend khong? (y/n)"
if ($openBrowser -eq "y") {
    Start-Process "http://localhost:5173"
}

Write-Host "
==================================================
       LAVISHSTAY DA KHOI DONG THANH CONG
==================================================
Frontend: http://localhost:5173
Backend:  http://localhost:8000
" -ForegroundColor Green