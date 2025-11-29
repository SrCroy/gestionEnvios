@extends('home.index')

@section('title', 'Dashboard - UES FMO')

@section('styles')
    <!-- Google Maps API -->
    <link rel="stylesheet" href="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw">
@endsection

@section('content')
    <!-- UES Header -->
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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>1,248</h4>
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
                            <h4>856</h4>
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
                            <h4>289</h4>
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
                            <h4>12</h4>
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

    <!-- Mapa de Distribución -->
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
                    <div id="map"></div>
                    
                    <!-- Información de Distribución -->
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
                                            <strong>Ruta a San Miguel Centro</strong><br>
                                            <small>UES FMO → Carretera → Centro Ciudad</small>
                                        </span>
                                        <span class="badge bg-success">18.5 km</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between border-bottom py-2 mt-2">
                                        <span>
                                            <strong>Ruta a Ciudad Universitaria</strong><br>
                                            <small>UES FMO → Carretera → Campus Principal</small>
                                        </span>
                                        <span class="badge bg-warning">12.3 km</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between py-2 mt-2">
                                        <span>
                                            <strong>Ruta a Pueblos Cercanos</strong><br>
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
                                        <strong>Institución:</strong> 
                                        <span>Universidad de El Salvador</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Facultad:</strong> 
                                        <span>Multidisciplinaria Oriental</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Dirección Exacta:</strong> 
                                        <span>Carretera al Cuco Km 144, San Miguel</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Coordenadas:</strong> 
                                        <span>13.439920, -88.158602</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Capacidad:</strong> 
                                        <span>1,500 paquetes/día</span>
                                    </div>
                                    <div>
                                        <strong>Flota:</strong> 
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

    <!-- Resumen Rápido -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Paquete #00125 entregado</h6>
                                <small>Hace 5 min</small>
                            </div>
                            <p class="mb-1">Cliente: Juan Pérez - San Miguel Centro</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Nuevo paquete registrado</h6>
                                <small>Hace 15 min</small>
                            </div>
                            <p class="mb-1">Cliente: María García - Ciudad Universitaria</p>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Vehículo #003 en ruta</h6>
                                <small>Hace 25 min</small>
                            </div>
                            <p class="mb-1">Ruta Carretera al Cuco - 28 paquetes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Estadísticas del Día</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-primary">856</h4>
                            <small>Entregados</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">289</h4>
                            <small>En camino</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info">103</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" style="width: 70%">70% Entregado</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 23%">23% En tránsito</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7_z1Byfn5Yoj280LSGJZl8QTeRnPCvbw&callback=initMap" async defer></script>
    
    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        
        // COORDENADAS EXACTAS de la UES FMO
        const uesFMO = { lat: 13.439920, lng: -88.158602 };

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: uesFMO,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [{"gamma": 0.01}, {"lightness": 20}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{"saturation": -100}, {"lightness": 45}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#3498db"}, {"visibility": "on"}]
                    }
                ]
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: "#0056b3",
                    strokeOpacity: 0.8,
                    strokeWeight: 5
                }
            });

            // Agregar UES FMO en la ubicación EXACTA
            addUESFMO();
            // Agregar rutas de distribución reales
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
                title: "UES - FACULTAD MULTIDISCIPLINARIA ORIENTAL (UBICACIÓN EXACTA)",
                icon: uesIcon,
                animation: google.maps.Animation.BOUNCE
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-3" style="min-width: 320px;">
                        <h5 class="text-primary">🏫 UES FMO - UBICACIÓN EXACTA</h5>
                        <p class="mb-1"><strong>📍 Facultad:</strong> Multidisciplinaria Oriental</p>
                        <p class="mb-1"><strong>🗺️ Dirección:</strong> Carretera al Cuco Km 144</p>
                        <p class="mb-1"><strong>📍 Coordenadas:</strong> 13.439920, -88.158602</p>
                        <p class="mb-1"><strong>🏙️ Ciudad:</strong> San Miguel, CP 3301</p>
                        <p class="mb-1"><strong>📦 Capacidad:</strong> 1,500 paquetes/día</p>
                        <p class="mb-1"><strong>🕒 Horario:</strong> 6:00 AM - 8:00 PM</p>
                        <p class="mb-0"><strong>🚚 Flota:</strong> 10 vehículos</p>
                        <div class="mt-2">
                            <span class="badge bg-primary">UBICACIÓN EXACTA</span>
                            <span class="badge bg-success">CARREtera AL CUCO</span>
                        </div>
                    </div>
                `
            });

            uesMarker.addListener("click", () => {
                infoWindow.open(map, uesMarker);
            });

            // Círculo para destacar la ubicación EXACTA
            new google.maps.Circle({
                strokeColor: "#0056b3",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#0056b3",
                fillOpacity: 0.2,
                map: map,
                center: uesFMO,
                radius: 80 // 80 metros
            });
        }

        function addDistributionRoutes() {
            // Puntos de entrega REALES desde UES FMO (Carretera al Cuco)
            const deliveryPoints = [
                {
                    name: "San Miguel Centro",
                    position: { lat: 13.4832, lng: -88.1832 },
                    paquetes: 45,
                    distancia: "18.5 km",
                    tipo: "urbano"
                },
                {
                    name: "Ciudad Universitaria",
                    position: { lat: 13.7166, lng: -89.2022 },
                    paquetes: 32,
                    distancia: "12.3 km", 
                    tipo: "educativo"
                },
                {
                    name: "Pueblos Carretera al Cuco",
                    position: { lat: 13.4200, lng: -88.1400 },
                    paquetes: 28,
                    distancia: "8.7 km",
                    tipo: "rural"
                },
                {
                    name: "Zona Industrial San Miguel",
                    position: { lat: 13.4800, lng: -88.1700 },
                    paquetes: 35,
                    distancia: "15.2 km",
                    tipo: "industrial"
                },
                {
                    name: "Barrios Residenciales",
                    position: { lat: 13.4500, lng: -88.1650 },
                    paquetes: 22,
                    distancia: "4.3 km",
                    tipo: "residencial"
                }
            ];

            // Crear rutas reales por calles
            deliveryPoints.forEach((point, index) => {
                const marker = new google.maps.Marker({
                    position: point.position,
                    map: map,
                    title: point.name,
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                        scaledSize: new google.maps.Size(45, 45)
                    }
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="p-2">
                            <h6 class="text-success">📦 ${point.name}</h6>
                            <p class="mb-1"><strong>Paquetes:</strong> ${point.paquetes}</p>
                            <p class="mb-1"><strong>Distancia desde UES:</strong> ${point.distancia}</p>
                            <p class="mb-1"><strong>Tipo:</strong> ${point.tipo}</p>
                            <p class="mb-0"><strong>Estado:</strong> Entrega programada</p>
                            <div class="mt-1">
                                <span class="badge bg-success">PUNTO ENTREGA</span>
                            </div>
                        </div>
                    `
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                // Crear ruta por calles reales
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
                        const routeRenderer = new google.maps.DirectionsRenderer({
                            map: map,
                            directions: response,
                            suppressMarkers: true,
                            polylineOptions: {
                                strokeColor: routeColors[routeIndex % routeColors.length],
                                strokeOpacity: 0.7,
                                strokeWeight: 4
                            }
                        });
                    }
                }
            );
        }

        // Botones del mapa
        document.getElementById('zoomToUES').addEventListener('click', function() {
            map.setZoom(16);
            map.setCenter(uesFMO);
        });

        document.getElementById('showAllRoutes').addEventListener('click', function() {
            map.setZoom(13);
            map.setCenter(uesFMO);
        });
    </script>
@endsection
