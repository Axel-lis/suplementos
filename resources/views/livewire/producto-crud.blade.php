<div class="p-6">
    @if (session()->has('message'))
    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
        {{ session('message') }}
    </div>
    @endif

    <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        Crear Producto
    </button>

    <!-- Tabla de productos -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Marca</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock Mínimo</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($productos as $producto)
                <tr>
                    <td class="px-4 py-2">{{ $producto->nombre }}</td>
                    <td class="px-4 py-2">{{ $producto->descripcion }}</td>
                    <td class="px-4 py-2">{{ $producto->categoria->nombre }}</td>
                    <td class="px-4 py-2">{{ $producto->marca->nombre }}</td>
                    <td class="px-4 py-2">{{ $producto->stock_minimo }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="edit({{ $producto->id }})"
                            class="text-blue-600 hover:underline">Editar</button>
                        <button wire:click="delete({{ $producto->id }})"
                            class="text-red-600 hover:underline">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 z-10 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">{{ $producto_id ? 'Editar Producto' : 'Crear Producto' }}</h2>
            </div>
            <div class="p-6">
                @include('livewire.form-producto')

            </div>
        </div>
    </div>
    @endif
</div>