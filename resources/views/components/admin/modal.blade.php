@props(['id', 'titulo', 'icono' => 'fa-solid fa-pen', 'ancho' => 'max-w-lg'])
{{-- Modal estilo Contfast. Abrir con: abrirModal('id') --}}
<div id="{{ $id }}" class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-16 overflow-y-auto">
    <div class="absolute inset-0 bg-black/10" onclick="cerrarModal('{{ $id }}')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full {{ $ancho }} mx-4 p-6 mb-10 text-left">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <i class="{{ $icono }} text-slate-600"></i>{{ $titulo }}
            </h2>
            <button type="button" onclick="cerrarModal('{{ $id }}')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
