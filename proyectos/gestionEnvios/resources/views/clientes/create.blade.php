<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action={{ route('clientes.store', $cliente->id) }} method="post" class="flex flex-col">
        @csrf
        @method('PUT')
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
            Guardar cambios
        </button>
    </form>
</body>
</html>