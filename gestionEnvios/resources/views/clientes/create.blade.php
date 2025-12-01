@extends('home.app')

@section('title', 'Crear Cliente - UES FMO')

@section('content')
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i>
            Registrar Nuevo Cliente
        </h5>
    </div>
    <form action="{{ route('clientes.create') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del cliente">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">E-mail <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Correo del cliente">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Telefono <span class="text-danger">*</span></label>
                <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ej: 76543210">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Dirreción <span class="text-danger">*</span></label>
                <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirreción del cliente">
            </div>
        </div>
        <div class="modal-footer gap-2">
            <button type="button" class="btn btn-secondary">
                <a href={{ route('clientes.index') }} class="link-dark link-underline-opacity-0 text-white">Cancelar</a>
            </button>
            <button type="submit" class="btn btn-primary">
                Crear Cliente
            </button>
        </div>
    </form>
@endsection