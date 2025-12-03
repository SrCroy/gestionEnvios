<div>
    <!-- Header principal -->
    <div class="glass-card mb-4">
        <div class="gradient-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-boxes me-2"></i>Sistema de Seguimiento de Paquetes
                    </h4>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-clock me-1"></i>Gestión en tiempo real de todos tus envíos
                    </p>
                </div>
                <button class="btn btn-light btn-modern" wire:click="$toggle('showModal')">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Paquete
                </button>
            </div>
        </div>
        
        <!-- Barra de búsqueda y filtros -->
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input 
                            type="text" 
                            class="form-control input-modern border-start-0" 
                            placeholder="🔍 Buscar paquetes, clientes, descripciones..."
                            wire:model.debounce.300ms="busqueda"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select select-modern" wire:model="estadoFiltro">
                        <option value="todos">
                            <i class="fas fa-layer-group me-2"></i>Todos los estados ({{ $contadores['todos'] }})
                        </option>
                        <option value="Recoger">
                            <i class="fas fa-hand-paper me-2"></i>Para Recoger ({{ $contadores['recoger'] }})
                        </option>
                        <option value="Almacén">
                            <i class="fas fa-warehouse me-2"></i>En Almacén ({{ $contadores['almacen'] }})
                        </option>
                        <option value="Entregar">
                            <i class="fas fa-shipping-fast me-2"></i>Para Entregar ({{ $contadores['entregar'] }})
                        </option>
                    </select>
                </div>
            </div>

            <!-- Tarjetas de resumen -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="neumorphic-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-hand-paper me-1"></i>Para Recoger
                                    </h6>
                                    <h3 class="mb-0">{{ $contadores['recoger'] }}</h3>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Pendientes de recogida
                                    </small>
                                </div>
                                <div class="avatar-gradient">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="neumorphic-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-warehouse me-1"></i>En Almacén
                                    </h6>
                                    <h3 class="mb-0">{{ $contadores['almacen'] }}</h3>
                                    <small class="text-muted">
                                        <i class="fas fa-pallet me-1"></i>En almacenamiento
                                    </small>
                                </div>
                                <div class="avatar-gradient" style="background: var(--estado-almacen);">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="neumorphic-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-shipping-fast me-1"></i>Para Entregar
                                    </h6>
                                    <h3 class="mb-0">{{ $contadores['entregar'] }}</h3>
                                    <small class="text-muted">
                                        <i class="fas fa-home me-1"></i>Listos para entrega
                                    </small>
                                </div>
                                <div class="avatar-gradient" style="background: var(--estado-entregar);">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de paquetes (sin columna de vehículo) -->
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-align-left me-1"></i>Descripción</th>
                            <th><i class="fas fa-paper-plane me-1"></i>Remitente</th>
                            <th><i class="fas fa-user-check me-1"></i>Destinatario</th>
                            <th><i class="fas fa-exchange-alt me-1"></i>Estado</th>
                            <th class="text-end"><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paquetes as $paquete)
                            @php
                                $badgeClass = match($paquete->estadoActual) {
                                    'Recoger' => 'badge-recoger',
                                    'Almacén' => 'badge-almacen',
                                    'Entregar' => 'badge-entregar',
                                    default => 'badge-almacen'
                                };
                            @endphp
                            
                            <tr wire:key="paquete-{{ $paquete->id }}">
                                <td class="fw-bold">
                                    <span class="text-primary">
                                        <i class="fas fa-tag me-1"></i>#{{ str_pad($paquete->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-box text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($paquete->descripcion, 35) }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-weight me-1"></i>{{ $paquete->peso }} kg · 
                                                <i class="fas fa-ruler-vertical me-1"></i>{{ $paquete->altura }} cm
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-gradient" style="width: 32px; height: 32px; background: var(--info-gradient);">
                                                <i class="fas fa-paper-plane" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" style="font-size: 0.9rem;">{{ $paquete->remitente->nombre ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>Remitente
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-gradient" style="width: 32px; height: 32px; background: var(--success-gradient);">
                                                <i class="fas fa-user-check" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" style="font-size: 0.9rem;">{{ $paquete->destinatario->nombre ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user-tag me-1"></i>Destinatario
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-modern {{ $badgeClass }}">
                                        <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                                        {{ $paquete->estadoActual }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button 
                                            class="btn btn-sm btn-outline-primary btn-modern"
                                            wire:click="verDetalle({{ $paquete->id }})"
                                            title="Ver seguimiento completo"
                                        >
                                            <i class="fas fa-search-plus me-1"></i>Ver
                                        </button>
                                        <button 
                                            class="btn btn-sm btn-outline-info btn-modern"
                                            wire:click="seleccionarPaquete({{ $paquete->id }})"
                                            title="Editar información"
                                        >
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary btn-modern dropdown-toggle" 
                                                    type="button" 
                                                    data-bs-toggle="dropdown"
                                                    title="Cambiar estado del paquete">
                                                <i class="fas fa-sync-alt me-1"></i>Estado
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @foreach($estados as $estado)
                                                    @php
                                                        $iconoEstado = match($estado) {
                                                            'Recoger' => 'fas fa-hand-paper',
                                                            'Almacén' => 'fas fa-warehouse',
                                                            'Entregar' => 'fas fa-shipping-fast',
                                                            default => 'fas fa-circle'
                                                        };
                                                    @endphp
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           wire:click="cambiarEstadoRapido({{ $paquete->id }}, '{{ $estado }}')">
                                                            <i class="{{ $iconoEstado }} me-2"></i>{{ $estado }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                        <h5 class="mb-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>No hay paquetes registrados
                                        </h5>
                                        <p class="mb-0">
                                            <i class="fas fa-info-circle me-1"></i>Comienza creando un nuevo paquete
                                        </p>
                                        <button class="btn btn-primary btn-modern mt-3" wire:click="$set('showModal', true)">
                                            <i class="fas fa-plus-circle me-2"></i>Crear primer paquete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Paginación -->
                @if($paquetes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination">
                            {{ $paquetes->links() }}
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Detalle/Seguimiento (también sin vehículo) -->
    @if($showDetalleModal && $paqueteSeleccionado)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" wire:click="$set('showDetalleModal', false)">
        <div class="modal-dialog modal-lg modal-dialog-centered" wire:click.stop>
            <div class="modal-content glass-card" style="border: none;">
                <div class="gradient-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                Seguimiento Paquete #{{ str_pad($paqueteSeleccionado->id, 6, '0', STR_PAD_LEFT) }}
                            </h5>
                            <small class="opacity-75">
                                <i class="fas fa-history me-1"></i>Historial completo del envío
                            </small>
                        </div>
                        <button type="button" class="btn btn-light btn-sm btn-modern" wire:click="$set('showDetalleModal', false)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- Timeline de seguimiento -->
                    <div class="mb-4">
                        <h6 class="mb-3 d-flex align-items-center">
                            <i class="fas fa-road me-2 text-primary"></i>Ruta de Seguimiento
                        </h6>
                        <div class="timeline-modern">
                            @foreach($paqueteSeleccionado->historiales as $index => $historial)
                                @php
                                    $markerColor = match($historial->estado) {
                                        'Entregar' => 'background: var(--estado-entregar);',
                                        'Almacén' => 'background: var(--estado-almacen);',
                                        'Recoger' => 'background: var(--estado-recoger);',
                                        default => 'background: var(--estado-pendiente);'
                                    };
                                    
                                    $iconoEstado = match($historial->estado) {
                                        'Entregar' => 'fas fa-shipping-fast',
                                        'Almacén' => 'fas fa-warehouse',
                                        'Recoger' => 'fas fa-hand-paper',
                                        default => 'fas fa-clock'
                                    };
                                @endphp
                                <div class="timeline-item-modern">
                                    <div class="timeline-marker-modern" style="{{ $markerColor }}">
                                        <i class="{{ $iconoEstado }}" style="font-size: 0.7rem;"></i>
                                    </div>
                                    <div class="timeline-content-modern">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">
                                                <i class="{{ $iconoEstado }} me-2"></i>{{ $historial->estado }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($historial->fechaCambio)->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <p class="mb-2">
                                            <i class="fas fa-comment me-1 text-muted"></i>
                                            {{ $historial->comentarios }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-user-circle me-1"></i>
                                            {{ $historial->motorista->name ?? 'Sistema' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Información del paquete -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="glass-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                        Información del Envío
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5 text-muted">
                                            <i class="fas fa-align-left me-1"></i>Descripción:
                                        </dt>
                                        <dd class="col-sm-7">{{ $paqueteSeleccionado->descripcion }}</dd>
                                        
                                        <dt class="col-sm-5 text-muted">
                                            <i class="fas fa-weight me-1"></i>Peso:
                                        </dt>
                                        <dd class="col-sm-7">{{ $paqueteSeleccionado->peso }} kg</dd>
                                        
                                        <dt class="col-sm-5 text-muted">
                                            <i class="fas fa-ruler-vertical me-1"></i>Altura:
                                        </dt>
                                        <dd class="col-sm-7">{{ $paqueteSeleccionado->altura }} cm</dd>
                                        
                                        <dt class="col-sm-5 text-muted">
                                            <i class="fas fa-exchange-alt me-1"></i>Estado Actual:
                                        </dt>
                                        <dd class="col-sm-7">
                                            <span class="badge-modern 
                                                @if($paqueteSeleccionado->estadoActual == 'Recoger') badge-recoger
                                                @elseif($paqueteSeleccionado->estadoActual == 'Almacén') badge-almacen
                                                @else badge-entregar @endif">
                                                {{ $paqueteSeleccionado->estadoActual }}
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="glass-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-address-card me-2 text-primary"></i>
                                        Información de Contactos
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-info d-flex align-items-center">
                                            <i class="fas fa-share-square me-2"></i>Remitente
                                        </h6>
                                        <p class="mb-1 fw-bold">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $paqueteSeleccionado->remitente->nombre ?? 'N/A' }}
                                        </p>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-phone-alt me-1"></i>
                                            {{ $paqueteSeleccionado->remitente->telefono ?? '' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $paqueteSeleccionado->remitente->direccion ?? '' }}
                                        </small>
                                        <button class="btn btn-sm btn-outline-info btn-modern mt-2"
                                                wire:click="reasignarCliente('remitente', {{ $paqueteSeleccionado->remitente->id ?? '' }})">
                                            <i class="fas fa-user-edit me-1"></i>Reasignar Remitente
                                        </button>
                                    </div>
                                    
                                    <div>
                                        <h6 class="text-success d-flex align-items-center">
                                            <i class="fas fa-inbox me-2"></i>Destinatario
                                        </h6>
                                        <p class="mb-1 fw-bold">
                                            <i class="fas fa-user-tag me-1"></i>
                                            {{ $paqueteSeleccionado->destinatario->nombre ?? 'N/A' }}
                                        </p>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-mobile-alt me-1"></i>
                                            {{ $paqueteSeleccionado->destinatario->telefono ?? '' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-home me-1"></i>
                                            {{ $paqueteSeleccionado->destinatario->direccion ?? '' }}
                                        </small>
                                        <button class="btn btn-sm btn-outline-success btn-modern mt-2"
                                                wire:click="reasignarCliente('destinatario', {{ $paqueteSeleccionado->destinatario->id ?? '' }})">
                                            <i class="fas fa-user-friends me-1"></i>Reasignar Destinatario
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-modern" wire:click="$set('showDetalleModal', false)">
                        <i class="fas fa-door-closed me-2"></i>Cerrar
                    </button>
                    <button type="button" class="btn btn-primary btn-modern" 
                            wire:click="seleccionarPaquete({{ $paqueteSeleccionado->id }})">
                        <i class="fas fa-pencil-alt me-2"></i>Editar Paquete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Edición/Creación (sin campo de vehículo) -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" wire:click="$set('showModal', false)">
        <div class="modal-dialog modal-lg modal-dialog-centered" wire:click.stop>
            <div class="modal-content glass-card" style="border: none;">
                <div class="gradient-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-file-edit me-2"></i>
                                @if($paqueteSeleccionado)
                                    Editar Paquete #{{ str_pad($paqueteSeleccionado->id, 6, '0', STR_PAD_LEFT) }}
                                @else
                                    Crear Nuevo Paquete
                                @endif
                            </h5>
                            <small class="opacity-75">
                                <i class="fas fa-keyboard me-1"></i>Complete todos los campos requeridos
                            </small>
                        </div>
                        <button type="button" class="btn btn-light btn-sm btn-modern" wire:click="$set('showModal', false)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <form wire:submit.prevent="{{ $paqueteSeleccionado ? 'actualizarPaquete' : 'crearNuevoPaquete' }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Selección de Clientes -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-send me-1 text-info"></i>Remitente *
                                </label>
                                <select class="form-select select-modern" wire:model="remitente_id" required>
                                    <option value="">
                                        <i class="fas fa-user me-1"></i>Seleccionar remitente...
                                    </option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">
                                            <i class="fas fa-user-circle me-1"></i>{{ $cliente->nombre }} - 
                                            <i class="fas fa-phone me-1"></i>{{ $cliente->telefono }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-receive me-1 text-success"></i>Destinatario *
                                </label>
                                <select class="form-select select-modern" wire:model="destinatario_id" required>
                                    <option value="">
                                        <i class="fas fa-user me-1"></i>Seleccionar destinatario...
                                    </option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">
                                            <i class="fas fa-user-check me-1"></i>{{ $cliente->nombre }} - 
                                            <i class="fas fa-phone-alt me-1"></i>{{ $cliente->telefono }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Información del paquete -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-file-alt me-1 text-primary"></i>Descripción *
                                </label>
                                <textarea class="form-control input-modern" 
                                          rows="2"
                                          wire:model="descripcion"
                                          placeholder="📝 Descripción detallada del contenido del paquete..."
                                          required></textarea>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-flag me-1 text-warning"></i>Estado
                                </label>
                                <select class="form-select select-modern" wire:model="estadoActual">
                                    @foreach($estados as $estado)
                                        @php
                                            $iconoEstado = match($estado) {
                                                'Recoger' => 'fas fa-hand-paper',
                                                'Almacén' => 'fas fa-warehouse',
                                                'Entregar' => 'fas fa-shipping-fast',
                                                default => 'fas fa-circle'
                                            };
                                        @endphp
                                        <option value="{{ $estado }}">
                                            <i class="{{ $iconoEstado }} me-2"></i>{{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Medidas -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-balance-scale me-1 text-primary"></i>Peso (kg) *
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control input-modern" 
                                           step="0.01"
                                           wire:model="peso"
                                           placeholder="⚖️ 0.00"
                                           required>
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-weight"></i> kg
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-ruler-combined me-1 text-primary"></i>Altura (cm) *
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control input-modern" 
                                           step="0.01"
                                           wire:model="altura"
                                           placeholder="📐 0.00"
                                           required>
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-ruler"></i> cm
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Comentarios para historial -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-sticky-note me-1 text-info"></i>Comentarios (Historial)
                                </label>
                                <textarea class="form-control input-modern" 
                                          rows="2"
                                          wire:model="comentarios"
                                          placeholder="💭 Observaciones para el historial de seguimiento..."></textarea>
                            </div>
                        </div>
                        
                        @if($paqueteSeleccionado)
                        <div class="alert alert-info glass-card mt-3" style="background: rgba(13, 110, 253, 0.1); border: none;">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Al actualizar este paquete, se registrará automáticamente en el historial de seguimiento.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-modern" wire:click="$set('showModal', false)">
                            <i class="fas fa-ban me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ $paqueteSeleccionado ? 'Actualizar' : 'Crear' }} Paquete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>