@extends('home.index')

@section('title', 'Dashboard - UES FMO')

@section('content')
    
    {{-- Cargamos el componente Livewire --}}
    @livewire('dashboard.dashboard-index')

@endsection