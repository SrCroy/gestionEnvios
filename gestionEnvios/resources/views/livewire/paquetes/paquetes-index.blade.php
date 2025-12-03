<div>
    {{-- Do your work, then step back. --}}
    <style>
        .pkg-card {
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            transition: all 0.2s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .pkg-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border-color: var(--accent-color);
        }

        .pkg-icon {
            width: 45px;
            height: 45px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .route-line {
            position: relative;
            padding-left: 20px;
            border-left: 2px dashed #dee2e6;
            margin-left: 10px;
            padding-bottom: 20px;
        }

        .route-line:last-child {
            border-left: none;
            padding-bottom: 0;
        }

        .route-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #adb5bd;
        }

        .route-dot.start {
            background: var(--ues-color);
        }

        .route-dot.end {
            background: #28a745;
        }

        .filter-btn {
            border-radius: 20px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: white;
        }

        .filter-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-check:checked+.btn-outline-warning,
        .btn-check:active+.btn-outline-warning,
        .btn-outline-warning.active,
        .btn-outline-warning.dropdown-toggle.show,
        .btn-outline-warning:active {
            color: #fff !important;
        }
    </style>

    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-box-seam me-2"></i>Mis Paquetes</h3>
            <p class="text-muted mb-0">Gestiona y rastrea todos tus envíos.</p>
        </div>
        <div class="col-md-6 text-end">
            <button wire:click="$dispatch('abrirModalCrear')" data-bs-toggle="modal" data-bs-target="#crearPaqueteModal" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Envío
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 overflow-auto pb-2">

                {{-- 1. TODOS (Info/Cyan) --}}
                <button wire:click="setFiltro('todos')"
                    class="btn btn-outline-info filter-btn {{ $filtroEstado === 'todos' ? 'active' : '' }} border shadow-sm">
                    Todos
                    @if(isset($stats['total']))
                    <span class="badge {{ $filtroEstado === 'todos' ? 'bg-white text-info' : 'bg-info text-white' }} ms-1">
                        {{ $stats['total'] }}
                    </span>
                    @endif
                </button>

                {{-- 2. PENDIENTES (Warning/Amarillo) --}}
                <button wire:click="setFiltro('Pendiente')"
                    class="btn btn-outline-warning filter-btn {{ $filtroEstado === 'Pendiente' ? 'active' : '' }} border shadow-sm">
                    Pendientes
                    @if(isset($stats['pendientes']))
                    {{-- Nota: text-dark se lee mejor en amarillo que text-white --}}
                    <span class="badge {{ $filtroEstado === 'Pendiente' ? 'bg-white text-warning' : 'bg-warning text-dark' }} ms-1">
                        {{ $stats['pendientes'] }}
                    </span>
                    @endif
                </button>

                {{-- 3. EN TRÁNSITO (Primary/Azul) --}}
                <button wire:click="setFiltro('En Tránsito')"
                    class="btn btn-outline-primary filter-btn {{ $filtroEstado === 'En Tránsito' ? 'active' : '' }} border shadow-sm">
                    En Tránsito
                    @if(isset($stats['en_transito']))
                    <span class="badge {{ $filtroEstado === 'En Tránsito' ? 'bg-white text-primary' : 'bg-primary text-white' }} ms-1">
                        {{ $stats['en_transito'] }}
                    </span>
                    @endif
                </button>

                {{-- 4. ENTREGADOS (Success/Verde) --}}
                <button wire:click="setFiltro('Entregado')"
                    class="btn btn-outline-success filter-btn {{ $filtroEstado === 'Entregado' ? 'active' : '' }} border shadow-sm">
                    Entregados
                    @if(isset($stats['entregados']))
                    <span class="badge {{ $filtroEstado === 'Entregado' ? 'bg-white text-success' : 'bg-success text-white' }} ms-1">
                        {{ $stats['entregados'] }}
                    </span>
                    @endif
                </button>

            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($paquetes as $paquete)
        <div class="col-lg-6" wire:key="pkg-{{ $paquete->id }}">
            <div class="pkg-card p-3 h-100 {{ $paquete->estadoActual === 'Entregado' ? 'border-success border-opacity-25' : '' }}"
                style="{{ $paquete->estadoActual === 'Entregado' ? 'background-color: #f8fff9;' : '' }}">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pkg-icon {{ $paquete->estadoActual === 'En Tránsito' ? 'text-primary bg-primary' : '' }} {{ $paquete->estadoActual === 'Pendiente' ? 'text-warning bg-warning' : '' }} {{ $paquete->estadoActual === 'Entregado' ? 'text-success bg-white border border-success' : '' }} bg-opacity-10">
                            <i class="bi {{ $paquete->estadoActual === 'Entregado' ? 'bi-check-lg' : 'bi-box-seam' }}"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 {{ $paquete->estadoActual === 'Entregado' ? 'text-success' : '' }}">
                                {{ $paquete->descripcion }}
                            </h6>
                            <small class="text-muted">ID: <span class="text-dark fw-bold">#{{ $paquete->id }}</span></small>
                        </div>
                    </div>
                    <span class="badge bg-secondary rounded-pill">{{ $paquete->estadoActual }}</span>
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="route-line">
                        <div class="route-dot start"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">ORIGEN (Cliente Remitente)</small>
                        <span class="fw-bold text-dark">
                            {{ \Illuminate\Support\Str::limit($paquete->remitente->direccion ?? 'Dirección no registrada', 40) }}
                        </span>
                    </div>
                    <div class="route-line">
                        <div class="route-dot end"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">DESTINO (Cliente Destinatario)</small>
                        <span class="fw-bold text-dark">
                            {{ \Illuminate\Support\Str::limit($paquete->destinatario->direccion ?? 'Dirección no registrada', 40) }}
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $paquete->created_at->format('d/m/Y') }}</small>
                    <div class="btn-group">
                        @if($paquete->estadoActual === 'Pendiente')
                        <button wire:click="edit({{ $paquete->id }})" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
                        <button wire:click="confirmDelete({{ $paquete->id }})" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">No hay paquetes.</p>
        </div>
        @endforelse
    </div>

    @if($paquetes->hasPages())
    <div class="mt-4">{{ $paquetes->links() }}</div>
    @endif

    @livewire('paquetes.crear-paquete')

    <!-- Modal de editar -->
    <div class="modal fade" id="editModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-black bg-opacity-10 border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark">
                        Editar Paquete
                    </h5>
                    <button type="button" class="btn-close" wire:click="$dispatch('closeModal', 'editModal')"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="update">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">CONTENIDO DEL PAQUETE</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-box"></i></span>
                                <input type="text" wire:model="descripcionEdicion" class="form-control border-start-0 ps-0" placeholder="Ej: Caja de ropa">
                            </div>
                            @error('descripcionEdicion') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">PESO (Libras)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" wire:model="pesoEdicion" class="form-control">
                                    <span class="input-group-text bg-light text-muted">lb</span>
                                </div>
                                @error('pesoEdicion') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-6">
                                <label class="form-label fw-bold small text-muted">ALTURA (cm)</label>
                                <div class="input-group">
                                    <input type="number" step="1" wire:model="alturaEdicion" class="form-control">
                                    <span class="input-group-text bg-light text-muted">cm</span>
                                </div>
                                @error('alturaEdicion') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-2 d-flex justify-content-end gap-2 border-top">
                            <button type="button" class="btn btn-light" wire:click="$dispatch('closeModal', 'editModal')">Cancelar</button>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-black bg-opacity-10 border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        Cancelar Envío
                    </h5>
                    <button type="button" class="btn-close btn-close-black" wire:click="$dispatch('closeModal', 'deleteModal')"></button>
                </div>
                
                <div class="modal-body text-center p-4">
                    <p class="text-muted">
                        Vas a eliminar el paquete: 
                        {{-- AQUÍ MOSTRAMOS EL NOMBRE DEL PAQUETE SELECCIONADO --}}
                        <br>
                        <span class="fw-bold text-dark fs-5">
                            "{{ $paqueteParaEliminar->descripcion ?? 'Desconocido' }}"
                        </span>
                    </p>
                    <div class="alert alert-warning d-flex align-items-center mt-3 p-2 small" role="alert">
                        <div>Esta acción es irreversible y removerá el paquete de la lista de pendientes.</div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" wire:click="$dispatch('closeModal', 'deleteModal')">
                        No, cancelar
                    </button>
                    <button type="button" class="btn btn-danger px-4 fw-bold" wire:click="delete">
                        Sí, eliminarlo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>