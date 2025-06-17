# Script khoi dong tu dong LavishStay Frontend va Backend
# Su dung: Chay file nay trong PowerShell bang lenh ./start-dev.ps1

# ASCII Art cho vibe hacker
Write-Host @"
   _          _ _       _     ____ ____            
  | |__   ___| | | ___| |__ / ___|__ / ___|  ___  
  | '_ \ / __| | |/ _ \ '_ \ |   | '_ \___ \ / __| 
  | | | | (__| | |  __/ | | | |___| |_) |__) | (__ 
  |_| |_| \___|_|_|\___|_| |_|____|_.__/____/ \___|

==================================================
[*] LAVISHSTAY DEV ENVIRONMENT
==================================================
"@ -ForegroundColor Cyan

# Kiem tra xem da co npm chua
Write-Host "[*] Checking npm installation..." -ForegroundColor Yellow
if (!(Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "[!] ERROR: npm not found. Please install Node.js first." -ForegroundColor Red
    exit
}
Write-Host "[+] npm found!" -ForegroundColor Green

# Kiem tra xem da co php chua
Write-Host "[*] Checking PHP installation..." -ForegroundColor Yellow
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "[!] ERROR: PHP not found. Please install PHP first." -ForegroundColor Red
    exit
}
Write-Host "[+] PHP found!" -ForegroundColor Green

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
    
    Write-Host "[*] Checking $name dependencies..." -ForegroundColor Yellow
    if (Test-Path -Path "$path\node_modules") {
        Write-Host "[+] $name dependencies already installed." -ForegroundColor Green
    } else {
        Write-Host "[*] Installing $name dependencies..." -ForegroundColor Yellow
        Push-Location $path
        npm install
        Pop-Location
        Write-Host "[+] $name dependencies installed successfully!" -ForegroundColor Green
    }
}

# Hoi nguoi dung muon chay Frontend, Backend hay ca hai
Write-Host "`n>>> Select service to start:" -ForegroundColor Magenta
Write-Host "[1] Frontend" -ForegroundColor White
Write-Host "[2] Backend" -ForegroundColor White
Write-Host "[3] Both" -ForegroundColor White
$choice = Read-Host "[*] Enter your choice (1-3)"

# Kiem tra lua chon
if ($choice -ne "1" -and $choice -ne "2" -and $choice -ne "3") {
    Write-Host "[!] ERROR: Invalid choice. Please select 1, 2, or 3." -ForegroundColor Red
    exit
}

# Kiem tra va cai dat cac dependency cho Frontend neu chon 1 hoac 3
if ($choice -eq "1" -or $choice -eq "3") {
    Write-Host "`n>>> Preparing Frontend..." -ForegroundColor Magenta
    if (Test-Path -Path $frontendPath) {
        Install-Dependencies -path $frontendPath -name "Frontend"
    } else {
        Write-Host "[!] ERROR: Frontend directory not found at $frontendPath" -ForegroundColor Red
        exit
    }
}

# Kiem tra va cai dat cac dependency cho Backend neu chon 2 hoac 3
if ($choice -eq "2" -or $choice -eq "3") {
    Write-Host "`n>>> Preparing Backend..." -ForegroundColor Magenta
    if (Test-Path -Path $backendPath) {
        # Kiem tra file .env
        Write-Host "[*] Checking .env file..." -ForegroundColor Yellow
        if (!(Test-Path -Path "$backendPath\.env")) {
            if (Test-Path -Path "$backendPath\.env.example") {
                Write-Host "[*] Creating .env from .env.example..." -ForegroundColor Yellow
                Copy-Item "$backendPath\.env.example" "$backendPath\.env"
            } else {
                Write-Host "[!] ERROR: .env or .env.example not found" -ForegroundColor Red
                Write-Host "[*] Generating default .env file..." -ForegroundColor Yellow
                
                @"
APP_NAME=LavishStay
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
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
            Write-Host "[*] Generating Laravel application key..." -ForegroundColor Yellow
            php artisan key:generate
            Pop-Location
            Write-Host "[+] Laravel key generated!" -ForegroundColor Green
        } else {
            Write-Host "[+] .env file found." -ForegroundColor Green
        }
        
        # Kiem tra composer dependencies
        Write-Host "[*] Checking Composer dependencies..." -ForegroundColor Yellow
        if (!(Test-Path -Path "$backendPath\vendor")) {
            Write-Host "[*] Installing Composer dependencies for Backend..." -ForegroundColor Yellow
            Push-Location $backendPath
            composer install
            Pop-Location
            Write-Host "[+] Composer dependencies installed!" -ForegroundColor Green
        } else {
            Write-Host "[+] Composer dependencies already installed." -ForegroundColor Green
        }
    } else {
        Write-Host "[!] ERROR: Backend directory not found at $backendPath" -ForegroundColor Red
        exit
    }
}

# Khoi dong dich vu theo lua chon
Write-Host "`n>>> Starting LavishStay Services..." -ForegroundColor Magenta

if ($choice -eq "1" -or $choice -eq "3") {
    # Khoi dong Frontend
    Write-Host "[*] Launching React Frontend at http://localhost:5173..." -ForegroundColor Yellow
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$frontendPath'; Write-Host '[+] React Frontend running at http://localhost:5173' -ForegroundColor Cyan; npm run dev"
}

if ($choice -eq "2" -or $choice -eq "3") {
    # Khoi dong Backend    Write-Host "[*] Launching Laravel Backend at http://localhost:8888..." -ForegroundColor Yellow
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$backendPath'; Write-Host '[+] Laravel Backend running at http://localhost:8888' -ForegroundColor Cyan; php artisan serve --port=8888"
}

# Hien thi thong bao thanh cong
Write-Host @"
`n==================================================
[+] LAVISHSTAY STARTED SUCCESSFULLY
==================================================
"@ -ForegroundColor Green

if ($choice -eq "1") {
    Write-Host "Frontend: http://localhost:5173" -ForegroundColor Cyan
} elseif ($choice -eq "2") {
    Write-Host "Backend: http://localhost:8888" -ForegroundColor Cyan
} else {
    Write-Host "Frontend: http://localhost:5173" -ForegroundColor Cyan
    Write-Host "Backend: http://localhost:8888" -ForegroundColor Cyan
}
Write-Host ">>> System online. Ready to dominate!" -ForegroundColor Magenta