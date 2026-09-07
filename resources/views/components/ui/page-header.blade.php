@props(['title', 'actionLabel' => null, 'actionRoute' => null])
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h2 class="mb-0">{{ $title }}</h2>
    <div class="d-flex gap-2">
        @if ($actionLabel && $actionRoute)
            <a href="{{ $actionRoute }}" class="btn btn-primary">{{ $actionLabel }}</a>
        @endif
        {{ $slot ?? '' }}
    </div>
</div>
