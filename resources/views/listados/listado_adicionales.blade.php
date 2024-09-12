@extends('layouts.app')

@section('content_tittle')
    Listado Adicionales
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan productos y servicios</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/form-adicionales')}}" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success">Registrar</button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>Nombre</th>
          <th>Precio</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in adicionales">
          <td><a :href="url_editar+'?idadicional='+elemento.idcotadicionales">@{{elemento.cotad_nombre}}</a></td>
          <td>@{{elemento.cotad_precio}}</td>
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
                adicionales:<?php echo json_encode($adicionales);?>
                ,url_editar: "{{url('/catalogos/form-adicionales')}}"
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
