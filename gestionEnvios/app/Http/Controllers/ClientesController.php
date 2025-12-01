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

        return view('clientes.clientes-index', compact('clientes', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $cliente = new clientes();

        $cliente->nombre = $request->nombre;
        $cliente->email = $request->email;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->latitud = $request->latitud;
        $cliente->longitud = $request->longitud;

        $cliente->save();

        return redirect()->route('clientes.index');
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
        $cliente = $clientes::findOrFail($id);

        $titulo = 'cliente editar';

        return view('clientes.edit', compact('cliente', 'titulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, clientes $clientes)
    {
        $cliente = clientes::findOrFail($id);

        $cliente->nombre = $request->nombre;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->latitud = $request->latitud;
        $cliente->longitud = $request->longitud;

        $cliente->save();

        $clientes = clientes::all();

        return redirect()->route('clientes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = clientes::findOrFail($id);
        $cliente->delete();
        
        return redirect()->route('clientes.index');
    }
}
