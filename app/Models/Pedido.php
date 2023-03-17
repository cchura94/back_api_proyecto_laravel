<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot(["cantidad"])->withTimestamps();
    }

    public static function generarCodigoPedido()
    {
        $ultimoPedido = self::latest('id')->first();
        $codigo = 'PED-' . str_pad($ultimoPedido ? $ultimoPedido->id + 1 : 1, 4, '0', STR_PAD_LEFT);
        return $codigo;
    }

}
