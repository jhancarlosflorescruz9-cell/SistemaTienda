@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false])
<div {{ $attributes->only('class') }}>
    <label class="block text-sm font-medium text-blue-700 mb-1">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" @required($required)
        {{ $attributes->except('class') }}
        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
</div>
