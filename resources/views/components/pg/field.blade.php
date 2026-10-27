@props(['label', 'name', 'type' => 'text', 'hint' => null, 'value' => null, 'autocomplete' => null, 'inputmode' => null, 'maxlength' => null])

<label class="pg-field {{ $errors->has($name) ? 'has-err' : '' }}">
  {{ $label }}
  <input type="{{ $type }}" name="{{ $name }}" @if($type !== 'password' && $type !== 'file') value="{{ old($name, $value) }}" @endif
         @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
         @if($inputmode) inputmode="{{ $inputmode }}" @endif
         @if($maxlength) maxlength="{{ $maxlength }}" @endif
         {{ $attributes }}>
  @if($hint)<small>{{ $hint }}</small>@endif
  @error($name)<span class="err">{{ $message }}</span>@enderror
</label>
