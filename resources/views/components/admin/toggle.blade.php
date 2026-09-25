@props(['name', 'label', 'checked' => false])
<label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer select-none">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($checked) class="w-4 h-4 accent-[#0407e2]">
    {{ $label }}
</label>
