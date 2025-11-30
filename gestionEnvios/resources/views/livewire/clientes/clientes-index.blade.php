<div>
    <a href={{ route('clientes.create') }} class="btn btn-success">Agregar Cliente</a>
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
</div>