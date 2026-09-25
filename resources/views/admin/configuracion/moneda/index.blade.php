@extends('layouts.admin.app')
@section('title', 'Monedas')
@section('js')
@endsection
@section('content')
    <div class="flex items-center gap-2 text-sm mb-4">
        <a href="{{ route('configuracion.index') }}"
           class="text-slate-400 hover:text-slate-700 flex items-center gap-1">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>
        <span class="text-slate-400">/</span>
        <span class="text-slate-700 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-gear"></i>
            Configuración
        </span>
    </div>

    <h1 class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-4">
        <i class="fa-solid fa-coins"></i>
        Listado de monedas
    </h1>


    <div class="bg-white rounded-3xl shadow-md p-4 sm:p-6">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="py-2 font-semibold w-16">#</th>
                        <th class="py-2 font-semibold">Código</th>
                        <th class="py-2 font-semibold">Descripción</th>
                        <th class="py-2 font-semibold">Símbolo</th>
                        <th class="py-2 font-semibold">Estado</th>
                        <th class="py-2 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monedas as $index => $moneda)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $moneda->codigo }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $moneda->descripcion }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $moneda->simbolo }}</td>
                            <td class="py-2">
                                @if($moneda->estado === 'Si')
                                    <span class="text-green-600 font-semibold">
                                        Si
                                    </span>
                                @else
                                    <span class="text-red-600 font-semibold">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="py-2">
                                <div class="flex justify-end gap-2 whitespace-nowrap">
                                    <button type="button"
                                        onclick="document.getElementById('modalEditarMoneda{{ $moneda->id }}').classList.remove('hidden')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #64DD17;">
                                        Editar
                                    </button>

                                    <div id="modalEditarMoneda{{ $moneda->id }}"
                                        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-6 sm:pt-24 overflow-y-auto">
                                        <div class="absolute inset-0 bg-black/10"
                                            onclick="document.getElementById('modalEditarMoneda{{ $moneda->id }}').classList.add('hidden')">
                                        </div>
                                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-3 sm:mx-4 p-4 sm:p-6 my-4 sm:my-0">
                                            <div class="flex items-center justify-between mb-6">
                                                <h2 class="text-lg font-semibold text-slate-800">
                                                    <i class="fa-solid fa-coins text-slate-600"></i>
                                                    Editar Moneda
                                                </h2>
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditarMoneda{{ $moneda->id }}').classList.add('hidden')"
                                                    class="text-slate-400 hover:text-slate-600">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>

                                            {{-- Formulario --}}
                                            <form action="{{ route('configuracion.moneda.update', $moneda->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex flex-col md:flex-row gap-4">
                                                    <div class="flex-1">
                                                        <label for="codigo{{ $moneda->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                            Código
                                                        </label>
                                                        <input type="text"id="codigo{{ $moneda->id }}" name="codigo" value="{{ $moneda->codigo }}"
                                                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                    {{-- Descripción --}}
                                                    <div class="flex-1">
                                                        <label for="descripcion{{ $moneda->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                            Descripción
                                                        </label>
                                                        <input type="text"id="descripcion{{ $moneda->id }}" name="descripcion" value="{{ $moneda->descripcion }}"
                                                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                    {{-- Símbolo --}}
                                                    <div class="flex-1">
                                                        <label for="simbolo{{ $moneda->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                            Símbolo
                                                        </label>
                                                        <input type="text" id="simbolo{{ $moneda->id }}" name="simbolo" value="{{ $moneda->simbolo }}"
                                                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                    {{-- Estado --}}
                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-blue-700 mb-2">
                                                            Activo
                                                        </label>
                                                        <label class="inline-flex items-center cursor-pointer gap-3">
                                                            <input type="hidden"name="estado"value="No">
                                                            <input type="checkbox"name="estado" value="Si" class="sr-only peer" {{ $moneda->estado == 'Si' ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700">
                                                                No
                                                            </span>
                                                            <div class="relative w-11 h-6 bg-slate-300 rounded-full
                                                                peer peer-checked:after:translate-x-full
                                                                after:content-['']
                                                                after:absolute after:top-[2px] after:left-[2px]
                                                                after:bg-white after:border-slate-300 after:border
                                                                after:rounded-full after:h-5 after:w-5
                                                                after:transition-all peer-checked:bg-[#0407e2]">
                                                            </div>
                                                            <span class="text-sm font-medium text-[#0407e2]">
                                                                Sí
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- Botones --}}
                                                <div class="flex justify-end gap-3 mt-8">
                                                    <button type="button"
                                                        onclick="document.getElementById('modalEditarMoneda{{ $moneda->id }}').classList.add('hidden')"
                                                        class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
                                                        Actualizar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <button type="button"
                                        onclick="confirmarEliminar('{{ route('configuracion.moneda.destroy', $moneda->id) }}')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #D50000;">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">
                                No hay monedas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button type="button"
            onclick="document.getElementById('modalNuevoMoneda').classList.remove('hidden')"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i>
            Nuevo
        </button>

    </div>


    <div id="modalNuevoMoneda"
        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-6 sm:pt-24 overflow-y-auto">
        <div class="absolute inset-0 bg-black/10"
            onclick="document.getElementById('modalNuevoMoneda').classList.add('hidden')">
        </div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-3 sm:mx-4 p-4 sm:p-6 my-4 sm:my-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-coins text-slate-600"></i>
                    Nueva Moneda
                </h2>
                <button type="button"
                    onclick="document.getElementById('modalNuevoMoneda').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            {{-- Formulario --}}
            <form action="{{ route('configuracion.moneda.store') }}"
                method="POST">
                @csrf
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="codigo" class="block text-sm font-medium text-blue-700 mb-1">
                            Código
                        </label>
                        <input type="text" id="codigo" name="codigo"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    {{-- Descripción --}}
                    <div class="flex-1">
                        <label for="descripcion" class="block text-sm font-medium text-blue-700 mb-1">
                            Descripción
                        </label>
                        <input type="text" id="descripcion" name="descripcion"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    {{-- Símbolo --}}
                    <div class="flex-1">
                        <label for="simbolo"
                            class="block text-sm font-medium text-blue-700 mb-1">
                            Símbolo
                        </label>
                        <input type="text" id="simbolo" name="simbolo"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    {{-- Estado --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-blue-700 mb-2">
                            Activo
                        </label>
                        <label class="inline-flex items-center cursor-pointer gap-3">
                            <input type="hidden" name="estado" value="No">
                            <input type="checkbox" name="estado" value="Si" class="sr-only peer"checked>
                            <span class="text-sm font-medium text-slate-700">
                                No
                            </span>
                            <div class="relative w-11 h-6 bg-slate-300 rounded-full
                                peer peer-checked:after:translate-x-full
                                after:content-['']
                                after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:border-slate-300 after:border
                                after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:bg-[#0407e2]">
                            </div>
                            <span class="text-sm font-medium text-[#0407e2]">
                                Sí
                            </span>
                        </label>
                    </div>
                </div>
                {{-- Botones --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button"
                        onclick="document.getElementById('modalNuevoMoneda').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
