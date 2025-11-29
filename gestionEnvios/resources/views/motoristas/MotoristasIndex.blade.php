@extends('home.index')
@section('content')
  <div class="ues-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="bi bi-person-plus"></i>
                            GESTIÓN DE MOTORISTAS
                        </h3>
                        <p class="mb-0">Administra todos los motoristas registrados en el sistema.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route("motoristas.create") }}" class="btn btn-warning">
                            <i class="bi bi-plus-circle"></i>
                            Nuevo Motorista
                        </a>
                    </div>
                </div>
            </div>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($motoristas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Fecha Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($motoristas as $motorista)
                                <tr>
                                    <td>
                                        <strong>{{ $motorista->name }}</strong>
                                    </td>
                                    <td>{{ $motorista->email }}</td>
                                    <td>{{ $motorista->telefono }}</td>
                                    <td>{{ Str::limit($motorista->direccion, 40) }}</td>
                                    <td>{{ $motorista->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Botón Ver -->
                                          <!--  <a href="{{ route('motoristas.show', $motorista->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>-->

                                            <!-- Botón Editar -->
                                            <a href="{{ route('motoristas.edit', encrypt($motorista->id)) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('motoristas.destroy', encrypt($motorista->id)) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar a {{ $motorista->nombre }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $motoristas->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-3 text-muted">No hay motoristas registrados</p>
                        <a href="{{ route('motoristas.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i>
                            Registrar Primer Motorista
                        </a>
                    </div>
                    @endif
                </div>
            </div>
@endsection