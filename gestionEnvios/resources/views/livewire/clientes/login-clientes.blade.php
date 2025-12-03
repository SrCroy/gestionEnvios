@extends('layouts.login')

@section('content')
<div class="d-flex align-items-center justify-content-center p-3" style="min-height: 100vh;">

    <div class="login-container" style="background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; width: 100%; max-width: 380px;">

        <div style="background: linear-gradient(135deg, var(--ues-blue) 0%, var(--ues-dark-blue) 100%); color: white; padding: 25px 20px; text-align: center;">
            <div style="width: 60px; height: 60px; background: var(--ues-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 1.5rem; color: var(--ues-blue); font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                UES
            </div>
            <h5 class="mb-0">UES FMO</h5>
            <small class="opacity-75" style="font-size: 0.8rem;">Portal de Clientes</small>
        </div>

        <div style="padding: 30px 25px;">

            {{-- Alertas --}}
            @if($errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3 small">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger py-2 px-3 mb-3 small text-center">{{ session('error') }}</div>
            @endif

            @if(session('success'))
            <div class="alert alert-success py-2 px-3 mb-3 small text-center">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('cliente.login.store') }}">
                @csrf

                <div class="mb-3">
                    <div class="input-group">
                        <span style="border: 1px solid #00000093;" class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input style="border: 1px solid #00000093;"
                            type="email"
                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                            name="email"
                            placeholder="Correo electrónico"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            style="padding: 10px;">
                    </div>

                </div>
               

                <div class="mb-4">
                    <div class="input-group">
                        <span style="border: 1px solid #00000093;" class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input style="border: 1px solid #00000093;"
                            type="password"
                            class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="Contraseña"
                            required
                            style="padding: 10px;">
                    </div>

                </div>
              
                <button type="submit" class="btn w-100 mb-3"
                    style="background: var(--ues-blue); border: none; border-radius: 8px; padding: 12px; color: white; font-weight: 600;">
                    Iniciar Sesión
                </button>

                <div class="position-relative text-center mt-4">
                    <hr class="position-absolute w-100 top-50 translate-middle-y m-0" style="z-index: 0; opacity: 0.1;">
                    <span class="position-relative bg-white px-2 text-muted small" style="z-index: 1;">o crea una cuenta</span>
                </div>

                <div class="mt-3">
                    <a href="{{ route('cliente.register') }}" class="btn w-100 btn-outline-primary"
                        style="border-radius: 8px; padding: 10px; font-size: 0.95rem;">
                        Registrarse
                    </a>
                </div>

            </form>
        </div>

        <div class="bg-light py-2 text-center border-top">
            <small class="text-muted" style="font-size: 0.75rem;">UES FMO &copy; {{ date('Y') }}</small>
        </div>

    </div>
</div>

<style>
    /* Estilos limpios */
    .form-control:focus {
        border-color: var(--ues-blue);
        box-shadow: none;
        background-color: #f8fbff;
    }

    .input-group-text {
        border-color: #ced4da;
    }

    .form-control {
        border-color: #ced4da;
    }

    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: var(--ues-blue);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');

        form.addEventListener('submit', function() {
            if (form.checkValidity()) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Entrando...';
                submitBtn.style.opacity = '0.8';
                submitBtn.style.pointerEvents = 'none';
            }
        });
    });
</script>
@endsection