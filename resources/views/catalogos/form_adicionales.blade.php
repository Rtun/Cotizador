@extends('layouts.app')

@section('content_tittle')
    Agregar Adicional
@endsection


@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Adicionales</h3>
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
        <form action="{{ url('/catalogos/form-adicionales/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idadicional" name="idadicional" value="{{ $adicionales->idcotadicionales }}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Adicional:</label>
                        <input type="text" class="form-control" id="ad_nombre" name="ad_nombre" placeholder="Nombre del Adicional" value="{{ $adicionales->cotad_nombre }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Precio:</label>
                        <input type="number" class="form-control" id="ad_precio" name="ad_precio" placeholder="Precio" value="{{ $adicionales->cotad_precio }}">
                    </div>
                </div>
            </div>
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
