@props(['activo'])
<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $activo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
    {{ $activo ? 'Activo' : 'Inactivo' }}
</span>
