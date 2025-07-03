@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => false,
    'icon' => true,
])

@php
use Illuminate\Support\Str;

$alertId = 'alert-' . Str::random(6);

$typeConfig = [
    'success' => [
        'classes' => 'bg-success-50 border-success-200 text-success-800 dark:bg-success-900/20 dark:border-success-800 dark:text-success-200',
        'iconClasses' => 'text-success-400',
        'icon' => 'check-circle',
    ],
    'error' => [
        'classes' => 'bg-error-50 border-error-200 text-error-800 dark:bg-error-900/20 dark:border-error-800 dark:text-error-200',
        'iconClasses' => 'text-error-400',
        'icon' => 'x-circle',
    ],
    'warning' => [
        'classes' => 'bg-warning-50 border-warning-200 text-warning-800 dark:bg-warning-900/20 dark:border-warning-800 dark:text-warning-200',
        'iconClasses' => 'text-warning-400',
        'icon' => 'exclamation-triangle',
    ],
    'info' => [
        'classes' => 'bg-gray-50 border-gray-200 text-gray-800 dark:bg-gray-900/20 dark:border-gray-800 dark:text-gray-200',
        'iconClasses' => 'text-gray-400',
        'icon' => 'information-circle',
    ],
];

$config = $typeConfig[$type];
$baseClasses = 'border rounded-lg p-4 ' . $config['classes'];

// Simple icons for alerts (since we don't have all heroicons)
$iconPaths = [
    'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'x-circle' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    'exclamation-triangle' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z',
    'information-circle' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];
@endphp

<div id="{{ $alertId }}" {{ $attributes->merge(['class' => $baseClasses]) }} role="alert">
    <div class="flex">
        @if($icon)
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 {{ $config['iconClasses'] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$config['icon']] ?? $iconPaths['information-circle'] }}" />
                </svg>
            </div>
        @endif
        
        <div class="{{ $icon ? 'ml-3' : '' }} flex-1">
            @if($title)
                <h3 class="text-sm font-medium mb-1">{{ $title }}</h3>
            @endif
            
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        type="button" 
                        class="inline-flex rounded-md p-1.5 hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-current"
                        onclick="document.getElementById('{{ $alertId }}').remove()"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
