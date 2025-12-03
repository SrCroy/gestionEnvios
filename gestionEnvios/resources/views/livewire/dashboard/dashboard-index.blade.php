<div>
    {{-- Encabezado UES --}}
    <div class="ues-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2 fw-bold text-white">
                    <i class="bi bi-building me-2"></i>
                    UNIVERSIDAD DE EL SALVADOR
                </h3>
                <p class="mb-0 text-white-50">Facultad Multidisciplinaria Oriental - Panel de Control</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                    <i class="bi bi-activity me-1"></i> SISTEMA EN LÍNEA
                </span>
            </div>
        </div>
    </div>

    {{-- Tarjetas Dinámicas --}}
    <div class="row g-4" wire:poll.10s> {{-- wire:poll.10s actualiza los datos cada 10 segundos automáticamente --}}
        
        <!-- Tarjeta 1: Total Paquetes -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white h-100 shadow-sm border-0" 
                 style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalPaquetes) }}</h2>
                            <p class="mb-0 opacity-75">Paquetes Registrados</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box-seam fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Entregados Hoy -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white h-100 shadow-sm border-0" 
                 style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ number_format($entregadosHoy) }}</h2>
                            <p class="mb-0 opacity-75">Entregados Hoy</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: En Tránsito -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white h-100 shadow-sm border-0" 
                 style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ number_format($enTransito) }}</h2>
                            <p class="mb-0 opacity-75">En Tránsito</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-truck fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4: Vehículos Activos -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card text-white h-100 shadow-sm border-0" 
                 style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ number_format($vehiculosActivos) }}</h2>
                            <p class="mb-0 opacity-75">Vehículos Activos</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-car-front fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>