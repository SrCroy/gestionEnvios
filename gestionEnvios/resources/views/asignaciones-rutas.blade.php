<div class="card" wire:ignore.self>
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-route me-2"></i>Asignación de Paquetes a Motoristas
        </h4>
        <button class="btn btn-sm btn-light" wire:click="recargarTodo" wire:loading.attr="disabled">
            <i class="fas fa-sync-alt me-1" wire:loading.class="fa-spin"></i>
            <span wire:loading.remove>Recargar</span>
            <span wire:loading>Cargando...</span>
        </button>
    </div>
    
    <div class="card-body">
        <!-- Fecha y Tipo de Acción -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-day me-1"></i>Fecha de Trabajo
                </label>
                <select class="form-select" id="selectFecha" wire:model.live="fechaSeleccionada">
                    @foreach($fechasConAsignaciones as $fecha)
                        <option value="{{ $fecha }}">
                            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Fechas con motoristas asignados</small>
            </div>
            
            <div class="col-md-8">
                <label class="form-label fw-bold">
                    <i class="fas fa-tasks me-1"></i>Tipo de Acción
                </label>
                <div class="btn-group w-100" role="group">
                    <button 
                        type="button" 
                        class="btn {{ $tipoAccion === 'recoger' ? 'btn-success' : 'btn-outline-success' }}"
                        wire:click="cambiarTipoAccion('recoger')"
                    >
                        <i class="fas fa-box-open me-1"></i>Recoger Paquetes
                    </button>
                    <button 
                        type="button" 
                        class="btn {{ $tipoAccion === 'entregar' ? 'btn-warning' : 'btn-outline-warning' }}"
                        wire:click="cambiarTipoAccion('entregar')"
                    >
                        <i class="fas fa-truck me-1"></i>Entregar Paquetes
                    </button>
                </div>
            </div>
        </div>

        <!-- Motoristas con Asignaciones en esta Fecha -->
        <div class="row mb-4" id="seccion-motoristas">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Motoristas Trabajando el {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            <span class="badge bg-primary ms-2" id="contador-motoristas">{{ count($motoristasDelDia) }}</span>
                        </h5>
                        <div wire:loading wire:target="fechaSeleccionada" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:loading.remove wire:target="fechaSeleccionada">
                            @if(count($motoristasDelDia) > 0)
                                <div class="row">
                                    @foreach($motoristasDelDia as $motorista)
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border 
                                                {{ $motoristaSeleccionado == $motorista->id ? 'border-success border-2' : 'border-secondary' }} 
                                                hover-shadow motorista-card"
                                                style="cursor: pointer;"
                                                data-motorista-id="{{ $motorista->id }}"
                                                onclick="seleccionarMotorista({{ $motorista->id }})"
                                            >
                                                <div class="card-body text-center">
                                                    <div class="mb-2">
                                                        <i class="fas fa-user-tie fa-2x text-primary"></i>
                                                    </div>
                                                    <h6 class="card-title">{{ $motorista->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i>{{ $motorista->telefono }}
                                                    </small>
                                                    <br>
                                                    @if($motorista->asignaciones_count > 0)
                                                        <small class="badge bg-success mt-1">
                                                            {{ $motorista->asignaciones_count }} paquete(s)
                                                        </small>
                                                    @else
                                                        <small class="badge bg-warning mt-1">
                                                            Sin paquetes asignados
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay motoristas trabajando en esta fecha.
                                </div>
                            @endif
                        </div>
                        <div wire:loading wire:target="fechaSeleccionada" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando motoristas...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($motoristaSeleccionado)
            @php
                $motoristaData = $motoristasDelDia->firstWhere('id', $motoristaSeleccionado);
            @endphp
            
            <!-- Información del Motorista Seleccionado -->
            <div class="row mb-4" id="info-motorista">
                <div class="col-md-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie me-2"></i>
                                {{ $motoristaData->name }} - Asignaciones del {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            </h5>
                            <button class="btn btn-sm btn-light" onclick="deseleccionarMotorista()">
                                <i class="fas fa-times me-1"></i>Cambiar motorista
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6><i class="fas fa-user me-2"></i>Motorista</h6>
                                    <p class="mb-1">
                                        <strong>Nombre:</strong> {{ $motoristaData->name }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Teléfono:</strong> {{ $motoristaData->telefono }}
                                    </p>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6><i class="fas fa-truck me-2"></i>Vehículo</h6>
                                    @if($vehiculoAsignado)
                                        <p class="mb-1">
                                            <strong>Vehículo:</strong> {{ $vehiculoAsignado->marca }} {{ $vehiculoAsignado->modelo }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Capacidad:</strong> {{ number_format($vehiculoAsignado->pesoMaximo, 2) }} kg
                                        </p>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Sin vehículo asignado.
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <h6><i class="fas fa-chart-pie me-2"></i>Estadísticas</h6>
                                    <p class="mb-1">
                                        <strong>Paquetes asignados:</strong> 
                                        <span class="badge bg-info" id="contador-paquetes">
                                            {{ $asignacionesDelMotorista->whereNotNull('idPaquete')->count() }}
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Peso total:</strong> <span id="peso-total">{{ number_format($pesoTotal, 2) }}</span> kg
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Progreso de Capacidad -->
            @if($vehiculoAsignado)
            <div class="row mb-4" id="barra-capacidad">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Uso de Capacidad del Vehículo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Peso utilizado: {{ number_format($pesoTotal, 2) }} kg / {{ number_format($capacidadMaxima, 2) }} kg</span>
                                    <span id="porcentaje-uso">{{ $porcentajeUso }}%</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" 
                                         id="progreso-capacidad"
                                         role="progressbar" 
                                         style="width: {{ $porcentajeUso }}%"
                                         aria-valuenow="{{ $porcentajeUso }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $porcentajeUso }}%
                                    </div>
                                </div>
                            </div>
                            
                            @if($porcentajeUso >= 100)
                                <div class="alert alert-danger" id="alerta-capacidad">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>¡Capacidad máxima alcanzada!</strong> No puedes agregar más paquetes.
                                </div>
                            @elseif($porcentajeUso >= 90)
                                <div class="alert alert-warning" id="alerta-capacidad">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>¡Cuidado!</strong> Capacidad casi completa.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Paquetes Disponibles para Asignar -->
            <div class="row mb-4" id="seccion-paquetes">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>
                                Paquetes Disponibles para {{ $tipoAccion }}
                            </h5>
                            <span class="badge bg-primary" id="contador-paquetes-disponibles">
                                {{ count($paquetesDisponibles) }} disponibles
                            </span>
                        </div>
                        <div class="card-body">
                            @if(count($paquetesDisponibles) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Seleccionar</th>
                                                <th>Descripción</th>
                                                <th>Peso (kg)</th>
                                                <th>Altura (m)</th>
                                                <th>Destinatario</th>
                                                <th>Remitente</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paquetesDisponibles as $paquete)
                                                <tr class="{{ $paqueteSeleccionado == $paquete->id ? 'table-success' : '' }}" 
                                                    onclick="seleccionarPaquete({{ $paquete->id }})"
                                                    style="cursor: pointer;">
                                                    <td>
                                                        <div class="form-check">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="radio" 
                                                                name="paqueteSeleccionado" 
                                                                value="{{ $paquete->id }}"
                                                                wire:model="paqueteSeleccionado"
                                                                {{ (!$vehiculoAsignado || $porcentajeUso >= 100) ? 'disabled' : '' }}
                                                            >
                                                        </div>
                                                    </td>
                                                    <td>{{ Str::limit($paquete->descripcion, 50) }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ number_format($paquete->peso, 2) }} kg
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ number_format($paquete->altura, 2) }} m
                                                        </span>
                                                    </td>
                                                    <td>{{ $paquete->destinatario->nombre ?? 'N/A' }}</td>
                                                    <td>{{ $paquete->remitente->nombre ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $paquete->estadoActual == 'Pendiente' ? 'warning' : ($paquete->estadoActual == 'En camino' ? 'info' : 'primary') }}">
                                                            {{ $paquete->estadoActual }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Botón Asignar -->
                                <div class="text-center mt-3">
                                    <button 
                                        class="btn btn-success btn-lg px-5"
                                        wire:click="asignarPaquete"
                                        wire:loading.attr="disabled"
                                        id="btn-asignar"
                                        {{ !$paqueteSeleccionado || !$vehiculoAsignado || $porcentajeUso >= 100 ? 'disabled' : '' }}
                                    >
                                        <i class="fas fa-plus-circle me-2" wire:loading.class="fa-spin"></i>
                                        <span wire:loading.remove>Asignar Paquete Seleccionado</span>
                                        <span wire:loading>Asignando...</span>
                                    </button>
                                    @if(!$vehiculoAsignado)
                                        <p class="text-danger mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            No se puede asignar paquetes sin vehículo.
                                        </p>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay paquetes disponibles para {{ $tipoAccion }} en esta fecha.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paquetes Ya Asignados a este Motorista -->
            <div class="row" id="seccion-asignados">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list-check me-2"></i>
                                Paquetes Asignados a {{ $motoristaData->name }}
                            </h5>
                            <span class="badge bg-success" id="badge-asignados">
                                {{ $asignacionesDelMotorista->whereNotNull('idPaquete')->count() }} asignados
                            </span>
                        </div>
                        <div class="card-body">
                            @if($asignacionesDelMotorista->whereNotNull('idPaquete')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Paquete</th>
                                                <th>Peso</th>
                                                <th>Altura</th>
                                                <th>Destino</th>
                                                <th>Acción</th>
                                                <th>Operación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asignacionesDelMotorista->whereNotNull('idPaquete') as $index => $asignacion)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $asignacion->paquete->descripcion ?? 'N/A' }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ number_format($asignacion->paquete->peso ?? 0, 2) }} kg
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ number_format($asignacion->paquete->altura ?? 0, 2) }} m
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $asignacion->paquete->destinatario->nombre ?? 'N/A' }}
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $asignacion->paquete->destinatario->direccion ?? '' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $tipoAccion === 'recoger' ? 'warning' : 'primary' }}">
                                                            {{ ucfirst($tipoAccion) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button 
                                                            class="btn btn-sm btn-danger"
                                                            wire:click="quitarPaquete({{ $asignacion->id }})"
                                                            wire:loading.attr="disabled"
                                                            onclick="return confirm('¿Estás seguro de quitar este paquete?')"
                                                        >
                                                            <i class="fas fa-trash me-1"></i>Quitar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <!-- Total -->
                                            <tr class="table-active">
                                                <td colspan="2" class="text-end"><strong>TOTAL:</strong></td>
                                                <td>
                                                    <strong id="total-peso">{{ number_format($pesoTotal, 2) }} kg</strong>
                                                </td>
                                                <td>
                                                    <strong>
                                                        {{ number_format($asignacionesDelMotorista->whereNotNull('idPaquete')->sum(function($a) { return $a->paquete->altura ?? 0; }), 2) }} m³
                                                    </strong>
                                                </td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-box-open me-2"></i>
                                    Este motorista no tiene paquetes asignados aún para esta fecha.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5" id="sin-seleccion">
                <i class="fas fa-user-clock fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Selecciona un motorista para gestionar sus paquetes</h4>
                <p class="text-muted">Elige un motorista de la lista superior</p>
            </div>
        @endif
    </div>
    
    <!-- Footer -->
    <div class="card-footer text-muted">
        <small>
            <i class="fas fa-info-circle me-1"></i>
            Sistema de asignación de rutas - {{ date('Y') }}
            | Fecha: <span id="fecha-actual">{{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}</span>
            @if($motoristaSeleccionado)
                | Motorista: <span id="nombre-motorista">{{ $motoristasDelDia->firstWhere('id', $motoristaSeleccionado)->name ?? '' }}</span>
                @if($vehiculoAsignado)
                    | Capacidad: <span id="capacidad-actual">{{ $porcentajeUso }}%</span>
                @endif
            @endif
        </small>
    </div>
</div>

@push('scripts')
<script>
    // Función para seleccionar motorista
    function seleccionarMotorista(motoristaId) {
        // Remover selección anterior
        document.querySelectorAll('.motorista-card').forEach(card => {
            card.classList.remove('border-success', 'border-2');
            card.classList.add('border-secondary');
        });
        
        // Agregar selección actual
        const card = document.querySelector(`[data-motorista-id="${motoristaId}"]`);
        if (card) {
            card.classList.remove('border-secondary');
            card.classList.add('border-success', 'border-2');
        }
        
        // Enviar a Livewire
        Livewire.dispatch('seleccionarMotorista', { motoristaId: motoristaId });
    }
    
    // Función para deseleccionar motorista
    function deseleccionarMotorista() {
        document.querySelectorAll('.motorista-card').forEach(card => {
            card.classList.remove('border-success', 'border-2');
            card.classList.add('border-secondary');
        });
        
        Livewire.dispatch('seleccionarMotorista', { motoristaId: null });
    }
    
    // Función para seleccionar paquete
    function seleccionarPaquete(paqueteId) {
        const inputs = document.querySelectorAll('input[name="paqueteSeleccionado"]');
        inputs.forEach(input => {
            input.checked = (input.value == paqueteId);
        });
        
        // Enviar a Livewire
        @this.set('paqueteSeleccionado', paqueteId);
    }
    
    // Actualizar interfaz cuando cambia la fecha
    document.addEventListener('livewire:init', () => {
        // Escuchar cambios de fecha
        Livewire.on('fecha-cambiada', (event) => {
            // Actualizar fecha en el footer
            const fechaElement = document.getElementById('fecha-actual');
            if (fechaElement) {
                const fecha = new Date(event.fecha);
                fechaElement.textContent = fecha.toLocaleDateString('es-ES');
            }
            
            // Deseleccionar motorista
            deseleccionarMotorista();
            
            // Mostrar mensaje de carga
            console.log('Fecha cambiada a:', event.fecha);
        });
        
        // Escuchar selección de motorista
        Livewire.on('motorista-seleccionado', (event) => {
            console.log('Motorista seleccionado:', event.motoristaId);
        });
        
        // Escuchar actualización de interfaz
        Livewire.on('actualizar-interfaz', () => {
            console.log('Interfaz actualizada');
            // Aquí puedes agregar animaciones o efectos visuales
            document.getElementById('seccion-asignados')?.classList.add('highlight');
            setTimeout(() => {
                document.getElementById('seccion-asignados')?.classList.remove('highlight');
            }, 1000);
        });
    });
    
    // Actualizar progreso de capacidad con colores
    function actualizarProgresoCapacidad() {
        const progreso = document.getElementById('progreso-capacidad');
        const porcentaje = parseFloat(document.getElementById('porcentaje-uso')?.textContent || 0);
        
        if (progreso) {
            if (porcentaje >= 90) {
                progreso.className = 'progress-bar bg-danger';
            } else if (porcentaje >= 70) {
                progreso.className = 'progress-bar bg-warning';
            } else {
                progreso.className = 'progress-bar bg-success';
            }
        }
    }
    
    // Ejecutar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el select de fecha
        const selectFecha = document.getElementById('selectFecha');
        if (selectFecha) {
            selectFecha.addEventListener('change', function() {
                console.log('Fecha seleccionada:', this.value);
            });
        }
        
        // Actualizar progreso inicial
        actualizarProgresoCapacidad();
        
        // Actualizar progreso cada 2 segundos (por si hay cambios)
        setInterval(actualizarProgresoCapacidad, 2000);
    });
</script>
@endpush

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .highlight {
        animation: highlight 1s ease;
    }
    
    @keyframes highlight {
        0% { background-color: rgba(40, 167, 69, 0.1); }
        100% { background-color: transparent; }
    }
    
    tr:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }
    
    .motorista-card {
        transition: all 0.3s ease;
    }
    
    .motorista-card:hover {
        transform: scale(1.02);
    }
</style>
@endpush