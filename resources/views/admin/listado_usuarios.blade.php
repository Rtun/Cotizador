@extends('layouts.app')

@section('content_tittle')
Usuarios
@endsection

@section('content')

<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Usuarios</h3>

      <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 150px;">
          <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

          <div class="input-group-append">
            <button type="submit" class="btn btn-default">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Rol</th>
            <th>Estatus</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="usuario in usuarios">
            <td>@{{usuario.idusuario}}</td>
            <td>@{{ usuario. N_usuario}}</td>
            <td>@{{ usuario.email }}</td>
            <td>@{{ usuario.telefono }}</td>
            <td>@{{ usuario.rol }}</td>
            <td>@{{ usuario.status }}</td>
            <td>
                <button id="editar" type="" class="btn btn-primary" @click="editar(usuario.idusuario)"><i class="fas fa-pencil-alt"></i></button>
                <button v-if="usuario.status == 'AC'" id="eliminar" type="" class="btn btn-danger" @click="eliminar(usuario.idusuario)"><i class="fas fa-trash"></i></button>
                <button v-else id="reactivar" type="" class="btn btn-success" @click="reactivar(usuario.idusuario)"><i class="fas fa-check"></i></button>
            </td>
          </tr>
        </tbody>
      </table>


      <div class="modal fade" id="modal-usuario" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-usuarios">Editor Usuarios</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente-nombre">Nombre:</label>
                                <input type="text" class="form-control" id="cliente-nombre" v-model="datosList.nombre" name="" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Empresa:</label>
                                <input type="text" class="form-control" v-model="datosList.empresa" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-6">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Correo:</label>
                                <input type="text" class="form-control" v-model="datosList.email"  name="sr_cantidad" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-6">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Teléfono:</label>
                                <input type="number" class="form-control" id="" name="" v-model="datosList.telefono">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-6">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label class="form-label" for=''>Rol</label>
                                <select v-model="datosList.rol" name="idrol" class="form-control" >
                                 <option v-for="rol in roles" :value="rol.idrol">@{{rol.nombre}}</option>
                                </select>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Contraseña:</label>
                                <input type="text" class="form-control" v-model="datosList.password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="guardar">Guardar</button>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
</div>

@endsection
@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                usuarios: <?php echo json_encode($usuarios);?>,
                roles: [],
                operacion: '',
                datosList: {
                    idusuario: '',
                    nombre:'',
                    empresa: '',
                    email: '',
                    telefono: '',
                    rol: '',
                    password: ''
                },
                originalData: {
                    idusuario: '',
                    nombre:'',
                    empresa: '',
                    email: '',
                    telefono: '',
                    rol: '',
                    password: ''
                }
            },
            methods: {
                editar(idusuario) {
                    this.roles = [];
                    let url = '/admin/catalogos/usuario/' + idusuario;
                    axios.get(url).then(response =>{
                        if(response.data.usuario) {
                            // v-model
                            this.datosList.idusuario = response.data.usuario.id
                            this.datosList.nombre = response.data.usuario.name;
                            this.datosList.empresa = response.data.usuario.empresa;
                            this.datosList.email = response.data.usuario.email;
                            this.datosList.telefono = response.data.usuario.telefono;
                            this.datosList.rol= response.data.usuario.idrol;

                            //validacion
                            this.originalData.idusuario = response.data.usuario.id
                            this.originalData.nombre = response.data.usuario.name;
                            this.originalData.empresa = response.data.usuario.empresa;
                            this.originalData.email = response.data.usuario.email;
                            this.originalData.telefono = response.data.usuario.telefono;
                            this.originalData.rol= response.data.usuario.idrol;

                            this.roles.push(...response.data.roles);
                            $('#modal-usuario').modal('show');
                        }
                        else {
                            Swal.fire({
                                title:'Error',
                                text: 'Hubo un error al obtener los datos del cliente con el id ' + idusuario,
                                icon: 'error'
                            });
                        }
                    }).catch( error => {
                        console.log('Este es el error que brinda el servidor => '+ error)
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error en el servidor, revisa la consola del navegador',
                            icon: 'error'
                        });
                    });
                },
                validarCambios() {
                    return JSON.stringify(this.datosList) !== JSON.stringify(this.originalData);
                },
                guardar() {
                    datos = {};
                    this.operacion = 'Editar';
                    datos = {
                        idusuario: this.datosList.idusuario,
                        nombre: this.datosList.nombre,
                        empresa: this.datosList.empresa,
                        email: this.datosList.email,
                        telefono: this.datosList.telefono,
                        rol: this.datosList.rol,
                        operacion: this.operacion,
                    };
                    if (this.datosList.password && this.datosList.password.trim() !== '') {
                        datos.password = this.datosList.password;
                    }
                    console.log( datos);
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                        });
                        swalWithBootstrapButtons.fire({
                            title: "Actualizar datos?",
                            text: "Quieres actualizar los datos del usuario " + datos.nombre,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Si, Guardar!",
                            cancelButtonText: "No, cancelar!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if(this.validarCambios()){
                                    axios.post('/admin/catalogos/usuario/actualizar', datos).then(response => {
                                        Swal.fire({
                                            title: 'Actualizado con exito!',
                                            text: response.data.mesagge,
                                            icon: 'success'
                                        }).then(result => {
                                            window.location.reload();
                                        });
                                    }).catch(error => {
                                        console.log('este es el error que brinda el servidor => ' + error);
                                        Swal.fire({
                                            title:'Error',
                                            text:'Hubo un error en el servidor, revisa la consola',
                                            icon:'error'
                                        });
                                    });
                                }
                                else{
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'No fue posible guardar porque no se hizo ninguna modificacion',
                                        icon:'error'
                                    });
                                }
                            }
                        });
                },
                eliminar(id) {
                    this.operacion = 'Eliminar';
                    let datos = {
                        idusuario: id,
                        operacion: this.operacion
                    };

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Estas Seguro?",
                        text: "Quieres adar de baja al usuario ",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, Guardar!",
                        cancelButtonText: "No, cancelar!",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post('/admin/catalogos/usuario/actualizar', datos).then(response => {
                                Swal.fire({
                                    title: 'Actualizado!',
                                    text: response.data.mesagge,
                                    icon: 'success'
                                }).then(result => {
                                    window.location.reload();
                                });
                            }).catch(error => {
                                console.log('este es el error que brinda el servidor => ' + error);
                                Swal.fire({
                                    title:'Error',
                                    text:'Hubo un error en el servidor, revisa la consola',
                                    icon:'error'
                                });
                            });
                        }
                    });
                },
                reactivar(id) {
                    this.operacion = 'Reactivar';
                    datos = {
                        idusuario: id,
                        operacion: this.operacion
                    };

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Estas Seguro?",
                        text: "Quieres reactivar al usuario ",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, Activar!",
                        cancelButtonText: "No, cancelar!",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post('/admin/catalogos/usuario/actualizar', datos).then(response => {
                                Swal.fire({
                                    title: 'Actualizado!',
                                    text: response.data.mesagge,
                                    icon: 'success'
                                }).then(result => {
                                    window.location.reload();
                                });
                            }).catch(error => {
                                console.log('este es el error que brinda el servidor => ' + error);
                                Swal.fire({
                                    title:'Error',
                                    text:'Hubo un error en el servidor, revisa la consola',
                                    icon:'error'
                                });
                            });
                        }
                    });
                }
            },
        });
    </script>
@endsection
