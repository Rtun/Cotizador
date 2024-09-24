@extends('layouts.app')

@section('content_tittle')
   Listado Conceptos
@endsection

@section('modulo')
    Conceptos
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
