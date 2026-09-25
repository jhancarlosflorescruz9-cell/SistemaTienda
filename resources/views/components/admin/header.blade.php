@props(['titulo', 'icono' => 'fa-solid fa-list', 'seccion' => null])
<div class="flex items-center gap-2 text-sm mb-4">
    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-slate-700 flex items-center gap-1 no-underline">
        <i class="fa-solid fa-house"></i>Dashboard</a>
    @if ($seccion)
        <span class="text-slate-400">/</span>
        <span class="text-slate-700 font-semibold">{{ $seccion }}</span>
    @endif
</div>
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h1 class="flex items-center gap-2 text-sm font-semibold text-slate-700 m-0">
        <i class="{{ $icono }} text-slate-600"></i>
        {{ $titulo }}
    </h1>
    <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
</div>
