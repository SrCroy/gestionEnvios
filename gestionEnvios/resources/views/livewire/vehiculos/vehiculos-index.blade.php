<div>
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
                <button wire:click="create" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuevo Vehículo
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg col-md-6 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['total'] }}</h4>
                            <p class="mb-0">Total Vehículos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-car-front-fill display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['disponibles'] }}</h4>
                            <p class="mb-0">Disponibles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle-fill display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['en_ruta'] }}</h4>
                            <p class="mb-0">En Ruta</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['mantenimiento'] }}</h4>
                            <p class="mb-0">Mantenimiento</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-wrench display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['fuera_servicio'] }}</h4>
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

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <button wire:click="setFiltro('todos')"
                    class="btn btn-sm btn-outline-secondary {{ $filtroEstado === 'todos' ? 'active' : '' }}">
                    <i class="bi bi-list-ul me-1"></i> Todos ({{ $stats['total'] }})
                </button>
                <button wire:click="setFiltro('Disponible')"
                    class="btn btn-sm btn-outline-success {{ $filtroEstado === 'Disponible' ? 'active' : '' }}">
                    <i class="bi bi-check-circle me-1"></i> Disponibles ({{ $stats['disponibles'] }})
                </button>
                <button wire:click="setFiltro('En Ruta')"
                    class="btn btn-sm btn-outline-primary {{ $filtroEstado === 'En Ruta' ? 'active' : '' }}">
                    <i class="bi bi-truck me-1"></i> En Ruta ({{ $stats['en_ruta'] }})
                </button>
                <button wire:click="setFiltro('Mantenimiento')"
                    class="btn btn-sm btn-outline-warning {{ $filtroEstado === 'Mantenimiento' ? 'active' : '' }}">
                    <i class="bi bi-wrench me-1"></i> Mantenimiento ({{ $stats['mantenimiento'] }})
                </button>
                <button wire:click="setFiltro('Fuera de Servicio')"
                    class="btn btn-sm btn-outline-danger {{ $filtroEstado === 'Fuera de Servicio' ? 'active' : '' }}">
                    <i class="bi bi-x-circle me-1"></i> Fuera de Servicio ({{ $stats['fuera_servicio'] }})
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-dark">
                <i class="bi bi-list-ul me-2"></i>
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
                        <tr wire:key="vehiculo-{{ $vehiculo->id }}">
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
                                    <button wire:click="edit({{ $vehiculo->id }})"
                                        class="btn btn-sm btn-warning btn-action"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $vehiculo->id }})"
                                        class="btn btn-sm btn-danger btn-action"
                                        title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted">No hay vehículos registrados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal nuevo vehículo -->
    <div class="modal fade" id="createModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Registrar Nuevo Vehículo
                    </h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeModal', 'createModal')"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marca <span class="text-danger">*</span></label>
                                <input type="text" wire:model="marca" class="form-control @error('marca') is-invalid @enderror" placeholder="Ej: Toyota">
                                @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                <input type="text" wire:model="modelo" class="form-control @error('modelo') is-invalid @enderror" placeholder="Ej: Hilux">
                                @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peso Máximo (kg) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="pesoMaximo" step="0.01" class="form-control @error('pesoMaximo') is-invalid @enderror" placeholder="5000.00">
                                @error('pesoMaximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Volumen Máximo (m³) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="volumenMaximo" step="0.01" class="form-control @error('volumenMaximo') is-invalid @enderror" placeholder="20.00">
                                @error('volumenMaximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal', 'createModal')">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="store" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">Guardar Vehículo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editar vehículo -->
    <div class="modal fade" id="editModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Editar Vehículo
                    </h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeModal', 'editModal')"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update" wire:key="form-edit-{{$vehiculoId}}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marca <span class="text-danger">*</span></label>
                                <input type="text" wire:model="marca" class="form-control @error('marca') is-invalid @enderror">
                                @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Modelo <span class="text-danger">*</span></label>
                                <input type="text" wire:model="modelo" class="form-control @error('modelo') is-invalid @enderror">
                                @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peso Máximo (kg) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="pesoMaximo" step="0.01" class="form-control @error('pesoMaximo') is-invalid @enderror">
                                @error('pesoMaximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Volumen Máximo (m³) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="volumenMaximo" step="0.01" class="form-control @error('volumenMaximo') is-invalid @enderror">
                                @error('volumenMaximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select wire:model="estado" class="form-select @error('estado') is-invalid @enderror">
                                    <option value="">-- Seleccione --</option>
                                    @foreach($estados as $estadoOption)
                                    <option value="{{ $estadoOption }}">{{ $estadoOption }}</option>
                                    @endforeach
                                </select>
                                @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal', 'editModal')">Cancelar</button>
                    <button type="button" class="btn btn-warning" wire:click="update" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="update">Actualizar Vehículo</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal eliminar vehiculo -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeModal', 'deleteModal')"></button>
                </div>
                <div class="modal-body">
                    @if($vehiculoToDelete)
                    <p>¿Está seguro de eliminar el vehículo <strong>{{ $vehiculoToDelete->marca }} {{ $vehiculoToDelete->modelo }}</strong>?</p>
                    <span class="badge bg-{{ $vehiculoToDelete->estadoBadge }} mb-2">
                        Estado actual: {{ $vehiculoToDelete->estado }}
                    </span>
                    <br>
                    @endif
                    <span class="text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Esta acción no se puede deshacer.
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal', 'deleteModal')">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="delete" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="delete">Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>