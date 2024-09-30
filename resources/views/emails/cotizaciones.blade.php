<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
        }
        h1 {
            color: #0056b3;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .footer {
            padding: 10px;
            background-color: #eee;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ $mail->cotizacion_encabezado }}</h1>

    <div class="content">
        <p>Estimado(a) <strong>{{ $mail->nombre_cliente }}</strong>, Nos complace adjuntar la cotización correspondiente a su solicitud. A continuación, encontrará los detalles principales:</p>


        <p><strong>Detalle de la Cotización:</strong></p>
        <ul>
            <li><strong>Vendedor:</strong> {{ $mail->usuario }}</li>
            <li><strong>Cantidad de productos:</strong> {{ $mail->cantidad }}</li>
            <li><strong>Fecha de Inicio:</strong> {{ $mail->fecha_creacion }}</li>
            <li><strong>Fecha de Fin:</strong> {{ $mail->fecha_cierre }}</li>
        </ul>



        <p>Adjuntamos la cotización en formato Excel.</p>
    </div>

    <div class="footer">
        <p>Este correo ha sido enviado automáticamente. Por favor, no lo responda.</p>
    </div>
</body>
</html>
