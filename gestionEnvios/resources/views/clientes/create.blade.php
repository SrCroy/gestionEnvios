@extends('home.index')

@section('title', 'Crear Cliente - UES FMO')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-light d-flex align-items-center">
            <i class="fas fa-user-plus fa-lg me-2"></i>
            <h4 class="mb-0">Crear Cliente</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.create') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label"><strong>Nombre</strong></label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label"><strong>Dirección</strong></label>
                    <input type="text" id="direccion" name="direccion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><strong>Email</strong></label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label"><strong>Teléfono</strong></label>
                    <input type="text" id="telefono" name="telefono" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="latitud" class="form-label"><strong>Latitud</strong></label>
                    <input type="number" step="0.00001" name="latitud" id="latitud" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="longitud" class="form-label"><strong>Longitud</strong></label>
                    <input type="number" step="0.00001" name="longitud" id="longitud" class="form-control">
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
