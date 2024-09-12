@extends('layouts.app')

@section('content_tittle')
   Listado Proveedores
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan los proveedores registrdos</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/proveedores')}}" method="POST">
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
          <th>Clave</th>
          <th>Nombre</th>
          <th>Usuario Alta</th>
          <th>Fecha de creación</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in proveedores">
          <td><a :href="url_editar+'?idproveedor='+elemento.idproveedor">@{{elemento.idproveedor}}</a></td>
          <td>@{{elemento.prv_nombre}}</td>
          <td>@{{elemento.usuario}}</td>
          <td>@{{elemento.fecha_creacion}}</td>
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
                proveedores:<?php echo json_encode($proveedores);?>
                ,url_editar: "{{url('/catalogos/proveedores')}}"
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
