#!/bin/bash
# Script khoi dong tu dong LavishStay Frontend va Backend
# Su dung: Chay file nay trong Git Bash bang lenh ./start-dev.sh
# Tac gia: NGUYEN VAN QUYEN
# Ngay tao: 10/06/2025

# Enhanced Colors for output
COLOR_SUCCESS='\033[0;32m'
COLOR_WARNING='\033[0;33m' 
COLOR_ERROR='\033[0;31m'
COLOR_INFO='\033[0;36m'
COLOR_CYAN='\033[0;36m'
COLOR_MAGENTA='\033[0;35m'
COLOR_BLUE='\033[0;34m'
COLOR_YELLOW='\033[0;33m'
COLOR_BOLD='\033[1m'
COLOR_RESET='\033[0m'

# Unicode box drawing characters
BOX_H="─"
BOX_V="│"
BOX_TL="╭"
BOX_TR="╮"
BOX_BL="╰"
BOX_BR="╯"

function clear_screen() {
    clear
}

function write_color_output() {
    local message="$1"
    local color="$2"
    echo -e "${color}${message}${COLOR_RESET}"
}

function show_ascii_logo() {
    echo -e "${COLOR_CYAN}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                                                             │
    │    ██╗      █████╗ ██╗   ██╗██╗███████╗██╗  ██╗███████╗     │
    │    ██║     ██╔══██╗██║   ██║██║██╔════╝██║  ██║██╔════╝     │
    │    ██║     ███████║██║   ██║██║███████╗███████║███████╗     │
    │    ██║     ██╔══██║╚██╗ ██╔╝██║╚════██║██╔══██║╚════██║     │
    │    ███████╗██║  ██║ ╚████╔╝ ██║███████║██║  ██║███████║     │
    │    ╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝╚══════╝╚═╝  ╚═╝╚══════╝     │
    │                                                             │
    │         ██████╗ ███████╗██╗   ██╗    ███████╗███╗   ██╗     │
    │         ██╔══██╗██╔════╝██║   ██║    ██╔════╝████╗  ██║     │
    │         ██║  ██║█████╗  ██║   ██║    █████╗  ██╔██╗ ██║     │
    │         ██║  ██║██╔══╝  ╚██╗ ██╔╝    ██╔══╝  ██║╚██╗██║     │
    │         ██████╔╝███████╗ ╚████╔╝     ███████╗██║ ╚████║     │
    │         ╚═════╝ ╚══════╝  ╚═══╝      ╚══════╝╚═╝  ╚═══╝     │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
}

function draw_box() {
    local text="$1"
    local color="$2"
    local width=${3:-65}
    
    echo -e "${color}"
    # Top border
    echo -n "${BOX_TL}"
    for ((i=1; i<width-1; i++)); do echo -n "${BOX_H}"; done
    echo "${BOX_TR}"
    
    # Content with padding
    local padding=$((($width - ${#text} - 4) / 2))
    echo -n "${BOX_V}"
    for ((i=0; i<padding; i++)); do echo -n " "; done
    echo -n "${text}"
    for ((i=0; i<padding; i++)); do echo -n " "; done
    echo " ${BOX_V}"
    
    # Bottom border
    echo -n "${BOX_BL}"
    for ((i=1; i<width-1; i++)); do echo -n "${BOX_H}"; done
    echo "${BOX_BR}"
    echo -e "${COLOR_RESET}"
}

function loading_animation() {
    local message="$1"
    local duration=${2:-2}
    
    echo -n -e "${COLOR_YELLOW}${message}"
    for ((i=1; i<=duration*2; i++)); do
        echo -n "."
        sleep 0.5
    done
    echo -e " ✅${COLOR_RESET}"
}

function check_command() {
    local cmd="$1"
    local name="$2"
    
    echo -e "${COLOR_INFO}[*] Kiem tra cai dat $name...${COLOR_RESET}"
    if ! command -v "$cmd" &> /dev/null; then
        echo -e "${COLOR_ERROR}[!] LOI: $name khong duoc tim thay. Vui long cai dat $name truoc.${COLOR_RESET}"
        exit 1
    fi
    echo -e "${COLOR_SUCCESS}[+] $name da duoc cai dat!${COLOR_RESET}"
}

function install_dependencies() {
    local path="$1"
    local name="$2"
    local cmd="$3"
    
    echo -e "${COLOR_INFO}[*] Kiem tra dependencies cua $name...${COLOR_RESET}"
    
    if [ "$cmd" = "npm" ]; then
        if [ -d "$path/node_modules" ]; then
            echo -e "${COLOR_SUCCESS}[+] Dependencies cua $name da duoc cai dat.${COLOR_RESET}"
        else
            echo -e "${COLOR_WARNING}[*] Dang cai dat dependencies cho $name...${COLOR_RESET}"
            cd "$path" || exit
            npm install
            if [ $? -eq 0 ]; then
                echo -e "${COLOR_SUCCESS}[+] Dependencies cua $name da duoc cai dat thanh cong!${COLOR_RESET}"
            else
                echo -e "${COLOR_ERROR}[!] LOI: Khong the cai dat dependencies cho $name${COLOR_RESET}"
                exit 1
            fi
            cd - > /dev/null
        fi
    elif [ "$cmd" = "composer" ]; then
        if [ -d "$path/vendor" ]; then
            echo -e "${COLOR_SUCCESS}[+] Dependencies cua $name da duoc cai dat.${COLOR_RESET}"
        else
            echo -e "${COLOR_WARNING}[*] Dang cai dat dependencies cho $name...${COLOR_RESET}"
            cd "$path" || exit
            composer install
            if [ $? -eq 0 ]; then
                echo -e "${COLOR_SUCCESS}[+] Dependencies cua $name da duoc cai dat thanh cong!${COLOR_RESET}"
            else
                echo -e "${COLOR_ERROR}[!] LOI: Khong the cai dat dependencies cho $name${COLOR_RESET}"
                exit 1
            fi
            cd - > /dev/null
        fi
    fi
}

function setup_backend_env() {
    local backend_path="$1"
    
    echo -e "${COLOR_INFO}[*] Kiem tra file .env...${COLOR_RESET}"
    
    if [ ! -f "$backend_path/.env" ]; then
        if [ -f "$backend_path/.env.example" ]; then
            echo -e "${COLOR_WARNING}[*] Tao .env tu .env.example...${COLOR_RESET}"
            cp "$backend_path/.env.example" "$backend_path/.env"
        else
            echo -e "${COLOR_WARNING}[*] Tao file .env mac dinh...${COLOR_RESET}"
            cat > "$backend_path/.env" << EOF
APP_NAME=LavishStay
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavishstay
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
        fi
        
        # Tao khoa ung dung Laravel
        echo -e "${COLOR_WARNING}[*] Tao khoa ung dung Laravel...${COLOR_RESET}"
        cd "$backend_path" || exit
        php artisan key:generate
        cd - > /dev/null
        echo -e "${COLOR_SUCCESS}[+] Khoa Laravel da duoc tao!${COLOR_RESET}"
    else
        echo -e "${COLOR_SUCCESS}[+] File .env da ton tai.${COLOR_RESET}"
    fi
}

function show_menu() {
    echo -e "${COLOR_MAGENTA}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                     CHON DICH VU                           │
    ├─────────────────────────────────────────────────────────────┤
    │                                                             │
    │     [1] Frontend (React + Vite)                            │
    │     [2] Backend (Laravel)                                  │
    │     [3] Ca hai (Full Stack)                                │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
}

function success_message() {
    local choice="$1"
    
    echo -e "${COLOR_SUCCESS}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                   KHOI DONG THANH CONG                     │
    ├─────────────────────────────────────────────────────────────┤
    │                                                             │
    │        LAVISHSTAY DEV ENVIRONMENT READY                    │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
    
    echo -e "${COLOR_CYAN}${COLOR_BOLD}>>> DANH SACH DICH VU HOAT DONG:${COLOR_RESET}"
    
    if [ "$choice" = "1" ] || [ "$choice" = "3" ]; then
        echo -e "${COLOR_CYAN}Frontend: http://localhost:5173${COLOR_RESET}"
    fi
    
    if [ "$choice" = "2" ] || [ "$choice" = "3" ]; then
        echo -e "${COLOR_CYAN}Backend: http://localhost:8000${COLOR_RESET}"
    fi
    
    echo -e "${COLOR_MAGENTA}${COLOR_BOLD}>>> He thong truc tuyen. San sang thong tri!${COLOR_RESET}"
}

function create_terminal_shortcuts() {
    local choice="$1"
    
    echo -e "${COLOR_WARNING}[*] Tao shortcut desktop cho cac terminal...${COLOR_RESET}"
    
    if [ "$choice" = "1" ] || [ "$choice" = "3" ]; then
        # Create desktop shortcut for Frontend
        cat > "Frontend-LavishStay.cmd" << EOF
@echo off
title Frontend-React-LavishStay
color 0A
echo.
echo ================================
echo    LAVISHSTAY FRONTEND
echo ================================
echo.
cd /d "$(cygpath -w "$FRONTEND_PATH" 2>/dev/null || echo "$FRONTEND_PATH" | sed 's|^/d/|D:/|' | sed 's|/|\\|g')"
echo [+] React Frontend dang chay tai http://localhost:5173
echo [+] Thu muc hien tai: %CD%
echo.
npm run dev
echo.
echo [!] Frontend da dung. Nhan phim bat ky de dong...
pause
EOF
        echo -e "${COLOR_INFO}[+] Tao Frontend-LavishStay.cmd de chay thu cong${COLOR_RESET}"
    fi
    
    if [ "$choice" = "2" ] || [ "$choice" = "3" ]; then
        # Create desktop shortcut for Backend
        cat > "Backend-LavishStay.cmd" << EOF
@echo off
title Backend-Laravel-LavishStay
color 0E
echo.
echo ================================
echo    LAVISHSTAY BACKEND
echo ================================
echo.
cd /d "$(cygpath -w "$BACKEND_PATH" 2>/dev/null || echo "$BACKEND_PATH" | sed 's|^/d/|D:/|' | sed 's|/|\\|g')"
echo [+] Laravel Backend dang chay tai http://localhost:8000
echo [+] Thu muc hien tai: %CD%
echo.
php artisan serve
echo.
echo [!] Backend da dung. Nhan phim bat ky de dong...
pause
EOF
        echo -e "${COLOR_INFO}[+] Tao Backend-LavishStay.cmd de chay thu cong${COLOR_RESET}"
    fi
}

# Main script starts here
clear_screen
show_ascii_logo

draw_box "LAVISHSTAY DEV ENVIRONMENT LAUNCHER" "$COLOR_CYAN" 65
echo "by NGUYEN VAN QUYEN - $(date +'%d/%m/%Y')"
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FRONTEND_PATH="$SCRIPT_DIR/lavishstay-frontend"
BACKEND_PATH="$SCRIPT_DIR/lavishstay-backend"

# Check required tools
check_command "node" "Node.js"
check_command "npm" "NPM"
check_command "php" "PHP"
check_command "composer" "Composer"

echo ""

# Show menu and get user choice
show_menu
echo "[*] Nhap lua chon cua ban (1-3): "
read -r choice

# Validate choice
if [[ ! "$choice" =~ ^[1-3]$ ]]; then
    echo "[!] LOI: Lua chon khong hop le. Vui long chon 1, 2, hoac 3."
    exit 1
fi

echo ""

# Automatically use Windows Command Prompt terminals
terminal_choice="1"
echo "=== KHOI DONG VOI WINDOWS COMMAND PROMPT ==="

echo ""

# Setup Frontend if selected
if [ "$choice" = "1" ] || [ "$choice" = "3" ]; then
    draw_box "CHUAN BI FRONTEND" "$COLOR_MAGENTA" 65
    if [ -d "$FRONTEND_PATH" ]; then
        install_dependencies "$FRONTEND_PATH" "Frontend" "npm"
    else
        echo -e "${COLOR_ERROR}[!] LOI: Thu muc Frontend khong ton tai tai $FRONTEND_PATH${COLOR_RESET}"
        exit 1
    fi
    echo ""
fi

# Setup Backend if selected
if [ "$choice" = "2" ] || [ "$choice" = "3" ]; then
    draw_box "CHUAN BI BACKEND" "$COLOR_MAGENTA" 65
    if [ -d "$BACKEND_PATH" ]; then
        setup_backend_env "$BACKEND_PATH"
        install_dependencies "$BACKEND_PATH" "Backend" "composer"
    else
        echo -e "${COLOR_ERROR}[!] LOI: Thu muc Backend khong ton tai tai $BACKEND_PATH${COLOR_RESET}"
        exit 1
    fi
    echo ""
fi

# Start services
# Chỉ gọi file BAT, không cần kiểm tra process hay sleep

draw_box "KHOI DONG DICH VU" "$COLOR_MAGENTA" 65

if [ "$choice" = "1" ] || [ "$choice" = "3" ]; then
    echo "[*] Khoi dong React Frontend tai http://localhost:5173..."
    if command -v powershell.exe &> /dev/null; then
        powershell.exe -Command "Start-Process cmd -ArgumentList '/c', 'start_frontend.bat' -WindowStyle Maximized" 2>/dev/null &
        echo "[+] Frontend terminal da duoc mo qua PowerShell!"
    else
        cmd.exe //c "start \"Frontend\" //max start_frontend.bat" 2>/dev/null &
        echo "[+] Frontend terminal da duoc mo qua CMD!"
    fi
fi

if [ "$choice" = "2" ] || [ "$choice" = "3" ]; then
    echo "[*] Khoi dong Laravel Backend tai http://localhost:8000..."
    if command -v powershell.exe &> /dev/null; then
        powershell.exe -Command "Start-Process cmd -ArgumentList '/c', 'start_backend.bat' -WindowStyle Maximized" 2>/dev/null &
        echo "[+] Backend terminal da duoc mo qua PowerShell!"
    else
        cmd.exe //c "start \"Backend\" //max start_backend.bat" 2>/dev/null &
        echo "[+] Backend terminal da duoc mo qua CMD!"
    fi
fi

echo ""
success_message "$choice"



exit 0
