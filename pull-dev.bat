@echo off
REM Script để pull code mới nhất từ nhánh DEV (Windows version)

echo 🔄 Pulling latest changes from DEV branch...

REM Kiểm tra nhánh hiện tại
for /f "tokens=*" %%i in ('git branch --show-current') do set current_branch=%%i
echo 📍 Current branch: %current_branch%

REM Stash các thay đổi chưa commit (nếu có)
echo 💾 Stashing uncommitted changes...
git stash

REM Chuyển về nhánh DEV nếu chưa ở đó
if not "%current_branch%"=="DEV" (
    echo 🔀 Switching to DEV branch...
    git checkout DEV
)

REM Pull code mới nhất
echo ⬇️ Pulling from origin/DEV...
git pull origin DEV

REM Unstash các thay đổi đã stash
echo 📤 Restoring stashed changes...
git stash pop

echo ✅ Pull completed successfully!
echo 💡 Your local changes have been restored. You can continue working.
pause
