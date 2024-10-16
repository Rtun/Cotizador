@extends('layouts.app')

@section('modulo')
    Acceso denegado
@endsection

@section('content')
    <style>
        /* .container {
            border-style: dashed;
            border-color: black;
        } */

        h1 {
            color: red;
        }

        h6 {
            color: red;
            text-decoration: underline;
        }

        h3 {
            color: black;

        }

        .btn-danger-custom {
            background-color: red;
            color: white;
            padding: 15px 32px;
            font-size: 16px;
        }

        .btn-danger-custom:hover {
            background-color: darkred;
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-3 text-danger">Acceso Denegado</h1>
                <hr class="border-black" style="width:50%">
                <h3>{{ $mensaje }}</h3>
                <h3>🚫🚫🚫🚫</h3>
                <br><br>
                @if ($estatus == '403Baja')
                    <a href="{{ route('login.destroy') }}" class="btn btn-danger-custom btn-lg">Click Para Regresar</a>
                @else
                    <a href="{{ url()->previous() }}" class="btn btn-danger-custom btn-lg">Click Para Salir</a>
                @endif
                <br><br>
                <h6 class="text-danger text-decoration-underline">error code: 403 forbidden</h6>
            </div>
        </div>
    </div>
@endsection
