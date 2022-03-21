@props(['label', 'name', 'type'=>'text', 'value'=>''])
<div class="form-group">
    <Label>{{ $label }}</Label>
    <input
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ old($name,$value) }}"
    class="form-control{{ $errors->has($name) ? ' is-invalid' : ''}}"
    >
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- div.form-group>label+input[name="$name" type="$type" value="old$name,$value" class="form-control$error->has$name?is-invalid:"]+div.invalid-feedback --}}
