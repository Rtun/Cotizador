@extends('layouts.app')

@section('content_tittle')
   Listado Proveedores
@endsection

@section('modulo')
    Proveedores
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

@section('java_extensions')
<!--Tablas-->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
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
