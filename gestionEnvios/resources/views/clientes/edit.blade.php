@extends('home.index')

@section('title', 'Editar Cliente - UES FMO')

@section('content')
    <div class="flex flex-col p-10 bg-info">
        <a href={{ route('clientes.show', $cliente->id) }} class="btn btn-danger">Cancelar/Regresar</a>
        <form action={{ route('clientes.update', $cliente->id) }} method="post" class="flex flex-col">
            @csrf
            @method('PUT')
            <label for="">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ $cliente->nombre }}" class="w-full"><br>
            <label for="">Direccion</label>
            <input type="text" id="direccion" name="direccion" value="{{ $cliente->direccion }}" class="w-full"><br>
            <label for="">Email</label>
            <input type="email" id="email" name="email" value="{{ $cliente->email }}" class="w-full"><br>
            <label for="">telefono</label>
            <input type="text" id="telefono" name="telefono" value="{{ $cliente->telefono }}" class="w-full"><br>
            <label for="">Latitud</label>
            <input type="number" step="0.00001" name="latitud" id="latitud" value="{{ $cliente->latitud }}" class="w-full"><br>
            <label for="">Longitud</label>
            <input type="number" step="0.00001" name="longitud" id="longitud" value="{{ $cliente->longitud }}" class="w-full"><br>
            <button type="submit" class="btn btn-success">
                Guardar cambios
            </button>
        </form>
        
    </div>
@endsection