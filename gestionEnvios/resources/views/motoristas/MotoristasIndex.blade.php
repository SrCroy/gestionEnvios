@extends('home.index')
@section('content')
    @livewire('motoristas.motoristas')
    @section('scripts')
    <script src="{{ asset('vendor/livewire/livewire.js') }}" data-navigate-once></script>

    <script>
        window.livewireScriptConfig = {
            uri: '/gestionEnvios/gestionEnvios/public/livewire/update', // <--- LA RUTA CLAVE
            csrf: '{{ csrf_token() }}',
            updateUri: '/gestionEnvios/gestionEnvios/public/livewire/update',
            progressBar: '',
            nonce: ''
        };

        // Iniciar Livewire manualmente
        //Livewire.start();
    </script>
    @endsection
@endsection