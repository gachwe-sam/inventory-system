@props(['name', 'label' => null, 'placeholder' => null, 'value' => null, 'target' => null])
<div class="mb-3">
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
           @if ($target) data-live-search data-target="{{ $target }}" @endif
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
