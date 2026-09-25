<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function index(Request $request)
    {
        $resenas = Resena::with('producto:id,nombre,slug')
            ->when($request->estado === 'pendientes', fn ($q) => $q->where('aprobada', false))
            ->when($request->estado === 'aprobadas', fn ($q) => $q->where('aprobada', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.clientes.resenas', compact('resenas'));
    }

    public function update(Resena $resena)
    {
        $resena->update(['aprobada' => ! $resena->aprobada]);
        $this->recalcular($resena);

        return back()->with('success', $resena->aprobada ? 'Reseña publicada.' : 'Reseña ocultada.');
    }

    public function destroy(Resena $resena)
    {
        $resena->delete();
        $this->recalcular($resena);

        return back()->with('success', 'Reseña eliminada correctamente.');
    }

    private function recalcular(Resena $resena): void
    {
        $producto = $resena->producto;
        if ($producto) {
            $promedio = $producto->resenas()->where('aprobada', true)->avg('calificacion');
            $producto->update(['calificacion' => round((float) $promedio, 1)]);
        }
    }
}
