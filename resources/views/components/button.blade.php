@props([
    'variant' => 'primary', // primary, secondary, danger, outline, ghost
    'size' => 'md', // xs, sm, md, lg, xl
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left', // left, right
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$sizeClasses = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-4 py-2 text-base',
    'xl' => 'px-6 py-3 text-base',
];

$variantClasses = [
    'primary' => 'bg-gray-900 text-white hover:bg-gray-700 focus:ring-gray-900 shadow-sm',
    'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600 shadow-sm',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
    'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
    'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:text-gray-300 dark:hover:bg-gray-700',
];

$iconSizes = [
    'xs' => 'w-3 h-3',
    'sm' => 'w-4 h-4',
    'md' => 'w-4 h-4',
    'lg' => 'w-5 h-5',
    'xl' => 'w-5 h-5',
];

$classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
$iconSize = $iconSizes[$size];

$tag = $href ? 'a' : 'button';
$attributes = $href ? $attributes->merge(['href' => $href]) : $attributes->merge(['type' => $type]);

if ($disabled) {
    $attributes = $attributes->merge(['disabled' => true]);
}
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 {{ $iconSize }} text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    @else
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" :size="$size === 'xs' ? 'xs' : 'sm'" class="mr-2" />
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" :size="$size === 'xs' ? 'xs' : 'sm'" class="ml-2" />
        @endif
    @endif
</{{ $tag }}>
