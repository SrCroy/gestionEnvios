<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-route me-2"></i>Asignación de Paquetes a Motoristas
        </h4>
        <button class="btn btn-sm btn-light" wire:click="$refresh">
            <i class="fas fa-sync-alt"></i> Actualizar
        </button>
    </div>
    
    <div class="card-body">
        <!-- Fecha y Tipo de Acción -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-day me-1"></i>Fecha de Trabajo
                </label>
                <select class="form-select" wire:model="fechaSeleccionada" wire:change="actualizarFecha($event.target.value)">
                    @foreach($fechasConAsignaciones as $fecha)
                        <option value="{{ $fecha }}">
                            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Fechas con motoristas programados</small>
            </div>
            
            <div class="col-md-8">
                <label class="form-label fw-bold">
                    <i class="fas fa-tasks me-1"></i>Tipo de Paquete a Asignar
                </label>
                <div class="btn-group w-100" role="group">
                    <button 
                        type="button" 
                        class="btn {{ $tipoAccion === 'recoger' ? 'btn-success' : 'btn-outline-success' }}"
                        wire:click="cambiarTipoAccion('recoger')"
                    >
                        <i class="fas fa-box-open me-1"></i>Paquetes para Recoger
                    </button>
                    <button 
                        type="button" 
                        class="btn {{ $tipoAccion === 'entregar' ? 'btn-warning' : 'btn-outline-warning' }}"
                        wire:click="cambiarTipoAccion('entregar')"
                    >
                        <i class="fas fa-truck me-1"></i>Paquetes para Entregar
                    </button>
                </div>
            </div>
        </div>

        <!-- Motoristas Programados para esta Fecha -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Motoristas Programados para el {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            <span class="badge bg-primary ms-2">{{ count($motoristasDelDia) }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($motoristasDelDia) > 0)
                            <div class="row">
                                @foreach($motoristasDelDia as $motorista)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 border 
                                            {{ $motoristaSeleccionado == $motorista->id ? 'border-success border-2' : 'border-secondary' }} 
                                            hover-shadow"
                                            style="cursor: pointer;"
                                            wire:click="seleccionarMotorista({{ $motorista->id }})"
                                            wire:key="motorista-{{ $motorista->id }}"
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
                                                        Listo para trabajar
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No hay motoristas programados para trabajar en esta fecha.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($motoristaSeleccionado)
            @php
                $motoristaData = $motoristasDelDia->firstWhere('id', $motoristaSeleccionado);
            @endphp
            
            <!-- Información del Motorista Seleccionado -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie me-2"></i>
                                {{ $motoristaData->name }} - {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            </h5>
                            <button type="button" class="btn btn-sm btn-light" wire:click="resetSelecciones">
                                <i class="fas fa-times me-1"></i>Cambiar Motorista
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6><i class="fas fa-user me-2"></i>Motorista</h6>
                                    <p class="mb-1"><strong>Nombre:</strong> {{ $motoristaData->name }}</p>
                                    <p class="mb-0"><strong>Teléfono:</strong> {{ $motoristaData->telefono }}</p>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6><i class="fas fa-truck me-2"></i>Vehículo Asignado</h6>
                                    @if($vehiculoAsignado)
                                        <p class="mb-1">
                                            <strong>Vehículo:</strong> {{ $vehiculoAsignado->marca }} {{ $vehiculoAsignado->modelo }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Capacidad:</strong> {{ number_format($vehiculoAsignado->pesoMaximo, 2) }} kg
                                        </p>
                                    @else
                                        <div class="alert alert-danger mb-0">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>¡No hay vehículo asignado!</strong>
                                            <br><small>Este motorista no tiene vehículo asignado para esta fecha.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <h6><i class="fas fa-chart-pie me-2"></i>Estadísticas del Día</h6>
                                    <p class="mb-1">
                                        <strong>Paquetes asignados:</strong> 
                                        <span class="badge bg-info">
                                            {{ $asignacionesDelMotorista instanceof \Illuminate\Support\Collection ? $asignacionesDelMotorista->whereNotNull('idPaquete')->count() : 0 }}
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Peso total:</strong> {{ number_format($pesoTotal, 2) }} kg
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra de Progreso de Capacidad -->
            @if($vehiculoAsignado)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-weight-hanging me-2"></i>
                                Uso de Capacidad del Vehículo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Peso: {{ number_format($pesoTotal, 2) }} kg / {{ number_format($capacidadMaxima, 2) }} kg</span>
                                    <span>{{ $porcentajeUso }}%</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar 
                                        @if($porcentajeUso >= 90) bg-danger
                                        @elseif($porcentajeUso >= 70) bg-warning
                                        @else bg-success @endif"
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
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>¡Capacidad máxima alcanzada!</strong> No se pueden agregar más paquetes.
                                </div>
                            @elseif($porcentajeUso >= 90)
                                <div class="alert alert-warning">
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
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>
                                Paquetes para {{ $tipoAccion === 'recoger' ? 'RECOGER' : 'ENTREGAR' }}
                            </h5>
                            <span class="badge bg-primary">
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
                                                <th>Destinatario</th>
                                                <th>Dirección</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paquetesDisponibles as $paquete)
                                                <tr wire:key="paquete-{{ $paquete->id }}">
                                                    <td>
                                                        <div class="form-check">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="checkbox" 
                                                                value="{{ $paquete->id }}"
                                                                wire:model="paquetesSeleccionados"
                                                            >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $paquete->descripcion }}</strong>
                                                    </td>
                                                    <td>{{ number_format($paquete->peso, 2) }}</td>
                                                    <td>{{ $paquete->destinatario->nombre ?? 'N/A' }}</td>
                                                    <td>
                                                        <small>{{ $paquete->destinatario->direccion ?? 'Sin dirección' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $paquete->estadoActual === 'Recoger' ? 'warning' : 'primary' }}">
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
                                    @php
                                        $disabled = empty($paquetesSeleccionados) || !$vehiculoAsignado || ($pesoSeleccionado + $pesoTotal > $capacidadMaxima);
                                    @endphp
                                    
                                    <button 
                                        type="button"
                                        class="btn btn-success btn-lg px-5 {{ $disabled ? 'disabled' : '' }}"
                                        wire:click="asignarPaquetes"
                                        wire:loading.attr="disabled"
                                        @if($disabled) disabled @endif
                                    >
                                        <i class="fas fa-plus-circle me-2" wire:loading.class="fa-spin"></i>
                                        <span wire:loading.remove>Asignar Paquetes Seleccionados</span>
                                        <span wire:loading>Asignando...</span>
                                    </button>

                                    <div class="mt-2">
                                        @if(empty($paquetesSeleccionados))
                                            <p class="text-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Selecciona al menos un paquete
                                            </p>
                                        @endif
                                        @if(!$vehiculoAsignado)
                                            <p class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                <strong>No se puede asignar:</strong> Este motorista no tiene vehículo asignado
                                            </p>
                                        @endif
                                        @if($vehiculoAsignado && ($pesoSeleccionado + $pesoTotal > $capacidadMaxima))
                                            <p class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Capacidad excedida en {{ number_format($pesoSeleccionado + $pesoTotal - $capacidadMaxima, 2) }} kg
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay paquetes disponibles para {{ $tipoAccion === 'recoger' ? 'recoger' : 'entregar' }}.
                                    <br>
                                    <small>Todos los paquetes en estado <strong>{{ $tipoAccion === 'recoger' ? 'Recoger' : 'Entregar' }}</strong> ya están asignados o tienen vehículo.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paquetes Ya Asignados a este Motorista -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list-check me-2"></i>
                                Paquetes Asignados el {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            </h5>
                            <span class="badge bg-success">
                                {{ $asignacionesDelMotorista instanceof \Illuminate\Support\Collection ? $asignacionesDelMotorista->whereNotNull('idPaquete')->count() : 0 }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($asignacionesDelMotorista instanceof \Illuminate\Support\Collection && $asignacionesDelMotorista->whereNotNull('idPaquete')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Paquete</th>
                                                <th>Peso</th>
                                                <th>Destinatario</th>
                                                <th>Dirección</th>
                                                <th>Estado</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asignacionesDelMotorista->whereNotNull('idPaquete') as $index => $asignacion)
                                                <tr wire:key="asignacion-{{ $asignacion->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $asignacion->paquete->descripcion ?? 'N/A' }}</strong>
                                                        @if($asignacion->paquete && $asignacion->paquete->remitente)
                                                            <br><small class="text-muted">De: {{ $asignacion->paquete->remitente->nombre }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ number_format($asignacion->paquete->peso ?? 0, 2) }} kg
                                                        </span>
                                                    </td>
                                                    <td>{{ $asignacion->paquete->destinatario->nombre ?? 'N/A' }}</td>
                                                    <td>
                                                        <small>{{ $asignacion->paquete->destinatario->direccion ?? '' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $asignacion->paquete->estadoActual === 'En camino' ? 'warning' : 'primary' }}">
                                                            {{ $asignacion->paquete->estadoActual ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button 
                                                            type="button"
                                                            class="btn btn-sm btn-danger"
                                                            wire:click="quitarPaquete({{ $asignacion->id }})"
                                                            onclick="return confirm('¿Quitar este paquete del motorista?')"
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
                                                    <strong class="text-primary">{{ number_format($pesoTotal, 2) }} kg</strong>
                                                </td>
                                                <td colspan="4"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-box-open me-2"></i>
                                    Este motorista no tiene paquetes asignados para esta fecha.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-clock fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Selecciona un motorista</h4>
                <p class="text-muted">Elige un motorista de la lista para asignarle paquetes de trabajo</p>
            </div>
        @endif
    </div>
</div>