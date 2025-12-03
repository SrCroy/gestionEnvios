<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
    
    <title>@yield('title', 'UES FMO - Gestor de Paquetes')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --ues-color: #0056b3;
        }

        /* Corrección para parpadeos de carga en Livewire */
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block],
        [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex],
        [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
            display: none;
        }

        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background: var(--ues-color);
            position: fixed;
            width: 250px;
            z-index: 1000;
            transition: transform 0.3s;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #FFD700;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Content Wrapper */
        .content {
            margin-left: 250px;
            background: #f8f9fa;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        /* Responsive Mobile */
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

        /* Utilidades UES */
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

        /* Estilos de Toastify Personalizado */
        .toastify {
            padding: 16px 20px;
            color: #ffffff;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="sidebar p-0" id="sidebar">
        <div class="p-3 text-center border-bottom border-light">
            <h4 class="text-white mb-1"><i class="bi bi-building"></i> UES FMO</h4>
            <small class="text-warning">Facultad Multidisciplinaria Oriental</small>
            <div class="ues-badge mt-2">SAN MIGUEL</div>
        </div>

        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('paquetes.*') ? 'active' : '' }}" href="#paquetes">
                <i class="bi bi-box-seam"></i>Gestión Paquetes
            </a>
            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                <i class="bi bi-people"></i>Clientes
            </a>
            <a class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}" href="{{ route('vehiculos.index') }}">
                <i class="bi bi-truck"></i>Vehículos
            </a>
            <a class="nav-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}" href="{{ route('asignaciones.index') }}">
                <i class="bi bi-calendar"></i>Asignaciones
            </a>
            <a class="nav-link {{ request()->routeIs('rutas.*') ? 'active' : '' }}" href="{{ route('rutas.index') }}">
                <i class="bi bi-map"></i>Rutas
            </a>
            <a class="nav-link {{ request()->routeIs('motoristas.*') ? 'active' : '' }}" href="{{ route('motoristas.index') }}">
                <i class="bi bi-person-badge"></i>Motoristas
            </a>
        </nav>

        <div class="p-3 mt-auto border-top border-light small text-white-50">
            <div class="mb-2"><i class="bi bi-database me-2"></i><span class="text-success">En línea</span></div>
            <div><i class="bi bi-building me-2"></i>UES FMO</div>
        </div>
    </div>

    <div class="content p-0">

        <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
            <div class="container-fluid">
                <button class="btn btn-outline-primary me-2 d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand"><i class="bi bi-building me-2"></i>Centro de Distribución</span>

                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name ?? 'Usuario' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-navigate-once></script>

    <script>
        // Configuración Livewire
        window.livewireScriptConfig = {
            uri: '/gestionEnvios/gestionEnvios/public/livewire/update',
            csrf: '{{ csrf_token() }}',
            updateUri: '/gestionEnvios/gestionEnvios/public/livewire/update',
            progressBar: '',
            nonce: ''
        };

        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('sidebarToggle');
            if(toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    document.getElementById('sidebar').classList.toggle('show');
                });
            }
        });

        // Toastify
        function showToast(message, type = 'success') {
            const colors = {
                success: "linear-gradient(to right, #00b09b, #96c93d)", 
                error: "linear-gradient(to right, #ff5f6d, #ffc371)",
                info: "linear-gradient(to right, #4facfe, #00f2fe)"
            };

            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top", 
                position: "right", 
                // --- AQUÍ ESTÁ EL CAMBIO ---
                // Antes: backgroundColor: colors[type]...
                // Ahora: usamos el objeto style
                style: {
                    background: colors[type] || colors['success'],
                },
                // ---------------------------
                stopOnFocus: true, 
            }).showToast();
        }

        // Livewire Listeners
        document.addEventListener('livewire:init', () => {
            // Toast Listener
            Livewire.on('toast', (data) => {
                let msg = Array.isArray(data) ? data[0].message : data.message;
                let type = Array.isArray(data) ? data[0].type : data.type;
                if(msg) showToast(msg, type);
            });

            // ABRIR MODAL
            Livewire.on('openModal', (id) => {
                const modalId = Array.isArray(id) ? id[0] : id;
                const el = document.getElementById(modalId);
                if(el) {
                    // getOrCreateInstance recupera el modal si ya existe, evitando duplicados
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });

            // CERRAR MODAL
            Livewire.on('closeModal', (id) => {
                const modalId = Array.isArray(id) ? id[0] : id;
                const el = document.getElementById(modalId);
                if(el) {
                    // --- SOLUCIÓN AL ERROR ARIA-HIDDEN ---
                    // Quitamos el foco del botón antes de ocultar el modal
                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }

                    const modal = bootstrap.Modal.getInstance(el);
                    if(modal) modal.hide();
                    
                    // Limpieza forzada del fondo oscuro (Backdrop)
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                    }, 200);
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>