<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Services\Carrito;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function __construct(private Carrito $carrito)
    {
    }

    public function index()
    {
        return view('tienda.carrito', ['resumen' => $this->carrito->resumen()]);
    }

    public function agregar(Request $request, Producto $producto)
    {
        $data = $request->validate(['cantidad' => 'nullable|integer|min:1|max:99']);
        if (! $producto->activo || $producto->stock < 1) {
            return back()->with('error', 'Este producto está agotado.');
        }
        $this->carrito->agregar($producto, $data['cantidad'] ?? 1);

        if ($request->boolean('comprar_ahora')) {
            return redirect()->route('tienda.checkout');
        }

        return back()->with('success', 'Agregado al carrito: '.$producto->nombre);
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $data = $request->validate(['cantidad' => 'required|integer|min:0|max:99']);
        $this->carrito->actualizar($producto->id, $data['cantidad']);

        return back();
    }

    public function cupon(Request $request)
    {
        $request->validate(['codigo' => 'nullable|string|max:40']);
        $this->carrito->aplicarCupon($request->codigo);
        $resumen = $this->carrito->resumen();

        if (! $request->codigo) {
            return back()->with('success', 'Cupón retirado.');
        }

        return $resumen['error_cupon']
            ? back()->with('error', $resumen['error_cupon'])
            : back()->with('success', 'Cupón aplicado: -S/ '.number_format($resumen['descuento'], 2));
    }
}
