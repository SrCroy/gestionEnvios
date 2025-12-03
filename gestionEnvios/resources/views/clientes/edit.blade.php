@extends('home.app')

@section('title', 'Editar Cliente - UES FMO')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-light d-flex align-items-center">
            <i class="fas fa-edit fa-lg me-2"></i>
            <h4 class="mb-0">Editar Cliente</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label"><strong>Nombre</strong></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $cliente->nombre }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label"><strong>Email</strong></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $cliente->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label"><strong>Teléfono</strong></label>
                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $cliente->telefono }}" required>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label"><strong>Dirección</strong></label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $cliente->direccion }}" required>
                </div>
                <div class="mb-3">
                    <label for="latitud" class="form-label"><strong>Latitud</strong></label>
                    <input type="text" name="latitud" id="latitud" class="form-control" value="{{ $cliente->latitud }}">
                </div>
                <div class="mb-3">
                    <label for="longitud" class="form-label"><strong>Longitud</strong></label>
                    <input type="text" name="longitud" id="longitud" class="form-control" value="{{ $cliente->longitud }}">
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
