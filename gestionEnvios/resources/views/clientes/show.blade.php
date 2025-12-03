@extends('home.index')

@section('title', 'Detalles del Cliente - UES FMO')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-light">
            <h4 class="mb-0"><i class="fas fa-user me-2"></i>Detalles del Cliente</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-4 text-secondary">
                <p><strong>ID:</strong></p>
                <p><strong>Nombre:</strong></p>
                <p><strong>Email:</strong></p>
                <p><strong>Teléfono:</strong></p>
                <p><strong>Dirección:</strong></p>
                <p><strong>Latitud:</strong></p>
                <p><strong>Longitud:</strong></p>
            </div>
            <div class="col-md-8">
                <p class="text-dark">{{ $cliente->id }}</p>
                <p class="text-dark">{{ $cliente->nombre }}</p>
                <p class="text-dark">{{ $cliente->email }}</p>
                <p class="text-dark">{{ $cliente->telefono }}</p>
                <p class="text-dark">{{ $cliente->direccion }}</p>
                <p class="text-dark">{{ $cliente->latitud }}</p>
                <p class="text-dark">{{ $cliente->longitud }}</p>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
