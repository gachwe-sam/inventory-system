@props(['fallback' => null])
<a href="{{ $fallback ?? route('dashboard') }}" class="btn btn-outline-secondary btn-sm" data-back-button>
    <i class="bi bi-arrow-left"></i>
</a>