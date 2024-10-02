@extends('layouts.app')

@section('content_tittle')
Inicio
@endsection

@section('modulo')
    Inicio
@endsection

@section('content')
<section class="content" id="app">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>@{{N_productos}}</h3>

              <p>Productos</p>
            </div>
            <div class="icon">
                <i class="fas fa-tag"></i>
            </div>
            <a href="{{ url('/catalogos/listado/productos') }}" class="small-box-footer">Ver Productos <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>@{{N_adicionales}}</h3>

              <p>Adicionales</p>
            </div>
            <div class="icon">
                <i class="fas fa-link mr-1"></i>
            </div>
            <a href="{{ url('/catalogos/listado/adicionales') }}" class="small-box-footer">Ver Adicionales <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>@{{N_cotizaciones}}</h3>

              <p>Mis Cotizaciones</p>
            </div>
            <div class="icon">
                <i class="ion ion-clipboard mr-1"></i>
            </div>
            <a href="{{ url('/cotizacion/listado') }}" class="small-box-footer">Ver Adicionales <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>@{{N_reuniones}}</h3>

              <p>Reuniones en sala de hoy</p>
            </div>
            <div class="icon">
                <i class="far fa-calendar-alt"></i>
            </div>
            <a href="{{ url('/catalogos/calendario') }}" class="small-box-footer">Ver Calendario <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
    <br>
    <div class="alert alert-danger" v-show="hayMensaje">
        <center>
            @{{ getMensaje }}
        </center>
    </div>

    <div class="modal fade" id="modal-lg" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Avisos</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">{{usuario()->name}}</span>
              </button>
            </div>
            <div class="modal-body" v-if="mensaje == ''">
                <p style="color: red">Las siguientes cotizaciones ya tienen un mes o mas desde su creación,
                    si le das clic a "Rechazar" se actualizará el estatus de todas las cotizaciones en la lista,
                    cambiandolos a Rechazado
                </p>
                <table class="table table-responsive text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>N. CRM</th>
                        <th>Cantidad articulos</th>
                        <th>Fecha Creación</th>
                        <th>Fecha Modificación</th>
                        <th>Estatus</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="cotizacion in pendientes">
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input idcotizacion" type="checkbox" :id="'check'+cotizacion.idcotizacion" :value="cotizacion.idcotizacion">
                                <label :for="'check'+cotizacion.idcotizacion" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>@{{ cotizacion.cliente}}</td>
                        <td>@{{ cotizacion.cot_num_crm }}</td>
                        <td>@{{ cotizacion.cot_prod_cantidad }}</td>
                        <td>@{{ cotizacion.fecha_creacion }}</td>
                        <td>@{{ cotizacion.fecha_modificacion }}</td>
                        <td>@{{ cotizacion.estado_cot }}</td>
                      </tr>
                    </tbody>
                  </table>
            </div>

            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Alerta</button>
              <button v-show="pendientes.length > 0" type="button" class="btn btn-primary" @click="cerrarSeleccionados">Rechazar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  </section>
@endsection

@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                N_productos: <?php echo json_encode($N_productos);?>,
                N_adicionales: <?php echo json_encode($N_adicionales);?>,
                N_cotizaciones: <?php echo json_encode($N_cotizaciones);?>,
                N_reuniones: <?php echo json_encode($N_reuniones);?>,
                pendientes: <?php echo json_encode($pendientes);?>,
                idcotizacion: [],
                hayMensaje: false,
                mensaje: '',
                getMensaje: ''
            },
            mounted() {
                this.checkPendientes();
            },
            methods: {
                checkPendientes() {
                    if(this.pendientes.length > 0){
                        $('#modal-lg').modal('show');
                    }
                    else{
                        this.mensaje = "No hay pendientes para hoy";
                        $('#modal-lg').modal('show');
                    }

                },
                cerrarSeleccionados (){
                     //verifica y extrae el valor de los checkbox seleccionados
                    const collection = document.getElementsByClassName("idcotizacion");
                    this.idcotizacion = [];
                    for (let i = 0; i < collection.length; i++) {
                        if (collection[i].type === "checkbox") {
                            if (collection[i].checked == true) {
                                const seleccionados  =  collection[i].value;
                                this.idcotizacion.push(seleccionados);
                            }
                        }
                    }

                    let datos = {
                        cotizaciones: this.idcotizacion
                    };

                    if(this.idcotizacion.length > 0) {
                        const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                        });
                        swalWithBootstrapButtons.fire({
                            title: "Estas Seguro?",
                            text: "El estatus de las cotizaciones seleccionadas cambiara a Rechazado, por lo que no se mostraran en las alertas de nuevo!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Si, cambiar!",
                            cancelButtonText: "No, cancelar!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post('/cotizacion/rechazar', datos).then(response => {
                                    if(response.data.estatus == 'OK') {
                                        this.hayMensaje = true;
                                        this.getMensaje = response.data.mensaje;
                                        $('#modal-lg').modal('hide');

                                        swalWithBootstrapButtons.fire({
                                            title: "Hecho!",
                                            text: "El estatus de las cotizaciones cambio.",
                                            icon: "success"
                                        });
                                    }
                                });
                            }
                        });
                    }
                    else {
                        Swal.fire({
                            title: 'Hubo un error',
                            text: 'Tienes que seleccionar al menos una opción para, cerrar las cotizaciones',
                            icon: 'error'
                        });
                    }
                }
            },
        });
    </script>
@endsection

