@extends('home.index-clientes')
@section('title', 'UES - Mis Paquetes')

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

    @livewire('paquetes.paquetes-index')

@endsection