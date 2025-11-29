@extends('layouts.login')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-container" style="background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); overflow: hidden; width: 100%; max-width: 400px;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, var(--ues-blue) 0%, var(--ues-dark-blue) 100%); color: white; padding: 30px 20px; text-align: center;">
            <div style="width: 80px; height: 80px; background: var(--ues-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 2rem; color: var(--ues-blue); font-weight: bold; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);">
                UES
            </div>
            <h4 class="mb-1">UES FMO</h4>
            <p class="mb-0 opacity-75">Facultad Multidisciplinaria Oriental</p>
            <small class="text-warning">Sistema de Gestión de Paquetes</small>
        </div>

        <!-- Login Form -->
        <div style="padding: 30px;">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Error de validación:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; border-radius: 10px 0 0 10px;">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            style="border: 2px solid #e9ecef; border-radius: 0 10px 10px 0; padding: 12px 15px; transition: all 0.3s;"
                        >
                    </div>
                    @error('email')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; border-radius: 10px 0 0 10px;">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password"
                            placeholder="Contraseña"
                            required
                            style="border: 2px solid #e9ecef; border-radius: 0 10px 10px 0; padding: 12px 15px; transition: all 0.3s;"
                        >
                    </div>
                    @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">
                        Recordar sesión
                    </label>
                </div>

                <button type="submit" class="btn w-100 mb-3" style="background: linear-gradient(135deg, var(--ues-blue) 0%, var(--ues-dark-blue) 100%); border: none; border-radius: 10px; padding: 12px; color: white; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 86, 179, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>

                <div class="text-center">
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-key me-1"></i>¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; background: #f8f9fa; border-top: 1px solid #e9ecef;">
            <small class="text-muted">
                <i class="bi bi-shield-check me-1"></i>Sistema Seguro - UES FMO 
                <br>
                <span class="text-primary">v2.0.1</span>
            </small>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--ues-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Iniciando sesión...';
            submitBtn.disabled = true;
        });
    });
</script>
@endsection
