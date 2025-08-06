#!/bin/bash

echo "🚀 Pushing changes to DEV branch..."

DEFAULT_BRANCH="DEV"
REMOTE_NAME=${1:-origin}  # Mặc định origin
REMOTE_BRANCH=$DEFAULT_BRANCH

# Kiểm tra rebase/merge đang dở
if [ -d ".git/rebase-merge" ] || [ -d ".git/rebase-apply" ]; then
    echo "⚠️ Đang có quá trình rebase/merge dang dở. Hãy hoàn thành trước khi push!"
    echo "👉 git rebase --continue hoặc git rebase --abort"
    exit 1
fi

# Hiển thị stash nếu có
stash_list=$(git stash list)
if [ -n "$stash_list" ]; then
    echo "⚠️ Bạn đang có stash:"
    echo "$stash_list"
    echo "👉 Chỉ là thông báo, không ảnh hưởng lần push này."
fi

# Kiểm tra nhánh hiện tại
current_branch=$(git branch --show-current)
echo "📍 Current branch: $current_branch"
if [ "$current_branch" != "$DEFAULT_BRANCH" ]; then
    echo "🔀 Switching to $DEFAULT_BRANCH..."
    git checkout $DEFAULT_BRANCH || exit 1
fi

# Auto add toàn bộ
echo "➕ Tự động add tất cả thay đổi..."
git add .

# Nếu không có gì để commit → thoát
if git diff --cached --quiet; then
    echo "✅ Không có thay đổi nào để commit."
    exit 0
fi

# Stash nếu đang staged nhưng chưa commit → để tránh lỗi pull --rebase
echo "📦 Stash tạm thời các thay đổi để pull an toàn..."




stash_result=$(git stash push -u -m "Auto stash before rebase" 2>&1)

if [[ $? -ne 0 ]]; then
    echo "❌ Stash lỗi: $stash_result"
    echo "👉 Có thể bạn có file bị lỗi đường dẫn hoặc bị xóa ngoài Git."
    echo "👉 Gợi ý: git status để kiểm tra và dùng git rm --cached <file>"
    exit 1
fi

# Pull với rebase
echo "⬇️ Pulling với rebase..."
git pull --rebase $REMOTE_NAME $REMOTE_BRANCH
if [ $? -ne 0 ]; then
    echo "❌ Rebase bị conflict! Hãy xử lý thủ công rồi chạy lại script."
    git diff --name-only --diff-filter=U
    exit 1
fi

# Apply lại stash
echo "📥 Áp dụng lại thay đổi từ stash..."
git stash pop
if [ $? -ne 0 ]; then
    echo "⚠️ Không có stash để apply hoặc apply lỗi!"
fi



git stash push -u -m "Auto stash before rebase"

# Pull với rebase
echo "⬇️ Pulling với rebase..."
git pull --rebase $REMOTE_NAME $REMOTE_BRANCH
if [ $? -ne 0 ]; then
    echo "❌ Rebase bị conflict! Hãy xử lý thủ công rồi chạy lại script."
    git diff --name-only --diff-filter=U
    exit 1
fi

# Apply lại stash
echo "📥 Áp dụng lại thay đổi từ stash..."
git stash pop
if [ $? -ne 0 ]; then
    echo "⚠️ Không có stash để apply hoặc apply lỗi!"
fi


# Add lại sau khi stash pop
git add .

# Tạo commit message tự động
changed_files=$(git diff --cached --name-only | tr '\n' ', ' | sed 's/, $//')
commit_message="Update files: $changed_files - $(date '+%Y-%m-%d %H:%M:%S')"
echo "💬 Commit: $commit_message"
git commit -m "$commit_message"

# Push lên remote
echo "⬆️ Pushing to $REMOTE_NAME/$REMOTE_BRANCH..."
git push $REMOTE_NAME $REMOTE_BRANCH
if [ $? -ne 0 ]; then
    echo "❌ Push thất bại!"
    exit 1
fi

# Xác nhận lại thông tin commit
latest_commit=$(git rev-parse --short HEAD)
repo_url=$(git config --get remote.$REMOTE_NAME.url | sed 's/.git$//' | sed 's/git@/https:\/\//; s/:/\//')

echo "✅ Push thành công!"
echo "🔗 Xem commit: $repo_url/commit/$latest_commit"
