@extends('home.index-clientes')

@section('title', 'Mis Paquetes - UES FMO')

@section('content')
    <style>
        /* Estilos exclusivos para esta vista */
        .pkg-card {
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            transition: all 0.2s ease;
            background: white;
        }
        .pkg-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
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
        .route-dot.start { background: var(--ues-color); }
        .route-dot.end { background: #28a745; }
        
        .filter-btn {
            border-radius: 20px;
            font-weight: 600;
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
        }
        .filter-btn.active {
            background-color: var(--ues-color);
            color: white;
            border-color: var(--ues-color);
        }
    </style>

    <!-- Encabezado y Buscador -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Mis Paquetes</h3>
            <p class="text-muted mb-0">Gestiona y rastrea todos tus envíos.</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 py-2" placeholder="Buscar por guía, destinatario o contenido...">
                <button class="btn btn-primary px-4 fw-bold">Buscar</button>
            </div>
        </div>
    </div>

    <!-- Filtros de Estado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 overflow-auto pb-2">
                <button class="btn btn-outline-secondary filter-btn active border-0 shadow-sm">
                    Todos <span class="badge bg-white text-dark ms-1">5</span>
                </button>
                <button class="btn btn-outline-secondary filter-btn border-0 bg-white shadow-sm">
                    En Tránsito <span class="badge bg-light text-dark ms-1">2</span>
                </button>
                <button class="btn btn-outline-secondary filter-btn border-0 bg-white shadow-sm">
                    Entregados <span class="badge bg-light text-dark ms-1">3</span>
                </button>
                <button class="btn btn-outline-secondary filter-btn border-0 bg-white shadow-sm">
                    Devoluciones <span class="badge bg-light text-dark ms-1">0</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Paquetes -->
    <div class="row g-3">
        
        <!-- ITEM 1: En Tránsito -->
        <div class="col-lg-6">
            <div class="pkg-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pkg-icon text-primary bg-primary bg-opacity-10">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Laptop Gaming HP</h6>
                            <small class="text-muted">Guía: <span class="text-dark fw-bold">PK-2024-001</span></small>
                        </div>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="bi bi-truck me-1"></i> En Ruta
                    </span>
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="route-line">
                        <div class="route-dot start"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">ORIGEN</small>
                        <span class="fw-bold text-dark">UES FMO, San Miguel</span>
                    </div>
                    <div class="route-line">
                        <div class="route-dot end"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">DESTINO</small>
                        <span class="fw-bold text-dark">Colonia San Francisco, SM</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> Est. Hoy, 5:00 PM</small>
                    <a href="#" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>

        <!-- ITEM 2: Procesando -->
        <div class="col-lg-6">
            <div class="pkg-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pkg-icon text-warning bg-warning bg-opacity-10">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Paquete Documentos</h6>
                            <small class="text-muted">Guía: <span class="text-dark fw-bold">PK-2024-045</span></small>
                        </div>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                        <i class="bi bi-hourglass-split me-1"></i> Procesando
                    </span>
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="route-line">
                        <div class="route-dot start"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">ORIGEN</small>
                        <span class="fw-bold text-dark">UES FMO, San Miguel</span>
                    </div>
                    <div class="route-line">
                        <div class="route-dot end"></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">DESTINO</small>
                        <span class="fw-bold text-dark">Metrocentro, San Miguel</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> Est. Mañana</small>
                    <a href="#" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>

        <!-- ITEM 3: Entregado -->
        <div class="col-lg-6">
            <div class="pkg-card p-3 h-100 border-success border-opacity-25" style="background-color: #f8fff9;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pkg-icon text-success bg-white border border-success border-opacity-25">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-success">Zapatos Deportivos</h6>
                            <small class="text-muted">Guía: <span class="text-dark fw-bold">PK-2023-999</span></small>
                        </div>
                    </div>
                    <span class="badge bg-success text-white px-3 py-2 rounded-pill">
                        Entregado
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-geo-alt-fill text-success"></i>
                    <span class="text-dark fw-bold small">Entregado en: Res. Los Pinos #44</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-success border-opacity-10">
                    <small class="text-success fw-bold"><i class="bi bi-check-all me-1"></i> Recibido por Juan Pérez</small>
                    <button class="btn btn-sm btn-link text-secondary text-decoration-none">Ver Comprobante</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Paginación -->
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection