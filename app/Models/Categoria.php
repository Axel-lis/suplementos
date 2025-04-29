<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    public function productos() {
        return $this->hasMany(Producto::class);
    }
}