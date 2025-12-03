@extends('home.index-clientes')

@section('content')
<div class="d-flex align-items-center justify-content-center p-4" style="min-height: 100vh;">

    <div class="container" style="max-width: 800px;">

        @livewire('clientes.editar-perfil')

    </div>

</div>
@endsection