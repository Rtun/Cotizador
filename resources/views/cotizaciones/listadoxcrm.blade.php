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

@section('modulo')
    Cotizaciones
@endsection

@section('content')
<div class="card" id="app">
    <div class="card-header">
      <h3 class="card-title">Se enlistan las cotizaciones relacionadas al numero  CRM-{{$crm}}</h3>
      <div class="card-tools">
        <form action="{{url('/cotizacion/listado')}}" method="GET">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</button>
        </form>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nombre de Cliente</th>
                <th v-if="usuario.idrol == 1">Personal</th>
                <th>N. CRM</th>
                <th>Concepto</th>
                <th>Fecha de Creacion</th>
                <th>Fecha de modifificacion</th>
                <th>Fecha de Cierre</th>
                <th>N. cotizados</th>
                <th>Documeto</th>
                <th>Elementos</th>
                <th>Enviar</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="elemento in cotizaciones">
                <td>@{{elemento.cliente}}</td>
                <td v-if="usuario.idrol == 1">@{{elemento.usuario}}</td>
                <td>@{{elemento.crm}}</td>
                <td>@{{elemento.encabezado}}</td>
                <td>@{{elemento.fecha_creacion}}</td>
                <td>@{{elemento.fecha_modificacion}}</td>
                <td v-if="elemento.fecha_cierre != null">@{{elemento.fecha_cierre}}</td>
                <td v-else>@{{ elemento.estado_cot }}</td>
                <td>@{{elemento.conteo}}</td>
                <td><button class="btn btn-success" @click="descargar_doc(elemento.documento)"><i class="fas fa-cloud-download-alt"></i></button></td>
                <td>
                    @if (validacion_rol(Auth()->user()->idrol, 'COTDETALLE'))
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-detalles" @click="mostrar_detalle(elemento.idcotizacion)"><i class="fas fa-eye"></i></button>
                    @endif
                    @if (validacion_rol(Auth()->user()->idrol, 'COTEDITAR'))
                    <button id="editar" class="btn btn-warning" @click="copiarCot(elemento.idcotizacion)"><i class="fas fa-pencil-alt"></i></button>
                    @endif
                </td>
                <td>
                    <button v-show="!enviando" class="btn btn-primary" id="enviar" @click="enviarCotizacion(elemento.idcotizacion)"><i class="fas fa-paper-plane"></i></button>
                    <div v-show="enviando" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
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
                                <label>Tipo Cambio:</label>
                                <input type="text" readonly class="form-control" v-model="datosList.tipo_cambio" name="" value="">
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
                                            <td>@{{ producto.nombre }}</td>
                                            <td>@{{ producto.descripcion }}</td>
                                            <td>@{{ producto.cantidad }}</td>
                                            <td>@{{ producto.unit_med }}</td>
                                            <td v-if="producto.tipo == 'SR'">Servicio</td>
                                            <td v-else>Producto</td>
                                            <td>@{{producto.costo_u_document}}</td>
                                            <td>@{{ producto.costo_adicionales }}</td>
                                            <td>@{{ producto.costo_desperdicio }}</td>
                                            <td>@{{ producto.precioTotal }}</td>
                                            <td>@{{ producto.moneda }}</td>
                                            <td>
                                                <button v-show="producto.tipo == 'SR'" type="button" class="btn btn-primary" @click="showAdicionales(index)"><i class="fas fa-eye"></i></button>
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
                    <button id="actualizar" class="btn btn-success" @click="actualizarPrecio(datosList.idcotizacion)"><i class="fas fa-sync-alt"></i></button>
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
                cotizaciones:<?php echo json_encode($cotizaciones);?>
                ,usuario: <?php echo json_encode($usuario)?>
                ,url_descargar: "{{url('/descargar-cotizacion/')}}"
                ,url_detalle: "{{url('/cotizacion/listado/detalle/')}}"
                ,url_editar: "{{url('/cotizacion/editar')}}"
                ,url_enviar: "{{url('/enviar/cotizacion/')}}"
                ,adicionalesList:[]
                ,datosList : {}
                ,enviando: false
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
                },
                actualizarPrecio(id) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                        });
                        swalWithBootstrapButtons.fire({
                            title: "Estas Seguro, quieres actualizar los precios?",
                            text: "Al aceptar todos los productos que fueron cotizados en dolares, actualizaran el tipo de cambio junto con sus precios",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Si, actualizar!",
                            cancelButtonText: "No, cancelar!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let datos = {
                                    idcotizacion: id,
                                    productos: this.datosList.detalles,
                                    servicios: [],
                                    cot_nombre_cli: this.datosList.cli_nombre,
                                    cot_empresa_cli: this.datosList.cli_empresa,
                                    cot_puesto_cli: this.datosList.cli_puesto,
                                    cot_telefono_cli: this.datosList.cli_puesto,
                                    cot_correo_cli: this.datosList.cli_correo,
                                    cot_encabezado: this.datosList.encabezado,
                                    cot_concepto: this.datosList.concepto,
                                    crm: this.datosList.crm,
                                    nombre_documento: this.datosList.documento,
                                    show_detalle: false
                                };

                                axios.post('/cotizacion/actualizar/precios', datos).then(response => {
                                    Swal.fire({
                                        title: response.data.titulo,
                                        text: response.data.mensaje,
                                        icon: response.data.icon,
                                    }).then(result => {
                                        if(response.data.icon == 'success') {
                                            window.location.reload();
                                        }
                                    });
                                }).catch(error => {
                                    console.log('Este es el error que brinda el servidor => ' + error);

                                    Swal.fire({
                                        title: 'Hubo un error',
                                        text: 'Hubo un error en el servidor, por favor contactame',
                                        icon: 'error'
                                    });
                                });
                            }
                        });
                },
                enviarCotizacion(id) {
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-danger"
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: "Estas seguro?",
                        text: "Quieres enviar este documento al cliente?, si hiciste modificaciones al documento. se recomienda redactar y enviar el correo de forma manual al cliente.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Si, enviar!",
                        cancelButtonText: "No, cancelar!",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.enviando = true;
                            let url = this.url_enviar + '/' + id;

                            axios.post(url).then(response => {
                                Swal.fire({
                                    title: 'Hecho',
                                    text:'La cotizacion a sido enviada al cliente via correo',
                                    icon: 'success'
                                }).then(result => {
                                    this.enviando = false;
                                });
                            }).catch(error => {
                                console.log('Este es el error que brinda el servidor => ' + error);
                                Swal.fire({
                                    title:'Error',
                                    text: 'Ha ocurrido un error al enviar el correo, por favor contactame',
                                    icon: 'error'
                                });
                            });
                        }
                    });
                }
            },
        });
    </script>

@endsection
