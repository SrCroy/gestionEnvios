@extends('home.index')
@section('content')
<div class="ues-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="mb-2">
                <i class="bi bi-person-plus"></i>
                REGISTRAR NUEVO MOTORISTA
            </h3>
            <p class="mb-0">Complete el formulario para agregar un nuevo motorista al sistema.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="../motoristas" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Volver al Listado
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="{{ route("motoristas.store") }}" method="post" class="mb-3 form-control">
            @csrf
            <h3>Datos del Motorista</h3>
            <div class="row g-3">
                <div class="col mb-3">
                    <label class="form-label" for="nombreMotorista">Nombre del motorista:</label>
                    <input class="form-control" type="text" name="nombreMotorista">
                    @error('nombreMotorista')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label class="form-label" for="emailMotorista">Email del motorista:</label>
                    <input class="form-control" type="email" name="emailMotorista">
                    @error('emailMotorista')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="row g-3">
                <div class="col mb-3">
                    <label class="form-label" for="direccionMotorista">Dirección del motorista:</label>
                    <input class="form-control" type="text" name="direccionMotorista">
                    @error('direccionMotorista')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col mb-3">
                    <label class="form-label" for="telefonoMotorista">Telefono del motorista:</label>
                    <input class="form-control" type="text" name="telefonoMotorista">
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
                <button class="btn btn-primary" type="submit">Registrar Motorista</button>
            </div>

        </form>
    </div>
</div>
@endsection