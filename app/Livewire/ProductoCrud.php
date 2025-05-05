<?php

namespace App\Livewire;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosExport;

class ProductoCrud extends Component
{
    use WithPagination;
    public $lista_productos, $nombre, $descripcion, $categoria_id, $marca_id, $stock_minimo, $producto_id, $precio_publico, $precio_costo, $precio_preferencial;
    public $isOpen = 0; // Para controlar si estamos creando/ editando

    public $filtroCategoria = '';
    public $filtroMarca = '';
// Método para aplicar filtros manualmente
public function aplicarFiltros()
{
    $this->resetPage(); // Reiniciar paginación
}

// Método para resetear filtros
public function resetearFiltros()
{
    $this->filtroCategoria = '';
    $this->filtroMarca = '';
    $this->resetPage();
}    
public function updatedFiltroCategoria()
{
    $this->resetPage();
}

public function updatedFiltroMarca()
{
    $this->resetPage();
}
public function render()
{
    $query = Producto::query()
        ->with('categoria', 'marca', 'precios');

    // Aplicar filtros
    if ($this->filtroCategoria) {
        $query->whereHas('categoria', function ($q) {
            $q->where('id', $this->filtroCategoria);
        });
    }

    if ($this->filtroMarca) {
        $query->whereHas('marca', function ($q) {
            $q->where('id', $this->filtroMarca);
        });
    }

    $productos = $query->paginate(10);
    $categorias = Categoria::all();
    $marcas = Marca::all();

    return view('livewire.producto-crud', compact('productos', 'categorias', 'marcas'));
}



    // Editar Producto
    public function edit($id)
{
    $producto = Producto::with('precios')->findOrFail($id);
    $this->producto_id = $producto->id;
    $this->nombre = $producto->nombre;
    $this->descripcion = $producto->descripcion;
    $this->categoria_id = $producto->categoria_id;
    $this->marca_id = $producto->marca_id;
    $this->stock_minimo = $producto->stock_minimo;

    // Buscar precios por tipo
    $this->precio_publico = optional($producto->precios->firstWhere('tipo', 'publico'))->valor;
    $this->precio_costo = optional($producto->precios->firstWhere('tipo', 'costo'))->valor;
    $this->precio_preferencial = optional($producto->precios->firstWhere('tipo', 'preferencial'))->valor;

    $this->isOpen = 1;
}


    // Borrar Producto
    public function delete($id)
    {
        Producto::find($id)->delete();
        session()->flash('message', 'Producto eliminado exitosamente.');
    }
    
    // Método para abrir el modal (Crear Producto)
    public function create()
    {
        $this->resetForm(); // Resetea los campos
        $this->isOpen = true; // Abre el modal
    }

    // Método para cerrar el modal (Cancelar)
    public function closeModal()
    {
        $this->resetForm(); // Resetea los campos
        $this->isOpen = false; // Cierra el modal
    }

    // Método privado para resetear campos
    private function resetForm()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->categoria_id = '';
        $this->marca_id = '';
        $this->stock_minimo = '';
        $this->producto_id = null;
        $this->precio_publico = '';
        $this->precio_costo = '';
        $this->precio_preferencial = '';
    }

    // Crear Producto
    public function store()
    {
        $validatedData = $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'stock_minimo' => 'required|integer|min:0',
            'precio_publico' => 'required|numeric|min:0',
            'precio_costo' => 'required|numeric|min:0',
            'precio_preferencial' => 'nullable|numeric|min:0',
        ]);

        $producto = Producto::updateOrCreate(
            ['id' => $this->producto_id],
            [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'categoria_id' => $this->categoria_id,
                'marca_id' => $this->marca_id,
                'stock_minimo' => $this->stock_minimo,
            ]
        );

        // Elimina precios anteriores si es edición
        $producto->precios()->delete();

        // Crear precios
    $producto->precios()->createMany([
        ['tipo' => 'publico', 'valor' => $this->precio_publico, 'fecha_vigencia' => now()],
        ['tipo' => 'costo', 'valor' => $this->precio_costo, 'fecha_vigencia' => now()], // Cambié 'privado' a 'costo'
    ]);

        // Solo si hay precio preferencial
        if ($this->precio_preferencial !== null) {
            $producto->precios()->create([
                'tipo' => 'preferencial',
                'valor' => $this->precio_preferencial,
                'fecha_vigencia' => now(),
            ]);
        }

        session()->flash('message', $this->producto_id ? 'Producto actualizado exitosamente.' : 'Producto creado exitosamente.');
        
        $this->resetForm(); 
        $this->isOpen = false;

    }

    //* AGREGAR CATEGORIA DE PRODUCTO *//
    public $isCategoriaModalOpen = false;
    public $nueva_categoria_nombre = '';

    // Abrir modal de categoría
    public function abrirModalCategoria()
    {
        $this->nueva_categoria_nombre = '';
        $this->isCategoriaModalOpen = true;
    }

    // Cerrar modal
    public function cerrarModalCategoria()
    {
        $this->nueva_categoria_nombre = '';
        $this->isCategoriaModalOpen = false;
    }

    // Guardar nueva categoría
    public function guardarCategoria()
    {
        $this->validate([
            'nueva_categoria_nombre' => 'required|string|max:255',
        ]);

        Categoria::create([
            'nombre' => $this->nueva_categoria_nombre,
        ]);

        session()->flash('message', 'Categoría creada exitosamente.');

        $this->cerrarModalCategoria();
    }

    //* AGREGAR MARCA DE PRODUCTO *//
    // Mostrar modal para agregar nueva marca
    public $isMarcaModalOpen = false;
    public $nueva_marca_nombre = '';

    // Abrir el modal para agregar marca
    public function abrirModalMarca()
    {
        $this->nueva_marca_nombre = ''; // Limpia el campo de entrada
        $this->isMarcaModalOpen = true;  // Abre el modal
    }

    // Cerrar el modal de marca
    public function cerrarModalMarca()
    {
        $this->nueva_marca_nombre = ''; // Limpia el campo de entrada
        $this->isMarcaModalOpen = false; // Cierra el modal
    }

    // Guardar nueva marca
    public function guardarMarca()
    {
        // Validar el nombre de la marca
        $this->validate([
            'nueva_marca_nombre' => 'required|string|max:255',
        ]);

        // Crear una nueva marca
        Marca::create([
            'nombre' => $this->nueva_marca_nombre,
        ]);

        // Mostrar un mensaje de éxito
        session()->flash('message', 'Marca creada exitosamente.');

        // Cerrar el modal
        $this->cerrarModalMarca();
    }
    //* Exportar productos a Excel *//
    public $isExportModalOpen = false;
    public $columnasDisponibles = [
        'nombre' => 'Nombre',
        'descripcion' => 'Descripción',
        'categoria' => 'Categoría',
        'marca' => 'Marca',
        'stock_minimo' => 'Stock Mínimo',
        'precio_publico' => 'Precio Público',
        'precio_costo' => 'Precio Costo',
        'precio_preferencial' => 'Precio Preferencial',
    ];
    public $columnasSeleccionadas = [];

    public function abrirModalExportar()
    {
        $this->isExportModalOpen = true;
    }
    public function cerrarModalExportar()
    {
        $this->isExportModalOpen  = false;
        $this->columnasSeleccionadas = []; // Reinicia las columnas seleccionadas
    }
   //* Exportar productos a Excel *//
    public function exportarExcel()
    {
        if(empty($this->columnasSeleccionadas)){
            session()->flash('message', 'Por favor selecciona al menos una columna para exportar.');
            return;
        }
        
    // En el método exportarExcel() del componente:
    $nombreArchivo = 'productos';
    if ($this->filtroCategoria) {
        $nombreArchivo .= '-categoria-' . Categoria::find($this->filtroCategoria)->nombre;
    }
    if ($this->filtroMarca) {
        $nombreArchivo .= '-marca-' . Marca::find($this->filtroMarca)->nombre;
    }
    $nombreArchivo .= '.xlsx';

    return Excel::download(
        new ProductosExport($this->columnasSeleccionadas, $this->filtroCategoria, $this->filtroMarca), 
        $nombreArchivo
    );
    }
/**
 * Agrega un producto al carrito de compras (usando sesión).
 *
 * @param  int  $id
 * @return void
 */
public function addToCart($id)
{
    $producto = Producto::with('categoria', 'marca', 'precios')->find($id);
    if (! $producto) {
        session()->flash('message', 'Producto no encontrado.');
        return;
    }

    $precioPublico = optional($producto->precios->firstWhere('tipo','publico'))->valor ?? 0;

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['cantidad']++;
    } else {
        $cart[$id] = [
            'nombre'        => $producto->nombre,
            'descripcion'   => $producto->descripcion,
            'categoria'     => $producto->categoria->nombre,
            'marca'         => $producto->marca->nombre,
            'precio_publico'=> $precioPublico,
            'cantidad'      => 1,
        ];
    }

    session()->put('cart', $cart);

    session()->flash('message', 'Producto agregado al carrito correctamente.');
}


}