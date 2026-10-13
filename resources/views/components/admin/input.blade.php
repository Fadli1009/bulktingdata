@props(['label' => null, 'name' => null, 'id' => null, 'hint' => null, 'type' => 'text', 'required' => false])

@php
    $classes = 'block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 shadow-sm shadow-slate-900/[0.02] transition-all duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 disabled:bg-slate-50 disabled:cursor-not-allowed';
@endphp

@if ($label)
    <label for="{{ $id ?? $name }}" class="mb-1 block text-sm font-medium text-slate-700">
        {{ $label }}
        @if ($required)<span class="ml-0.5 text-rose-500">*</span>@endif
        @if ($hint)<span class="font-normal text-slate-400"> ({{ $hint }})</span>@endif
    </label>
@endif

<input type="{{ $type }}" @if ($name) name="{{ $name }}" @endif @if ($required) required @endif {{ $attributes->merge(['class' => $classes]) }}>

@if ($name)
    @error($name)<p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
@endif
