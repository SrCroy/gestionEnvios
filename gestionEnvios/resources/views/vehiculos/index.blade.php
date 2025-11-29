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
    <!-- Total Vehículos -->
    <div class="col-lg col-md-6 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ $vehiculos->count() }}</h4>
                        <p class="mb-0">Total Vehículos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-car-front-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disponibles -->
    <div class="col-lg col-md-6 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ $vehiculos->where('estado', 'Disponible')->count() }}</h4>
                        <p class="mb-0">Disponibles</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- En Ruta -->
    <div class="col-lg col-md-6 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ $vehiculos->where('estado', 'En Ruta')->count() }}</h4>
                        <p class="mb-0">En Ruta</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-truck display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mantenimiento -->
    <div class="col-lg col-md-6 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ $vehiculos->where('estado', 'Mantenimiento')->count() }}</h4>
                        <p class="mb-0">Mantenimiento</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wrench display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fuera de Servicio -->
    <div class="col-lg col-md-6 mb-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ $vehiculos->where('estado', 'Fuera de Servicio')->count() }}</h4>
                        <p class="mb-0">Fuera de Servicio</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-x-circle-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros rápidos -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="todos">
                <i class="bi bi-list-ul me-1"></i> Todos ({{ $vehiculos->count() }})
            </button>
            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="Disponible">
                <i class="bi bi-check-circle me-1"></i> Disponibles ({{ $vehiculos->where('estado', 'Disponible')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="En Ruta">
                <i class="bi bi-truck me-1"></i> En Ruta ({{ $vehiculos->where('estado', 'En Ruta')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="Mantenimiento">
                <i class="bi bi-wrench me-1"></i> Mantenimiento ({{ $vehiculos->where('estado', 'Mantenimiento')->count() }})
            </button>
            <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="Fuera de Servicio">
                <i class="bi bi-x-circle me-1"></i> Fuera de Servicio ({{ $vehiculos->where('estado', 'Fuera de Servicio')->count() }})
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Vehículos -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Vehículos
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="vehiculosTable">
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
                        <tr data-estado="{{ $vehiculo->estado }}">
                            <td>{{ $vehiculo->id }}</td>
                            <td><strong>{{ $vehiculo->marca }}</strong></td>
                            <td>{{ $vehiculo->modelo }}</td>
                            <td>{{ number_format($vehiculo->pesoMaximo, 2) }} kg</td>
                            <td>{{ number_format($vehiculo->volumenMaximo, 2) }} m³</td>
                            <td>
                                <span class="badge bg-{{ $vehiculo->estadoBadge }}">
                                    <i class="bi bi-{{ $vehiculo->estadoIcono }} me-1"></i>
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
                                                    Confirmar Eliminación
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de eliminar el vehículo 
                                                <strong>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong>?
                                                <br><br>
                                                <span class="badge bg-{{ $vehiculo->estadoBadge }} mb-2">
                                                    Estado actual: {{ $vehiculo->estado }}
                                                </span>
                                                <br>
                                                <span class="text-danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Esta acción no se puede deshacer.
                                                </span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
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

@push('scripts')
<script>
// Filtros rápidos para la tabla
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#vehiculosTable tbody tr[data-estado]');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Actualizar botones activos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrar filas
            tableRows.forEach(row => {
                if (filter === 'todos' || row.dataset.estado === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush