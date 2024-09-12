<!DOCTYPE html>
<html>
<head>
    <title>Listado de Pruebas</title>
</head>
<body>
    <h1>Listado de Pruebas</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pruebas as $prueba)
                <tr>
                    <td>{{ $prueba->id_prueba }}</td>
                    <td>{{ $prueba->Nombre }}</td>
                    <td>{{ $prueba->Apellido }}</td>
                    <td>{{ $prueba->Telefono }}</td>
                    <td>{{ $prueba->Correo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
