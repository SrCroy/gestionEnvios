<div>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

    <div wire:ignore id="calendar"></div>

    <div wire:ignore.self class="modal fade" id="modalAsignacion" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Gestión de Asignación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="text" class="form-control" wire:model="fecha" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motorista</label>
                        <select class="form-control" wire:model="idMotorista">
                            <option value="">-- Seleccionar --</option>
                            @foreach ($motoristas as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                        @error('idMotorista') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vehículo</label>
                        <select class="form-control" wire:model="idVehiculo">
                            <option value="">-- Seleccionar --</option>
                            @foreach ($vehiculos as $v)
                                <option value="{{ $v->id }}">{{ $v->placa }} - {{ $v->modelo }}</option>
                            @endforeach
                        </select>
                        @error('idVehiculo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    @if($idAsignacion)
                        <button class="btn btn-danger" wire:click="eliminar">Eliminar</button>
                    @endif
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" wire:click="guardar">Guardar</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        let calendar;
        let lwComponent;
        
        // 1. CAPTURAR EL ROL
        const userRol = "{{ Auth::user()->rol }}";
        const isAdmin = (userRol === 'admin'); // TRUE si es admin, FALSE si es motorista

        function obtenerComponente() {
            if (lwComponent) return lwComponent;
            const compEl = document.querySelector('[wire\\:id]');
            if (compEl && window.Livewire && typeof Livewire.find === 'function') {
                lwComponent = Livewire.find(compEl.getAttribute('wire:id'));
            }
            return lwComponent;
        }

        function mostrarModal() {
            const modalEl = document.getElementById('modalAsignacion');
            if (!modalEl) return;
            let instance = bootstrap.Modal.getInstance(modalEl);
            if (!instance) instance = new bootstrap.Modal(modalEl);
            instance.show();
        }

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            if (calendar) calendar.destroy();

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 650,
                
                // 2. CONFIGURACIÓN DE INTERACCIÓN
                // Si NO es admin, 'selectable' es false (no puede seleccionar días vacíos)
                selectable: isAdmin, 
                editable: false, // Nadie arrastra para evitar errores

                eventContent(arg) {
                    const m = arg.event.extendedProps?.motorista || 'Sin motorista';
                    const v = arg.event.extendedProps?.vehiculo || 'Sin vehículo';
                    
                    // Icono visual
                    let icon = '<i class="bi bi-clock"></i>';
                    if(arg.event.extendedProps?.estado === 'completo') icon = '<i class="bi bi-check-circle"></i>';
                    
                    return { html: `<div class="p-1 text-truncate" style="cursor: ${isAdmin ? 'pointer' : 'default'}">${icon} ${m} - ${v}</div>` };
                },

                // 3. CLIC EN DÍA VACÍO (CREAR)
                dateClick(info) {
                    if (!isAdmin) return; // SI ES MOTORISTA, SE DETIENE AQUÍ. NO HACE NADA.

                    const comp = obtenerComponente();
                    if (comp) {
                        Livewire.dispatch('abrirModal', { fecha: info.dateStr });
                    }
                    mostrarModal();
                },

                // 4. CLIC EN EVENTO EXISTENTE (EDITAR)
                eventClick(info) {
                    if (!isAdmin) return; // SI ES MOTORISTA, SE DETIENE AQUÍ. NO ABRE MODAL.

                    // Solo el admin pasa de aquí
                    Livewire.dispatch('editarAsignacion', { id: info.event.id });
                },

                events(info, successCallback, failureCallback) {
                    const url = "{{ route('asignaciones.events') }}?start=" +
                        encodeURIComponent(info.startStr) +
                        "&end=" + encodeURIComponent(info.endStr);

                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.ok ? res.json() : [])
                        .then(data => successCallback(Array.isArray(data) ? data : []))
                        .catch(err => successCallback([]));
                }
            });

            calendar.render();
        }

        document.addEventListener('DOMContentLoaded', initCalendar);
        document.addEventListener('livewire:navigated', initCalendar);

        document.addEventListener('cerrar-modal', () => {
            const modalEl = document.getElementById('modalAsignacion');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            if (calendar) calendar.refetchEvents();
        });

        document.addEventListener('abrir-modal-show', () => {
            mostrarModal();
        });
    </script>
</div>