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
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-day me-1"></i>Fecha de Trabajo
                </label>
                <select class="form-select" wire:model.live="fechaSeleccionada">
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

        ---

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Motoristas Programados para el {{ \Carbon\Carbon::parse($fechaSeleccionada)->format('d/m/Y') }}
                            <span class="badge bg-primary ms-2">{{ $motoristasDelDia->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($motoristasDelDia->count() > 0)
                            <div class="row">
                                @foreach($motoristasDelDia as $motorista)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 border 
                                            {{ $motoristaSeleccionado == $motorista->id ? 'border-success border-2 shadow-lg' : 'border-secondary' }} 
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
                                                <small class="badge bg-{{ $motorista->asignaciones_count > 0 ? 'success' : 'warning' }} mt-1">
                                                    {{ $motorista->asignaciones_count }} paquete(s) asignado(s)
                                                </small>
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
            
            ---

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-tie me-2"></i>
                                **Motorista Seleccionado:** {{ $motoristaData->name ?? 'N/A' }}
                            </h5>
                            <button type="button" class="btn btn-sm btn-light" wire:click="resetSelecciones">
                                <i class="fas fa-times me-1"></i>Cambiar Motorista
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6><i class="fas fa-truck me-2"></i>Vehículo Asignado</h6>
                                    @if($vehiculoAsignado)
                                        <p class="mb-1">
                                            <strong>Vehículo:</strong> {{ $vehiculoAsignado->marca ?? 'N/A' }} {{ $vehiculoAsignado->modelo ?? '' }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Capacidad Max:</strong> {{ number_format($capacidadMaxima, 2) }} kg
                                        </p>
                                    @else
                                        <div class="alert alert-danger mb-0">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            **¡No hay vehículo asignado!**
                                            <br><small>El botón de asignación estará inhabilitado.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <h6><i class="fas fa-chart-pie me-2"></i>Estadísticas del Día</h6>
                                    <p class="mb-1">
                                        <strong>Paquetes asignados:</strong> 
                                        <span class="badge bg-info">
                                            {{ $asignacionesDelMotorista->whereNotNull('idPaquete')->count() }}
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Peso total actual:</strong> {{ number_format($pesoTotal, 2) }} kg
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6><i class="fas fa-box-open me-2"></i>Paquetes a Añadir</h6>
                                    <p class="mb-1">
                                        <strong>Paquetes seleccionados:</strong> 
                                        <span class="badge bg-secondary">{{ count($paquetesSeleccionados) }}</span>
                                    </p>
                                    <p class="mb-0">
                                        {{-- LÍNEA 160: ESTA ES LA LÍNEA QUE CAUSÓ EL ERROR --}}
                                        <strong>Peso a añadir:</strong> {{ number_format($pesoSeleccionado, 2) }} kg
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($vehiculoAsignado)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-weight-hanging me-2"></i>
                                Uso de Capacidad del Vehículo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>**Peso:** {{ number_format($pesoTotal + $pesoSeleccionado, 2) }} kg / {{ number_format($capacidadMaxima, 2) }} kg</span>
                                    @php
                                        $pesoActualizado = $pesoTotal + $pesoSeleccionado;
                                        $porcentajeActualizado = $capacidadMaxima > 0 ? min(100, round(($pesoActualizado / $capacidadMaxima) * 100, 2)) : 0;
                                    @endphp
                                    <span>**{{ $porcentajeActualizado }}%**</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar 
                                        @if($porcentajeActualizado >= 100) bg-danger
                                        @elseif($porcentajeActualizado >= 80) bg-warning
                                        @else bg-success @endif"
                                        role="progressbar" 
                                        style="width: {{ $porcentajeActualizado }}%"
                                        aria-valuenow="{{ $porcentajeActualizado }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                        {{ $porcentajeActualizado }}%
                                    </div>
                                </div>
                            </div>
                            
                            @if($pesoActualizado > $capacidadMaxima && $capacidadMaxima > 0)
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    **¡Capacidad máxima excedida!** Exceso de **{{ number_format($pesoActualizado - $capacidadMaxima, 2) }} kg**. Deselecciona paquetes.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            ---

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center bg-info bg-opacity-10">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>
                                Paquetes Disponibles para **{{ $tipoAccion === 'recoger' ? 'RECOGER' : 'ENTREGAR' }}**
                            </h5>
                            <span class="badge bg-info">
                                {{ $paquetesDisponibles->count() }} disponibles
                            </span>
                        </div>
                        <div class="card-body">
                            @if($paquetesDisponibles->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">Sel.</th>
                                                <th width="25%">Descripción</th>
                                                <th width="10%">Peso (kg)</th>
                                                <th width="20%">Destinatario</th>
                                                <th width="30%">Dirección</th>
                                                <th width="10%">Estado</th>
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
                                                                wire:model.live="paquetesSeleccionados"
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

                                <div class="text-center mt-3">
                                    @php
                                        $disabled = empty($paquetesSeleccionados) || !$vehiculoAsignado || ($pesoSeleccionado + $pesoTotal > $capacidadMaxima && $capacidadMaxima > 0);
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
                                                **No se puede asignar:** Este motorista no tiene vehículo asignado
                                            </p>
                                        @endif
                                        @if($vehiculoAsignado && $capacidadMaxima > 0 && ($pesoSeleccionado + $pesoTotal > $capacidadMaxima))
                                            <p class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Capacidad excedida en **{{ number_format($pesoSeleccionado + $pesoTotal - $capacidadMaxima, 2) }} kg**
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay paquetes disponibles para **{{ $tipoAccion === 'recoger' ? 'recoger' : 'entregar' }}**.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            ---

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center bg-success bg-opacity-10">
                            <h5 class="mb-0">
                                <i class="fas fa-list-check me-2"></i>
                                Paquetes Ya Asignados al Motorista
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($asignacionesDelMotorista->whereNotNull('idPaquete')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-success">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="20%">Paquete</th>
                                                <th width="10%">Peso</th>
                                                <th width="20%">Destinatario</th>
                                                <th width="30%">Dirección</th>
                                                <th width="10%">Estado</th>
                                                <th width="5%">Acción</th>
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
                                                        <span class="badge bg-{{ $asignacion->paquete->estadoActual === 'En camino' ? 'success' : 'primary' }}">
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
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
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