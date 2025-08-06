<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <!-- Styles -->
        @livewireStyles        

        <script>
            if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
                document.querySelector('html').classList.remove('dark');
                document.querySelector('html').style.colorScheme = 'light';
            } else {
                document.querySelector('html').classList.add('dark');
                document.querySelector('html').style.colorScheme = 'dark';
            }
        </script>
    </head>
    <body
        class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400"
        :class="{ 'sidebar-expanded': sidebarExpanded }"
        x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }"
        x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))"    
    >
@include('components.app.preloader')

        <script>
            if (localStorage.getItem('sidebar-expanded') == 'true') {
                document.querySelector('body').classList.add('sidebar-expanded');
            } else {
                document.querySelector('body').classList.remove('sidebar-expanded');
            }
        </script>

        <!-- Page wrapper -->
        <div class="flex h-[100dvh] overflow-hidden">

            <x-app.sidebar :variant="$attributes['sidebarVariant'] ?? null" />

            <!-- Content area -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if(isset($attributes['background'])){{ $attributes['background'] }}@endif" x-ref="contentarea">

                <x-app.header :variant="$attributes['headerVariant'] ?? null" />

                <main class="grow">
                    {{ $slot }}
                </main>

            </div>

        </div>

        @livewireScriptConfig
    </body>
</html>
<script>
// Điều chỉnh thời gian preloader dựa trên số lượng queries
document.addEventListener('DOMContentLoaded', function() {
    // Lấy thông tin từ Laravel (có thể truyền qua view)
    const queryCount = {{ $queryCount ?? 5 }}; // Số lượng queries
    const pageComplexity = '{{ $pageComplexity ?? "medium" }}'; // simple, medium, complex
    
    // Tính toán thời gian load dựa trên độ phức tạp
    let loadTime = 1000; // Base time
    
    switch(pageComplexity) {
        case 'simple':
            loadTime = 800;
            break;
        case 'medium':
            loadTime = 1200;
            break;
        case 'complex':
            loadTime = 2000;
            break;
    }
    
    // Thêm thời gian dựa trên số queries
    loadTime += (queryCount * 100);
    
    // Cập nhật preloader timing
    if (window.updatePreloaderTiming) {
        window.updatePreloaderTiming(loadTime);
    }
});
</script>

