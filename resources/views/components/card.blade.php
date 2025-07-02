{{--
Card Component - SPEKTRA PKL System

A flexible card component with consistent styling and multiple variants.

Props:
- variant: 'default' | 'bordered' | 'elevated' | 'flat' (default: 'default')
- padding: 'none' | 'sm' | 'md' | 'lg' | 'xl' (default: 'md')
- header: Optional header content
- footer: Optional footer content

Usage Examples:
<x-card>
    Basic card content
</x-card>

<x-card variant="elevated" padding="lg">
    <x-slot:header>
        <h3 class="text-lg font-semibold">Card Title</h3>
    </x-slot:header>

    Card content with header

    <x-slot:footer>
        <button class="btn btn-primary">Action</button>
    </x-slot:footer>
</x-card>
--}}

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
    'none' => ['content' => '', 'header' => '', 'footer' => ''],
    'sm' => ['content' => 'p-4', 'header' => 'px-4 py-3', 'footer' => 'px-4 py-3'],
    'md' => ['content' => 'p-6', 'header' => 'px-6 py-4', 'footer' => 'px-6 py-4'],
    'lg' => ['content' => 'p-8', 'header' => 'px-8 py-5', 'footer' => 'px-8 py-5'],
    'xl' => ['content' => 'p-10', 'header' => 'px-10 py-6', 'footer' => 'px-10 py-6'],
];

$classes = $baseClasses . ' ' . $variantClasses[$variant];
$contentPadding = $paddingClasses[$padding]['content'];
$headerPadding = $paddingClasses[$padding]['header'];
$footerPadding = $paddingClasses[$padding]['footer'];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($header)
        <div class="{{ $headerPadding }} border-b border-neutral-200 dark:border-neutral-700">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $contentPadding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="{{ $footerPadding }} border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50 rounded-b-xl">
            {{ $footer }}
        </div>
    @endif
</div>
