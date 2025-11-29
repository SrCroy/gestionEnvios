@extends('home.app')

@section('title', 'Crear Vehículo - UES FMO')

@section('content')
<!-- Breadcrumb -->
<!-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
        <li class="breadcrumb-item active">Crear Nuevo</li>
    </ol>
</nav> -->

<!-- Header -->
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-plus-circle me-2"></i>
                REGISTRAR NUEVO VEHÍCULO
            </h3>
            <p class="mb-0">Complete el formulario para agregar un vehículo</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Volver
            </a>
        </div>
    </div>
</div>

<!-- Formulario -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <!-- <i class="bi bi-truck me-2" style="color: var(--ues-color);"></i> -->
                    Datos del Vehículo
                </h5>
            </div>
            <div class="card-body">
                <!-- Mostrar errores de validación -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Por favor corrija los siguientes errores:
                        </h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('vehiculos.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Marca -->
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">
                                <!-- <i class="bi bi-tag me-1"></i> -->
                                Marca <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('marca') is-invalid @enderror" 
                                   id="marca" 
                                   name="marca" 
                                   value="{{ old('marca') }}" 
                                   placeholder="Ej: Toyota, Nissan, Isuzu"
                                   required>
                            @error('marca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Modelo -->
                        <div class="col-md-6 mb-3">
                            <label for="modelo" class="form-label">
                                <!-- <i class="bi bi-car-front me-1"></i> -->
                                Modelo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('modelo') is-invalid @enderror" 
                                   id="modelo" 
                                   name="modelo" 
                                   value="{{ old('modelo') }}" 
                                   placeholder="Ej: Hilux, Frontier, D-Max"
                                   required>
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Peso Máximo -->
                        <div class="col-md-6 mb-3">
                            <label for="pesoMaximo" class="form-label">
                                <!-- <i class="bi bi-box-seam me-1"></i> -->
                                Peso Máximo (kg) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('pesoMaximo') is-invalid @enderror" 
                                   id="pesoMaximo" 
                                   name="pesoMaximo" 
                                   value="{{ old('pesoMaximo') }}" 
                                   placeholder="Ej: 5000.00"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('pesoMaximo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Capacidad máxima de carga en kilogramos</small>
                        </div>

                        <!-- Volumen Máximo -->
                        <div class="col-md-6 mb-3">
                            <label for="volumenMaximo" class="form-label">
                                <!-- <i class="bi bi-grid-3x3 me-1"></i> -->
                                Volumen Máximo (m³) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('volumenMaximo') is-invalid @enderror" 
                                   id="volumenMaximo" 
                                   name="volumenMaximo" 
                                   value="{{ old('volumenMaximo') }}" 
                                   placeholder="Ej: 20.00"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('volumenMaximo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Capacidad volumétrica en metros cúbicos</small>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-12 mb-3">
                            <label for="estado" class="form-label">
                                <!-- <i class="bi bi-circle-fill me-1"></i> -->
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado" 
                                    required>
                                <option value="">-- Seleccione un estado --</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado }}" {{ old('estado') == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <!-- <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                    </div> -->

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                            <!-- <i class="bi bi-x-circle me-2"></i> -->
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <!-- <i class="bi bi-check-circle me-2"></i> -->
                            Guardar Vehículo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card de ayuda -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <h6 class="text-muted">
                    <i class="bi bi-question-circle me-2"></i>
                    Ayuda
                </h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Marca:</strong> Fabricante del vehículo (Toyota, Nissan, etc.)</li>
                    <li><strong>Modelo:</strong> Nombre del modelo específico</li>
                    <li><strong>Peso Máximo:</strong> Capacidad de carga en kilogramos</li>
                    <li><strong>Volumen Máximo:</strong> Espacio de carga en metros cúbicos</li>
                    <li><strong>Estado:</strong> Situación actual del vehículo en la flota</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection