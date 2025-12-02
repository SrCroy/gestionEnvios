<div>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>

    <div wire:ignore id="calendar"></div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalAsignacion" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Asignar Día de Trabajo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Fecha seleccionada</label>
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
                    <button class="btn btn-danger" wire:click="eliminar" wire:loading.attr="disabled">
                        Eliminar asignación
                    </button>

                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-success" wire:click="guardar" wire:loading.attr="disabled">
                        Guardar asignación
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPT DEL CALENDARIO -->
    <script>
        let calendar;
        let lwComponent;

        function obtenerComponente() {
            if (lwComponent) return lwComponent;
            const compEl = document.querySelector('[wire\\:id]');
            if (compEl && window.Livewire && typeof Livewire.find === 'function') {
                lwComponent = Livewire.find(compEl.getAttribute('wire:id'));
            } else {
                console.warn('Livewire component no encontrado');
            }
            return lwComponent;
        }

        function mostrarModal() {
            const modalEl = document.getElementById('modalAsignacion');
            if (!modalEl) {
                console.warn('Elemento modal no existe');
                return;
            }
            let instance = bootstrap.Modal.getInstance(modalEl);
            if (!instance) instance = new bootstrap.Modal(modalEl);
            instance.show();
        }

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            if (calendar) {
                calendar.destroy();
            }

            obtenerComponente();

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 650,
                selectable: true,

                // Mostrar siempre "motorista - vehículo" en el título
                eventContent(arg) {
                    const m = arg.event.extendedProps?.motorista || 'Sin motorista';
                    const v = arg.event.extendedProps?.vehiculo || 'Sin vehículo';
                    return { html: `<span>${m} - ${v}</span>` };
                },

                dateClick(info) {
                    console.log('dateClick', info.dateStr);
                    const comp = obtenerComponente();
                    if (comp && typeof comp.set === 'function') {
                        comp.set('fecha', info.dateStr);
                    } else {
                        Livewire.dispatch('abrirModal', { fecha: info.dateStr });
                    }
                    mostrarModal();
                },

                events(info, successCallback, failureCallback) {
                    const url = "{{ route('asignaciones.events') }}?start=" +
                        encodeURIComponent(info.startStr) +
                        "&end=" + encodeURIComponent(info.endStr);

                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP ' + res.status);
                            const ct = res.headers.get('content-type') || '';
                            if (!ct.includes('application/json')) throw new Error('No JSON');
                            return res.json();
                        })
                        .then(data => successCallback(Array.isArray(data) ? data : []))
                        .catch(err => {
                            console.error('Error eventos:', err);
                            successCallback([]);
                        });
                },

                eventClick(info) {
                    Livewire.dispatch('editarAsignacion', { id: info.event.id });
                }
            });

            calendar.render();
        }

        // Inicializar calendario al cargar
        document.addEventListener('DOMContentLoaded', initCalendar);
        document.addEventListener('livewire:navigated', initCalendar);

        // Cerrar modal y refrescar calendario
        document.addEventListener('cerrar-modal', () => {
            const modalEl = document.getElementById('modalAsignacion');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            if (calendar) calendar.refetchEvents();
        });

        // Abrir modal desde Livewire
        document.addEventListener('abrir-modal-show', () => {
            mostrarModal();
        });
    </script>
</div>
