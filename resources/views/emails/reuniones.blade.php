<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla de Correo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-collapse: collapse;
        }
        td {
            padding: 20px;
            text-align: left;
        }
        h1 {
            color: #333333;
        }
        p {
            color: #555555;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .footer {
            background-color: #333333;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="header">
                <h1>{{$datosReunion->titulo}}</h1>
            </td>
        </tr>
        <tr>
            <td>
                <p>Hola, <strong>{{$datosReunion->usuario}}</strong> Se te notifica que {{$datosReunion->mensaje}}:</p>
                <p><strong>Tema:</strong> {{ $datosReunion->tema }}</p>
                <p><strong>Fecha:</strong> {{ $datosReunion->fecha_inicio }}</p>
                <p><strong>Fecha Fin:</strong> {{ $datosReunion->fecha_fin }}</p>
                <p><strong>Lugar:</strong> {{ $datosReunion->sala }}</p>
                <p><strong>Descripcion:</strong> {{ $datosReunion->contenido }}</p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>Este es un mensaje automatizado. No respondas a este correo.</p>
            </td>
        </tr>
    </table>
</body>
</html>
