<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('css/quevedo.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('clientes.create') }}" method="post">
        @csrf
        <label for="">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="w-full"><br>
        <label for="">Direccion</label>
        <input type="text" id="direccion" name="direccion" class="w-full"><br>
        <label for="">Email</label>
        <input type="email" id="email" name="email" class="w-full"><br>
        <label for="">telefono</label>
        <input type="text" id="telefono" name="telefono" class="w-full"><br>
        <label for="">Latitud</label>
        <input type="number" step="0.00001" name="latitud" id="latitud" class="w-full"><br>
        <label for="">Longitud</label>
        <input type="number" step="0.00001" name="longitud" id="longitud" class="w-full"><br>
        <button type="submit" class="btn btn-success">
            crear
        </button>
    </form>
    <a href={{ route('clientes.index') }} class="btn btn-danger">Regresar</a>
</body>
</html>