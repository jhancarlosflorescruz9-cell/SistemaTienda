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
        <i class="fa-solid fa-credit-card text-slate-600"></i>
        Listado de cuentas bancarias
    </h1>
        

    <div class="bg-white rounded-3xl shadow-md p-3 sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-[1000px] w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-black">
                        <th class="font-semibold w-16">#</th>
                        <th class="font-semibold">Banco</th>
                        <th class="font-semibold">Descripción</th>
                        <th class="font-semibold">Número</th>
                        <th class="font-semibold">Moneda</th>
                        <th class="font-semibold">CCI</th>
                        <th class="font-semibold">Saldo inicial</th>
                        <th class="font-semibold">Comprobante</th>
                        <th class="font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuentasBancarias as $index => $cuenta)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $cuenta->banco->descripcion }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $cuenta->descripcion }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $cuenta->numero }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $cuenta->moneda->descripcion }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ $cuenta->cci }}</td>
                            <td class="py-2 text-blue-800 font-medium">{{ number_format($cuenta->saldo_inicial, 2) }}</td>
                            <td class="py-2">
                                @if($cuenta->mostrar_comprobante == 'Si')
                                    <span class="text-green-600 font-semibold">Sí</span>
                                @else
                                    <span class="text-red-600 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="py-2">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        onclick="document.getElementById('modalEditarCuentaBanco{{ $cuenta->id }}').classList.remove('hidden')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #64DD17;">
                                        Editar
                                    </button>
                                    {{-- MODAL EDITAR --}}
                                    <div id="modalEditarCuentaBanco{{ $cuenta->id }}"
                                        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-6 sm:pt-24 overflow-y-auto">
                                        <div class="absolute inset-0 bg-black/10"
                                            onclick="document.getElementById('modalEditarCuentaBanco{{ $cuenta->id }}').classList.add('hidden')">
                                        </div>
                                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-3 sm:mx-4 p-4 sm:p-6 my-4 sm:my-0">
                                            <div class="flex items-center justify-between mb-6">
                                                <h2 class="text-lg font-semibold text-slate-800">
                                                    <i class="fa-solid fa-credit-card text-slate-600"></i>
                                                    Editar Cuenta Bancaria
                                                </h2>
                                                <button type="button"
                                                    onclick="document.getElementById('modalEditarCuentaBanco{{ $cuenta->id }}').classList.add('hidden')"
                                                    class="text-slate-400 hover:text-slate-600">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('configuracion.cuentabancaria.update', $cuenta->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex flex-col md:flex-row gap-4">
                                                    <div class="flex-1">
                                                        <label for="banco_id{{ $cuenta->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                            Banco
                                                        </label>
                                                        <select id="banco_id{{ $cuenta->id }}"
                                                            name="banco_id"
                                                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                            @foreach($bancos as $banco)
                                                                <option value="{{ $banco->id }}"
                                                                    {{ $cuenta->banco_id == $banco->id ? 'selected' : '' }}>
                                                                    {{ $banco->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    {{-- Descripción --}}
                                                    <div class="flex-1">
                                                        <label for="descripcion{{ $cuenta->id }}"class="block text-sm font-medium text-blue-700 mb-1">
                                                            Descripción
                                                        </label>
                                                        <input type="text" id="descripcion{{ $cuenta->id }}" name="descripcion" value="{{ $cuenta->descripcion }}"
                                                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                </div>
                                                {{-- Número y moneda --}}
                                                <div class="flex flex-col md:flex-row gap-4 mt-4">
                                                    <div class="flex-1">
                                                        <label for="numero{{ $cuenta->id }}" class="block text-sm font-medium text-blue-700 mb-1">
                                                        Número
                                                        </label>
                                                        <input type="number" id="numero{{ $cuenta->id }}" name="numero" value="{{ $cuenta->numero }}"
                                                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                    {{-- Moneda --}}
                                                    <div class="flex-1">
                                                        <label for="moneda_id{{ $cuenta->id }}" class="block text-sm font-medium text-blue-700 mb-1">
                                                        Moneda
                                                        </label>
                                                        <select id="moneda_id{{ $cuenta->id }}"
                                                            name="moneda_id"
                                                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                            @foreach($monedas as $moneda)
                                                                <option value="{{ $moneda->id }}" {{ $cuenta->moneda_id == $moneda->id ? 'selected' : '' }}>
                                                                {{ $moneda->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col md:flex-row gap-4 mt-4">
                                                    {{-- CCI --}}
                                                    <div class="flex-1">
                                                        <label for="cci{{ $cuenta->id }}" class="block text-sm font-medium text-blue-700 mb-1">
                                                            CCI
                                                        </label>
                                                        <input type="number" id="cci{{ $cuenta->id }}" name="cci" value="{{ $cuenta->cci }}"
                                                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                    {{-- Saldo inicial --}}
                                                    <div class="flex-1">
                                                        <label for="saldo_inicial{{ $cuenta->id }}" class="block text-sm font-medium text-blue-700 mb-1">
                                                            Saldo inicial
                                                        </label>
                                                        <input type="number" step="0.01" id="saldo_inicial{{ $cuenta->id }}" name="saldo_inicial" value="{{ $cuenta->saldo_inicial }}"
                                                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <label class="block text-sm font-medium text-blue-700 mb-2">
                                                        Mostrar en comprobante
                                                    </label>
                                                    <label class="inline-flex items-center cursor-pointer gap-3">
                                                        <input type="hidden" name="mostrar_comprobante"value="No">
                                                        <input type="checkbox" name="mostrar_comprobante" value="Si"class="sr-only peer"
                                                            {{ $cuenta->mostrar_comprobante == 'Si' ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700">
                                                            No
                                                        </span>
                                                        <div class="relative w-11 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full
                                                            after:content-[''] after:absolute after:top-[2px]
                                                            after:left-[2px] after:bg-white
                                                            after:border-slate-300 after:border
                                                            after:rounded-full after:h-5
                                                            after:w-5 after:transition-all
                                                            peer-checked:bg-[#0407e2]">
                                                        </div>
                                                        <span class="text-sm font-medium text-[#0407e2]">
                                                            Sí
                                                        </span>
                                                    </label>
                                                </div>

                                                {{-- Botones --}}
                                                <div class="flex justify-end gap-3 mt-8">
                                                    <button type="button"
                                                        onclick="document.getElementById('modalEditarCuentaBanco{{ $cuenta->id }}').classList.add('hidden')"
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
                                        onclick="confirmarEliminar('{{ route('configuracion.cuentabancaria.destroy', $cuenta->id) }}')"
                                        class="px-4 py-1.5 rounded-md text-white text-xs font-semibold"
                                        style="background-color: #D50000;">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-4 text-center text-slate-500">
                                No hay cuentas bancarias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button type="button"
            onclick="document.getElementById('modalNuevoCuentaBanco').classList.remove('hidden')"
            class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">
            <i class="fa-solid fa-circle-plus"></i>
            Nuevo
        </button>

    </div>

    <div id="modalNuevoCuentaBanco"
        class="hidden fixed inset-0 z-[99999] flex items-start justify-center pt-6 sm:pt-24 overflow-y-auto">
        <div class="absolute inset-0 bg-black/10"
            onclick="document.getElementById('modalNuevoCuentaBanco').classList.add('hidden')">
        </div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-5xl mx-3 sm:mx-4 p-4 sm:p-6 my-4 sm:my-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-800">
                    <i class="fa-solid fa-credit-card text-slate-600"></i>
                    Nueva Cuenta Bancaria
                </h2>
                <button type="button"
                    onclick="document.getElementById('modalNuevoCuentaBanco').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('configuracion.cuentabancaria.store') }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Banco -->
                    <div class="flex-1">
                        <label for="banco_id"class="block text-sm font-medium text-blue-700 mb-1">
                            Banco
                        </label>
                        <select id="banco_id" name="banco_id"
                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Seleccione un banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Descripción -->
                    <div class="flex-1">
                        <label for="descripcion"
                            class="block text-sm font-medium text-blue-700 mb-1">
                            Descripción
                        </label>
                        <input type="text"id="descripcion"name="descripcion"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                </div>
                <!-- Número / Moneda -->
                <div class="flex flex-col md:flex-row gap-4 mt-4">
                    <div class="flex-1">
                        <label for="numero"class="block text-sm font-medium text-blue-700 mb-1">
                            Número de cuenta
                        </label>
                        <input type="text"id="numero"name="numero"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Moneda -->
                    <div class="flex-1">
                        <label for="moneda_id"class="block text-sm font-medium text-blue-700 mb-1">
                            Moneda
                        </label>
                        <select id="moneda_id" name="moneda_id"
                            class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Seleccione una moneda</option>
                            @foreach($monedas as $moneda)
                                <option value="{{ $moneda->id }}">{{ $moneda->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- CCI / Saldo inicial -->
                <div class="flex flex-col md:flex-row gap-4 mt-4">
                    <!-- CCI -->
                    <div class="flex-1">
                        <label for="cci"class="block text-sm font-medium text-blue-700 mb-1">
                            CCI
                        </label>
                        <input type="text"id="cci"name="cci"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <!-- Saldo inicial -->
                    <div class="flex-1">
                        <label for="saldo_inicial"class="block text-sm font-medium text-blue-700 mb-1">
                            Saldo inicial
                        </label>
                        <input type="number"id="saldo_inicial"name="saldo_inicial"step="0.01"min="0"
                        class="w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                </div>

                <!-- Mostrar comprobante -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-blue-700 mb-2">
                        Mostrar en comprobante
                    </label>
                    <label class="inline-flex items-center cursor-pointer gap-3">
                        <input type="hidden"name="mostrar_comprobante"value="No">
                        <input type="checkbox"name="mostrar_comprobante"value="Si"class="sr-only peer"checked>
                        <span class="text-sm font-medium text-slate-700">
                            No
                        </span>
                        <div class="relative w-11 h-6 bg-slate-300 rounded-full
                            peer peer-checked:after:translate-x-full
                            after:content-['']
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:border-slate-300 after:border
                            after:rounded-full after:h-5 after:w-5
                            after:transition-all
                            peer-checked:bg-[#0407e2]">
                        </div>
                        <span class="text-sm font-medium text-[#0407e2]">
                            Sí
                        </span>
                    </label>
                </div>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8">
                    <button type="button"
                        onclick="document.getElementById('modalNuevoCuentaBanco').classList.add('hidden')"
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