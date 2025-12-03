@extends('home.index-clientes')

@section('title', 'Mis Envíos - UES FMO')

@section('content')
    <style>
        /* Estilos específicos para el Stepper de progreso */
        .step-progress {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        .step-progress::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #e9ecef;
            z-index: 0;
        }
        .step-item {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 25%;
        }
        .step-circle {
            width: 35px;
            height: 35px;
            background: #fff;
            border: 3px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #adb5bd;
            transition: all 0.3s;
        }
        .step-item.active .step-circle {
            border-color: var(--ues-color);
            background: var(--ues-color);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.1);
        }
        .step-item.completed .step-circle {
            border-color: #28a745;
            background: #28a745;
            color: #fff;
        }
        .step-item.completed::after {
            content: '';
            position: absolute;
            top: 15px;
            left: -50%;
            width: 100%;
            height: 3px;
            background: #28a745;
            z-index: -1;
        }
        /* Fix para la primera línea */
        .step-item:first-child.completed::after { display: none; }
        
        .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
        }
        .step-item.active .step-label { color: var(--ues-color); }
        .step-item.completed .step-label { color: #28a745; }
    </style>

    <!-- Header / Buscador Hero -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, var(--ues-color) 0%, #00306b 100%); border-radius: 15px;">
                <div class="card-body p-5 text-center text-white position-relative">
                    <h2 class="fw-bold mb-3 position-relative">Rastrea tu envío</h2>
                    <p class="mb-4 opacity-75 position-relative">Ingresa el número de guía para ver el estado actual de tu paquete.</p>
                    
                    <div class="row justify-content-center position-relative">
                        <div class="col-md-8 col-lg-6">
                            <div class="input-group input-group-lg bg-white p-1 rounded-pill shadow-lg">
                                <input type="text" class="form-control border-0 rounded-pill ps-4" placeholder="Ej: PK-2024-8890" style="box-shadow: none;">
                                <button class="btn btn-warning rounded-pill px-4 fw-bold text-dark" type="button">
                                    Rastrear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Principal: Envíos Activos -->
        <div class="col-lg-8">
            <h5 class="fw-bold text-secondary mb-3">Envíos en Curso</h5>

            <!-- Tarjeta de Envío 1 (En Tránsito) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">EN TRÁNSITO</span>
                        <span class="ms-2 fw-bold text-dark">Guía: #PK-2023-889</span>
                    </div>
                    <small class="text-muted">Est. Entrega: <strong>Hoy, 14:00 PM</strong></small>
                </div>
                <div class="card-body pt-0 pb-4 px-4">
                    <div class="d-flex align-items-center mb-4 mt-3">
                        <div class="bg-light p-3 rounded me-3">
                            <i class="bi bi-laptop fs-3 text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Laptop HP Pavilion</h6>
                            <small class="text-muted">Destino: San Miguel Centro</small>
                        </div>
                    </div>

                    <!-- Stepper Visual -->
                    <div class="step-progress">
                        <div class="step-item completed">
                            <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                            <div class="step-label">Recolectado</div>
                        </div>
                        <div class="step-item completed">
                            <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                            <div class="step-label">En Almacén</div>
                        </div>
                        <div class="step-item active">
                            <div class="step-circle"><i class="bi bi-truck"></i></div>
                            <div class="step-label">En Ruta</div>
                        </div>
                        <div class="step-item">
                            <div class="step-circle"><i class="bi bi-house"></i></div>
                            <div class="step-label">Entregado</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 text-center py-2">
                    <a href="#" class="text-decoration-none small fw-bold text-primary">Ver detalles completos <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>

            <!-- Tarjeta de Envío 2 (Procesando) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">PROCESANDO</span>
                        <span class="ms-2 fw-bold text-dark">Guía: #PK-2023-902</span>
                    </div>
                    <small class="text-muted">Est. Entrega: <strong>Mañana</strong></small>
                </div>
                <div class="card-body pt-0 pb-4 px-4">
                    <div class="d-flex align-items-center mb-4 mt-3">
                        <div class="bg-light p-3 rounded me-3">
                            <i class="bi bi-file-earmark-text fs-3 text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Documentos Legales</h6>
                            <small class="text-muted">Destino: Ciudad Universitaria</small>
                        </div>
                    </div>

                    <!-- Stepper Visual -->
                    <div class="step-progress">
                        <div class="step-item active">
                            <div class="step-circle"><i class="bi bi-box-seam"></i></div>
                            <div class="step-label">Recolectado</div>
                        </div>
                        <div class="step-item">
                            <div class="step-circle">2</div>
                            <div class="step-label">En Almacén</div>
                        </div>
                        <div class="step-item">
                            <div class="step-circle">3</div>
                            <div class="step-label">En Ruta</div>
                        </div>
                        <div class="step-item">
                            <div class="step-circle">4</div>
                            <div class="step-label">Entregado</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna Lateral: Resumen y Ayuda -->
        <div class="col-lg-4">
            
            <!-- Resumen de Cuenta -->
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-secondary">Mi Resumen</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 border rounded text-center h-100 bg-light">
                                <h3 class="fw-bold text-success mb-0">12</h3>
                                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Entregados</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded text-center h-100 bg-light">
                                <h3 class="fw-bold text-primary mb-0">2</h3>
                                <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Activos</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="btn btn-outline-primary w-100 btn-sm">Ver Historial Completo</a>
                    </div>
                </div>
            </div>

            <!-- Direcciones Guardadas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3 border-bottom-0">
                    <i class="bi bi-geo-alt me-2 text-danger"></i> Dirección Principal
                </div>
                <div class="card-body pt-0">
                    <p class="mb-1 text-muted small">Av. Las Amapolas #123, Col. San Francisco</p>
                    <p class="mb-0 text-muted small">San Miguel, El Salvador</p>
                    <hr class="my-2">
                    <a href="#" class="text-decoration-none small fw-bold">Editar dirección</a>
                </div>
            </div>

        </div>
    </div>
@endsection