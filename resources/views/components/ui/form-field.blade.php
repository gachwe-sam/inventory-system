@props(['name', 'label', 'type' => 'text', 'value' => null, 'help' => null])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>