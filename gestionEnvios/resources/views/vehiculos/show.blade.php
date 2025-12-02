@extends('home.index')

@section('title', 'Detalle del Vehículo - UES FMO')

@section('content')
<!-- Header -->
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-info-circle me-2"></i>
                DETALLE DEL VEHÍCULO
            </h3>
            <p class="mb-0">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>
            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<!-- Contenido -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Tarjeta Principal -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Información General
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">ID del Vehículo</label>
                        <h5><span class="badge bg-secondary">#{{ $vehiculo->id }}</span></h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Estado Actual</label>
                        <h5>
                            <span class="badge bg-{{ $vehiculo->estadoBadge }}">
                                {{ $vehiculo->estado }}
                            </span>
                        </h5>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Marca
                        </label>
                        <h5 class="mb-0">{{ $vehiculo->marca }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Modelo
                        </label>
                        <h5 class="mb-0">{{ $vehiculo->modelo }}</h5>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Peso Máximo
                        </label>
                        <h4 class="mb-0">
                            {{ number_format($vehiculo->pesoMaximo, 2) }} <small>kg</small>
                        </h4>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Volumen Máximo
                        </label>
                        <h4 class="mb-0">
                            {{ number_format($vehiculo->volumenMaximo, 2) }} <small>m³</small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Fechas -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Información de Registro
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Fecha de Creación
                        </label>
                        <p class="mb-0">
                            <strong>{{ $vehiculo->created_at->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ $vehiculo->created_at->format('H:i:s') }}</small>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            Última Actualización
                        </label>
                        <p class="mb-0">
                            <strong>{{ $vehiculo->updated_at->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ $vehiculo->updated_at->format('H:i:s') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2" style="color: var(--ues-color);"></i>
                    Acciones Disponibles
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning">
                        Editar Vehículo
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Eliminar Vehículo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar el vehículo?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection