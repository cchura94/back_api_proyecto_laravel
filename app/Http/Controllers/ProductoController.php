<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // /api/producto?page=2&limit=10&q=laptop&orderby=id
        // $page = $request->page;
        $limit = $request->limit?$request->limit:10;
        $q = $request->q;
        $orderby = $request->orderby?$request->orderby:'id';

        if($q){
            $productos = Producto::where('nombre', 'like', '%'.$q.'%')
                                     ->orderBy($orderby, 'asc')
                                     ->paginate($limit);
        }else{
            $productos = Producto::with('categoria:id,nombre')->orderBy($orderby, 'desc')->paginate($limit);
        }

        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar
        $request->validate([
            "nombre" => 'required|min:3|max:200',
            "categoria_id"=> "required"
        ]);
        // subir img
        $direccion_imagen = "";


        // guardar
        $prod = new Producto();
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->stock = $request->stock;
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria_id;
        $prod->imagen = $direccion_imagen;
        $prod->save();

        // responder
        return response()->json(["mensaje" => "Producto registrado", "data" => $prod]);
    }

    public function actualizarImagen($id, Request $request)
    {
        $producto = Producto::find($id);

        if($file = $request->file('imagen')){
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            $file->move("imagenes", $direccion_imagen);

            $direccion_imagen = "imagenes/". $direccion_imagen;
            $producto->imagen = $direccion_imagen;
            $producto->save();
        }

        return response()->json(["mensaje" => "Producto imagen actualizada"], 201);
        
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
