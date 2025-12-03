@extends('home.index')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary">
            <i class="fas fa-users me-2"></i>Gestión de Clientes
        </h4>
        <a href="{{ route('clientes.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-1"></i>Agregar Cliente
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Lista de Clientes
            </h5>
        </div>
        <div class="card-body">
            @if($clientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Ubicacion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->id }}</td>
                                    <td>{{ $cliente->nombre }}</td>
                                    <td>{{ $cliente->email }}</td>
                                    <td>{{ $cliente->direccion }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('clientes.show', $cliente->id) }}" 
                                            class="btn btn-sm btn-outline-info" title="Ver">
                                                <i class="fas fa-eye me-1"></i> Ver
                                            </a>
                                            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </a>
                                            <form action="{{ route('clientes.delete', $cliente->id) }}" method="POST" 
                                                onsubmit="return confirm('¿Eliminar cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash me-1"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No hay clientes registrados.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
