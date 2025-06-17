#!/bin/bash

# Script đồng bộ code tự động - Pull và Push liên tục
echo "🔄 Auto-sync script for DEV branch"
echo "Press Ctrl+C to stop"

# Function để pull code
pull_changes() {
    echo "⬇️ Pulling latest changes..."
    git stash -q
    git pull origin DEV
    git stash pop -q 2>/dev/null || true
}

# Function để push code nếu có thay đổi
push_changes() {
    # Kiểm tra có thay đổi gì không
    if ! git diff --quiet || ! git diff --staged --quiet; then
        echo "📤 Found changes, pushing..."
        git add .
        git commit -m "Auto-sync: $(date '+%Y-%m-%d %H:%M:%S')"
        git push origin DEV
        echo "✅ Changes pushed successfully!"
    else
        echo "💤 No changes to push"
    fi
}

# Đảm bảo đang ở nhánh DEV
current_branch=$(git branch --show-current)
if [ "$current_branch" != "DEV" ]; then
    echo "🔀 Switching to DEV branch..."
    git checkout DEV
fi

# Loop chính
while true; do
    echo ""
    echo "🕐 $(date '+%H:%M:%S') - Checking for updates..."
    
    # Pull code mới
    pull_changes
    
    # Push code nếu có thay đổi
    push_changes
    
    # Chờ 30 giây trước lần kiểm tra tiếp theo
    echo "⏳ Waiting 30 seconds..."
    sleep 30
done
