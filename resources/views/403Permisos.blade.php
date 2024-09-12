<!DOCTYPE html>
<html>
<head>
<title>Access Denied</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
    body{
        background-color: black;
        color: white;
    }

    h1 {
        color: red;
    }

    h6{
        color: red;
        text-decoration: underline;
    }

    .w3-button-custom {
        background-color: red; /* Cambia el color del botón */
        color: white;
        padding: 15px 32px;
        font-size: 16px;
    }

    .w3-button-custom:hover {
        background-color: darkred; /* Color al pasar el mouse */
    }
</style>
</head>
<body>
    <div class="w3-display-middle">
        <h1 class="w3-jumbo w3-animate-top w3-center"><code>Acceso Denegado</code></h1>
        <hr class="w3-border-white w3-animate-left" style="margin:auto;width:50%">
        <h3 class="w3-center w3-animate-right">{{ $mensaje }}</h3>
        <h3 class="w3-center w3-animate-zoom">🚫🚫🚫🚫</h3>
        <br><br>
        @if ($estatus == '403Baja')
            <div class="w3-center">
                <a href="{{ route('login.destroy') }}" class="w3-button w3-button-custom w3-round-large">Click Para Regresar</a>
            </div>
        @else
            <div class="w3-center">
                <a href="{{ url()->previous() }}"class="w3-button w3-button-custom w3-round-large">Click Para Regresar</a>
            </div>
        @endif
        <br><br>
        <h6 class="w3-center w3-animate-zoom">error code: 403 forbidden</h6>
    </div>
</body>
</html>
