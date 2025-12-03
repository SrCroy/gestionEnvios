@extends('layouts.login')

@section('content')


<div class="d-flex align-items-center justify-content-center p-4" style="min-height: 100vh;">

    <div class="login-container" style="background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); overflow: hidden; width: 100%; max-width: 650px;">

        <div style="background: linear-gradient(135deg, var(--ues-blue), var(--ues-dark-blue)); color: white; padding: 30px; text-align: center;">
            <h4 style="font-weight: 700;" class="mb-1">Registro de Clientes</h4>
            <small style="font-weight: 500;" class="text-warning">Sistema de Gestión de Paquetes</small>
        </div>

        <div style="padding: 35px;">
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('cliente.register.store') }}">
                @csrf

               
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" placeholder="Ej: Juan Perez" name="nombre" class="form-control" style="border: 1px solid #00000093;" required value="{{ old('nombre') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input placeholder="Ej: correo@ejemplo.com" style="border: 1px solid #00000093;" type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                </div>

                {{-- FILA 2: Teléfono y Dirección --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label  class="form-label">Teléfono</label>
                        <input placeholder="Ej: 1234-5678" style="border: 1px solid #00000093;" type="text" name="telefono" class="form-control" required value="{{ old('telefono') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección (Auto-completado)</label>
                        <input style="border: 1px solid #00000093;" type="text" id="direccion" name="direccion" class="form-control" required placeholder="Buscar calle o colonia...">
                        {{-- Inputs ocultos --}}
                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">
                    </div>
                </div>

                {{-- FILA 3: Contraseñas --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input style="border: 1px solid #00000093;" type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input style="border: 1px solid #00000093;" type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                {{-- Botón de Registro (Estilo UES) --}}
                <button type="submit" class="btn w-100" 
                    style="
                        background: var(--ues-blue); 
                        color: white; 
                        border-radius: 10px; 
                        padding: 12px; 
                        font-weight: 600; 
                        font-size: 1.1rem;
                        transition: all 0.3s;
                    "
                    onmouseover="this.style.opacity='0.9'"
                    onmouseout="this.style.opacity='1'">
                    Registrarme
                </button>

                {{-- Enlace Volver --}}
                <div class="text-center mt-3">
                    <a href="{{ route('cliente.login') }}" style="color: var(--ues-blue); text-decoration: none; font-size: 0.9rem;">
                        <i class="bi bi-arrow-left me-1"></i>¿Ya tienes cuenta? Iniciar Sesión
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

{{-- SCRIPT DEL MAPA (Versión Permisiva) --}}
<script>
    function initAutocomplete() {
        const input = document.getElementById("direccion");
        const latInput = document.getElementById("latitud");
        const lngInput = document.getElementById("longitud");

        // 1. Configuración: Busca solo en SV, pero permite todo tipo de lugares
        const autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: { country: "sv" },
            fields: ["geometry", "formatted_address"],
            types: [] 
        });

        // 2. Evitar Enter accidental
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        // 3. Listener
        autocomplete.addListener("place_changed", function() {
            const place = autocomplete.getPlace();

            // Si no hay mapa (dirección manual), solo limpiamos coordenadas
            if (!place.geometry) {
                latInput.value = "";
                lngInput.value = "";
                return;
            }

            // Si hay mapa, guardamos
            latInput.value = place.geometry.location.lat();
            lngInput.value = place.geometry.location.lng();
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw&libraries=places&callback=initAutocomplete" async defer></script>

{{-- Estilos CSS para mantener la identidad visual --}}
<style>
    /* Efecto focus idéntico al Login */
    .form-control:focus {
        border-color: var(--ues-blue);
        box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
    }
    
    /* Ajuste responsivo: En celular (pantalla pequeña) vuelve a 1 sola columna automáticamente */
    @media (max-width: 768px) {
        .login-container {
            max-width: 100% !important;
        }
    }
</style>

@endsection