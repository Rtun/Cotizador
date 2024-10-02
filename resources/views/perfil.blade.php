@extends('layouts.app')

@section('content_tittle')
    {{usuario()->name}}
@endsection

@section('modulo')
    Perfil
@endsection

@section('content')
<section class="content" id="app">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{asset('LOGO COMSITEC.png')}}"
                     alt="User profile picture">
              </div>

              <h3 class="profile-username text-center">@{{usuario.name}}</h3>

              <p class="text-muted text-center">@{{usuario.rol}}</p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Cotizaciones</b> <a class="float-right">{{$cotizaciones}}</a>
                </li>
                <li class="list-group-item">
                  <b>Following</b> <a class="float-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Friends</b> <a class="float-right">13,287</a>
                </li>
              </ul>

              <a href="{{ route('login.destroy') }}" class="btn btn-danger btn-block"><b>Cerrar Sesion</b></a>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- About Me Box -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Sobre Mi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <strong><i class="fas fa-book mr-1"></i> Educación</strong>

              <p class="text-muted">
                Proximamente modificable
              </p>

              <hr>

              <strong><i class="fas fa-map-marker-alt mr-1"></i> Ubicación</strong>

              <p class="text-muted">Proximamente modificable</p>

              <hr>

              <strong><i class="fas fa-pencil-alt mr-1"></i> Habilidades</strong>

              <p class="text-muted">
                <span class="tag tag-danger">Proximamnete modificable</span>
              </p>

              <hr>

              <strong><i class="far fa-file-alt mr-1"></i> Notas</strong>

              <p class="text-muted">Proximamente modificable</p>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Pendientes</a></li>
                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Actividad</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Configuracion</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body" style="max-height: 900px; overflow-y: auto;">
              <div class="tab-content">
                <div class="active tab-pane" id="activity">
                  <!-- Post -->
                  <div class="post" v-for="(item, indexPend) in pendientes" :key="indexPend">
                    <div class="user-block">
                      <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                      <span class="username">
                        <a href="#">@{{ item.cliente }}</a>
                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                      </span>
                      <span class="description">Ya un mes de su creación - @{{ item.fecha_modificacion }}</span>
                    </div>
                    <!-- /.user-block -->
                    <p>
                        Número CRM: @{{ item.cot_num_crm }} <br>
                        Cantidad de productos: @{{ item.cot_prod_cantidad }} <br>
                        Fecha de creación: @{{ item.fecha_creacion }} <br>
                        Hora de Creacion: @{{ item.hora_creacion }} <br>
                        Usuario: @{{ item.usuario }} <br>
                        Estado: @{{ item.estado_cot }}
                    </p>

                    <p>
                      <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                      <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                      <span class="float-right">
                        <a href="#" class="link-black text-sm">
                          <i class="far fa-comments mr-1"></i> Comments (5)
                        </a>
                      </span>
                    </p>
                  </div>
                  <!-- /.post -->
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                  <!-- The timeline -->
                  <div class="timeline timeline-inverse">
                    <template v-for="item in actividad">
                        <!-- timeline time label -->
                        <div class="time-label">
                            <span class="bg-danger">
                                @{{ item.fecha_modificacion }}
                            </span>
                        </div>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <div>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> @{{ item.hora_modificacion }}</span>

                            <h3 class="timeline-header"><a href="#">@{{ item.cliente }}</a> @{{ item.encabezado }}</h3>

                            <div class="timeline-body">
                                Número CRM: @{{ item.cot_num_crm }} <br>
                                Cantidad de productos: @{{ item.cot_prod_cantidad }} <br>
                                Fecha de creación: @{{ item.fecha_creacion }} <br>
                                Hora de Creacion: @{{ item.hora_creacion }} <br>
                                Usuario: @{{ item.usuario }} <br>
                                Estado: @{{ item.estado_cot }}
                            </div>
                            <div class="timeline-footer">
                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                            </div>
                        </div>
                        </div>
                    </template>
                        <div>
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <!-- END timeline item -->
                  </div>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="settings">
                  <form action="{{ route('perfil.actualizar') }}" class="form-horizontal" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="idusuario" id="" value="{{$usuario->id}}">
                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">Nombre</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" name="nombre" value="{{$usuario->name}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail" name="email" value="{{$usuario->email}}" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Telefono</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName2" name="telefono" value="{{$usuario->telefono}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputExperience" class="col-sm-2 col-form-label">Empresa</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputExperience" name="empresa" value="{{$usuario->empresa}}"></input>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputWeb" class="col-sm-2 col-form-label">Web</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputWeb" name="web" value="{{$usuario->web}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPass" class="col-sm-2 col-form-label">Contraseña</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPass" name="password" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <div class="checkbox">
                          <label>
                            <input name="terminos" type="checkbox"> Acepto <a href="#" @click="terminos">Leer antes de modificar</a>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Guardar</button>
                      </div>
                    </div>
                  </form>
                  @error('message')

                    <div class="alert alert-danger text-center mt-2 mb-3" role="alert">
                        {{$message}}
                    </div>

                  @enderror
                </div>
                <!-- /.tab-pane -->

                <div class="modal fade" id="modal-terminos" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-servicios">Leer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <center><h2>Terminos</h2></center>

                                <ul>
                                    <li><strong>Nombre</strong> El nombre es actualizable sin ningun problema, se recomienda establecer almenos
                                    un apellido ya que en caso de requerirlo, se utilizara para las cotizaciones u otros documentos que se requieran a futuro.
                                    </li>

                                    <li>
                                        <strong>Email</strong> no se recomienda modificarlo ya que este funciona para los registros de la sesion
                                        ademas de ser utilizado en otros documentos actuales.
                                    </li>
                                    <li>
                                        <strong>Telefono</strong> Actualmente el sistema no realiza la verificacion por lo que debes asegurarte que el numero este correcto
                                        ya que el numero se utiliza y utilizara en todos los documento que cree el sistema.
                                    </li>
                                    <li>
                                        <strong>Empresa</strong> Este es importante ya que los logos de los documentos por defecto son de COMSITEC, en caso de modificar la empresa
                                        favor de notificarme para realizar los ajustes correspondientes.
                                    </li>
                                    <li>
                                        <strong>Web</strong> Esta funciona de igual forma que la empresa, favor de notificar si se requiere realizar un cambio.
                                    </li>
                                    <li>
                                        <strong>Contraseña</strong> esta es personal, por lo que si se olvida no hay forma de recuperarla actualmente, el sistema la guarda encriptada por lo que no se podria saber cual es,
                                        en caso de extravio notificar a un administrador para la reposicion de una nueva.
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                {{-- <button type="button" class="btn btn-primary">Guardar</button> --}}
                            </div>
                        </div>
                    </div>
                    </div>
                  </div>
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
@endsection

@section('scripts')
    <script>
         new Vue({
            el: '#app',
            data: {
                usuario: <?php echo json_encode($usuario);?>,
                pendientes: <?php echo json_encode($pendientes);?>,
                actividad: <?php echo json_encode($actividad);?>
            },
            methods: {
                terminos() {
                    console.log('Diste un clic');
                    $('#modal-terminos').modal('show');
                }
            },
         });
    </script>
@endsection


