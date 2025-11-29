@extends('home.app')

@section('title', 'Detalle del Vehículo - UES FMO')

@section('content')
<!-- Breadcrumb -->
<!-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
        <li class="breadcrumb-item active">Detalle #{{ $vehiculo->id }}</li>
    </ol>
</nav> -->

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
                    <!-- <i class="bi bi-truck me-2" style="color: var(--ues-color);"></i> -->
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
                            <!-- <i class="bi bi-tag me-1"></i>  -->
                            Marca
                        </label>
                        <h5 class="mb-0">{{ $vehiculo->marca }}</h5>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            <!-- <i class="bi bi-car-front me-1"></i>  -->
                            Modelo
                        </label>
                        <h5 class="mb-0">{{ $vehiculo->modelo }}</h5>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            <!-- <i class="bi bi-box-seam me-1"></i>  -->
                            Peso Máximo
                        </label>
                        <h4 class="mb-0">
                            {{ number_format($vehiculo->pesoMaximo, 2) }} <small>kg</small>
                        </h4>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            <!-- <i class="bi bi-grid-3x3 me-1"></i>  -->
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
                    <!-- <i class="bi bi-calendar me-2" style="color: var(--ues-color);"></i> -->
                    Información de Registro
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            <!-- <i class="bi bi-calendar-plus me-1"></i>  -->
                            Fecha de Creación
                        </label>
                        <p class="mb-0">
                            <strong>{{ $vehiculo->created_at->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ $vehiculo->created_at->format('H:i:s') }}</small>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block">
                            <!-- <i class="bi bi-calendar-check me-1"></i>  -->
                            Última Actualización
                        </label>
                        <p class="mb-0">
                            <strong>{{ $vehiculo->updated_at->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ $vehiculo->updated_at->format('H:i:s') }}</small>
                        </p>
                    </div>
                </div>
                <!-- <div class="alert alert-info mb-0 mt-2">
                    <i class="bi bi-clock-history me-2"></i>
                    Registrado hace <strong>{{ $vehiculo->created_at->diffForHumans() }}</strong>
                </div> -->
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
                        <!-- <i class="bi bi-pencil me-2"></i> -->
                        Editar Vehículo
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <!-- <i class="bi bi-trash me-2"></i> -->
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
                    <!-- <i class="bi bi-exclamation-triangle text-danger me-2"></i> -->
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar el vehículo?</p>
                <!-- <div class="alert alert-warning">
                    <strong>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong><br>
                    ID: #{{ $vehiculo->id }}
                </div> -->
                <p class="text-danger mb-0">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <!-- <i class="bi bi-x-circle me-1"></i> -->
                    Cancelar
                </button>
                <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <!-- <i class="bi bi-trash me-1"></i> -->
                        Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection