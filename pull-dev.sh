#!/bin/bash

# Script để pull code mới nhất từ nhánh DEV
echo "🔄 Pulling latest changes from DEV branch..."

# Kiểm tra nhánh hiện tại
current_branch=$(git branch --show-current)
echo "📍 Current branch: $current_branch"

# Stash các thay đổi chưa commit (nếu có)
echo "💾 Stashing uncommitted changes..."
git stash

# Chuyển về nhánh DEV nếu chưa ở đó
if [ "$current_branch" != "DEV" ]; then
    echo "🔀 Switching to DEV branch..."
    git checkout DEV
fi

# Pull code mới nhất
echo "⬇️ Pulling from origin/DEV..."
git pull origin DEV

# Unstash các thay đổi đã stash
echo "📤 Restoring stashed changes..."
git stash pop

echo "✅ Pull completed successfully!"
echo "💡 Your local changes have been restored. You can continue working."
