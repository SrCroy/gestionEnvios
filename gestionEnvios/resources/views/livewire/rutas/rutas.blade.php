@extends('home.index')

@section('title', 'Dashboard - Calendario de Rutas')

@section('styles')
    <!-- FullCalendar CSS (para el calendario) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-calendar-event me-2" style="color: var(--ues-color);"></i>
                        Calendario de Rutas
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="btnToday">
                            Hoy
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="btnMonth">
                            Mes
                        </button>
                        <button class="btn btn-sm btn-outline-info" id="btnWeek">
                            Semana
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Contenedor del calendario -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de detalles (información de paquetes y motoristas) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detalle del día seleccionado</h6>
                </div>
                <div class="card-body">
                    <div id="dayDetails">
                        <p class="text-muted">Selecciona una fecha en el calendario para ver los paquetes, motoristas y rutas programadas.</p>
                        <!-- Aquí Livewire podrá cargar los detalles dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa de puntos de entrega -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-map me-2"></i>Mapa de puntos de entrega</h6>
                </div>
                <div class="card-body">
                    <div id="mapCalendar" style="height: 500px;"></div>
                    <p class="text-muted mt-2">Los puntos se actualizarán según la fecha seleccionada en el calendario.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [], // Aquí luego Livewire o JS llenará las rutas/paquetes
                dateClick: function(info) {
                    // Aquí puedes llamar a Livewire para cargar detalles de la fecha seleccionada
                    console.log('Fecha seleccionada: ' + info.dateStr);
                }
            });
            calendar.render();

            // Botones de control externo
            document.getElementById('btnToday').addEventListener('click', () => calendar.today());
            document.getElementById('btnMonth').addEventListener('click', () => calendar.changeView('dayGridMonth'));
            document.getElementById('btnWeek').addEventListener('click', () => calendar.changeView('timeGridWeek'));
        });
    </script>

    <!-- Mapa placeholder (Google Maps o Leaflet luego) -->
    <script>
        function initMapCalendar() {
            var map = new google.maps.Map(document.getElementById("mapCalendar"), {
                zoom: 12,
                center: { lat: 13.439920, lng: -88.158602 },
            });

            // Aquí luego Livewire podrá agregar markers dinámicamente según la fecha seleccionada
        }

        window.addEventListener('load', initMapCalendar);
    </script>
@endsection
