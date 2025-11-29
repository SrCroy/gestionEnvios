@extends('home.index')
@section('content')
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-person-plus"></i>
                EDITAR MOTORISTA
            </h3>
            <p class="mb-0">Actualice los datos del motorista.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('motoristas.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">

        <form action="{{ route('motoristas.update', encrypt($motorista->id)) }}" 
              method="POST" class="mb-3 form-control">
            @csrf
            @method('PUT')
              <input type="hidden" name="id" value="{{ encrypt($motorista->id) }}">
    

            <h3>Datos del Motorista</h3>

            <div class="row g-3">
                <div class="col mb-3">
                    <label class="form-label">Nombre del motorista:</label>
                    <input class="form-control" type="text" name="nombreMotorista"
                        value="{{ old('nombreMotorista', $motorista->name) }}">
                    @error('nombreMotorista')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col mb-3">
                    <label class="form-label">Email del motorista:</label>
                    <input class="form-control" type="email" name="emailMotorista"
                        value="{{ old('emailMotorista', $motorista->email) }}">
                    @error('emailMotorista')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col mb-3">
                    <label class="form-label">Dirección del motorista:</label>
                    <input class="form-control" type="text" name="direccionMotorista"
                        value="{{ old('direccionMotorista', $motorista->direccion) }}">
                    @error('direccionMotorista')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col mb-3">
                    <label class="form-label">Teléfono del motorista:</label>
                    <input class="form-control" type="text" name="telefonoMotorista"
                        value="{{ old('telefonoMotorista', $motorista->telefono) }}">
                    @error('telefonoMotorista')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col">
                <label for="passwordMotorista" class="form-label">Contraseña del motorista:</label>
                <input class="form-control" type="password" name="passwordMotorista">
                @error('passwordMotorista')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
              <div class="col">
                <label for="passwordMotorista_confirmation" class="form-label">Confirmar Contraseña:</label>
                <input class="form-control" type="password" name="passwordMotorista_confirmation">
              </div>
            </div>
            

            <div class="mb-3">
                <button class="btn btn-primary" type="submit">Actualizar Motorista</button>
            </div>

        </form>
    </div>
</div>
@endsection
