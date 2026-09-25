@extends('layouts.admin.app')
@section('title', 'Métodos de pago - ingreso / gastos')
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
        <i class="fa-solid fa-money-bill-transfer text-slate-600"></i>
        Métodos de pago - ingreso / gastos
    </h1>

    {{--Métodos metodo de gasto--}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-arrow-up text-red-500"></i>
                Métodos de gasto
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold w-16">#</th>
                        <th class="font-semibold">Descripción</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metodosGasto as $index => $metodo)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $metodo->descripcion }}</td>
                            <td class="py-2">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        onclick="document.getElementById('modalEditarMetodoGasto{{ $metodo->id }}').classList.remove('hidden')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #64DD17;">
                                        Editar
                                    </button>

                                    {{-- MODAL EDITAR --}}
                                    <div id="modalEditarMetodoGasto{{ $metodo->id }}"
                                        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
                                        <div class="absolute inset-0 bg-black/10"
                                            onclick="document.getElementById('modalEditarMetodoGasto{{ $metodo->id }}').classList.add('hidden')">
                                        </div>
                                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                                            <div class="flex items-center justify-between mb-6">
                                                <h2 class="text-lg font-semibold text-slate-800">
                                                    <i class="fa-solid fa-money-bill-transfer text-slate-600"></i>
                                                    Editar Método de Gasto
                                                </h2>
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditarMetodoGasto{{ $metodo->id }}').classList.add('hidden')"
                                                    class="text-slate-400 hover:text-slate-600">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('configuracion.metodogasto.update', $metodo->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                {{-- Descripción --}}
                                                <label for="descripcion{{ $metodo->id }}"
                                                    class="block text-sm font-medium text-blue-700 mb-1">
                                                    Descripción
                                                </label>
                                                <input type="text" id="descripcion{{ $metodo->id }}" name="descripcion" value="{{ $metodo->descripcion }}" maxlength="150"
                                                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                                                <div class="flex justify-end gap-3 mt-8">
                                                    <button type="button"
                                                        onclick="document.getElementById('modalEditarMetodoGasto{{ $metodo->id }}').classList.add('hidden')"
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

                                    {{-- ELIMINAR --}}
                                    <button type="button"
                                        onclick="confirmarEliminar('{{ route('configuracion.metodogasto.destroy', $metodo->id) }}')"
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
                                No hay métodos de gasto registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button type="button"
            onclick="document.getElementById('modalNuevoMetodoGasto').classList.remove('hidden')"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i>
            Nuevo
        </button>
    </div>
   
    <div id="modalNuevoMetodoGasto"
        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
        <div class="absolute inset-0 bg-black/10" onclick="document.getElementById('modalNuevoMetodoGasto').classList.add('hidden')">
        </div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-money-bill-transfer text-slate-600"></i>
                    Nuevo Método de Gasto
                </h2>
                <button type="button"
                    onclick="document.getElementById('modalNuevoMetodoGasto').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('configuracion.metodogasto.store') }}"
                method="POST">
                @csrf
                {{-- Descripción --}}
                <label for="descripcion" class="block text-sm font-medium text-blue-700 mb-1">
                    Descripción
                </label>
                <input type="text" id="descripcion" name="descripcion" maxlength="150"
                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button"
                        onclick="document.getElementById('modalNuevoMetodoGasto').classList.add('hidden')"
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




    {{--Métodos de pago - ingreso--}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-arrow-up text-red-500"></i>
                Métodos de pago - ingreso
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold w-16">#</th>
                        <th class="font-semibold">Código</th>
                        <th class="font-semibold">Descripción</th>
                        <th class="font-semibold">Condición de pago</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metodosPago as $index => $metodo)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $metodo->codigo }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $metodo->descripcion }}</td>
                            <td class="py-2 text-slate-600">{{ $metodo->condicion_pago }}</td>
                            <td class="py-2">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        onclick="document.getElementById('modalEditarMetodoPago{{ $metodo->id }}').classList.remove('hidden')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #64DD17;">
                                        Editar
                                    </button>

                                    {{-- MODAL EDITAR --}}
                                    <div id="modalEditarMetodoPago{{ $metodo->id }}"
                                        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
                                        <div class="absolute inset-0 bg-black/10"
                                            onclick="document.getElementById('modalEditarMetodoPago{{ $metodo->id }}').classList.add('hidden')">
                                        </div>
                                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                                            <div class="flex items-center justify-between mb-6">
                                                <h2 class="text-lg font-semibold text-slate-800">
                                                    <i class="fa-solid fa-money-bill-transfer text-slate-600"></i>
                                                    Editar Nuevo método de pago
                                                </h2>
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditarMetodoPago{{ $metodo->id }}').classList.add('hidden')"
                                                    class="text-slate-400 hover:text-slate-600">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('configuracion.metodopago.update', $metodo->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                {{-- codigo --}}
                                                <label for="codigo{{ $metodo->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                    Código
                                                </label>
                                                <input type="text" id="codigo{{ $metodo->id }}" name="codigo" value="{{ $metodo->codigo }}" maxlength="10"
                                                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                                                {{-- Descripción --}}
                                                <label for="descripcion{{ $metodo->id }}"
                                                    class="block text-sm font-medium text-blue-700 mb-1">
                                                    Descripción
                                                </label>
                                                <input type="text"id="descripcion{{ $metodo->id }}"name="descripcion" value="{{ $metodo->descripcion }}" maxlength="150"
                                                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                                                {{-- Condición de pago --}}
                                                <label for="condicion_pago{{ $metodo->id }}" class="block text-sm font-medium text-blue-700 mb-1">
                                                    Condición de pago
                                                </label>
                                                <select name="condicion_pago" id="condicion_pago{{ $metodo->id }}"
                                                    class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    <option value="Contado"
                                                        {{ $metodo->condicion_pago == 'Contado' ? 'selected' : '' }}>
                                                        Contado
                                                    </option>
                                                    <option value="Crédito"
                                                        {{ $metodo->condicion_pago == 'Crédito' ? 'selected' : '' }}>
                                                        Crédito
                                                    </option>
                                                </select>
                                                <div class="flex justify-end gap-3 mt-8">
                                                    <button type="button"
                                                        onclick="document.getElementById('modalEditarMetodoPago{{ $metodo->id }}').classList.add('hidden')"
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


                                    {{-- ELIMINAR --}}
                                    <button type="button"
                                        onclick="confirmarEliminar('{{ route('configuracion.metodopago.destroy', $metodo->id) }}')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #D50000;">
                                        Eliminar
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">
                                No hay métodos de pago registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <button type="button"
            onclick="document.getElementById('modalNuevoMetodoPago').classList.remove('hidden')"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i>
            Nuevo
        </button>
    </div>
    <div id="modalNuevoMetodoPago"
        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-24">
        <div class="absolute inset-0 bg-black/10" onclick="document.getElementById('modalNuevoMetodoPago').classList.add('hidden')">
        </div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-money-bill-transfer text-slate-600"></i>
                    Nuevo método de pago
                </h2>
                <button type="button"
                    onclick="document.getElementById('modalNuevoMetodoPago').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('configuracion.metodopago.store') }}"
                method="POST">
                @csrf
                {{-- Código --}}
                <label for="codigo" class="block text-sm font-medium text-blue-700 mb-1">
                    Código
                </label>
                <input type="text" id="codigo" name="codigo" maxlength="10"
                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                {{-- Descripción --}}
                <label for="descripcion" class="block text-sm font-medium text-blue-700 mb-1">
                    Descripción
                </label>
                <input type="text" id="descripcion" name="descripcion" maxlength="150"
                class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 mb-4">
                {{-- Condición de pago --}}
                <label for="condicion_pago"class="block text-sm font-medium text-blue-700 mb-1">
                    Condición de pago
                </label>
                <select name="condicion_pago" id="condicion_pago"
                    class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="Contado">Contado</option>
                    <option value="Crédito">Crédito</option>
                </select>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button"
                        onclick="document.getElementById('modalNuevoMetodoPago').classList.add('hidden')"
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