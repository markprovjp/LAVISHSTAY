#!/bin/bash

# Script để push code lên nhánh DEV
echo "🚀 Pushing changes to DEV branch..."

# Kiểm tra nhánh hiện tại
current_branch=$(git branch --show-current)
echo "📍 Current branch: $current_branch"

# Chuyển về nhánh DEV nếu chưa ở đó
if [ "$current_branch" != "DEV" ]; then
    echo "🔀 Switching to DEV branch..."
    git checkout DEV
fi

# Pull code mới nhất trước khi push (để tránh conflict)
echo "⬇️ Pulling latest changes first..."
git pull origin DEV

# Add tất cả thay đổi
echo "➕ Adding all changes..."
git add .

# Kiểm tra có thay đổi gì không
if git diff --staged --quiet; then
    echo "⚠️ No changes to commit!"
    exit 0
fi

# Nhập commit message hoặc dùng message mặc định
if [ -z "$1" ]; then
    commit_message="Update code - $(date '+%Y-%m-%d %H:%M:%S')"
else
    commit_message="$1"
fi

echo "💬 Commit message: $commit_message"

# Commit
echo "💾 Committing changes..."
git commit -m "$commit_message"

# Push lên remote
echo "⬆️ Pushing to origin/DEV..."
git push origin DEV

echo "✅ Push completed successfully!"
echo "🎉 Your changes are now on the DEV branch!"
