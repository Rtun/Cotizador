@extends('layouts.app')

@section('content_tittle')
   Listado Clientes
@endsection

@section('modulo')
    Clientes
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan los Clientes registrados</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/clientes')}}" method="POST">
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
          <th>Empresa</th>
          <th>Puesto</th>
          <th>Correo</th>
          <th>Telefono</th>
          <th>Estatus</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in clientes">
          <td><a :href="url_editar+'?idcliente='+elemento.idclientes">@{{elemento.idclientes}}</a></td>
          <td>@{{elemento.cli_nombre}}</td>
          <td>@{{elemento.cli_empresa}}</td>
          <td>@{{elemento.cli_puesto}}</td>
          <td>@{{elemento.cli_correo}}</td>
          <td>@{{elemento.cli_telefono}}</td>
          <td>@{{elemento.status}}</td>
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
                clientes:<?php echo json_encode($clientes);?>
                ,url_editar: "{{url('/catalogos/clientes')}}"
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
