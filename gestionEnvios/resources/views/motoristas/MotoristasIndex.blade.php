<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UES FMO - Gestor de Paquetes</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --ues-color: #0056b3;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--ues-color);
            position: fixed;
            width: 250px;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #FFD700;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #FFD700;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content {
            margin-left: 250px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }
        }

        .navbar-brand {
            font-weight: bold;
        }

        .stat-card {
            border-radius: 10px;
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        #map {
            height: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 3px solid var(--ues-color);
        }

        .route-info {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ues-badge {
            background: #FFD700;
            color: #000;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .ues-header {
            background: linear-gradient(135deg, var(--ues-color) 0%, #003d82 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar Estático -->
    <div class="sidebar p-0">
        <div class="p-3 text-center border-bottom border-light">
            <h4 class="text-white mb-1">
                <i class="bi bi-building"></i>
                UES FMO
            </h4>
            <small class="text-warning">Facultad Multidisciplinaria Oriental</small>
            <div class="ues-badge mt-2">SAN MIGUEL</div>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link active" href="#dashboard">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
            <a class="nav-link" href="#paquetes">
                <i class="bi bi-box-seam"></i>Gestión Paquetes
            </a>
            <a class="nav-link" href="#clientes">
                <i class="bi bi-people"></i>Clientes
            </a>
            <a class="nav-link" href="#vehiculos">
                <i class="bi bi-truck"></i>Vehículos
            </a>
            <a class="nav-link" href="#rutas">
                <i class="bi bi-map"></i>Rutas
            </a>
            <a class="nav-link" href="motoristas">
                <i class="bi bi-person-badge"></i>Motoristas
            </a>
            <a class="nav-link" href="#reportes">
                <i class="bi bi-graph-up"></i>Reportes
            </a>
        </nav>

        <!-- System Status -->
        <div class="p-3 mt-auto border-top border-light">
            <div class="text-white-50 small">
                <div class="mb-2">
                    <i class="bi bi-database me-2"></i>
                    <span class="text-success">En línea</span>
                </div>
                <div class="mb-2">
                    <i class="bi bi-geo-alt me-2"></i>
                    <span>Carretera al Cuco Km 144</span>
                </div>
                <div>
                    <i class="bi bi-building me-2"></i>
                    UES FMO
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content p-0">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="btn btn-outline-primary me-2 d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand">
                    <i class="bi bi-building me-2"></i>
                    UES FMO - Centro de Distribución
                </span>

                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            Administrador
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            <div class="ues-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="bi bi-person-plus"></i>
                            GESTIÓN DE MOTORISTAS
                        </h3>
                        <p class="mb-0">Administra todos los motoristas registrados en el sistema.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route("motoristas.create") }}" class="btn btn-warning">
                            <i class="bi bi-plus-circle"></i>
                            Nuevo Motorista
                        </a>
                    </div>
                </div>
            </div>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($motoristas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($motoristas as $motorista)
                                <tr>
                                    <td>
                                        <strong>{{ $motorista->name }}</strong>
                                    </td>
                                    <td>{{ $motorista->email }}</td>
                                    <td>{{ $motorista->telefono }}</td>
                                    <td>{{ Str::limit($motorista->direccion, 40) }}</td>
                                    <td>{{ $motorista->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Botón Ver -->
                                          <!--  <a href="{{ route('motoristas.show', $motorista->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>-->

                                            <!-- Botón Editar -->
                                            <a href="{{ route('motoristas.edit', $motorista->id) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('motoristas.destroy', $motorista->id) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar a {{ $motorista->nombre }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $motoristas->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-3 text-muted">No hay motoristas registrados</p>
                        <a href="{{ route('motoristas.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i>
                            Registrar Primer Motorista
                        </a>
                    </div>
                    @endif
                </div>
            </div>


            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>