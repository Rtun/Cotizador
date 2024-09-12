@extends('layouts.app')

@section('content_tittle')
   Listado de Cotizaciones
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan las cotizaciones de {{$usuario->name}}</h3>
      <div class="card-tools">
        <form @submit="avanzar" action="{{url('/cotizacion')}}" method="POST">
            {{ csrf_field() }}
            <button v-if ="!redirigiendo" type="submit" class="btn btn-success">Nueva</button>
            <button v-else class="btn btn-success" type="button" disabled>
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span role="status">Redirigiendo...</span>
            </button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
          <th>Nombre de Cliente</th>
          <th>Empresa</th>
          <th>N. CRM</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in cotizaciones">
          <td>@{{elemento.cliente}}</td>
          <td>@{{elemento.empresa}}</td>
          <td><a :href="url_buscar+'/'+elemento.crm">@{{elemento.crm}}</a></td>
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
                cotizaciones:<?php echo json_encode($cotizaciones);?>
                ,url_buscar: "{{url('/cotizacion/listado/')}}"
                ,redirigiendo: false
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
            },
            methods: {
                avanzar (event) {
                    this.redirigiendo = true;
                }
            },
        });
    </script>

@endsection
