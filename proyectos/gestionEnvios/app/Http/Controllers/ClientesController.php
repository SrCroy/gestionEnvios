<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = clientes::all();

        $titulo = "clientes";

        return view('clientes.clientes', compact('clientes', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(clientes $clientes, $id)
    {
        $cliente = $clientes::find($id);

        $titulo = 'cliente mostrar';

        return view('clientes.show', compact('cliente', 'titulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(clientes $clientes, $id)
    {
        $cliente = $clientes::find($id);

        $titulo = 'cliente editar';

        return view('clientes.edit', compact('cliente', 'titulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, clientes $clientes)
    {
        $cliente = clientes::find($id);

        $cliente::fill([
            'nombre'=>$request->nombre,
            'telefono'=>$request->telefono,
            'direccion'=>$request->direccion,
            'email'=>$request->email,
            'longitud'=>$request->longitud,
            'latitud'=>$request->latitud,
        ]);

        $cliente->save();

        $clientes = clientes::all();

        $titulo = "clientes";

        return view('clientes.clientes', compact('clientes', 'titulo'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(clientes $clientes)
    {
        //
    }
}
