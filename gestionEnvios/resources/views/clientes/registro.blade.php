@extends('layouts.login')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-container" style="background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,.3); overflow: hidden; width: 100%; max-width: 420px;">

        <!-- Header -->
        <div style="background: linear-gradient(135deg, var(--ues-blue), var(--ues-dark-blue)); color: white; padding: 30px; text-align: center;">
            <h4 class="mb-1">Registro de Clientes</h4>
            <small class="text-warning">Sistema de Gestión de Paquetes</small>
        </div>

        <!-- Form -->
        <div style="padding: 30px;">
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('cliente.register.store') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required value="{{ old('telefono') }}">
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label class="form-label">Dirección (Auto-completado)</label>
                    <input type="text" id="direccion" name="direccion" class="form-control" required>
                    <input type="hidden" id="latitud" name="latitud">
                    <input type="hidden" id="longitud" name="longitud">

                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn w-100" style="background: var(--ues-blue); color: white; border-radius: 10px;">
                    Registrarme
                </button>
            </form>
        </div>

    </div>
</div>
<script>
    function initAutocomplete() {
        const input = document.getElementById("direccion");
        const latInput = document.getElementById("latitud");
        const lngInput = document.getElementById("longitud");

        // 1. Configuración del Autocompletado
        const autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: {
                country: "sv"
            }, // <--- ESTO hace la magia: Solo busca en El Salvador
            fields: ["geometry", "formatted_address"],
            types: [] // Busca todo: calles, casas, negocios, ciudades
        });

        // 2. Prevenir que el "Enter" envíe el formulario accidentalmente
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        // 3. Qué hacer cuando cambia el lugar
        autocomplete.addListener("place_changed", function() {
            const place = autocomplete.getPlace();

            // ESCENARIO A: El usuario escribió algo manual que Google no reconoce
            if (!place.geometry) {
                // YA NO borramos el texto.
                // YA NO mostramos alerta de error.
                // Solo limpiamos las coordenadas para no enviar basura.
                latInput.value = "";
                lngInput.value = "";
                return;
            }

            // ESCENARIO B: El usuario seleccionó una sugerencia de la lista
            latInput.value = place.geometry.location.lat();
            lngInput.value = place.geometry.location.lng();
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw&libraries=places&callback=initAutocomplete" async defer></script>


@endsection