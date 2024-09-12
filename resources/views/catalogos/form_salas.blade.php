@extends('layouts.app')

@section('content_tittle')
    Agregar Sala
@endsection


@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Salas</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ url('/catalogos/salas/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idproducto" name="idsala" value="{{ old('idsala', $sala->idsala ?? '')  }}">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" class="form-control" id="nombre_prod" name="nombre_sala" placeholder="Nombre de la sala" value="{{ old('nombre_sala', $sala->sa_nombre ?? '')  }}">
                    </div>
                </div>
            </div>
            @if ($errors->has('nombre_sala'))
                <div class="alert alert-danger">
                    {{ $errors->first('nombre_sala') }}
                </div>
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" name="operacion" value="{{ $operacion }}">
                    @if ($operacion == 'Editar')
                    <input type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
                    @endif
                    <input type="submit" class="btn btn-warning" name="operacion" value="Cancelar">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
