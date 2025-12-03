@extends('home.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center">Asignar Rutas a Motoristas</h2>

    {{-- Cargar el componente Livewire --}}
    @livewire('asignar-rutas')

</div>

@endsection