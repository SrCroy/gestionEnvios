@extends('home.index')
@section('title', 'UES - Gestión de Vehículos')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')

    @livewire('vehiculos.vehiculos-index')

@endsection