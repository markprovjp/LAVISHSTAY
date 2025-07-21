@props(['type' => 'info', 'title', 'message', 'count'])

@php
$typeClasses = [
    'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
    'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800',
    'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
    'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'
];

$iconClasses = [
    'error' => 'text-red-600 dark:text-red-400',
    'warning' => 'text-yellow-600 dark:text-yellow-400',
    'info' => 'text-blue-600 dark:text-blue-400',
    'success' => 'text-green-600 dark:text-green-400'
];

$textClasses = [
    'error' => 'text-red-800 dark:text-red-200',
    'warning' => 'text-yellow-800 dark:text-yellow-200',
    'info' => 'text-blue-800 dark:text-blue-200',
    'success' => 'text-green-800 dark:text-green-200'
];

$icons = [
    'error' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
    'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z',
    'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
];
@endphp

<div class="flex items-center p-3 border rounded-lg {{ $typeClasses[$type] }}">
    <div class="flex-shrink-0">
        <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$type] }}"></path>
        </svg>
    </div>
    <div class="ml-3 flex-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium {{ $textClasses[$type] }}">
                    {{ $count }} {{ $title }}
                </p>
                <p class="text-xs {{ $textClasses[$type] }} opacity-75">
                    {{ $message }}
                </p>
            </div>
            <div class="text-2xl font-bold {{ $iconClasses[$type] }}">
                {{ $count }}
            </div>
        </div>
    </div>
</div>
