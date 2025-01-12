@props(['checked' => false])
<label class="switch">
    <input type="checkbox" {{ $attributes }} @checked($checked)>
    <span class="slider round"></span>
</label>
