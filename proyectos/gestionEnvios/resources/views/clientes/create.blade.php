@extends('home.index')

@section('title', 'Crear Cliente - UES FMO')

@section('content')
    <form action="{{ route('clientes.create') }}" method="post">
        @csrf
        <label for="">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="w-full"><br>
        <label for="">Direccion</label>
        <input type="text" id="direccion" name="direccion" class="w-full"><br>
        <label for="">Email</label>
        <input type="email" id="email" name="email" class="w-full"><br>
        <label for="">telefono</label>
        <input type="text" id="telefono" name="telefono" class="w-full"><br>
        <label for="">Latitud</label>
        <input type="number" step="0.00001" name="latitud" id="latitud" class="w-full"><br>
        <label for="">Longitud</label>
        <input type="number" step="0.00001" name="longitud" id="longitud" class="w-full"><br>
        <button type="submit" class="btn btn-success">
            crear
        </button>
    </form>
    <a href={{ route('clientes.index') }} class="btn btn-danger">Regresar</a>
@endsection