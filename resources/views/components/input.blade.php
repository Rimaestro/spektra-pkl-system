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
use Illuminate\Support\Str;

$inputId = $attributes->get('id', 'input-' . Str::random(6));
$inputName = $attributes->get('name', '');

$baseClasses = 'block w-full rounded-lg border border-gray-200 bg-white text-gray-900 placeholder-gray-500 focus:border-gray-900 focus:ring-gray-900 transition-colors duration-200 text-base py-3 px-4';

$errorClasses = $error ? 'border-error-300 focus:border-error-500 focus:ring-error-500' : '';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';

$iconClasses = $icon ? ($iconPosition === 'left' ? 'pl-12' : 'pr-12') : '';

$classes = $baseClasses . ' ' . $errorClasses . ' ' . $disabledClasses . ' ' . $iconClasses;
@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-900">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 {{ $iconPosition === 'left' ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center pointer-events-none">
                <x-icon :name="$icon" size="md" class="text-neutral-400" />
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
