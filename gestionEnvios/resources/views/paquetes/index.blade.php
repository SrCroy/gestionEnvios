@extends('home.app')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-center">Paquetes tracking</h2>

    {{-- Cargar el componente Livewire --}}
    @livewire('paquetes.paquetes-tracking')

</div>

@endsection