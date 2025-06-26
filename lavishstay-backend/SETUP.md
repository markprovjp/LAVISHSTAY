# LAVISH STAY - BACKEND SETUP GUIDE

## 🚀 Quick Start

### 1. Start Development Server

```powershell
# Option 1: Use the helper script
.\dev.ps1 serve

# Option 2: Direct command
.\start-server.ps1

# Option 3: Manual command
php -S localhost:8000 -t public router.php
```

### 2. Start Full Stack (Frontend + Backend)

```powershell
# From root directory
.\start-fullstack.ps1
```

## 🛠️ Development Commands

### Using the dev helper script:

```powershell
.\dev.ps1 help           # Show all commands
.\dev.ps1 serve          # Start server
.\dev.ps1 migrate        # Run migrations
.\dev.ps1 fresh          # Fresh migrate + seed
.\dev.ps1 clear          # Clear caches
.\dev.ps1 status         # Show app status
```

### Manual Laravel commands:

```powershell
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with seed data
php artisan db:seed             # Run seeders only
php artisan cache:clear         # Clear cache
php artisan config:clear        # Clear config cache
```

## 🔧 Setup from Scratch

### 1. Install Dependencies

```powershell
composer install
npm install
```

### 2. Environment Setup

```powershell
# Copy .env file (already exists)
# Generate app key
php artisan key:generate
```

### 3. Database Setup

```powershell
# Create database: datn_build_basic
# Run migrations
php artisan migrate:fresh --seed
```

### 4. Start Development

```powershell
.\dev.ps1 serve
```

## 🌐 Server URLs

-   **Backend API**: http://localhost:8000
-   **Frontend**: http://localhost:3000 (when running full stack)

## 📝 Important Notes

-   ⚠️ **php artisan serve** has issues on this system
-   ✅ **Use php -S localhost:8000 -t public router.php** instead
-   🔄 The router.php file handles Laravel routing properly
-   📁 Make sure you're in the lavishstay-backend directory

## 🐛 Troubleshooting

### Server won't start:

1. Check if port 8000 is free: `netstat -ano | findstr :8000`
2. Try different port: `php -S localhost:8001 -t public router.php`
3. Check PHP version: `php -v` (requires PHP 8.1+)

### Database issues:

1. Ensure MySQL is running
2. Check database exists: `datn_build_basic`
3. Verify .env database credentials
4. Run: `php artisan migrate:fresh --seed`

### Permission issues:

1. Run PowerShell as Administrator
2. Set execution policy: `Set-ExecutionPolicy RemoteSigned -Scope CurrentUser`
