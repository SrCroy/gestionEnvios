<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="modal fade" id="crearPaqueteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header bg-white border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bi bi-box-seam me-2"></i>Registrar Nuevo Paquete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <style>
                        /* Estilos Wizard exclusivos del modal */
                        .step-wizard {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 2rem;
                            position: relative;
                            padding: 0 10px;
                        }

                        .step-wizard::before {
                            content: '';
                            position: absolute;
                            top: 15px;
                            left: 0;
                            width: 100%;
                            height: 2px;
                            background: #e9ecef;
                            z-index: 0;
                        }

                        .step-wizard-item {
                            position: relative;
                            z-index: 1;
                            text-align: center;
                            flex: 1;
                        }

                        .step-wizard-circle {
                            width: 30px;
                            height: 30px;
                            background: #fff;
                            border: 2px solid #e9ecef;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 5px;
                            font-weight: bold;
                            font-size: 0.8rem;
                            color: #adb5bd;
                            transition: all 0.3s;
                        }

                        .step-wizard-item.active .step-wizard-circle {
                            border-color: #0056b3;
                            background: #0056b3;
                            color: #fff;
                        }

                        .step-wizard-item.completed .step-wizard-circle {
                            border-color: #28a745;
                            background: #28a745;
                            color: #fff;
                        }

                        .step-wizard-label {
                            font-size: 0.75rem;
                            font-weight: 600;
                            color: #6c757d;
                        }

                        .step-wizard-item.active .step-wizard-label {
                            color: #0056b3;
                        }

                        .destinatario-item {
                            cursor: pointer;
                            transition: all 0.2s;
                            border-left: 3px solid transparent;
                        }

                        .destinatario-item:hover {
                            background: #f8f9fa;
                            border-left-color: #0056b3;
                        }
                    </style>

                    <div class="step-wizard">
                        <div class="step-wizard-item {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'completed' : '' }}">
                            <div class="step-wizard-circle">@if($step > 1) <i class="bi bi-check-lg"></i> @else 1 @endif</div>
                            <div class="step-wizard-label">Datos</div>
                        </div>
                        <div class="step-wizard-item {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'completed' : '' }}">
                            <div class="step-wizard-circle">@if($step > 2) <i class="bi bi-check-lg"></i> @else 2 @endif</div>
                            <div class="step-wizard-label">Destinatario</div>
                        </div>
                        <div class="step-wizard-item {{ $step >= 3 ? 'active' : '' }} {{ $step > 3 ? 'completed' : '' }}">
                            <div class="step-wizard-circle">@if($step > 3) <i class="bi bi-check-lg"></i> @else 3 @endif</div>
                            <div class="step-wizard-label">Direcciones</div>
                        </div>
                        <div class="step-wizard-item {{ $step >= 4 ? 'active' : '' }}">
                            <div class="step-wizard-circle">4</div>
                            <div class="step-wizard-label">Confirmar</div>
                        </div>
                    </div>

                    <form wire:submit.prevent="crearPaquete">
                        @if($step === 1)
                        <div class="step-content animate__animated animate__fadeIn">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Descripción del Paquete <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('descripcionContenido') is-invalid @enderror" wire:model="descripcionContenido" placeholder="Ej: Paquete de camisas">
                                    @error('descripcionContenido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Peso (kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control @error('peso') is-invalid @enderror" wire:model="peso">
                                    @error('peso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Altura (cm) <span class="text-danger">*</span></label>
                                    <input type="number" step="1" class="form-control @error('altura') is-invalid @enderror" wire:model="altura">
                                    @error('altura') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($step === 2)
                        <div class="step-content animate__animated animate__fadeIn">
                            <div class="form-check form-switch mb-3 bg-light p-2 rounded">
                                <input class="form-check-input ms-0 me-2" type="checkbox" wire:model.live="esAutoenvio">

                                <label class="form-check-label fw-bold text-primary">Enviarme a mí mismo (Auto-envío)</label>
                            </div>

                            @if(!$esAutoenvio)
                            <div class="mb-3 position-relative">
                                <label class="form-label fw-bold small">Buscar cliente</label>
                                <input type="text" class="form-control" wire:model.live.debounce.300ms="buscarDestinatario" placeholder="Nombre, correo, teléfono...">

                                @if(count($resultadosBusqueda) > 0)
                                <div class="list-group position-absolute w-100 shadow mt-1" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                    @foreach($resultadosBusqueda as $cliente)
                                    <button type="button" class="list-group-item list-group-item-action py-2" wire:click="seleccionarDestinatario({{ $cliente->id }})">
                                        <div class="d-flex w-100 justify-content-between">
                                            <strong class="mb-1">{{ $cliente->nombre }}</strong>
                                        </div>
                                        <small class="text-muted d-block">{{ $cliente->correo }} | {{ $cliente->telefono }}</small>
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($destinatarioSeleccionado)
                            <div class="alert alert-success d-flex align-items-center py-2">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">{{ $destinatarioSeleccionado->nombre }}</div>
                                    <div class="small">{{ $destinatarioSeleccionado->correo }}</div>
                                </div>
                            </div>
                            @endif
                            @error('buscarDestinatario') <span class="text-danger d-block small mb-2">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        @if($step === 3)
                        <div class="step-content animate__animated animate__fadeIn">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="fw-bold small">Dirección de Origen</label>
                                    <textarea class="form-control" rows="2" wire:model="direccionOrigen"></textarea>
                                    @error('direccionOrigen') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="fw-bold small">Dirección de Destino</label>
                                    <textarea class="form-control" rows="2" wire:model="direccionDestino"></textarea>
                                    @error('direccionDestino') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="verificarDirecciones" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="verificarDirecciones"><i class="bi bi-shield-check me-1"></i> Verificar</span>
                                    <span wire:loading wire:target="verificarDirecciones">Verificando...</span>
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($step === 4)
                        <div class="step-content text-center animate__animated animate__fadeIn">
                            <div class="card bg-light border-0 mb-3 text-start p-3 small">
                                <p class="mb-1"><strong><i class="bi bi-box me-1"></i> Contenido:</strong> {{ $descripcionContenido }}</p>
                                <p class="mb-1"><strong><i class="bi bi-person me-1"></i> Destinatario:</strong> {{ $destinatarioSeleccionado->nombre ?? '' }}</p>
                                <p class="mb-1"><strong><i class="bi bi-geo-alt me-1"></i> Origen:</strong> {{ \Illuminate\Support\Str::limit($direccionOrigen, 40) }}</p>
                                <p class="mb-1"><strong><i class="bi bi-geo me-1"></i> Destino:</strong> {{ \Illuminate\Support\Str::limit($direccionDestino, 40) }}</p>
                                <hr class="my-2">
                                <p class="mb-0 text-success fw-bold text-end">Estimado: ${{ number_format(3.00 + ($peso * 0.50), 2) }}</p>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>

                <div class="modal-footer bg-light">
                    @if($step > 1)
                    <button type="button" class="btn btn-secondary" wire:click="previousStep">Atrás</button>
                    @else
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @endif

                    @if($step < 4)
                        <button type="button" class="btn btn-primary" wire:click="nextStep">Siguiente</button>
                        @else
                        <button type="button" class="btn btn-success px-4" wire:click="crearPaquete" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="crearPaquete">Confirmar Envío</span>
                            <span wire:loading wire:target="crearPaquete">Procesando...</span>
                        </button>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>