@extends('home.app')

@section('title', 'Gestión de Vehículos - UES FMO')

@section('content')
<!-- UES Header -->
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-truck me-2"></i>
                GESTIÓN DE VEHÍCULOS
            </h3>
            <p class="mb-0">Administración de flota vehicular</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('vehiculos.create') }}" class="btn btn-warning">
                <i class="bi bi-plus-circle me-2"></i>
                Nuevo Vehículo
            </a>
        </div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $vehiculos->where('estado', 'Disponible')->count() }}</h4>
                        <p class="mb-0">Disponibles</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $vehiculos->where('estado', 'En Ruta')->count() }}</h4>
                        <p class="mb-0">En Ruta</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-truck display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $vehiculos->where('estado', 'Mantenimiento')->count() }}</h4>
                        <p class="mb-0">Mantenimiento</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wrench display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $vehiculos->count() }}</h4>
                        <p class="mb-0">Total Vehículos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-car-front display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Vehículos -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">
            <!-- <i class="bi bi-list-ul me-2" style="color: var(--ues-color);"></i> -->
            Lista de Vehículos
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Marca</th>
                        <th style="width: 15%;">Modelo</th>
                        <th style="width: 12%;">Peso Máx.</th>
                        <th style="width: 12%;">Volumen Máx.</th>
                        <th style="width: 15%;">Estado</th>
                        <th style="width: 13%;">Fecha Registro</th>
                        <th style="width: 13%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->id }}</td>
                            <td><strong>{{ $vehiculo->marca }}</strong></td>
                            <td>{{ $vehiculo->modelo }}</td>
                            <td>{{ number_format($vehiculo->pesoMaximo, 2) }} kg</td>
                            <td>{{ number_format($vehiculo->volumenMaximo, 2) }} m³</td>
                            <td>
                                <span class="badge bg-{{ $vehiculo->estadoBadge }}">
                                    {{ $vehiculo->estado }}
                                </span>
                            </td>
                            <td>{{ $vehiculo->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('vehiculos.show', $vehiculo) }}" 
                                       class="btn btn-sm btn-info btn-action" 
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" 
                                       class="btn btn-sm btn-warning btn-action" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $vehiculo->id }}"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal de Confirmación -->
                                <div class="modal fade" id="deleteModal{{ $vehiculo->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                                    Confirmar Eliminación
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de eliminar el vehículo 
                                                <strong>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong>?
                                                <br><br>
                                                <span class="text-danger">Esta acción no se puede deshacer.</span>
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
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted">No hay vehículos registrados</p>
                                <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Registrar Primer Vehículo
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection