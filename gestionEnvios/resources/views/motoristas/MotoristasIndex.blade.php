@extends('home.index')
@section('title', 'UES - Gestión de Motoristas')


@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endsection
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
    function showToast(message, type = 'success') {
        const colors = {
            success: 'linear-gradient(to right, #00b09b, #96c93d)',
            error: 'linear-gradient(to right, #ff5f6d, #ffc371)',
            warning: 'linear-gradient(to right, #f7b733, #fc4a1a)',
            info: 'linear-gradient(to right, #4facfe, #00f2fe)'
        };

        Toastify({
            text: message,
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: colors[type],
            stopOnFocus: true,
            className: "toastify-custom",
        }).showToast();
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (event) => {
            showToast(event.message, event.type);
        });
    });
</script>
    @endsection
@endsection