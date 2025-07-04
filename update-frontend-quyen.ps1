# Script tu dong cap nhat frontend len nhanh QUYEN
# Tac gia: GitHub Copilot
# Ngay tao: 29/05/2025

param(
    [string]$CommitMessage = "",
    [switch]$NoPull,
    [switch]$Force
)

# Mau sac cho output
$ColorSuccess = "Green"
$ColorWarning = "Yellow" 
$ColorError = "Red"
$ColorInfo = "Cyan"

function Write-ColorOutput {
    param([string]$Message, [string]$Color = "White")
    Write-Host $Message -ForegroundColor $Color
}

function Show-Banner {
    Write-Host ""
    Write-ColorOutput "=====================================================" $ColorInfo
    Write-ColorOutput "   LAVISHSTAY FRONTEND UPDATE SCRIPT - NHANH QUYEN" $ColorInfo
    Write-ColorOutput "=====================================================" $ColorInfo
    Write-Host ""
}

function Test-GitRepository {
    if (-not (Test-Path ".git")) {
        Write-ColorOutput "Loi: Khong phai la git repository!" $ColorError
        Write-ColorOutput "Hay chay script tu thu muc goc cua du an." $ColorWarning
        exit 1
    }
}

function Test-FrontendChanges {
    $frontendChanges = git status --porcelain lavishstay-frontend/
    if ([string]::IsNullOrWhiteSpace($frontendChanges)) {
        Write-ColorOutput "Khong co thay doi nao trong thu muc lavishstay-frontend/" $ColorWarning
        $continue = Read-Host "Ban co muon tiep tuc khong? (y/N)"
        if ($continue -ne "y" -and $continue -ne "Y") {
            Write-ColorOutput "Huy bo script." $ColorInfo
            exit 0
        }
    }
    return $frontendChanges
}

function Switch-ToBranch {
    param([string]$BranchName)
    
    $currentBranch = git rev-parse --abbrev-ref HEAD
    if ($currentBranch -ne $BranchName) {
        Write-ColorOutput "Chuyen tu nhanh '$currentBranch' sang nhanh '$BranchName'..." $ColorInfo
        git checkout $BranchName
        if ($LASTEXITCODE -ne 0) {
            Write-ColorOutput "Loi khi chuyen nhanh! Kiem tra lai." $ColorError
            exit 1
        }
        Write-ColorOutput "Da chuyen sang nhanh '$BranchName' thanh cong!" $ColorSuccess
    }
    else {
        Write-ColorOutput "Da o nhanh '$BranchName'" $ColorSuccess
    }
}

function Update-FromRemote {
    if (-not $NoPull) {
        Write-ColorOutput "Dang cap nhat tu remote..." $ColorInfo
        git pull origin QUYEN
        if ($LASTEXITCODE -ne 0) {
            Write-ColorOutput "Canh bao: Co van de khi pull tu remote" $ColorWarning
            if (-not $Force) {
                $continue = Read-Host "Ban co muon tiep tuc khong? (y/N)"
                if ($continue -ne "y" -and $continue -ne "Y") {
                    exit 1
                }
            }
        }
        else {
            Write-ColorOutput "Cap nhat tu remote thanh cong!" $ColorSuccess
        }
    }
}

function Show-Changes {
    param([string]$Changes)
    
    Write-ColorOutput "`nDanh sach files se duoc commit:" $ColorInfo
    Write-Host "----------------------------------------"
    
    $changeCount = 0
    $Changes -split "`n" | ForEach-Object {
        if ($_ -match "lavishstay-frontend/") {
            $changeCount++
            $status = $_.Substring(0, 2)
            $file = $_.Substring(3)
            
            switch ($status.Trim()) {
                "M" { Write-ColorOutput "[MODIFIED]  $file" "Yellow" }
                "A" { Write-ColorOutput "[ADDED]     $file" "Green" }
                "D" { Write-ColorOutput "[DELETED]   $file" "Red" }
                "R" { Write-ColorOutput "[RENAMED]   $file" "Cyan" }
                "??" { Write-ColorOutput "[NEW]       $file" "Green" }
                default { Write-ColorOutput "[$status]      $file" "White" }
            }
        }
    }
    
    Write-Host "----------------------------------------"
    Write-ColorOutput "Tong cong: $changeCount files" $ColorInfo
    Write-Host ""
}

function Get-CommitMessage {
    if ([string]::IsNullOrWhiteSpace($CommitMessage)) {
        Write-ColorOutput "Nhap commit message (Enter de su dung message mac dinh):" $ColorInfo
        $userMessage = Read-Host
        
        if ([string]::IsNullOrWhiteSpace($userMessage)) {
            $timestamp = Get-Date -Format "dd/MM/yyyy HH:mm"
            return "Cap nhat frontend - $timestamp"
        }
        else {
            return $userMessage
        }
    }
    else {
        return $CommitMessage
    }
}

function Confirm-Action {
    param([string]$Message)
    
    if (-not $Force) {
        Write-ColorOutput $Message $ColorWarning
        $confirm = Read-Host "Ban co chac chan khong? (y/N)"
        return ($confirm -eq "y" -or $confirm -eq "Y")
    }
    return $true
}

function Commit-Changes {
    param([string]$Message)
    
    Write-ColorOutput "Dang add files..." $ColorInfo
    git add lavishstay-frontend/
    
    if ($LASTEXITCODE -ne 0) {
        Write-ColorOutput "Loi khi add files!" $ColorError
        exit 1
    }
    
    Write-ColorOutput "Dang commit voi message: '$Message'" $ColorInfo
    git commit -m $Message
    
    if ($LASTEXITCODE -ne 0) {
        Write-ColorOutput "Loi khi commit!" $ColorError
        exit 1
    }
    
    return git rev-parse --short HEAD
}

function Push-ToRemote {
    Write-ColorOutput "Dang push len remote..." $ColorInfo
    git push origin QUYEN
    
    if ($LASTEXITCODE -ne 0) {
        Write-ColorOutput "Loi khi push len remote!" $ColorError
        exit 1
    }
    
    Write-ColorOutput "Push thanh cong!" $ColorSuccess
}

function Show-Summary {
    param([string]$CommitHash, [int]$FileCount)
    
    Write-Host ""
    Write-ColorOutput "=====================================================" $ColorSuccess
    Write-ColorOutput "                 HOAN THANH THANH CONG!" $ColorSuccess
    Write-ColorOutput "=====================================================" $ColorSuccess
    Write-ColorOutput "Commit hash: $CommitHash" $ColorInfo
    Write-ColorOutput "So files da cap nhat: $FileCount" $ColorInfo
    Write-ColorOutput "Nhanh: QUYEN" $ColorInfo
    Write-ColorOutput "Repository: https://github.com/markprovjp/LAVISHSTAY/tree/QUYEN" $ColorInfo
    Write-ColorOutput "=====================================================" $ColorSuccess
    Write-Host ""
}

# MAIN EXECUTION
try {
    Show-Banner
    
    # Kiem tra co phai git repo khong
    Test-GitRepository
    
    # Chuyen sang nhanh QUYEN
    Switch-ToBranch "QUYEN"
    
    # Cap nhat tu remote
    Update-FromRemote
    
    # Kiem tra thay doi frontend
    $changes = Test-FrontendChanges
    
    if (-not [string]::IsNullOrWhiteSpace($changes)) {
        # Hien thi cac thay doi
        Show-Changes $changes
        
        # Lay commit message
        $commitMsg = Get-CommitMessage
        
        # Xac nhan truoc khi commit
        if (Confirm-Action "Ban co muon commit va push cac thay doi nay khong?") {
            
            # Commit changes
            $commitHash = Commit-Changes $commitMsg
            
            # Xac nhan truoc khi push
            if (Confirm-Action "Ban co muon push len remote khong?") {
                Push-ToRemote
                
                # Dem so files
                $fileCount = ($changes -split "`n" | Where-Object { $_ -match "lavishstay-frontend/" }).Count
                
                # Hien thi ket qua
                Show-Summary $commitHash $fileCount
            }
            else {
                Write-ColorOutput "Da commit nhung chua push. Ban co the push sau bang lenh:" $ColorInfo
                Write-ColorOutput "git push origin QUYEN" $ColorWarning
            }
        }
        else {
            Write-ColorOutput "Huy bo thao tac." $ColorInfo
        }
    }
    
}
catch {
    Write-ColorOutput "Da xay ra loi: $($_.Exception.Message)" $ColorError
    exit 1
}