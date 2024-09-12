@extends('layouts.app')

@section('styles')
<style>
    .modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
    }

    .modal-open {
        overflow: auto;
    }
</style>
@endsection

@section('content_tittle')
   Listado del numero {{$crm}} de CRM
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan las cotizaciones relacionadas al numero  CRM-{{$crm}}</h3>
      <div class="card-tools">
        <form action="{{url('/cotizacion/listado')}}" method="GET">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-warning">Regresar Al Listado</button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nombre de Cliente</th>
                <th>N. CRM</th>
                <th>Concepto</th>
                <th>Fecha de Creacion</th>
                <th>Fecha de modifificacion</th>
                <th>Fecha de Cierre</th>
                <th>N. cotizados</th>
                <th>Documeto</th>
                <th>Elementos</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="elemento in cotizaciones">
                <td>@{{elemento.cliente}}</td>
                <td>@{{elemento.crm}}</td>
                <td>@{{elemento.encabezado}}</td>
                <td>@{{elemento.fecha_creacion}}</td>
                <td>@{{elemento.fecha_modificacion}}</td>
                <td v-if="elemento.fecha_cierre != null">@{{elemento.fecha_cierre}}</td>
                <td v-else>@{{ elemento.estado_cot }}</td>
                <td>@{{elemento.conteo}}</td>
                <td><button class="btn btn-success" @click="descargar_doc(elemento.documento)"><i class="fas fa-cloud-download-alt"></i></button></td>
                <td>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-detalles" @click="mostrar_detalle(elemento.idcotizacion)"><i class="fas fa-eye"></i></button>
                    <button id="editar" class="btn btn-warning"><i class="fas fa-pencil-alt" @click="copiarCot(elemento.idcotizacion)"></i></button>
                </td>
            </tr>
        </tfoot>
      </table>

      <!--/Modal de cotizacion/-->
      <div class="modal fade" id="modal-detalles" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-servicios">Servicio de Mano de Obra</h5>
                    <button v-show="datosList.fecha_cierre == null" type="button" class="btn btn-success" aria-label="Close">
                        <span aria-hidden="true" @click="finalizarCot(datosList.idcotizacion)">Cerrar Cotizacion</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <center><h2>Datos del cliente</h2></center>
                        </div>
                        <br>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cliente-nombre">Nombre:</label>
                                <input type="text" readonly class="form-control" id="cliente-nombre" v-model="datosList.cli_nombre" name="" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Empresa:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.cli_empresa" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Puesto:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.cli_puesto"  name="sr_cantidad" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Teléfono:</label>
                                <input type="number" readonly class="form-control" id="" name="" v-model="datosList.cli_telefono">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Correo:</label>
                                <input type="email" readonly class="form-control" v-model="datosList.cli_correo" name="sr_total" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <br>
                        <div class="col-md-12">
                            <center><h2>Datos de la cotización</h2></center>
                        </div>
                        <br>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>concepto:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.concepto" name="sr_total" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>N. CRM:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.crm" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Fecha de creación:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.fecha_creacion" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Fecha de Modificación:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.fecha_modificacion" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-4">
                            <!-- /.form-group -->
                            <div class="form-group">
                                <label>Fecha de Cierre:</label>
                                <input v-if="datosList.fecha_cierre == null" type="text" readonly class="form-control" name="" value="En curso">
                                <input v-else type="text" readonly class="form-control" v-model="datosList.fecha_cierre" name="" value="">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-md-12">
                            <center><h3>Productos / Servicios</h3></center>
                        </div>
                        <br>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Unidad Medicion</th>
                                            <th>Tipo</th>
                                            <th>Precio Unitario</th>
                                            <th>Precio Adicional</th>
                                            <th>precio Desperdicio</th>
                                            <th>precio Total</th>
                                            <th>Moneda</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(producto, index) in datosList.detalles" :key="index">
                                            <td>@{{ producto.prod_nombre }}</td>
                                            <td>@{{ producto.descripcion }}</td>
                                            <td>@{{ producto.cantidad }}</td>
                                            <td>@{{ producto.prod_medicion }}</td>
                                            <td v-if="producto.tipo_cot == 'SR'">Servicio</td>
                                            <td v-else>Producto</td>
                                            <td>@{{producto.prod_precio}}</td>
                                            <td>@{{ producto.prod_precio_adicionales }}</td>
                                            <td>@{{ producto.prod_precio_desperdicio }}</td>
                                            <td>@{{ producto.total }}</td>
                                            <td>@{{ producto.moneda }}</td>
                                            <td>
                                                <button v-show="producto.tipo_cot == 'SR'" type="button" class="btn btn-primary" @click="showAdicionales(index)"><i class="fas fa-eye"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-warning">
                                <p>La moneda en la tabla de productos referencia a como se cotizo, pero los precios totales y unitarios son totalmente en pesos mexicanos</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary">Guardar</button> --}}
                </div>
            </div>
        </div>
        </div>
      </div>

      <!--/Modal de adicionales/-->
      <div class="modal fade" id="modal-adicionales" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-servicios">Servicio de Mano de Obra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><h2>Listado de adicionales</h2></center>

                    <ul>
                        <li v-for="adicional in adicionalesList">
                            Nombre: @{{adicional.adic_nombre}} | Cantidad: @{{adicional.adic_cantidad}} | Precio: @{{adicional.adic_cantidad}} | Total: @{{adicional.adic_total}}
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="restarAdicional()">Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary">Guardar</button> --}}
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
                cotizaciones:<?php echo json_encode($cotizaciones);?>
                ,url_descargar: "{{url('/descargar-cotizacion/')}}"
                ,url_detalle: "{{url('/cotizacion/listado/detalle/')}}"
                ,url_editar: "{{url('/cotizacion/editar')}}"
                ,adicionalesList:[]
                ,datosList : {}
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

                $('#modal-adicionales').on('shown.bs.modal', function () {
                    $('body').addClass('modal-open');
                });

                $('#modal-adicionales').on('hidden.bs.modal', function () {
                    if ($('.modal.show').length) {
                        $('body').addClass('modal-open');
                    }
                });
            },
            methods: {
                descargar_doc(documento) {
                    const url = this.url_descargar + '/' + documento;
                    window.location.href = url;
                },
                mostrar_detalle (cotizacion) {
                    let url = this.url_detalle + '/' + cotizacion;

                    axios.get(url).then(response => {
                        this.datosList = response.data.cotizaciones[0];
                        $('#modal-detalle').modal('show');
                    }).catch(error => {
                        console.log('Este error brinda el servidor => ' + error);
                        Swal.fire({
                                title: "Error",
                                text: "Hubo un error al intentar mostrar la cotizacion, porfavor contactame",
                                icon: "error"
                            });
                    });
                },
                showAdicionales(index) {
                    const detalle =  this.datosList.detalles[index];
                   detalle.adicionales.forEach(element => {
                        this.adicionalesList.push(element);
                   });
                   $('#modal-adicionales').modal('show');
                },
                restarAdicional () {
                    this.adicionalesList = [];
                },
                finalizarCot (idcotizacion) {
                    var crm = this.datosList.crm;
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                        });
                        swalWithBootstrapButtons.fire({
                            title: "Estas Seguro?",
                            text: "Al finalizar una cotizacion en automatico se cerraran las demas",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Si, Cerrar!",
                            cancelButtonText: "No, cancelar!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                datos = {
                                    id: idcotizacion
                                };
                                axios.post('/cotizacion/finalizar/', datos)
                                .then(response => {
                                    if(response.data.message == 'OK'){
                                        let url = '/cotizacion/listado' + '/' + crm;
                                        swalWithBootstrapButtons.fire({
                                        title: "Bien Hecho!",
                                        text: "Haz finalizado las cotizaciones del CRM-" + crm,
                                        icon: "success"
                                        }).then(() => {
                                            setTimeout(() => {
                                                window.location.href = url;
                                            }, 500);
                                        });
                                    }
                                })
                                .catch(error => {
                                    swalWithBootstrapButtons.fire({
                                        title: "Error",
                                        text: "Hay un error al guardar la fecha de finalización, porfavor notificamelo :)",
                                        icon: "error"
                                    });
                                });

                            }
                        // else if (
                        //     /* Read more about handling dismissals below */
                        //     result.dismiss === Swal.DismissReason.cancel
                        // ) {
                        //     swalWithBootstrapButtons.fire({
                        //     title: "Cancelled",
                        //     text: "Your imaginary file is safe :)",
                        //     icon: "error"
                        //     });
                        // }
                        });
                },
                copiarCot(idcotizacion) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Estas seguro?",
                        text: "No editaras la cotización, en su lugar se creará una nueva tomando la que seleccionaste como base",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, continuar!",
                        cancelButtonText: "No, cancelar!",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let url = this.url_editar + '/' + idcotizacion;
                            window.location.href = url;
                        }
                    });
                }
            },
        });
    </script>

@endsection
