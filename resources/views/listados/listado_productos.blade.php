@extends('layouts.app')

@section('content_tittle')
   Listado Productos
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan productos y servicios</h3>
      <div class="card-tools">
        <form action="{{url('/catalogos/form-productos')}}" method="POST">
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
          <th>Clave</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Proveedor</th>
          <th>Medicion</th>
          <th>Precio</th>
          <th>Tipo</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="elemento in productos">
          <td><a :href="url_editar+'?idproducto='+elemento.idproductos">@{{elemento.prod_nombre}}</a></td>
          <td v-if="elemento.prod_cve != null">@{{elemento.prod_cve}}</td>
          <td v-else>@{{elemento.prod_cve_syscom}}</td>
          <td>@{{elemento.marca}}</td>
          <td>@{{elemento.modelo}}</td>
          <td>@{{elemento.proveedor}}</td>
          <td>@{{elemento.prod_medicion}}</td>
          <td>@{{parseFloat(elemento.prod_precio_brut)}}</td>
          <td>@{{elemento.prod_tipo}}</td>
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
                productos:<?php echo json_encode($productos);?>
                ,url_editar: "{{url('/catalogos/form-productos')}}"
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
