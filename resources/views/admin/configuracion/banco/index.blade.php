@extends('layouts.admin.app')

@section('title', 'Bancos')

@section('js')
    
@endsection

@section('content')

    <div class="flex items-center gap-2 text-sm mb-4">
        <a href="{{ route('configuracion.index') }}"
        class="text-slate-400 hover:text-slate-700 flex items-center gap-1">
            <i class="fa-solid fa-house"></i>Dashboard</a>
        <span class="text-slate-400">/</span>
        <span class="text-slate-700 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-gear"></i>Configuración
        </span>
    </div>
    <h1 class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-4">
        <i class="fa-solid fa-building-columns text-slate-600"></i>
        Listado de bancos
    </h1>
        

    <div class="bg-white rounded-3xl shadow-md p-6">
        <table class="w-full text-left text-sm">
            <thead>
               <tr class="border-b border-slate-200 text-black">
                    <th class=" font-semibold w-16">#</th>
                    <th class=" font-semibold">Descripción</th>
                    <th class=" font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bancos as $index => $banco)
                    <tr class="border-b border-slate-100">
                        <td class="py-2 text-slate-600">{{ $index + 1 }}</td>

                        <td class="py-2 text-blue-800 font-medium">
                            {{ $banco->descripcion }}
                        </td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="document.getElementById('modalEditarBanco{{ $banco->id }}').classList.remove('hidden')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                    style="background-color: #64DD17;">
                                    Editar
                                </button>
                                <div id="modalEditarBanco{{ $banco->id }}"
                                    class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">

                                    <!-- Fondo -->
                                    <div class="absolute inset-0 bg-black/10"
                                        onclick="document.getElementById('modalEditarBanco{{ $banco->id }}').classList.add('hidden')">
                                    </div>

                                    <!-- Modal -->
                                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                                        <div class="flex items-center justify-between mb-6">
                                            <h2 class="text-lg font-semibold text-slate-800">
                                                <i class="fa-solid fa-building-columns text-slate-600"></i>
                                                Editar Banco
                                            </h2>
                                            <button type="button"
                                                    onclick="document.getElementById('modalEditarBanco{{ $banco->id }}').classList.add('hidden')"
                                                    class="text-slate-400 hover:text-slate-600">

                                                <i class="fa-solid fa-xmark"></i>
                                            </button>

                                        </div>

                                        <form action="{{ route('configuracion.banco.update', $banco->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <label for="descripcion{{ $banco->id }}"
                                                class="block text-sm font-medium text-blue-700 mb-1">
                                                Descripción
                                            </label>
                                            <input type="text"
                                                id="descripcion{{ $banco->id }}"
                                                name="descripcion"
                                                value="{{ $banco->descripcion }}"
                                                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                            <div class="flex justify-end gap-3 mt-8">
                                                <button type="button"
                                                        onclick="document.getElementById('modalEditarBanco{{ $banco->id }}').classList.add('hidden')"
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
                                    onclick="confirmarEliminar('{{ route('configuracion.banco.destroy', $banco->id) }}')"
                                    class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                    style="background-color: #D50000;">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-slate-500">
                            No hay bancos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button type="button"
            onclick="document.getElementById('modalNuevoBanco').classList.remove('hidden')"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i>
            Nuevo
        </button>

    </div>

    <div id="modalNuevoBanco" class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
        <div class="absolute inset-0 bg-black/10"
             onclick="document.getElementById('modalNuevoBanco').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-building-columns text-slate-600"></i>Nuevo Banco</h2>
                <button type="button"
                        onclick="document.getElementById('modalNuevoBanco').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('configuracion.banco.store') }}" method="POST">
                @csrf
                <label for="descripcion" class="block text-sm font-medium text-blue-700 mb-1">
                    Descripción
                </label>
                <input type="text" id="descripcion" name="descripcion"class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('modalNuevoBanco').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">
                        Cancelar
                    </button>
                    <button type="submit"class="px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection