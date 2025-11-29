<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('css/quevedo.css') }}" rel="stylesheet">
    <title>{{ $titulo }}</title>
</head>
<body>
    <div class="d-flex flex-col p-10 bg-info">
        <form action={{ route('clientes.update', $cliente->id) }} method="post" class="flex flex-col">
            @csrf
            @method('PUT')
            <label for="">Nombre</label>
            <input type="text" id="nombre" name="nombre" value={{ $cliente->nombre }}><br>
            <label for="">Direccion</label>
            <input type="text" id="direccion" name="direccion" value={{ $cliente->direccion }}><br>
            <label for="">Email</label>
            <input type="email" id="email" name="email" value={{ $cliente->email }}><br>
            <label for="">telefono</label>
            <input type="text" id="telefono" name="telefono" value={{ $cliente->telefono }}><br>
            <label for="">Latitud</label>
            <input type="number" step="0.00001" name="latitud" id="latitud" value={{ $cliente->latitud }}><br>
            <label for="">Longitud</label>
            <input type="number" step="0.00001" name="longitud" id="longitud" value={{ $cliente->longitud }}><br>
            <button type="submit" class="btn btn-success">
                Guardar cambios
            </button>
        </form>
        <a href={{ route('clientes.show', $cliente->id) }} class="btn btn-danger">Cancelar/Regresar</a>
    </div>
</body>
</html>