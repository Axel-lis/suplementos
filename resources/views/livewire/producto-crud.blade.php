<div class="p-6">
    @if (session()->has('message'))
    <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
        {{ session('message') }}
    </div>
    @endif

    <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        Crear Producto
    </button>
    <button wire:click="abrirModalCategoria" type="button"
        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded mb-4">
        Agregar Categoría
    </button>
    <button wire:click="abrirModalMarca" type="button"
        class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded mb-4">
        Agregar Marca
    </button>
    <button wire:click="abrirModalExportar" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4">
        Exportar a Excel
    </button>

    <!-- Tabla de productos -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <div class="flex space-x-4 mb-4 items-center">
            <!-- Filtro Categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Filtrar por Categoría</label>
                <select wire:model="filtroCategoria" class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro Marca -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Filtrar por Marca</label>
                <select wire:model="filtroMarca" class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="">Todas</option>
                    @foreach($marcas as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="flex-1 flex justify-end space-x-2">
                <button wire:click="aplicarFiltros" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                    Buscar
                    <!-- Nuevo botón -->
                </button>
                <button wire:click="resetearFiltros" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                    Limpiar
                </button>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Marca</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock Mínimo</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Público</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Costo</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Preferencial</th>
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

                    <!-- Mostrar precios de cada tipo -->
                    <td class="px-4 py-2">
                        @foreach($producto->precios->where('tipo', 'publico') as $precio)
                        ${{ number_format($precio->valor, 2) }}
                        @endforeach
                    </td>
                    <td class="px-4 py-2">
                        @foreach($producto->precios->where('tipo', 'costo') as $precio)
                        ${{ number_format($precio->valor, 2) }}
                        @endforeach
                    </td>
                    <td class="px-4 py-2">
                        @foreach($producto->precios->where('tipo', 'preferencial') as $precio)
                        ${{ number_format($precio->valor, 2) }}
                        @endforeach
                    </td>

                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="edit({{ $producto->id }})"
                            class="text-blue-600 hover:underline">Editar</button>

                        <button wire:click="delete({{ $producto->id }})"
                            class="text-red-600 hover:underline">Eliminar</button>

                        <!-- NUEVO: botón Cargar al carrito -->
                        <button wire:click="addToCart({{ $producto->id }})" class="text-green-600 hover:underline">
                            Cargar al carrito
                        </button>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $productos->links('pagination::tailwind') }}
        </div>

    </div>

    <!-- Modal -->
    @if($isOpen)
    <div class="fixed inset-0 z-10 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">{{ $producto_id ? 'Editar Producto' : 'Crear Producto' }}</h2>
            </div>
            <div class="p-6">
                @include('livewire.form-producto')
            </div>
        </div>

    </div>
    @endif
    <!-- Modal de Categoría -->
    @if ($isCategoriaModalOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Nueva Categoría</h2>

            <input type="text" wire:model="nueva_categoria_nombre" placeholder="Nombre de la categoría"
                class="w-full border border-gray-300 rounded px-3 py-2 mb-2 focus:outline-none focus:ring focus:border-blue-300">
            @error('nueva_categoria_nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="cerrarModalCategoria"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button wire:click="guardarCategoria"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif
    <!-- Modal de Marca -->
    @if ($isMarcaModalOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Nueva Marca</h2>

            <input type="text" wire:model="nueva_marca_nombre" placeholder="Nombre de la marca"
                class="w-full border border-gray-300 rounded px-3 py-2 mb-2 focus:outline-none focus:ring focus:border-blue-300">
            @error('nueva_marca_nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="cerrarModalMarca"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button wire:click="guardarMarca" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif
    <!-- Modal de Excel -->

    <!-- Modal de exportación -->
    @if ($isExportModalOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Seleccionar columnas para exportar</h2>

            <div class="space-y-2">
                @foreach($columnasDisponibles as $columna => $label)
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="columnasSeleccionadas" value="{{ $columna }}"
                            class="form-checkbox">
                        <span class="ml-2">{{ $label }}</span>
                    </label>
                </div>
                @endforeach
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button wire:click="cerrarModalExportar"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
                <button wire:click="exportarExcel"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Exportar</button>
            </div>
        </div>
    </div>
    @endif