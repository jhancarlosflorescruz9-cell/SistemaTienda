@props(['modal', 'texto' => 'Guardar'])
<div class="flex justify-end gap-3 mt-8">
    <button type="button" onclick="cerrarModal('{{ $modal }}')"
        class="px-4 py-2 rounded-md border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Cancelar</button>
    <button type="submit" class="px-4 py-2 rounded-md bg-[#0407e2] hover:bg-[#0305b8] text-white text-sm font-semibold">{{ $texto }}</button>
</div>
