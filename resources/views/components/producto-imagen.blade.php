@props(['producto', 'clase' => 'w-full h-full', 'icono' => 'text-3xl'])
{{-- Imagen del producto o, si no tiene, un placeholder con el ícono y color de su categoría --}}
@if ($producto->imagen_url)
    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy" class="{{ $clase }} object-cover">
@else
    @php $color = $producto->categoria->color ?? '#fb7701'; @endphp
    <div class="{{ $clase }} flex items-center justify-center"
         style="background: linear-gradient(135deg, {{ $color }}22, {{ $color }}55);">
        <i class="{{ $producto->categoria->icono ?? 'fa-solid fa-box' }} {{ $icono }}" style="color: {{ $color }}"></i>
    </div>
@endif
