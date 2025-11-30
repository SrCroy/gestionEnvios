@extends('home.index')

@section('title', 'Detalles del Cliente - UES FMO')

@section('content')
    <div class="flex flex-col gap-2 p-2 bg-secondary container">
        <div class="row">
            <div class="col-2 text-info">
                <h1>id: </h1>
                <h1>nombre: </h1>
                <h1>email: </h1>
                <h1>telefono: </h1>
                <h1>direccion: </h1>
                <h1>latitud: </h1>
                <h1>longitud: </h1>
            </div>
            <div class="col-8">
                <h1 class="text-light">{{ $cliente->id }}</h1>
                <h1 class="text-light">{{ $cliente->nombre }}</h1>
                <h1 class="text-light">{{ $cliente->email }}</h1>
                <h1 class="text-light">{{ $cliente->telefono }}</h1>
                <h1 class="text-light">{{ $cliente->direccion }}</h1>
                <h1 class="text-light">{{ $cliente->latitud }}</h1>
                <h1 class="text-light">{{ $cliente->longitud }}</h1>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-around p-2 bg-info">
        <a href={{ route('clientes.edit', $cliente->id) }} class="btn btn-success">edit</a>
        <a href={{ route('clientes.delete', $cliente->id) }} class="btn btn-danger">delite</a>
    </div>
@endsection