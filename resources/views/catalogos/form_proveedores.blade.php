@extends('layouts.app')

@section('content_tittle')
    Agregar Proveedores
@endsection

@section('modulo')
    Proveedores
@endsection

@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Proveedores</h3>
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
        <form action="{{ url('/catalogos/proveedores/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idproveedor" name="idproveedor" value="{{ $proveedor->idproveedor }}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del proveedor" value="{{ $proveedor->prv_nombre}}">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
