<form wire:submit.prevent="store" class="space-y-4">
    <!-- Campos del formulario -->
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" id="nombre" wire:model="nombre"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
        <textarea id="descripcion" wire:model="descripcion" rows="3"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
        @error('descripcion') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría</label>
        <select id="categoria_id" wire:model="categoria_id"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Seleccione</option>
            @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
        @error('categoria_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="marca_id" class="block text-sm font-medium text-gray-700">Marca</label>
        <select id="marca_id" wire:model="marca_id"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">Seleccione</option>
            @foreach($marcas as $marca)
            <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
            @endforeach
        </select>
        @error('marca_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
        <input type="number" id="stock_minimo" wire:model="stock_minimo"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @error('stock_minimo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="flex justify-end space-x-2 pt-4">
        <!-- Botón de Cancelar (Cierra el modal y resetea el formulario) -->
        <button type="button" wire:click="closeModal"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            Cancelar
        </button>

        <!-- Botón de Enviar -->
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ $producto_id ? 'Actualizar' : 'Crear' }} Producto
        </button>
    </div>
</form>