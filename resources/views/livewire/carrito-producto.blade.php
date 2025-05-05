<div class="min-w-full divide-y divide-gray-200 shadow rounded-lg p-10 bg-white">
    <h2 class="text-2xl font-semibold mb-4">Tu Carrito</h2>

    @if (empty($cart))
    <p class="text-gray-600">No hay productos en el carrito.</p>
    @else
    <div class="flex justify-end mb-4">
        <button wire:click="exportPdf" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
            Exportar a PDF
        </button>
    </div>

    <table class="w-full mb-4 divide-y divide-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left">Producto</th>
                <th class="px-4 py-2 text-left">Descripción</th>
                <th class="px-4 py-2 text-left">Categoría</th>
                <th class="px-4 py-2 text-left">Marca</th>
                <th class="px-4 py-2 text-right">Precio Público</th>
                <th class="px-4 py-2 text-center">Cantidad</th>
                <th class="px-4 py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $id => $item)
            <tr>
                <td class="px-4 py-2">{{ $item['nombre'] }}</td>
                <td class="px-4 py-2">{{ $item['descripcion'] }}</td>
                <td class="px-4 py-2">{{ $item['categoria'] }}</td>
                <td class="px-4 py-2">{{ $item['marca'] }}</td>
                <td class="px-4 py-2 text-right">${{ number_format($item['precio_publico'], 2) }}</td>
                <td class="px-4 py-2 text-center">{{ $item['cantidad'] }}</td>
                <td class="px-4 py-2 text-right">
                    ${{ number_format($item['precio_publico'] * $item['cantidad'], 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end items-center">
        <span class="text-lg font-semibold mr-4">Total:</span>
        <span class="text-2xl font-bold">${{ number_format($total, 2) }}</span>
    </div>
    @endif
</div>