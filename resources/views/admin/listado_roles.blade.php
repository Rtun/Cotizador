@extends('layouts.app')

@section('content_tittle')
Roles
@endsection

@section('modulo')
    Roles
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Roles</h3>

      <div class="card-tools">
        <form action="{{ url('/admin/roles') }}" method="post">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i>
            </button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Permisos</th>
            <th>Usuarios</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="rol in roles">
            <td>@{{rol.idrol}}</td>
            <td>@{{ rol.nombre}}</td>
            <td>
                <a id="editar" :href="url_permisos+'?idrol='+rol.idrol" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
            </td>
            <td>
                <button id="ver" type="" class="btn btn-primary" @click="showUsuarios(rol.idrol)"><i class="fas fa-eye"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->

    <div class="modal fade" id="modal-usuario" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-usuarios">Usuarios con el rol @{{rol}}</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Rol</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="element in usuarios">
                                        <td>@{{element.usuario}}</td>
                                        <td>@{{element.rol}}</td>
                                        <td>@{{element.status}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
                </div>
            </div>
        </div>
        </div>
</div>

@endsection
@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                roles: <?php echo json_encode($roles)?>,
                url_permisos: "{{ url('/admin/roles/permisos') }}",
                usuarios: [],
                rol: ''
            },
            methods: {
                showUsuarios(idrol) {
                    this.usuarios = [];
                    let url = '/admin/usuarios/filtrar/' + idrol;
                    axios.get(url).then(response => {
                        this.usuarios.push(...response.data.usuarios);
                        this.rol = response.data.rol;
                        $('#modal-usuario').modal('show');
                    }).catch(error => {
                        console.log('Este es el error que brinda el servidor => '+ error);
                        Swal.fire({
                            title: 'Hubo un error',
                            text: 'Hubo un error al obtener los datos por favor contactame',
                            icon: 'error'
                        });
                    });
                }
            },
        });
    </script>
@endsection
