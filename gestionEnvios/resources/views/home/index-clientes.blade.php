<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
    
    <title>@yield('title', 'Portal Cliente - UES FMO')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --ues-color: #0056b3; /* Azul UES */
            --ues-secondary: #e67e22; /* Naranja complemento */
        }

        /* Fixes Livewire */
        [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block],
        [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex],
        [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
            display: none;
        }

        /* Estilos Generales */
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Sidebar Cliente - Diferenciado visualmente */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--ues-color) 0%, #004494 100%);
            position: fixed;
            width: 250px;
            z-index: 1000;
            transition: transform 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left: 4px solid #FFD700;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-left: 4px solid #FFD700;
        }

        .sidebar .nav-link i { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1rem; }

        /* Content Wrapper */
        .content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Superior */
        .client-navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0.8rem 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 100%; transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .content { margin-left: 0; }
        }

        /* Badges y Utilidades */
        .ues-badge {
            background: #FFD700;
            color: #003d82;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .ues-header {
            background: linear-gradient(135deg, var(--ues-color) 0%, #003d82 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Cards personalizadas para el cliente */
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        /* Toastify Custom */
        .toastify {
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- SIDEBAR CLIENTE -->
    <div class="sidebar p-0" id="sidebar">
        <div class="p-4 text-center border-bottom border-light border-opacity-10">
            <h4 class="text-white mb-1 fw-bold"><i class="bi bi-box-seam-fill"></i> UES POST</h4>
            <small class="text-white-50">Portal de Clientes</small>
            <div class="mt-3">
                <span class="ues-badge">FMO - SAN MIGUEL</span>
            </div>
        </div>

        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->routeIs('clientes.dashboard') ? 'active' : '' }}" href="{{ route('clientes.dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Resumen
            </a>
            
            <a class="nav-link {{ request()->routeIs('paquetes.PaquetesIndex') ? 'active' : '' }}" href="{{ route('paquetes.index') }}">
                <i class="bi bi-box2-heart-fill"></i> Mis Paquetes
            </a>

            <!-- <a class="nav-link" href="#">
                <i class="bi bi-geo-alt-fill"></i> Rastreo Rápido
            </a>

            <a class="nav-link" href="#">
                <i class="bi bi-clock-history"></i> Historial
            </a> -->

            <div class="mt-4 px-3 text-uppercase text-white-50" style="font-size: 0.75rem; font-weight: bold;">Cuenta</div>
            
            <a class="nav-link" href="{{ route('cliente.perfil' )}}">
                <i class="bi bi-person-circle"></i> Editar Perfil
            </a>
            
          
        </nav>

       
    </div>

    <!-- CONTENIDO -->
    <div class="content">
        <!-- Navbar Superior Cliente -->
        <nav class="navbar navbar-expand-lg client-navbar">
            <div class="container-fluid">
                <button class="btn btn-link text-dark p-0 me-3 d-md-none" id="sidebarToggle">
                    <i class="bi bi-list fs-3"></i>
                </button>
                
                <span class="navbar-brand fw-bold d-none d-md-block text-secondary">
                    <i class="bi bi-calendar-day me-1"></i> {{ now()->format('d/m/Y') }}
                </span>

                <div class="navbar-nav ms-auto align-items-center">
                    <!-- Perfil Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                                <!-- Inicial del nombre o 'C' por defecto -->
                                {{ substr(Auth::guard('cliente')->user()->nombre ?? 'C', 0, 1) }}
                            </div>
                            <span class="d-none d-sm-block fw-medium text-dark">
                                {{ Auth::guard('cliente')->user()->nombre ?? 'Cliente Invitado' }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                            <li><a class="dropdown-item" href="{{ route('cliente.perfil') }}"><i class="bi bi-person me-2"></i>Editar Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('cliente.logout') }}" method="POST">
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

        <!-- Área Principal -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>
        
        <footer class="mt-auto py-3 bg-white text-center text-muted small border-top">
            Universidad de El Salvador - Facultad Multidisciplinaria Oriental &copy; {{ date('Y') }}
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-navigate-once></script>

    <script>
        // 1. Configuración Livewire
        window.livewireScriptConfig = {
            uri: '/gestionEnvios/gestionEnvios/public/livewire/update',
            csrf: '{{ csrf_token() }}',
            updateUri: '/gestionEnvios/gestionEnvios/public/livewire/update',
            progressBar: '',
            nonce: ''
        };

        // 2. Función Helper para Toasts (Global)
        // La definimos fuera para poder usarla en cualquier parte si hace falta
        function showToast(message, type = 'success') {
            const colors = {
                success: "linear-gradient(to right, #00b09b, #96c93d)",
                error: "linear-gradient(to right, #ff5f6d, #ffc371)",
                info: "linear-gradient(to right, #4facfe, #00f2fe)"
            };

            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: { background: colors[type] || colors['success'] },
                stopOnFocus: true,
            }).showToast();
        }

        // 3. Lógica del DOM (Sidebar, etc)
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar Toggle
            const toggle = document.getElementById('sidebarToggle');
            if(toggle) {
                toggle.addEventListener('click', () => {
                    document.getElementById('sidebar').classList.toggle('show');
                });
            }
        });

        // 4. Listeners de Livewire
        document.addEventListener('livewire:init', () => {
            
            // ABRIR MODAL
            Livewire.on('openModal', (data) => {
                let modalId = data; 
                
                if (typeof data === 'object' && data !== null && 'modalId' in data) {
                    modalId = data.modalId;
                } else if (Array.isArray(data)) {
                    modalId = data[0];
                }

                const el = document.getElementById(modalId);
                if(el) {
                    const modal = new bootstrap.Modal(el);
                    modal.show();
                } else {
                    console.error('No se encontró el modal con ID:', modalId);
                }
            });

            // CERRAR MODAL
            Livewire.on('closeModal', (data) => {
                let modalId = data;
                if (typeof data === 'object' && data !== null && 'modalId' in data) { modalId = data.modalId; }
                else if (Array.isArray(data)) { modalId = data[0]; }

                const el = document.getElementById(modalId);
                if(el) {
                    const modal = bootstrap.Modal.getInstance(el);
                    if(modal) modal.hide();
                    
                    // Limpieza de fondos oscuros
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                }
            });

            // TOASTS (Usando la función helper de arriba)
            Livewire.on('toast', (data) => {
                let msg = data.message || (Array.isArray(data) ? data[0].message : 'Acción completada');
                let type = data.type || (Array.isArray(data) ? data[0].type : 'success');
                
                showToast(msg, type);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>