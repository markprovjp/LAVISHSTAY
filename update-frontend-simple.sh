#!/bin/bash
# Script tu dong cap nhat frontend len nhanh QUYEN - ENHANCED VERSION
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
BOX_CROSS="┼"
BOX_T_DOWN="┬"
BOX_T_UP="┴"
BOX_T_RIGHT="├"
BOX_T_LEFT="┤"

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
    │    ███████╗████████╗ █████╗ ██╗   ██╗                       │
    │    ██╔════╝╚══██╔══╝██╔══██╗╚██╗ ██╔╝                       │
    │    ███████╗   ██║   ███████║ ╚████╔╝                        │
    │    ╚════██║   ██║   ██╔══██║  ╚██╔╝                         │
    │    ███████║   ██║   ██║  ██║   ██║                          │
    │    ╚══════╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝                          │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
}

function draw_box() {
    local text="$1"
    local color="$2"
    local width=${3:-60}
    
    echo -e "${color}"
    # Top border
    echo -n "${BOX_TL}"
    for ((i=1; i<width-1; i++)); do echo -n "${BOX_H}"; done
    echo "${BOX_TR}"
    
    # Content
    echo "${BOX_V} ${text} ${BOX_V}"
    
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

function success_celebration() {
    echo -e "${COLOR_SUCCESS}${COLOR_BOLD}"
    cat << "EOF"
    
    🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊
    
            ✨ DEPLOYMENT SUCCESSFUL! ✨
            
    🚀 Frontend da duoc deploy thanh cong!
    🌟 Tat ca thay doi da duoc push len remote!
    💫 Code cua ban da san sang cho production!
    
    🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉🎊🎉
    
EOF
    echo -e "${COLOR_RESET}"
}

function show_enhanced_help() {
    clear_screen
    echo -e "${COLOR_CYAN}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                    📚 HELP MENU 📚                          │
    ├─────────────────────────────────────────────────────────────┤
    │                                                             │
    │  🚀 Usage: ./update-frontend-simple.sh [OPTIONS]            │
    │                                                             │
    │  📋 Options:                                                │
    │     -m, --message MESSAGE    📝 Custom commit message      │
    │     -h, --help              🆘 Show this help menu         │
    │                                                             │
    │  💡 Examples:                                               │
    │     ./update-frontend-simple.sh                            │
    │     ./update-frontend-simple.sh -m "Fix login bug"         │
    │                                                             │
    │  ⚡ Features:                                               │
    │     • 🎨 Beautiful ASCII art and colors                    │
    │     • 📊 File change preview table                         │
    │     • 🔄 Loading animations                                │
    │     • ✅ Success celebrations                               │
    │     • 🚨 Enhanced error messages                           │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
    exit 0
}

function show_file_changes_table() {
    local changes="$1"
    
    echo -e "${COLOR_MAGENTA}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                    📁 FILE CHANGES 📁                      │
    ├─────────────────────────────────────────────────────────────┤
EOF
    
    echo -e "${COLOR_YELLOW}"
    while IFS= read -r line; do
        if [[ -n "$line" ]]; then
            status="${line:0:2}"
            file="${line:3}"
            
            case "$status" in
                "A ")  echo "    │  ➕ NEW:      $file" ;;
                "M ")  echo "    │  ✏️  MODIFIED: $file" ;;
                "D ")  echo "    │  🗑️  DELETED:  $file" ;;
                "R ")  echo "    │  🔄 RENAMED:  $file" ;;
                "?? ") echo "    │  ❓ UNTRACKED: $file" ;;
                *)     echo "    │  📝 CHANGED:  $file" ;;
            esac
        fi
    done <<< "$changes"
    
    echo -e "${COLOR_MAGENTA}${COLOR_BOLD}"
    echo "    ╰─────────────────────────────────────────────────────────────╯"
    echo -e "${COLOR_RESET}"
}

# Default commit message (will be set later if not provided via command line)
COMMIT_MESSAGE=""

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -m|--message)
            COMMIT_MESSAGE="$2"
            shift 2
            ;;
        -h|--help)
            show_enhanced_help
            ;;
        *)
            echo -e "${COLOR_ERROR}❌ Unknown option: $1${COLOR_RESET}"
            echo -e "${COLOR_INFO}💡 Use -h or --help for usage information${COLOR_RESET}"
            exit 1
            ;;
    esac
done

# Clear screen and show logo
clear_screen
show_ascii_logo

draw_box "LAVISHSTAY FRONTEND UPDATE SCRIPT - ENHANCED VERSION" "$COLOR_CYAN" 65
echo -e "${COLOR_MAGENTA}✨ by NGUYEN VAN QUYEN - $(date +'%d/%m/%Y')${COLOR_RESET}"
echo ""

# Check if git repo
if [ ! -d ".git" ]; then
    echo -e "${COLOR_ERROR}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                      🚨 ERROR 🚨                           │
    │                                                             │
    │    ❌ Khong phai la git repository!                        │
    │    💡 Vui long chay script trong thu muc git project       │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
    exit 1
fi

# Switch to QUYEN branch
echo -e "${COLOR_INFO}🔍 Kiem tra branch hien tai...${COLOR_RESET}"
current_branch=$(git rev-parse --abbrev-ref HEAD)
if [ "$current_branch" != "QUYEN" ]; then
    loading_animation "🔄 Chuyen sang branch QUYEN" 2
    git checkout QUYEN
    
    if [ $? -eq 0 ]; then
        echo -e "${COLOR_SUCCESS}✅ Da chuyen sang branch QUYEN thanh cong${COLOR_RESET}"
    else
        echo -e "${COLOR_ERROR}❌ Khong the chuyen sang branch QUYEN${COLOR_RESET}"
        exit 1
    fi
else
    echo -e "${COLOR_SUCCESS}✅ Da o tren branch QUYEN${COLOR_RESET}"
fi

# Pull from remote
echo ""
loading_animation "📥 Dang tai thay doi moi nhat tu remote" 3
git pull origin QUYEN

if [ $? -eq 0 ]; then
    echo -e "${COLOR_SUCCESS}✅ Da tai tu remote thanh cong${COLOR_RESET}"
else
    echo -e "${COLOR_WARNING}⚠️  Canh bao: Khong the tai tu remote${COLOR_RESET}"
fi

# Check for frontend changes
echo ""
echo -e "${COLOR_INFO}🔍 Quet thay doi trong frontend...${COLOR_RESET}"
frontend_changes=$(git status --porcelain lavishstay-frontend/)

if [ -z "$frontend_changes" ]; then
    echo -e "${COLOR_WARNING}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                      ⚠️  CANH BAO ⚠️                        │
    │                                                             │
    │    📁 Khong tim thay thay doi trong lavishstay-frontend/   │
    │    💡 Khong co gi de commit hoac deploy                    │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
    echo -e "${COLOR_INFO}👋 Script hoan thanh - Khong can thuc hien gi${COLOR_RESET}"
    exit 0
fi

# Show changes in beautiful table
echo ""
show_file_changes_table "$frontend_changes"
echo ""

# Get commit message if not provided via command line
if [ -z "$COMMIT_MESSAGE" ]; then
    echo -e "${COLOR_CYAN}📝 Nhap tin nhan commit (Enter de su dung tin nhan mac dinh):${COLOR_RESET}"
    echo -e "${COLOR_YELLOW}   Mac dinh: '🚀 Cap nhat frontend - $(date +'%d/%m/%Y %H:%M')'${COLOR_RESET}"
    echo -n -e "${COLOR_BLUE}💬 Tin nhan commit: ${COLOR_RESET}"
    read -r user_message
    
    if [ -z "$user_message" ]; then
        COMMIT_MESSAGE="🚀 Cap nhat frontend - $(date +'%d/%m/%Y %H:%M')"
    else
        COMMIT_MESSAGE="$user_message"
    fi
fi

# Single confirmation with all information
echo ""
echo -e "${COLOR_MAGENTA}${COLOR_BOLD}╭─────────────────────────────────────────────────────────────╮${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}${COLOR_BOLD}│                  🚀 XAC NHAN DEPLOYMENT 🚀                 │${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}${COLOR_BOLD}├─────────────────────────────────────────────────────────────┤${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}│${COLOR_RESET} 📝 Commit message: ${COLOR_YELLOW}${COLOR_BOLD}$COMMIT_MESSAGE${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}│${COLOR_RESET} 🌿 Branch: ${COLOR_GREEN}QUYEN${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}│${COLOR_RESET} 📁 Directory: ${COLOR_CYAN}lavishstay-frontend/${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}│${COLOR_RESET} ⏰ Time: ${COLOR_BLUE}$(date +'%H:%M:%S - %d/%m/%Y')${COLOR_RESET}"
echo -e "${COLOR_MAGENTA}${COLOR_BOLD}╰─────────────────────────────────────────────────────────────╯${COLOR_RESET}"
echo ""
echo -e "${COLOR_YELLOW}${COLOR_BOLD}🤔 Xac nhan commit va push cac thay doi nay? (y/N): ${COLOR_RESET}"
read -r confirmation

if [[ ! "$confirmation" =~ ^[Yy]([Ee][Ss])?$ ]]; then
    echo -e "${COLOR_WARNING}❌ Thao tac da bi huy boi nguoi dung${COLOR_RESET}"
    exit 0
fi

# Add, commit and push with enhanced feedback
echo ""
loading_animation "📦 Dang them file vao staging area" 2
git add lavishstay-frontend/

echo ""
loading_animation "💾 Dang tao commit" 2
git commit -m "$COMMIT_MESSAGE"

if [ $? -eq 0 ]; then
    commit_hash=$(git rev-parse --short HEAD)
    echo -e "${COLOR_SUCCESS}✅ Commit thanh cong! Hash: ${COLOR_BOLD}$commit_hash${COLOR_RESET}"
    
    echo ""
    loading_animation "🚀 Dang push len remote repository" 3
    git push origin QUYEN
    
    if [ $? -eq 0 ]; then
        echo ""
        success_celebration
        
        # Final status box
        echo -e "${COLOR_SUCCESS}${COLOR_BOLD}"
        cat << EOF
        ╭─────────────────────────────────────────────────────────────╮
        │                   🎯 TINH TRANG TRIEN KHAI 🎯              │
        ├─────────────────────────────────────────────────────────────┤
        │                                                             │
        │  ✅ Status: THANH CONG                                      │
        │  📝 Commit: $commit_hash                                    │
        │  🌿 Branch: QUYEN                                           │
        │  ⏰ Time: $(date +'%H:%M:%S')                               │
        │  📅 Date: $(date +'%d/%m/%Y')                               │
        │                                                             │
        ╰─────────────────────────────────────────────────────────────╯
EOF
        echo -e "${COLOR_RESET}"
        
    else
        echo -e "${COLOR_ERROR}${COLOR_BOLD}"
        cat << "EOF"
        ╭─────────────────────────────────────────────────────────────╮
        │                      🚨 LOI 🚨                              │
        │                                                             │
        │    ❌ Khong the day vao kho luu tru tu xa!                  │
        │    💡 Vui long kiem tra ket noi mang cua ban                │
        │    🔧 Hoac xac minh quyen kho luu tru tu xa                 │
        │                                                             │
        ╰─────────────────────────────────────────────────────────────╯
EOF
        echo -e "${COLOR_RESET}"
        exit 1
    fi
else
    echo -e "${COLOR_ERROR}${COLOR_BOLD}"
    cat << "EOF"
    ╭─────────────────────────────────────────────────────────────╮
    │                      🚨 LOI 🚨                              │
    │                                                             │
    │    ❌ Khong tao duoc commit!                                │
    │    💡 Vui long kiem tra xem cac file co duoc staged khong   │
    │    🔧 Hoac xac minh dinh dang tin nhan commit               │
    │                                                             │
    ╰─────────────────────────────────────────────────────────────╯
EOF
    echo -e "${COLOR_RESET}"
    exit 1
fi
