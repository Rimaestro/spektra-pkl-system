@props([
    'variant' => 'default', // default, bordered, elevated, flat
    'padding' => 'md', // none, sm, md, lg, xl
    'header' => null,
    'footer' => null,
])

@php
$baseClasses = 'bg-white dark:bg-neutral-800 rounded-xl transition-all duration-200';

$variantClasses = [
    'default' => 'shadow-soft border border-neutral-200 dark:border-neutral-700',
    'bordered' => 'border-2 border-neutral-200 dark:border-neutral-700',
    'elevated' => 'shadow-large border border-neutral-200 dark:border-neutral-700',
    'flat' => 'border border-neutral-100 dark:border-neutral-800',
];

$paddingClasses = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10',
];

$classes = $baseClasses . ' ' . $variantClasses[$variant];
$contentPadding = $paddingClasses[$padding];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($header)
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $header || $footer ? 'px-6 py-4' : $contentPadding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 rounded-b-xl">
            {{ $footer }}
        </div>
    @endif
</div>
