<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Livewire\Component;

class ProductoCrud extends Component
{
    public $productos, $nombre, $descripcion, $categoria_id, $marca_id, $stock_minimo, $producto_id;
    public $isOpen = 0; // Para controlar si estamos creando/ editando

    public function render()
    {
        $this->productos = Producto::all();
        $categorias = Categoria::all();
        $marcas = Marca::all();

        return view('livewire.producto-crud', compact('categorias', 'marcas'));
    }

    // Editar Producto
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->categoria_id = $producto->categoria_id;
        $this->marca_id = $producto->marca_id;
        $this->stock_minimo = $producto->stock_minimo;

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
    ]);

    Producto::updateOrCreate(
        ['id' => $this->producto_id],
        $validatedData
    );

    session()->flash('message', $this->producto_id ? 'Producto actualizado exitosamente.' : 'Producto creado exitosamente.');
    
    $this->resetForm(); // Cierra el modal
}

}