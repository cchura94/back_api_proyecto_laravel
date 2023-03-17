<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with('cliente', 'productos')->orderBy('id', 'desc')->paginate(5);

        return response()->json($pedidos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        // validar
        $request->validate([
            "cliente_id" => "required",
        ]);

        try {
            // registrar nuevo Pedido (PENDIENTE)
            $pedido = new Pedido();
            // asignar cliente al pedido
            $pedido->cliente_id = $request->cliente_id['id'];
            $pedido->fecha = date("Y-m-d H:i:s");
            $pedido->cod_pedido = Pedido::generarCodigoPedido();
            $pedido->save();

            /*
        {
            cliente_id: 5,
            productos: [
                {producto_id: 3, cantidad: 1},
                {producto_id: 4, cantidad: 1},
                {producto_id: 1, cantidad: 1},
            ]
        }
        */

            // asignar Productos
            $productos = $request->productos;
            foreach ($productos as $prod) {
                $id = $prod["id"];
                $cantidad = $prod["cantidad"];

                // $user->roles()->attach($roleId, ['expires' => $expires]);
                $pedido->productos()->attach($id, ['cantidad' => $cantidad]);
            }

            //return $pedido;


            // actualizar estado //  COMPLETADO
            $pedido->estado = 2;
            // guardarmos cambios
            $pedido->update();
            // retornamos respuesta

            DB::commit();
            // all good
            return response()->json(["mensaje" => "Pedido Registrado", "data" => $pedido], 200);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response()->json(["mensaje" => "Error al generar Pedido", "error" => $e], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
