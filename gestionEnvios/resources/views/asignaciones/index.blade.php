@extends('home.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center">Asignaciones de Trabajo</h2>

    {{-- Cargar el componente Livewire del calendario --}}
    @livewire('calendario')

</div>

@endsection
