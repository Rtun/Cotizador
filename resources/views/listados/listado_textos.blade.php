@extends('layouts.app')

@section('content_tittle')
   Listado Conceptos
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Listado de Textos/Conceptos</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/conceptos')}}" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success">Registrar</button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="alert alert-warning" v-if="conceptos == 'Error'">
            <strong>Aun no hay registros</strong>
        </div>
      <table id="example1" class="table table-bordered table-striped" v-else>
        <thead>
        <tr>
          <th>Clave</th>
          <th>Texto</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in conceptos">
          <td><a :href="url_editar+'?idconcepto='+elemento.idconcepto">@{{elemento.con_clave}}</a></td>
          <td>@{{elemento.con_texto}}</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection

@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                conceptos:<?php echo json_encode($conceptos);?>
                ,url_editar: "{{url('/catalogos/conceptos')}}"
            },
            mounted() {
                $(function () {
                $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                    });
                });
            }
        });
    </script>

@endsection
