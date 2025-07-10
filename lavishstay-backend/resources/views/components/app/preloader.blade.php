<div id="app-preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white dark:bg-gray-900 transition-all duration-700 ease-in-out">
    <!-- Background overlay with blur -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-50/80 via-white/90 to-indigo-50/80 dark:from-gray-900/90 dark:via-gray-800/95 dark:to-gray-900/90 backdrop-blur-sm"></div>
    
    <!-- Preloader content -->
    <div class="relative z-10 flex flex-col items-center">
        <!-- Logo container with animations -->
        <div class="relative w-32 h-32 mb-8">
            <!-- Subtle glow effect -->
            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-violet-400/20 via-indigo-400/20 to-purple-400/20 dark:from-violet-500/30 dark:via-indigo-500/30 dark:to-purple-500/30 animate-pulse-slow blur-xl"></div>
            
            <!-- Rotating border -->
            <div class="absolute inset-0 rounded-full border-2 border-transparent bg-gradient-to-r from-violet-500/40 via-transparent to-indigo-500/40 dark:from-violet-400/50 dark:to-indigo-400/50 animate-spin-slow"></div>
            
            <!-- Main logo -->
            <div class="relative w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 fill-violet-500 dark:fill-violet-400 animate-float" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path d="M31.956 14.8C31.372 6.92 25.08.628 17.2.044V5.76a9.04 9.04 0 0 0 9.04 9.04h5.716ZM14.8 26.24v5.716C6.92 31.372.63 25.08.044 17.2H5.76a9.04 9.04 0 0 1 9.04 9.04Zm11.44-9.04h5.716c-.584 7.88-6.876 14.172-14.756 14.756V26.24a9.04 9.04 0 0 1 9.04-9.04ZM.044 14.8C.63 6.92 6.92.628 14.8.044V5.76a9.04 9.04 0 0 1-9.04 9.04H.044Z" />
                </svg>
                <a href=""></a>
            </div>
        </div>
        
        <!-- Brand name -->
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 animate-fade-in-up">
            LAVISHSTAY
        </h2>
        
        <!-- Loading text with dynamic updates -->
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-6 animate-fade-in-up animation-delay-200">
            <span id="loading-text">Đang khởi tạo...</span>
        </div>
        
        <!-- Progress bar -->
        <div class="w-64 h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full bg-gradient-to-r from-violet-500 to-indigo-500 dark:from-violet-400 dark:to-indigo-400 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
        
        <!-- Loading percentage -->
        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
            <span id="loading-percentage">0%</span>
        </div>
    </div>
</div>

<style>
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.4; transform: scale(0.95); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-8px) rotate(1deg); }
}

@keyframes fade-in-up {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

.animate-float {
    animation: float 4s ease-in-out infinite;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
}

.animation-delay-200 {
    animation-delay: 0.2s;
}

/* Preloader hide animation */
.preloader-hide {
    opacity: 0;
    visibility: hidden;
    transform: scale(0.95);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('app-preloader');
    const loadingText = document.getElementById('loading-text');
    const progressBar = document.getElementById('progress-bar');
    const loadingPercentage = document.getElementById('loading-percentage');
    
    // Loading messages
    const loadingMessages = [
        'Đang khởi tạo...',
        'Đang tải dữ liệu...',
        'Đang xử lý yêu cầu...',
        'Đang chuẩn bị giao diện...',
        'Hoàn tất!'
    ];
    
    let currentMessage = 0;
    let progress = 0;
    
    // Simulate loading progress
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15 + 5; // Random increment between 5-20
        
        if (progress >= 100) {
            progress = 100;
            clearInterval(progressInterval);
            
            // Final message
            loadingText.textContent = loadingMessages[4];
            
            // Hide preloader after completion
            setTimeout(() => {
                preloader.classList.add('preloader-hide');
                setTimeout(() => {
                    preloader.remove();
                }, 700);
            }, 500);
        }
        
        // Update progress bar and percentage
        progressBar.style.width = progress + '%';
        loadingPercentage.textContent = Math.round(progress) + '%';
        
        // Update loading message
        const messageIndex = Math.min(Math.floor(progress / 25), loadingMessages.length - 2);
        if (messageIndex !== currentMessage && messageIndex < loadingMessages.length - 1) {
            currentMessage = messageIndex;
            loadingText.textContent = loadingMessages[currentMessage];
        }
    }, 200 + Math.random() * 300); // Random interval between 200-500ms
    
    // Minimum loading time (adjust based on your needs)
    const minLoadTime = 100;
    setTimeout(() => {
        if (progress < 100) {
            // Force completion if taking too long
            clearInterval(progressInterval);
            progress = 100;
            progressBar.style.width = '100%';
            loadingPercentage.textContent = '100%';
            loadingText.textContent = loadingMessages[4];
            
            setTimeout(() => {
                preloader.classList.add('preloader-hide');
                setTimeout(() => {
                    preloader.remove();
                }, 700);
            }, 300);
        }
    }, minLoadTime);
});
</script>