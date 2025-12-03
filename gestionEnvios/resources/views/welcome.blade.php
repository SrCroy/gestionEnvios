<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - UES FMO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --ues-blue: #0056b3;
            --ues-dark: #003366;
            --ues-gold: #ffc107;
        }

        body {
            /* Degradado en toda la pantalla */
            background: linear-gradient(135deg, var(--ues-blue) 0%, var(--ues-dark) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh; /* Altura completa fija */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centrado Vertical */
            align-items: center;     /* Centrado Horizontal */
            overflow: hidden;        /* Evita scroll innecesario */
        }

        /* Contenedor principal que agrupa todo */
        .main-wrapper {
            width: 100%;
            max-width: 900px;
            padding: 20px;
            text-align: center;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-weight: bold;
            color: var(--ues-blue);
            font-size: 1.5rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        }

        .portal-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .btn-portal {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .btn-cliente {
            background-color: var(--ues-blue);
            color: white;
            border: none;
        }
        .btn-cliente:hover {
            background-color: var(--ues-dark);
            color: white;
        }

        .btn-empleado {
            background-color: var(--ues-dark);
            color: white;
            border: none;
        }
        .btn-empleado:hover {
            background-color: var(--ues-blue);
            color: white;
        }

        .footer-text {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        
        <!-- Header Integrado -->
        <div class="mb-5">
            <div class="logo-circle">UES</div>
            <h1 class="fw-bold text-white mb-1">Sistema de Gestión de Envíos</h1>
            <p class="text-white-50 fs-5">Facultad Multidisciplinaria Oriental</p>
        </div>

        <!-- Tarjetas Centradas -->
        <div class="row justify-content-center g-4">
            
            <!-- OPCIÓN 1: CLIENTE -->
            <div class="col-md-5">
                <div class="portal-card p-4">
                    <h4 class="fw-bold mb-2 text-primary">Soy Cliente</h4>
                    <p class="text-muted small mb-0">
                        Rastrea paquetes y gestiona envíos.
                    </p>
                    <a href="{{ route('cliente.login') }}" class="btn btn-portal btn-cliente w-100 stretched-link">
                        Ingresar al Portal
                    </a>
                </div>
            </div>

            <!-- OPCIÓN 2: EMPLEADO -->
            <div class="col-md-5">
                <div class="portal-card p-4">
                    <h4 class="fw-bold mb-2 text-dark">Soy Colaborador</h4>
                    <p class="text-muted small mb-0">
                        Acceso para Admin y Motoristas.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-portal btn-empleado w-100 stretched-link">
                        Acceso Interno
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Fijo al fondo -->
    <div class="footer-text">
        &copy; {{ date('Y') }} Universidad de El Salvador - FMO
    </div>

</body>
</html>