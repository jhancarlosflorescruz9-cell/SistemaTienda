@extends('layouts.admin.app')

@section('title', 'Configuración')

@section('js')
@endsection

@section('content')
<div class="grid grid-cols-12 gap-4 w-full">
    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">

        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">General</h2>
        </div>

        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="{{ route('configuracion.banco') }}" class="text-gray-800 text-sm hover:underline">Listado de bancos</a></li>
                <li><a href="{{ route('configuracion.cuentabancaria') }}" class="text-gray-800 text-sm hover:underline">Listado de cuentas bancarias</a></li>
                <li><a href="{{ route('configuracion.moneda') }}" class="text-gray-800 text-sm hover:underline">Lista de monedas</a></li>
                <li><a href="{{ route('configuracion.tarjeta') }}" class="text-gray-800 text-sm hover:underline">Listado de tarjetas</a></li>
                <li><a href="{{ route('configuracion.plataforma') }}" class="text-gray-800 text-sm hover:underline">Plataformas</a></li>
            </ul>
        </div>

    </div>

    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Empresa</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Empresa</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Giro de negocio</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Estilos y temas</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Avanzado</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Generador de link de pago</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Tienda Virtual/Restaurante</a></li> 
            </ul>
        </div>
    </div>


    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">SUNAT</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="{{ route('configuracion.atributo') }}" class="text-gray-800 text-sm hover:underline">Listado de Atributos</a></li>
                <li><a href="{{ route('configuracion.detraccion') }}" class="text-gray-800 text-sm hover:underline">Listado de tipos de detracciones</a></li>
                <li><a href="{{ route('configuracion.unidad') }}" class="text-gray-800 text-sm hover:underline">Listado de unidades</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Tipos de motivos de transferencias</a></li>
            </ul>
        </div>
    </div>

    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Ingresos/Egresos</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="{{ route('configuracion.metodopago') }}" class="text-gray-800 text-sm hover:underline">Métodos de pago - ingreso / gastos</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Motivos de ingresos / Gastos</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Listado de métodos de pago</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Tipos de comprobantes INGRESOS Y GASTOS</a></li>
            </ul>
        </div>
    </div>
    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Plantillas PDF</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="#" class="text-gray-800 text-sm hover:underline">PDF</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">PDF - Ticket</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Pre Impresos</a></li>
            </ul>
        </div>
    </div>
    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Avanzado</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Tareas programadas</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Numeración de facturación</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Avanzado - Contable</a></li> 
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Inventarios</a></li> 
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Nota de ventas</a></li> 
            </ul>
        </div>
    </div>

    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Visual</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Login</a></li>
            </ul>
        </div>
    </div>

    <div class="col-span-12 md:col-span-4 bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-blue-50 px-3 py-1">
            <h2 class="text-blue-900 font-bold text-base">Comisiones</h2>
        </div>
        <div class="px-6 py-6">
            <ul class="space-y-4 list-disc list-inside marker:text-slate-800">
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Vendedores</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Productos</a></li>
                <li><a href="#" class="text-gray-800 text-sm hover:underline">Cuentas pendientes</a></li>
            </ul>
        </div>
    </div>

</div>
@endsection