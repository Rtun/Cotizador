@extends('layouts.app')

@section('content_tittle')
    Listado Salas
@endsection

@section('modulo')
    Salas
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan las salas registradas</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/salas')}}" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success">Registrar</button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <ul>
           <li v-for="salas in sala"><a :href="url_editar+'?idsala='+salas.idsala">@{{salas.sa_nombre}}</a></li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>
@endsection

@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                sala:<?php echo json_encode($sala);?>
                ,url_editar: "{{url('/catalogos/salas')}}"
            }
        });
    </script>

@endsection
