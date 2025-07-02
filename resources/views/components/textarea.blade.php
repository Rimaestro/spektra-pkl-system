@props([
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'rows' => 4,
])

@php
$textareaId = $attributes->get('id', 'textarea-' . Str::random(6));
$textareaName = $attributes->get('name', '');

$baseClasses = 'block w-full rounded-lg border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 placeholder-neutral-500 dark:placeholder-neutral-400 focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200 sm:text-sm resize-vertical';

$errorClasses = $error ? 'border-error-300 focus:border-error-500 focus:ring-error-500' : '';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';

$classes = $baseClasses . ' ' . $errorClasses . ' ' . $disabledClasses;
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $textareaId }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif

    <textarea 
        id="{{ $textareaId }}"
        name="{{ $textareaName }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >{{ $slot }}</textarea>

    @if($error)
        <p class="text-sm text-error-600 dark:text-error-400">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $help }}</p>
    @endif
</div>
