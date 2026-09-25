@extends('layouts.admin.app')

@section('title', 'Reseñas')

@section('content')
    <x-admin.header titulo="Reseñas de productos" icono="fa-solid fa-star" seccion="Clientes">
        @foreach (['' => 'Todas', 'pendientes' => 'Pendientes', 'aprobadas' => 'Publicadas'] as $clave => $label)
            <a href="{{ route('admin.resenas.index', array_filter(['estado' => $clave])) }}"
               class="px-3 py-1.5 rounded-full text-xs font-semibold no-underline {{ request('estado', '') === $clave ? 'bg-[#0407e2] text-white' : 'bg-white text-slate-600 shadow-sm' }}">{{ $label }}</a>
        @endforeach
    </x-admin.header>

    <div class="bg-white rounded-3xl shadow-md p-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-black">
                    <th class="font-semibold py-2">Fecha</th><th class="font-semibold">Producto</th><th class="font-semibold">Autor</th>
                    <th class="font-semibold">Calificación</th><th class="font-semibold">Comentario</th><th class="font-semibold">Estado</th>
                    <th class="font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resenas as $resena)
                    <tr class="border-b border-slate-100 align-top">
                        <td class="py-2 text-slate-600 whitespace-nowrap">{{ $resena->created_at->format('d/m/Y') }}</td>
                        <td class="py-2 text-blue-800">{{ $resena->producto->nombre ?? '—' }}</td>
                        <td class="py-2 text-slate-700">{{ $resena->autor }}</td>
                        <td class="py-2 text-amber-500 whitespace-nowrap">
                            @for ($i = 1; $i <= 5; $i++)<i class="fa-{{ $i <= $resena->calificacion ? 'solid' : 'regular' }} fa-star text-xs"></i>@endfor
                        </td>
                        <td class="py-2 text-slate-600 max-w-xs">{{ $resena->comentario }}</td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $resena->aprobada ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $resena->aprobada ? 'Publicada' : 'Pendiente' }}</span>
                        </td>
                        <td class="py-2">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.resenas.update', $resena) }}">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 rounded-md text-white text-xs font-semibold whitespace-nowrap" style="background-color: {{ $resena->aprobada ? '#64748b' : '#64DD17' }};">
                                        {{ $resena->aprobada ? 'Ocultar' : 'Aprobar' }}
                                    </button>
                                </form>
                                <button type="button" onclick="confirmarEliminar('{{ route('admin.resenas.destroy', $resena) }}')"
                                    class="px-3 py-1.5 rounded-md text-white text-xs font-semibold" style="background-color: #D50000;">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-slate-500">No hay reseñas.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $resenas->links() }}</div>
    </div>
@endsection
