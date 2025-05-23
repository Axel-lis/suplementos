<?php
use App\Livewire\ProductoCrud;
use Illuminate\Support\Facades\Route;
use App\Livewire\CarritoProducto;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard'); // Redirige al dashboard si está autenticado
    }
    return redirect()->route('login'); // Redirige al login si no está autenticado
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/productos', ProductoCrud::class)->name('productos');
    Route::get('/carrito', CarritoProducto::class)->name('carrito');
});