@props(['type' => 'success', 'message' => null])
@php $text = $message ?? session($type); @endphp
@if ($text)
    <div class="alert alert-{{ $type }} alert-dismissible fade show">
        {{ $text }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
