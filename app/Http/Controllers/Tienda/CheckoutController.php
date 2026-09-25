<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public const METODOS_PAGO = [
        'Tarjeta de crédito/débito' => 'fa-regular fa-credit-card',
        'Yape / Plin' => 'fa-solid fa-mobile-screen-button',
        'Transferencia bancaria' => 'fa-solid fa-building-columns',
        'Pago contra entrega' => 'fa-solid fa-truck-fast',
    ];

    public function __construct(private Carrito $carrito)
    {
    }

    public function index()
    {
        $resumen = $this->carrito->resumen();
        if ($resumen['lineas']->isEmpty()) {
            return redirect()->route('tienda.carrito')->with('error', 'Tu carrito está vacío.');
        }

        return view('tienda.checkout', ['resumen' => $resumen, 'metodos' => self::METODOS_PAGO]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'telefono' => 'required|string|max:30',
            'documento' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:255',
            'distrito' => 'required|string|max:100',
            'metodo_pago' => 'required|in:'.implode(',', array_keys(self::METODOS_PAGO)),
            'notas' => 'nullable|string|max:500',
        ]);

        $resumen = $this->carrito->resumen();
        if ($resumen['lineas']->isEmpty()) {
            return redirect()->route('tienda.carrito')->with('error', 'Tu carrito está vacío.');
        }

        try {
            $pedido = DB::transaction(function () use ($data, $resumen) {
                $cliente = Cliente::updateOrCreate(
                    ['email' => strtolower($data['email'])],
                    collect($data)->only(['nombre', 'telefono', 'documento', 'direccion', 'distrito'])->all()
                );

                $pedido = Pedido::create([
                    'codigo' => Pedido::generarCodigo(),
                    'cliente_id' => $cliente->id,
                    'cupon_id' => $resumen['cupon']?->id,
                    'metodo_pago' => $data['metodo_pago'],
                    'subtotal' => $resumen['subtotal'],
                    'descuento' => $resumen['descuento'],
                    'envio' => $resumen['envio'],
                    'total' => $resumen['total'],
                    // Pago con tarjeta/Yape se considera confirmado en modo demo
                    'estado' => in_array($data['metodo_pago'], ['Tarjeta de crédito/débito', 'Yape / Plin']) ? 'pagado' : 'pendiente',
                    'direccion_envio' => $data['direccion'],
                    'distrito' => $data['distrito'],
                    'notas' => $data['notas'] ?? null,
                ]);

                foreach ($resumen['lineas'] as $linea) {
                    // Descuento de stock atómico para evitar sobreventa
                    $afectados = Producto::whereKey($linea->producto->id)
                        ->where('stock', '>=', $linea->cantidad)
                        ->update([
                            'stock' => DB::raw('stock - '.(int) $linea->cantidad),
                            'vendidos' => DB::raw('vendidos + '.(int) $linea->cantidad),
                        ]);
                    if (! $afectados) {
                        throw new \RuntimeException('Stock insuficiente para '.$linea->producto->nombre);
                    }

                    $pedido->items()->create([
                        'producto_id' => $linea->producto->id,
                        'nombre' => $linea->producto->nombre,
                        'precio' => $linea->precio,
                        'cantidad' => $linea->cantidad,
                        'subtotal' => $linea->subtotal,
                    ]);
                }

                $resumen['cupon']?->increment('usos');

                return $pedido;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('tienda.carrito')->with('error', $e->getMessage());
        }

        $this->carrito->vaciar();
        session(['ultimo_pedido' => $pedido->codigo]);

        return redirect()->route('tienda.confirmacion', $pedido->codigo);
    }

    public function confirmacion(string $codigo)
    {
        abort_unless(session('ultimo_pedido') === $codigo, 404);
        $pedido = Pedido::with(['items', 'cliente'])->where('codigo', $codigo)->firstOrFail();

        return view('tienda.confirmacion', compact('pedido'));
    }

    /** Seguimiento público: requiere código + correo. */
    public function seguimiento(Request $request)
    {
        $pedido = null;
        if ($request->filled(['codigo', 'email'])) {
            $pedido = Pedido::with('items')
                ->where('codigo', strtoupper(trim($request->codigo)))
                ->whereHas('cliente', fn ($q) => $q->where('email', strtolower(trim($request->email))))
                ->first();
            if (! $pedido) {
                session()->now('error', 'No encontramos un pedido con esos datos.');
            }
        }

        return view('tienda.seguimiento', compact('pedido'));
    }
}
