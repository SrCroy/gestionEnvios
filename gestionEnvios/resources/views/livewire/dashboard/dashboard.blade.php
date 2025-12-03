@extends('home.index')

@section('title', 'Dashboard - UES FMO')

@section('styles')
    <style>
        /* ESTO ES OBLIGATORIO: Si no defines la altura, el mapa no se ve */
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 3px solid #0056b3; /* Borde azul UES */
        }

        /* Estilo para las cajas de información flotantes del mapa */
        .route-info {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <div class="ues-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2">
                    <i class="bi bi-building me-2"></i>
                    UNIVERSIDAD DE EL SALVADOR
                </h3>
                <p class="mb-0">Facultad Multidisciplinaria Oriental - Carretera al Cuco Km 144</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="ues-badge me-2">
                    <i class="bi bi-geo-alt"></i> COORDENADAS EXACTAS
                </span>
                <span class="ues-badge">
                    <i class="bi bi-activity"></i> ACTIVO
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['total_paquetes'] ?? 1248 }}</h4>
                            <p>Paquetes Total</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-seam display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['entregados_hoy'] ?? 856 }}</h4>
                            <p>Entregados Hoy</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['en_transito'] ?? 289 }}</h4>
                            <p>En Tránsito</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['vehiculos_activos'] ?? 12 }}</h4>
                            <p>Vehículos Activos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-car-front display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="bi bi-map me-2" style="color: var(--ues-color);"></i>
                        Mapa de Distribución - UES FMO (Ubicación Exacta)
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="zoomToUES">
                            <i class="bi bi-building"></i>
                            Ver UES
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="showAllRoutes">
                            <i class="bi bi-signpost-split"></i>
                            Ver Rutas
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="map" wire:ignore></div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="route-info">
                                <h6 class="text-primary">
                                    <i class="bi bi-truck me-2"></i> 
                                    Rutas desde UES FMO
                                </h6>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span>
                                            <strong>🚚 Ruta a San Miguel Centro</strong><br>
                                            <small>UES FMO → Carretera → Centro Ciudad</small>
                                        </span>
                                        <span class="badge bg-success">18.5 km</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                        <span>
                                            <strong>🚚 Ruta a Ciudad Universitaria</strong><br>
                                            <small>UES FMO → Carretera → Campus Principal</small>
                                        </span>
                                        <span class="badge bg-warning">12.3 km</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between py-2 mt-2">
                                        <span>
                                            <strong>🚚 Ruta a Pueblos Cercanos</strong><br>
                                            <small>UES FMO → Carretera al Cuco → Pueblos</small>
                                        </span>
                                        <span class="badge bg-primary">8.7 km</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="route-info">
                                <h6 class="text-info">
                                    <i class="bi bi-info-circle me-2"></i> 
                                    UES FMO - Información Exacta
                                </h6>
                                <div class="mt-2">
                                    <div class="mb-2">
                                        <strong>🏫 Institución:</strong> 
                                        <span>Universidad de El Salvador</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>📍 Facultad:</strong> 
                                        <span>Multidisciplinaria Oriental</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>🗺️ Dirección Exacta:</strong> 
                                        <span>Carretera al Cuco Km 144, San Miguel</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>📍 Coordenadas:</strong> 
                                        <span>13.439920, -88.158602</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>📦 Capacidad:</strong> 
                                        <span>1,500 paquetes/día</span>
                                    </div>
                                    <div>
                                        <strong>🚚 Flota:</strong> 
                                        <span>10 vehículos de reparto</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Paquete #00125 entregado</h6>
                                <small class="text-muted">Hace 5 min</small>
                            </div>
                            <p class="mb-1 small">Cliente: Juan Pérez - San Miguel Centro</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Nuevo paquete registrado</h6>
                                <small class="text-muted">Hace 15 min</small>
                            </div>
                            <p class="mb-1 small">Cliente: María García - Ciudad Universitaria</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Vehículo #003 en ruta</h6>
                                <small class="text-muted">Hace 25 min</small>
                            </div>
                            <p class="mb-1 small">Ruta Carretera al Cuco - 28 paquetes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Estadísticas del Día</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <h4 class="text-primary">{{ $stats['entregados_hoy'] ?? 856 }}</h4>
                            <small class="text-muted">Entregados</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $stats['en_transito'] ?? 289 }}</h4>
                            <small class="text-muted">En camino</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info">103</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                    <div>
                        <small class="mb-1 d-block">Progreso de Entregas</small>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
                        </div>
                        
                        <small class="mb-1 d-block">Vehículos en Uso</small>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw" async defer></script>
    
    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        
        // COORDENADAS EXACTAS de la UES FMO
        const uesFMO = { lat: 13.439920, lng: -88.158602 };

        // Función global de inicio
        window.onload = function() {
            // Verificamos si el div del mapa existe antes de intentar cargar nada
            if(document.getElementById("map")) {
                initMap();
            }
        };

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: uesFMO,
                styles: [
                    { "featureType": "all", "elementType": "labels.text.fill", "stylers": [{"gamma": 0.01}, {"lightness": 20}] },
                    { "featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}] },
                    { "featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}] },
                    { "featureType": "water", "elementType": "all", "stylers": [{"color": "#3498db"}, {"visibility": "on"}] }
                ]
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                polylineOptions: { strokeColor: "#0056b3", strokeOpacity: 0.8, strokeWeight: 5 }
            });

            // Cargar marcadores
            addUESFMO();
            addDistributionRoutes();
        }

        function addUESFMO() {
            const uesIcon = {
                url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                scaledSize: new google.maps.Size(70, 70),
                anchor: new google.maps.Point(35, 35)
            };

            const uesMarker = new google.maps.Marker({
                position: uesFMO,
                map: map,
                title: "UES - FACULTAD MULTIDISCIPLINARIA ORIENTAL",
                icon: uesIcon,
                animation: google.maps.Animation.BOUNCE
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-3" style="min-width: 250px;">
                        <h6 class="text-primary mb-2">🏫 UES FMO</h6>
                        <p class="mb-1"><strong>📍 Dirección:</strong> Carretera al Cuco Km 144</p>
                        <p class="mb-1"><strong>📦 Capacidad:</strong> 1,500 paq/día</p>
                        <div class="mt-2">
                            <span class="badge bg-primary">CENTRO DISTRIBUCIÓN</span>
                        </div>
                    </div>
                `
            });

            uesMarker.addListener("click", () => {
                infoWindow.open(map, uesMarker);
            });

            // Círculo de área
            new google.maps.Circle({
                strokeColor: "#0056b3",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#0056b3",
                fillOpacity: 0.2,
                map: map,
                center: uesFMO,
                radius: 80
            });
        }

        function addDistributionRoutes() {
            // DATOS RESTAURADOS COMPLETOS
            const deliveryPoints = [
                {
                    name: "San Miguel Centro",
                    position: { lat: 13.4832, lng: -88.1832 },
                    paquetes: 45,
                    distancia: "18.5 km",
                    tipo: "Urbano"
                },
                {
                    name: "Ciudad Universitaria",
                    position: { lat: 13.7166, lng: -89.2022 },
                    paquetes: 32,
                    distancia: "12.3 km",
                    tipo: "Educativo"
                },
                {
                    name: "Pueblos Carretera al Cuco",
                    position: { lat: 13.4200, lng: -88.1400 },
                    paquetes: 28,
                    distancia: "8.7 km",
                    tipo: "Rural"
                },
                {
                    name: "Zona Industrial San Miguel",
                    position: { lat: 13.4800, lng: -88.1700 },
                    paquetes: 35,
                    distancia: "15.2 km",
                    tipo: "Industrial"
                },
                {
                    name: "Barrios Residenciales",
                    position: { lat: 13.4500, lng: -88.1650 },
                    paquetes: 22,
                    distancia: "4.3 km",
                    tipo: "Residencial"
                }
            ];

            deliveryPoints.forEach((point, index) => {
                // 1. Crear Marcador
                const marker = new google.maps.Marker({
                    position: point.position,
                    map: map,
                    title: point.name,
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                        scaledSize: new google.maps.Size(45, 45)
                    }
                });

                // 2. Crear Ventana de Información (InfoWindow) RESTAURADA
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="p-2">
                            <h6 class="text-success mb-2">📦 ${point.name}</h6>
                            <p class="mb-1"><strong>Paquetes:</strong> ${point.paquetes}</p>
                            <p class="mb-1"><strong>Distancia:</strong> ${point.distancia}</p>
                            <p class="mb-1"><strong>Tipo:</strong> ${point.tipo}</p>
                            <div class="mt-2">
                                <span class="badge bg-success">ENTREGA PROGRAMADA</span>
                            </div>
                        </div>
                    `
                });

                // 3. Evento Click para abrir la ventana
                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                // 4. Calcular Ruta visual
                setTimeout(() => {
                    calculateRoute(uesFMO, point.position, index);
                }, index * 600);
            });
        }

        function calculateRoute(start, end, routeIndex) {
            const routeColors = ["#0056b3", "#e74c3c", "#27ae60", "#f39c12", "#9b59b6"];
            
            directionsService.route(
                {
                    origin: start,
                    destination: end,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK") {
                        new google.maps.DirectionsRenderer({
                            map: map,
                            directions: response,
                            suppressMarkers: true,
                            polylineOptions: {
                                strokeColor: routeColors[routeIndex % routeColors.length],
                                strokeOpacity: 0.6,
                                strokeWeight: 4
                            }
                        });
                    }
                }
            );
        }

        // Listeners de botones externos
        const zoomBtn = document.getElementById('zoomToUES');
        if(zoomBtn) zoomBtn.addEventListener('click', () => { 
            map.setZoom(16); 
            map.setCenter(uesFMO); 
        });

        const routesBtn = document.getElementById('showAllRoutes');
        if(routesBtn) routesBtn.addEventListener('click', () => { 
            map.setZoom(12); 
            map.setCenter(uesFMO); 
        });
    </script>
@endsection