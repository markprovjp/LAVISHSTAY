@echo off
REM Script để push code lên nhánh DEV (Windows version)

echo 🚀 Pushing changes to DEV branch...

REM Kiểm tra nhánh hiện tại
for /f "tokens=*" %%i in ('git branch --show-current') do set current_branch=%%i
echo 📍 Current branch: %current_branch%

REM Chuyển về nhánh DEV nếu chưa ở đó
if not "%current_branch%"=="DEV" (
    echo 🔀 Switching to DEV branch...
    git checkout DEV
)

REM Pull code mới nhất trước khi push (để tránh conflict)
echo ⬇️ Pulling latest changes first...
git pull origin DEV

REM Add tất cả thay đổi
echo ➕ Adding all changes...
git add .

REM Kiểm tra có thay đổi gì không
git diff --staged --quiet
if %errorlevel%==0 (
    echo ⚠️ No changes to commit!
    pause
    exit /b 0
)

REM Sử dụng commit message từ tham số hoặc message mặc định
if "%~1"=="" (
    set "commit_message=Update code - %date% %time%"
) else (
    set "commit_message=%~1"
)

echo 💬 Commit message: %commit_message%

REM Commit
echo 💾 Committing changes...
git commit -m "%commit_message%"

REM Push lên remote
echo ⬆️ Pushing to origin/DEV...
git push origin DEV

echo ✅ Push completed successfully!
echo 🎉 Your changes are now on the DEV branch!
pause
