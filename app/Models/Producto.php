<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Precio;
use App\Models\Alerta;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 
        'descripcion',
        'categoria_id',
        'marca_id',
        'stock_minimo'
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function marca() {
        return $this->belongsTo(Marca::class);
    }

    public function precios() {
        return $this->hasMany(Precio::class);
    }

    public function alertas() {
        return $this->hasMany(Alerta::class);
    }
}