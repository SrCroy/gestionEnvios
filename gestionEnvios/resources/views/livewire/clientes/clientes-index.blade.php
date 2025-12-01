<div>
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }
    </style>

    <!-- UES Header -->
    <div class="ues-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2">
                    <i class="bi bi-people me-2"></i>
                    GESTIÓN DE CLIENTES
                </h3>
                <p class="mb-0">Administración de clientes</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-sm btn-warning btn-action">
                    <a href={{ route('clientes.store') }} class="link-dark link-underline-opacity-0 fs-6"><i class="bi bi-plus-circle"></i>Nuevo Cliente</a>
                </button>
            </div>
        </div>
    </div>

    <!--TABLA CLIENTES-->
    <div class="card-header bg-white">
        <h5 class="mb-0 text-dark">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Clientes
        </h5>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-">
                    <thead class="table-light">
                        <th>id</th>
                        <th>nombre</th>
                        <th>email</th>
                        <th>direccion</th>
                        <th>acciones</th>
                    </thead> 
                    <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button
                                        class="btn btn-sm btn-warning btn-action"
                                        title="Editar">
                                        <a href={{ route('clientes.edit', $cliente->id) }} class="link-dark"><i class="bi bi-pencil"></i></a>
                                    </button>
                                    <!--
                                    <button class="btn btn-sm btn-danger btn-action" title="Eliminar">
                                        <a href={{ route('clientes.delete', $cliente->id) }} class="link-light"><i class="bi bi-trash"></i></a>
                                    </button>
                                -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="mt-2 text-muted">No hay clientes registrados</p>
                                <button wire:click="create" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Registrar Primer Cliente
                                </button>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($clienteDelete)
                    <p>¿Está seguro de eliminar el Cliente <strong>{{ $clienteDelete->nombre }} {{ $clienteDelete->email }}</strong>?</p>

                    <br>
                    @endif
                    <span class="text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Esta acción no se puede deshacer.
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="delete" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="delete">Eliminar</span>
                        <span wire:loading wire:target="delete">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Eliminando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>