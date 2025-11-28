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
    <div class="flex flex-col gap-2 p-2 bg-secondary container">
        <div class="row">
            <div class="col-2">
                <h1>id: </h1>
                <h1>nombre: </h1>
                <h1>email: </h1>
                <h1>telefono: </h1>
                <h1>direccion: </h1>
                <h1>latitud: </h1>
                <h1>longitud: </h1>
            </div>
            <div class="col-8">
                <h1 class="text-primary">{{ $cliente->id }}</h1>
                <h1 class="text-primary">{{ $cliente->nombre }}</h1>
                <h1 class="text-primary">{{ $cliente->email }}</h1>
                <h1 class="text-primary">{{ $cliente->telefono }}</h1>
                <h1 class="text-primary">{{ $cliente->direccion }}</h1>
                <h1 class="text-primary">{{ $cliente->latitud }}</h1>
                <h1 class="text-primary">{{ $cliente->longitud }}</h1>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-around p-2 bg-info">
        <a href={{ route('clientes.edit', $cliente->id) }} class="btn btn-success">edit</a>
        <a href="" class="btn btn-danger">delite</a>
    </div>
</body>
</html>