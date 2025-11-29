<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('css/quevedo.css') }}" rel="stylesheet">
    <title>{{ $titulo }}</title>
</head>
<body class="bg-gray">
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
                    <a href="" class="btn btn-danger">delite</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>