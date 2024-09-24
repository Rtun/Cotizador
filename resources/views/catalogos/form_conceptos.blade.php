@extends('layouts.app')

@section('content_tittle')
    Agregar Conceptos
@endsection

@section('modulo')
    Conceptos
@endsection

@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Conceptos/Textos</h3>
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
        <form action="{{ url('/catalogos/conceptos/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idconcepto" name="idconcepto" value="{{ $conceptos->idconcepto }}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Palabra Clave:</label>
                        <input type="text" class="form-control" v-model="clave" id="con_clave" name="con_clave" placeholder="Palabra de referencia" value="{{ old('con_clave', $conceptos->con_clave ?? '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Texto:</label>
                        <textarea class="form-control" v-model="texto" id="con_texto" name="con_texto" placeholder="Texto" cols="10" rows="2">{{ old('con_texto', $conceptos->con_texto ?? '') }}</textarea>
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
