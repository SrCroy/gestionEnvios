@extends('home.app')

@section('title', 'Editar Vehículo - UES FMO')

@section('content')
<!-- Breadcrumb -->
<!-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
        <li class="breadcrumb-item active">Editar #{{ $vehiculo->id }}</li>
    </ol>
</nav> -->

<!-- Header -->
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-pencil-square me-2"></i>
                EDITAR VEHÍCULO
            </h3>
            <p class="mb-0">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
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

                <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                   value="{{ old('marca', $vehiculo->marca) }}" 
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
                                   value="{{ old('modelo', $vehiculo->modelo) }}" 
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
                                   value="{{ old('pesoMaximo', $vehiculo->pesoMaximo) }}" 
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
                                   value="{{ old('volumenMaximo', $vehiculo->volumenMaximo) }}" 
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
                                    <option value="{{ $estado }}" 
                                        {{ old('estado', $vehiculo->estado) == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información del Registro -->
                    <!-- <div class="alert alert-light border">
                        <div class="row small">
                            <div class="col-md-6">
                                <i class="bi bi-calendar-plus me-2"></i>
                                <strong>Creado:</strong> {{ $vehiculo->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-6">
                                <i class="bi bi-calendar-check me-2"></i>
                                <strong>Última actualización:</strong> {{ $vehiculo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div> -->

                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                            <!-- <i class="bi bi-x-circle me-2"></i> -->
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <!-- <i class="bi bi-check-circle me-2"></i> -->
                            Actualizar Vehículo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection