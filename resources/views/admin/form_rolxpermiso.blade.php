@extends('layouts.app')

@section('content_tittle')
Permisos
@endsection

@section('modulo')
    Permisos
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Permisos del Rol {{$rol->nombre}}</h3>

      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 150px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

          <div class="input-group-append">
            <button type="submit" class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div id="app" class="row" >
            <div class="col-md-12">
              <form action="{{route('admin.rolxpermiso')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="idrol" value="{{$rol->idrol}}">
                <table class="table">
                  <tr v-for="permiso in permisos">
                      <td>
                        <input type="checkbox" :checked='permiso.asignada' name="idpermiso[]" :value="permiso.idpermiso">
                      </td>
                      <td>@{{permiso.nombre}}</td>
                  </tr>
                </table>
                <input type="submit" class="btn btn-success" name="operacion" value="Guardar"></input>
                <input type="submit" class="btn btn-warning" name="operacion" value="Cancelar"></input>
              </form>
            </div>
        </div>
</div>

@endsection
@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
            permisos:<?php echo json_encode($permisos);?>
            }
        });
    </script>
@endsection
