@props(['value' => null])

@if (is_null($value))
    —
@else
    Rp {{ number_format((float) $value, 0, ',', '.') }}
@endif
