<div>
    {{-- The best athlete wants his opponent at his best. --}}
    {{-- resources/views/livewire/motoristas/motoristas.blade.php --}}
    <div>
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
                    <button wire:click="create" class="btn btn-warning">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Motorista
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
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
                                        <!-- Botón Editar -->
                                        <button wire:click="edit({{ $motorista->id }})"
                                            class="btn btn-sm btn-warning"
                                            title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <button wire:click="delete({{ $motorista->id }})"
                                            class="btn btn-sm btn-danger"
                                            title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de eliminar a {{ $motorista->name }}?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i>
                        Registrar Primer Motorista
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Modal para Crear/Editar -->
        @if($modalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus"></i>
                            {{ $motoristaId ? 'EDITAR MOTORISTA' : 'REGISTRAR NUEVO MOTORISTA' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="{{ $motoristaId ? 'update(' . $motoristaId . ')' : 'store' }}">
                        <div class="modal-body">
                            <h6 class="mb-3">Datos del Motorista</h6>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre del motorista:</label>
                                    <input class="form-control"
                                        type="text"
                                        wire:model="name"
                                        placeholder="Ingrese el nombre completo">
                                    @error('name')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email del motorista:</label>
                                    <input class="form-control"
                                        type="email"
                                        wire:model="email"
                                        placeholder="ejemplo@correo.com">
                                    @error('email')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Dirección del motorista:</label>
                                    <input class="form-control"
                                        type="text"
                                        wire:model="direccion"
                                        placeholder="Ingrese la dirección">
                                    @error('direccion')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono del motorista:</label>
                                    <input class="form-control"
                                        type="text"
                                        wire:model="telefono"
                                        placeholder="0000-0000">
                                    @error('telefono')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Contraseña del motorista:
                                        @if($motoristaId)
                                        <small class="text-muted">(dejar vacío para no cambiar)</small>
                                        @endif
                                    </label>
                                    <input class="form-control"
                                        type="password"
                                        wire:model="password"
                                        placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                    <p class="text-danger small mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmar Contraseña:</label>
                                    <input class="form-control"
                                        type="password"
                                        wire:model="password_confirmation"
                                        placeholder="Repita la contraseña">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                <i class="bi bi-x-circle"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                {{ $motoristaId ? 'Actualizar Motorista' : 'Registrar Motorista' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>