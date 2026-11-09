@props([
    'name',
    'label',
    'type' => 'text',
])

@php
    $hasError = $errors->has($name);
@endphp

<div>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-navy-900">{{ $label }}</label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if (! in_array($type, ['password', 'file']))
            value="{{ old($name) }}"
        @endif
        @if ($hasError)
            aria-invalid="true"
            aria-describedby="{{ $name }}-error"
        @endif
        {{ $attributes->class([
            'form-input',
            'form-input-error' => $hasError,
            'form-file' => $type === 'file',
        ]) }}
    >

    @isset($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endisset

    @error($name)
        <p id="{{ $name }}-error" class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>

