<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CarritoProducto extends Component
{
    public $cart = [];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)
            ->sum(fn($item) => $item['precio_publico'] * $item['cantidad']);
    }

    /**
     * Genera y descarga el PDF del carrito SIN usar una vista Blade externa.
     */
    public function exportPdf()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)
            ->sum(fn($item) => $item['precio_publico'] * $item['cantidad']);

        // 1) Construir el HTML "a mano"
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">'
              . '<style>'
              . 'body{font-family:sans-serif;}'
              . 'table{width:100%;border-collapse:collapse;margin-top:1rem}'
              . 'th,td{border:1px solid #444;padding:6px;}'
              . 'th{background:#eee;}'
              . 'h1{text-align:center;}'
              . '</style>'
              . '</head><body>'
              . '<h1>BOWER: Carrito de Compras</h1>';

        if (empty($cart)) {
            $html .= '<p>No hay productos en el carrito.</p>';
        } else {
            $html .= '<table>'
                   . '<thead><tr>'
                   . '<th>Producto</th><th>Descripción</th><th>Categoría</th>'
                   . '<th>Marca</th><th>Precio Público</th><th>Cantidad</th>'
                   . '<th>Subtotal</th>'
                   . '</tr></thead><tbody>';

            foreach ($cart as $item) {
                $subtotal = $item['precio_publico'] * $item['cantidad'];
                $html .= '<tr>'
                       . '<td>' . e($item['nombre']) . '</td>'
                       . '<td>' . e($item['descripcion']) . '</td>'
                       . '<td>' . e($item['categoria']) . '</td>'
                       . '<td>' . e($item['marca']) . '</td>'
                       . '<td style="text-align:right">$' . number_format($item['precio_publico'],2) . '</td>'
                       . '<td style="text-align:center">' . $item['cantidad'] . '</td>'
                       . '<td style="text-align:right">$' . number_format($subtotal,2) . '</td>'
                       . '</tr>';
            }

            $html .= '</tbody></table>'
                   . '<h3 style="text-align:right;margin-top:1rem;">'
                   . 'Total: $' . number_format($total,2)
                   . '</h3>';
        }

        $html .= '</body></html>';

        // 2) Cargar ese HTML directamente en DomPDF
        $pdf = Pdf::loadHTML($html)
                  ->setPaper('a4', 'portrait');

        // 3) Devolver descarga
        return response()->streamDownload(
            fn() => print($pdf->output()),
            'carrito-de-compras-'.now()->format('Ymd_His').'.pdf'
        );
    }

    public function render()
    {
        return view('livewire.carrito-producto', [
            'cart'  => $this->cart,
            'total' => $this->total,
        ])
        ->layout('layouts.app', [
            'title'  => 'BOWER - Carrito de Compras',
            'header' => 'Carrito de Compras',
        ]);
    }
}