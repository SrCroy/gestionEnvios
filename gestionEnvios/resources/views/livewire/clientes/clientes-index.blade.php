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
                    <i class="bi bi-truck me-2"></i>
                    GESTIÓN DE CLIENTES
                </h3>
                <p class="mb-0">Administración de clientes</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button wire:click="create" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuevo Cliente
                </button>
            </div>
        </div>
    </div>

    <!--<a href={{ route('clientes.create') }} class="btn btn-success">Agregar Cliente</a>-->
    <table class="fs-6 overflow-auto">
        <thead>
            <th>id</th>
            <th>nombre</th>
            <th>email</th>
            <th>latitud</th>
            <th>longitud</th>
            <th>acciones</th>
        </thead> 
        <tbody>
        @foreach ($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->latitud }}</td>
                <td>{{ $cliente->longitud }}</td>
                <td class="flex flex-row">
                    <a href={{ route('clientes.show', $cliente->id) }} class="btn btn-primary">show</a>
                    <a href={{ route('clientes.delete', $cliente->id) }} class="btn btn-danger">delite</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @push('scripts')
    <script>
        // Escuchar eventos de Livewire para abrir/cerrar modales
        document.addEventListener('livewire:init', () => {
            Livewire.on('openModal', (modalId) => {
                const modal = new bootstrap.Modal(document.getElementById(modalId[0]));
                modal.show();
            });

            Livewire.on('closeModal', (modalId) => {
                const modalElement = document.getElementById(modalId[0]);
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
    @endpush
</div>