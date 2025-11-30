<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
    <title>@yield('title', 'UES FMO - Gestor de Paquetes')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Livewire Styles - Manual -->
    <style>
        [wire\:loading],
        [wire\:loading\.delay],
        [wire\:loading\.inline-block],
        [wire\:loading\.inline],
        [wire\:loading\.block],
        [wire\:loading\.flex],
        [wire\:loading\.table],
        [wire\:loading\.grid],
        [wire\:loading\.inline-flex] {
            display: none;
        }

        [wire\:loading\.delay\.shortest],
        [wire\:loading\.delay\.shorter],
        [wire\:loading\.delay\.short],
        [wire\:loading\.delay\.long],
        [wire\:loading\.delay\.longer],
        [wire\:loading\.delay\.longest] {
            display: none;
        }

        [wire\:offline] {
            display: none;
        }

        [wire\:dirty]:not(textarea):not(input):not(select) {
            display: none;
        }

        input:-webkit-autofill,
        select:-webkit-autofill,
        textarea:-webkit-autofill {
            animation-duration: 50000s;
            animation-name: livewireautofill;
        }

        @keyframes livewireautofill {
            from {}
        }
    </style>

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
                transition: transform 0.3s;
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

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    @push('styles')
    <style>
        /* Estilos personalizados para Toastify */
        .toastify {
            padding: 16px 20px;
            color: #ffffff;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .toastify.on {
            opacity: 1;
        }
    </style>
    @endpush
    @stack('styles')
</head>

<body>
    <!-- Sidebar Estático -->
    <div class="sidebar p-0" id="sidebar">
        <div class="p-3 text-center border-bottom border-light">
            <h4 class="text-white mb-1">
                <i class="bi bi-building"></i>
                UES FMO
            </h4>
            <small class="text-warning">Facultad Multidisciplinaria Oriental</small>
            <div class="ues-badge mt-2">SAN MIGUEL</div>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/../gestionEnvios/gestionEnvios/public/">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-box-seam"></i>Gestión Paquetes
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-people"></i>Clientes
            </a>
            <a class="nav-link {{ request()->is('vehiculos*') ? 'active' : '' }}" href="{{ route('vehiculos.index') }}">
                <i class="bi bi-truck"></i>Vehículos
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-map"></i>Rutas
            </a>
            <a class="nav-link {{ request()->is('motoristas*') ? 'active' : '' }}" href="{{ route('motoristas.index') }}">
                <i class="bi bi-person-badge"></i>Motoristas
            </a>
            <a class="nav-link" href="#">
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
                            {{ Auth::user()->name ?? 'Administrador' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Livewire Scripts con ruta absoluta -->
    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-turbo-eval="false" data-turbolinks-eval="false" data-update-uri="{{ url('/livewire/update') }}" data-csrf="{{ csrf_token() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.Livewire !== 'undefined') {
                console.log('Livewire cargado correctamente');
                console.log('Base URL:', '{{ url("/") }}');
                console.log('Update URI:', '{{ url("/livewire/update") }}');
            } else {
                console.error('ERROR: Livewire no se cargó');
            }
        });
    </script>

    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (event) => {
                // Extraer los datos correctamente
                const data = event[0] || event;
                const message = data.message || 'Notificación';
                const type = data.type || 'info';

                let backgroundColor;

                // Colores según el tipo
                switch (type) {
                    case 'success':
                        backgroundColor = "linear-gradient(to right, #00b09b, #96c93d)";
                        break;
                    case 'error':
                        backgroundColor = "linear-gradient(to right, #ff5f6d, #ffc371)";
                        break;
                    case 'info':
                        backgroundColor = "linear-gradient(to right, #2193b0, #6dd5ed)";
                        break;
                    case 'warning':
                        backgroundColor = "linear-gradient(to right, #f2994a, #f2c94c)";
                        break;
                    default:
                        backgroundColor = "linear-gradient(to right, #00b09b, #96c93d)";
                }

                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: backgroundColor,
                        borderRadius: "8px",
                        padding: "16px 20px",
                        fontSize: "14px",
                        fontWeight: "500",
                        boxShadow: "0 4px 12px rgba(0, 0, 0, 0.15)"
                    }
                }).showToast();
            });
        });
    </script>

    @stack('scripts')
</body>

</html>