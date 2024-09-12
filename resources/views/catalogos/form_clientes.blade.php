@extends('layouts.app')

@section('content_tittle')
    Agregar Clientes
@endsection


@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Clientes</h3>
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
        <form action="{{ url('/catalogos/clientes/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idmarca" name="idcliente" value="{{ $clientes->idclientes }}">
                <input type="hidden" id="formulario" name="formulario" value="formulario">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nombre del cliente" value="{{ $clientes->cli_nombre }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telefono:</label>
                        <input type="text" class="form-control" id="telefono" name="cli_telefono" placeholder="Telefono del cliente" value="{{ $clientes->cli_telefono }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo:</label>
                        <input type="text" class="form-control" id="email" name="cli_email" placeholder="Email del cliente" value="{{ $clientes->cli_correo }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>empresa:</label>
                        <input type="text" class="form-control" id="empresa" name="cli_empresa" placeholder="Empresa del cliente" value="{{ $clientes->cli_empresa }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Puesto:</label>
                        <input type="text" class="form-control" id="puesto" name="cli_puesto" placeholder="Puesto del cliente" value="{{ $clientes->cli_puesto }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input @click="guardar" type="submit" class="btn btn-success" name="operacion" value="{{ $operacion }}">
                    @if ($operacion == 'Editar')
                    <input @click="editar" type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
                    @endif
                    <input type="submit" class="btn btn-warning" name="operacion" value="Cancelar">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

