<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
 <div class="container">
    
    
    <div class="card border-0 shadow-lg mx-auto" style="max-width: 800px; border-radius: 12px; overflow: hidden;">
        
         <div class="card-header p-3 text-center" style="background: linear-gradient(135deg, #003366 0%, #0056b3 100%); color: white;">
            <h5 class="mb-0 fw-bold">Editar Mi Perfil</h5>
        </div>

        <div class="card-body p-4 bg-white">

            
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 py-2 small border-0 shadow-sm" style="background-color: #d1e7dd;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form wire:submit.prevent="guardar">
                <div class="row g-3">
                   
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" class="form-control custom-input" wire:model="nombre">
                        @error('nombre') <span class="text-danger error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control custom-input" wire:model="email">
                        @error('email') <span class="text-danger error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" class="form-control custom-input" wire:model="telefono">
                        @error('telefono') <span class="text-danger error-text">{{ $message }}</span> @enderror
                    </div>


                    <div class="col-md-12" wire:ignore>
                        <label class="form-label fw-bold">Dirección</label>
                        <input 
                            type="text" 
                            id="direccion-input" 
                            class="form-control custom-input" 
                            wire:model="direccion"
                            placeholder="Busca tu dirección..."
                        >
                        <div class="form-text mt-1" style="font-size: 0.75rem;">Selecciona una opción de Google Maps.</div>
                    </div>
                    @error('direccion') <span class="text-danger error-text">{{ $message }}</span> @enderror

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Contraseña Actual</label>
                        <input type="password" class="form-control custom-input" wire:model="password_actual">
                        @error('password_actual') <span class="text-danger error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" class="form-control custom-input" wire:model="password_nueva">
                        @error('password_nueva') <span class="text-danger error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Confirmar Nueva</label>
                        <input type="password" class="form-control custom-input" wire:model="password_nueva_confirmation">
                    </div>

                </div>

                <div class="mt-4 pt-2 border-top text-end">
                    <button type="submit" class="btn px-4 shadow-sm btn-guardar">
                        <span wire:loading.remove>Guardar Cambios</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.initMap = function() {
            const input = document.getElementById("direccion-input");
            if (!input) return;

        //    input.value = @json($direccion);

            const component = Livewire.find('{{ $this->getId() }}');

            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: "sv" },
                fields: ["geometry", "formatted_address"],
                types: []
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') e.preventDefault();
            });

            autocomplete.addListener("place_changed", function() {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                component.set('latitud', place.geometry.location.lat());
                component.set('longitud', place.geometry.location.lng());
                component.set('direccion', place.formatted_address);
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw&libraries=places&callback=initMap" async defer></script>
</div>


<style>
   
    .custom-input {
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        padding: 8px 12px; 
        font-size: 0.9rem;
        border-radius: 6px;
        transition: all 0.2s ease;
        color: #495057;
    }


    .form-label {
        font-size: 0.85rem;
        color: #343a40;
        margin-bottom: 0.3rem;
    }

  
    .error-text {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 2px;
        display: block;
    }


    .custom-input:focus {
        background-color: #fff;
        border-color: #0056b3;
        box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
    }

    .btn-guardar {
        background-color: #003366;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 6px;
        padding-top: 8px;
        padding-bottom: 8px;
    }

    .btn-guardar:hover {
        background-color: #002244;
        color: white;
    }
</style>
</div>