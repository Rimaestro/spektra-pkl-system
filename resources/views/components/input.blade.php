@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'iconPosition' => 'left', // left, right
])

@php
$inputId = $attributes->get('id', 'input-' . Str::random(6));
$inputName = $attributes->get('name', '');

$baseClasses = 'block w-full rounded-lg border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 placeholder-neutral-500 dark:placeholder-neutral-400 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 sm:text-sm';

$errorClasses = $error ? 'border-error-300 focus:border-error-500 focus:ring-error-500' : '';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';

$iconClasses = $icon ? ($iconPosition === 'left' ? 'pl-10' : 'pr-10') : '';

$classes = $baseClasses . ' ' . $errorClasses . ' ' . $disabledClasses . ' ' . $iconClasses;
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 {{ $iconPosition === 'left' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
                <x-icon :name="$icon" size="sm" class="text-neutral-400" />
            </div>
        @endif

        <input 
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $inputName }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => $classes]) }}
        />
    </div>

    @if($error)
        <p class="text-sm text-error-600 dark:text-error-400">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $help }}</p>
    @endif
</div>
