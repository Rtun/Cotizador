@extends('layouts.app')

@section('content_tittle')
    Cotización
@endsection

@section('content')
<!-- /.inicio de la card -->
<div class="card card-default" id="app">


     <!-- /.inicio del header -->
    <div class="card-header">
        <h3 class="card-tittle">Formulario Cotizacion</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
     <!-- /.fin del header -->

     <!-- /.inicio de formulario principal -->
    <div id="form_cotizacion" v-show="estadoform">
     <!-- /.inicio del body de la card-->
        <div class="card-body">
            <form action="{{ url('/cotizacion/guardar')}}" method="POST">
                {{ csrf_field() }}
        <!-- /.inicio de clientes -->
                <center>
                    <h5>Datos del Cliente</h5>
                </center>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" v-show="!reg_nuevo_user">
                            <label>Empresa:</label>
                            <select ref="clienteselect" name="cli_empresa" id="cli_empresa" class="form-control select2cliente" style="width: 100%;">
                                <option value="" selected disabled>Selecciona una empresa</option>
                                <option v-for="cli in clientes"
                                :value="cli.idclientes"
                                :data-nombre="cli.cli_nombre"
                                :data-telefono="cli.cli_telefono"
                                :data-email="cli.cli_correo"
                                :data-puesto="cli.cli_puesto"
                                :data-empresa="cli.cli_empresa">
                                @{{cli.accountname}} | @{{cli.cli_empresa}}
                            </option>
                            </select>
                        </div>
                        <div class="form-group" v-show="reg_nuevo_user">
                            <label>Empresa:</label>
                            <input type="text" class="form-control" id="cli_empresa" v-model="cli_empresa" name="cli_empresa" placeholder="Nombre de la empresa">
                        </div>
                    </div>
                    <div class="col-md-4">
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Cliente:</label>
                        <input type="text" class="form-control" id="cliente" v-model="cliente" name="cliente" placeholder="Empresa del cliente" value="">
                    </div>
                    <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                    <div class="form-group">
                        <label>Puesto:</label>
                        <input type="text" class="form-control" id="cli_puesto" v-model="cli_puesto" name="cli_puesto" placeholder="Puesto del cliente" value="">
                    </div>
                    </div>
                    <div class="col-md-4">
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label>Télefono:</label>
                        <input type="text" class="form-control" id="cli_telefono" v-model="cli_telefono" name="cli_telefono" placeholder="Telefono del cliente" value="">
                    </div>
                    <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Correo:</label>
                            <input type="email" class="form-control" id="cli-email" v-model="cli_email" name="cli-email" placeholder="E-mail del cliente" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <input v-show="!reg_nuevo_user" class="form-check-input" v-model="reg_nuevo_user" type="checkbox" name="nuevo_usuario" id="nuevo_usuario">
                            <label v-show="!reg_nuevo_user" for="nuevo_usuario" class="form-check-label">El cliente es nuevo? </label>
                            <button v-show="reg_nuevo_user" type="button" class="btn btn-block btn-outline-success btn-sm" @click="registrarNuevo">Registrar Nuevo</button>
                        </div>
                    </div>
                </div>
        <!-- /.fin de clientes -->

        <!-- /.inicio de asesor -->
                <center>
                    <h5>Datos del asesor</h5>
                </center>
                <br>
                <div class="row">
                    <div>
                        <input type="hidden" :value="asesor.id" name="asesor_id">
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label>Nombre del Asesor</label>
                        <input type="text" disabled class="form-control" id="asesor_nombre" name="asesor_nombre" :value="asesor.name">
                    </div>
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label>Correo</label>
                            <input type="text" disabled class="form-control"  id="asesor_correo" name="asesor_correo" :value="asesor.email">
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <label>Telefono</label>
                            <input type="text" disabled class="form-control"  id="asesor_telefono" name="asesor_telefono" :value="asesor.telefono">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                        <label>Empresa</label>
                            <input type="text" disabled class="form-control"  id="asesor_empresa" name="asesor_empresa" :value="asesor.empresa">
                        </div>
                        </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                        <label>Página Web:</label>
                            <input type="text" disabled class="form-control"  id="asesor_web" name="asesor_web" :value="asesor.web">
                        </div>
                        </div>
                </div>
        <!-- /.fin de asesor -->

        <!-- /.inicio de cotizacion -->
                <center>
                    <h5>Datos De la Cotización</h5>
                </center>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        {{-- <div class="form-group">
                            <label for="cot_concepto">Concepto de la cotización:</label>
                            <input type="textarea" class="form-control" id="cot_concepto" name="cot_concepto" v-model="cot_concepto" placeholder="Escribe el concepto de la cotización" value="">
                        </div> --}}
                        <div class="form-group">
                            <label>Concepto de la cotización:</label>
                            <select ref="textoselect" name="cot_concepto" id="cot_concepto" class="form-control select2texto" style="width: 100%;">
                                <option value="" selected disabled>Selecciona un Concepto</option>
                                <option v-for="con in conceptos" :data-texto="con.con_texto" :value="con.idconcepto">@{{con.con_clave}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cot_concepto">Encabezado/Titulo:</label>
                            <input type="textarea" class="form-control" id="cot_encabezado" name="cot_encabezado" v-model="cot_encabezado" placeholder="Escribe el concepto de la cotización" value="">
                        </div>
                    </div>
                      <!-- /.col -->
                    <div class="col-md-4">
                        <!-- /.form-group -->
                        <div class="form-group">
                        <label>Tipo de cambio:</label>
                        <input type="text" class="form-control" v-model="tipo_cambio" id="tipocosto" placeholder="Tipo costo">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-4">
                        <!-- /.form-group -->
                        <div class="form-group">
                        <label>Numero De CRM:</label>
                        <input type="text" class="form-control" v-model="crm" id="crm" placeholder="N. CRM">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                              <input v-model="show_detalle" type="checkbox" class="custom-control-input" id="customSwitch1">
                              <label class="custom-control-label" for="customSwitch1">Mostrar Marca, Modelo, Proveedor en el documento</label>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- /.botones -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <button v-show="!cotizando" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-productos">Agregar Producto</button>
                            <button v-show="!cotizando" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">Agregar Servicio</button>
                        </div>
                    </div>
                </div>
        <!-- /.fin de botones -->

        <!--/. Modal Productos-->
                <div class="modal fade" id="modal-productos" tabindex="-1" role="dialog" aria-labelledby="mod_productos" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mod_productos">Productos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Producto:</label>
                                            <select ref="productoSelect" class="form-control select2 select2bs4 producto-select" style="width: 100%;" name="productos" data-index="0" id="producto-select">
                                                <option value="" selected disabled>Selecciona un producto</option>
                                                <option v-for="prod in productos" :value="prod.nombre"
                                                    :data-id_pr="prod.idproductos"
                                                    :data-cve_producto="prod.cve_producto"
                                                    :data-nombre="prod.nombre"
                                                    :data-unit_med="prod.unit_med"
                                                    :data-costo_unidad="prod.costo_unidad"
                                                    :data-marca="prod.marca"
                                                    :data-modelo="prod.modelo"
                                                    :data-proveedor="prod.proveedor">
                                                    @{{ prod.nombre }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Producto Seleccionado:</label>
                                            <input type="text" class="form-control" id="producto-nombre" v-model="currentProducto.nombre" placeholder="nombre del producto" name="pr_nombre" value="" readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Marca:</label>
                                            <input type="text" class="form-control" id="producto-marca" v-model="currentProducto.marca" placeholder="Marca del producto" name="pr_marca" value="" readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Modelo:</label>
                                            <input type="text" class="form-control" id="producto-modelo" v-model="currentProducto.modelo" placeholder="Modelo del producto" name="pr_modelo" value="" readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Proveedor:</label>
                                            <input type="text" class="form-control" id="producto-proveedor" v-model="currentProducto.proveedor" placeholder="proveedor del producto" name="pr_proveedor" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                    <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Precio Unitario:</label>
                                            <input type="number" class="form-control" id="producto-costo_unidad" v-model="currentProducto.costo_unidad" @input="calculateTotal" placeholder="Precio unitario" name="pr_precio_unitario" value="">
                                        </div>
                                    <!-- /.form-group -->
                                    </div>
                                    <div class="col-md-6">
                                    <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Precio desperdicio:</label>
                                            <input type="number" class="form-control" placeholder="Precio unitario" v-model="currentProducto.costo_desperdicio" @input="calculateTotal" name="pr_precio_desperdicio" value="">
                                        </div>
                                    <!-- /.form-group -->
                                    </div>
                                    <div class="col-md-6">
                                    <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Adicionales:</label>
                                            <input type="number" class="form-control" placeholder="Precio unitario" v-model="currentProducto.costo_adicionales" @input="calculateTotal" name="pr_precio_adicionales" value="">
                                        </div>
                                    <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" id="pr_cantidad" placeholder="Cantidad" v-model="currentProducto.cantidad" @input="calculateTotal" name="pr_cantidad" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                        <label>Unidad de medicion:</label>
                                        <input type="text" class="form-control" id="unit-med" placeholder="Unidad de medicion" v-model="currentProducto.unit_med" name="pr_unidad_medicion" value="">
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->

                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Utilidad</label>
                                            <input type="text" class="form-control" id="utilidad" placeholder="Utilidad" v-model="currentProducto.utilidad">
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <div class="col-md-6">
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Precio Total</label>
                                            <input readonly type="text" class="form-control" id="precioTotal" placeholder="precioTotal" v-model="currentProducto.precioTotal">
                                        </div>
                                        <!-- /.form-group -->
                                    </div>

                                    <div class="mb-6">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="currentProducto.isDollar" type="checkbox" name="productoDollar" id="productoDollar">
                                            <label for="productoDollar" class="form-check-label">El precio esta en dolares? </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="addProducto">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
        <!--/. Fin Modal Productos-->

        <!--Modal Mano Obra-->
                <div class="modal fade" id="modal-lg" tabindex="-1" role="dialog" aria-labelledby="modal-servicios" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-servicios">Servicio de Mano de Obra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-6">
                                    <div class="form-group">
                                        <label for="servicio-nombre">Descripcion de la mano de obra:</label>
                                        <textarea type="text" class="form-control" id="servicio-nombre" v-model="servicios.nombre" name="sr_descripcion" value=""></textarea>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <label>Precio Mano de Obra:</label>
                                        <input type="number" class="form-control" v-model="servicios.costo_unidad" placeholder="Precio" @input="calculateTotal_serv" name="sr_precio" value="">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="mb-6">
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <label>Cantidad:</label>
                                        <input type="number" class="form-control" v-model="servicios.cantidad" placeholder="Cantidad" name="sr_cantidad" @input="calculateTotal_serv" value="">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="mb-6">
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <label>Utilidad</label>
                                        <input type="number" class="form-control" id="utilidad" placeholder="Utilidad" v-model="servicios.utilidad">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="mb-6">
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <label>Total:</label>
                                        <input type="text" readonly class="form-control" v-model="servicios.precioTotal" placeholder="total" name="sr_total" value="">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="addServicio">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
        <!--Modal Mano Obra-->

        <!-- Modal Adicionales -->
        <div class="modal fade" id="modal-adicionales" tabindex="-1" role="dialog" aria-labelledby="modalAdicionalesLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAdicionalesLabel">Seleccionar Adicionales</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Adicionales</label>
                                    <select ref="adicionalselect" name="cli_nombre" id="cli_nombre" class="form-control select2" data-index_add="0" style="width: 100%;">
                                        <option value="" selected disabled>Selecciona un adicional</option>
                                        <option v-for="adicionales in adicional"
                                        :data-cotad_precio="adicionales.cotad_precio"
                                        :data-idcotadicionales="adicionales.idcotadicionales"
                                        :data-cotad_nombre="adicionales.cotad_nombre"
                                        :key="adicionales.idcotadicionales"
                                        :value="adicionales.idcotadicionales">
                                        @{{ adicionales.cotad_nombre }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Adicional Seleccionado:</label>
                                        <input type="text" class="form-control" id="adicional-nombre" v-model="currentAdicionales.cotad_nombre" placeholder="nombre del Adicional" name="ad_nombre" value="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Precio:</label>
                                        <input type="number" class="form-control" id="adicional-precio" v-model="currentAdicionales.cotad_precio" placeholder="precio del Adicional" name="ad_precio" value="" @input="calculateTotal_add">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Cantidad:</label>
                                        <input type="number" class="form-control" id="adicional-cantidad" v-model="currentAdicionales.cotad_cantidad" placeholder="cantidad de Adicionales" name="ad_cantidad" value="" @input="calculateTotal_add">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Total:</label>
                                        <input readonly type="number" class="form-control" id="adicional-total" v-model="currentAdicionales.cotad_total"  name="ad_total" value="">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" class="btn btn-success" @click="addAdicional">Agregar</button>
                                </div>

                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Total</th>
                                                    <th>Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(adicionales, index_add) in adicionalesList" :key="adicionales.idcotadicionales">
                                                    <td>@{{ adicionales.cotad_nombre }}</td>
                                                    <td>
                                                        <input type="number" class="form-control" v-model="adicionales.cotad_cantidad" @input="calculateTotal_add(index_add)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" v-model="adicionales.cotad_precio" @input="calculateTotal_add(index_add)">
                                                    </td>
                                                    <td>@{{ adicionales.cotad_total }}</td>
                                                    <td><button type="button" class="btn btn-danger" @click="removeAdicional(index_add)">Eliminar</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="saveAdicionales">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
 <!-- FIn Modal Adicionales -->

        <!-- /.Tabla Productos -->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Proveedor</th>
                                    <th>Unidad de Medición</th>
                                    <th>Precio Unitario</th>
                                    <th>Precio desperdicio</th>
                                    <th>Precio Adicional</th>
                                    <th>Cantidad</th>
                                    <th>Precio Total</th>
                                    <th>Acciones</th>
                                    <th>Moneda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(producto, index) in productosList" :key="index">
                                    <td>@{{ producto.nombre }}</td>
                                    <td>@{{ producto.marca }}</td>
                                    <td>@{{ producto.modelo }}</td>
                                    <td>@{{ producto.proveedor }}</td>
                                    <td>@{{ producto.unit_med }}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="producto.costo_unidad" @input="calculateTotal(index)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model="producto.costo_desperdicio" @input="calculateTotal(index)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model="producto.costo_adicionales" @input="calculateTotal(index)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model="producto.cantidad" @input="calculateTotal(index)">
                                    </td>
                                    <td>@{{ producto.precioTotal }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="removeProducto(index)">Eliminar</button>
                                    </td>
                                    <td>
                                        @{{ producto.moneda}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        <!-- /.Fin Tabla Productos -->

                <div class="col-md-12">
                    <center><h3>Servicios</h3></center>
                </div>
                <br>
        <!--./tabla servicio-->
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Cantidad Total</th>
                                    <th>Adicionales</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(servicio, index_serv) in serviciosList" :key="servicio.idcotadicionales">
                                    <td>@{{ servicio.nombre }}</td>
                                    <td>
                                        <input type="number" class="form-control" v-model="servicio.cantidad" @input="calculateTotal_serv(index_serv)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" v-model="servicio.costo_unidad" @input="calculateTotal_serv(index_serv)">
                                    </td>
                                    <td>@{{ servicio.precioTotal }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" @click="openModal(servicio, index_serv)">Añadir</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="removeServicio(index_serv)">Eliminar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        <!--fin tabla servicio-->
        <br>
        <!--Boton Guardar Cotizacion-->
        <button type="button" class="btn btn-success" v-show="!cotizando && (productosList.length > 0 || serviciosList.length > 0)" @click="enviarForm($event)">Guardar Cotizacion</button>
        <button class="btn btn-success" type="button" v-show="cotizando" disabled>
            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
            <span role="status">Guardando...</span>
        </button>
        <br>
        <!--Fin Boton Guardar Cotizacion-->

        <!-- /.fin de cotizacion -->
            </form>

            <!-- /.inicio footer de la card -->
            <div class="card-footer">
                <p>Al generar una cotizacion te redireccionara a un apartado para descargarla en formato pdf</p>
            </div>
            <!-- /.fin footer de la card -->

        </div>
     <!-- /.fin del body de la card -->
    </div>
     <!-- /.fin de formulario principal -->

     <!-- /.inicio de formulario inicial -->
    <div id="form_utilidad" v-show="!estadoform">
        <h5>Seccion de datos adicionales</h5>
        <div class="card-body">
            <h5>Que vas a cotizar?</h5>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label>Coloca el tipo de cambio</label>
                        <input type="numeric" class="form-control"  v-model="tipo_cambio"  id="tipo_cambio" placeholder="Valor del dolar">
                        <br>
                        <p>*El valor del dolar es el obtenido del banco de mexico + 0.60 centavos*</p>
                        <p>*En caso de requerir decimales, colocalos en el formato 20.95*</p>
                        <p>*No colocar el simbolo "$"*</p>
                        <p>*EL boton actualizar valor, actualiza el valor del dolar*</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" @click="cotizar_sig">Siguiente</button>
                        <button type="button" class="btn btn-success" @click="actualizarValor">actualizar valor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- /.fin de formulario inicial -->
</div>
<!-- /.fin de la card -->

@endsection

@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                show_detalle: false,
                isProducto: false,
                estadoform: false,
                reg_nuevo_user: false,
                cotizando: false,
                crm: '',
                tipo_cambio:'',
                productosList: [],
                serviciosList: [],
                adicionalesList:[],
                currentProducto: {
                    clave: 0,
                    nombre: '',
                    marca: '',
                    modelo: '',
                    proveedor: '',
                    id: '',
                    costo_unidad: 0,
                    costo_desperdicio: 0,
                    costo_adicionales: 0,
                    cantidad: 1,
                    tipo_cambio: '',
                    utilidad: '0.25',
                    unit_med: '',
                    moneda:'',
                    isDollar: false,
                    iva: 0,
                    tipo:'PR'
                },
                index_adicional: null,
                servicios: {
                    id: 10,
                    nombre: '',
                    cantidad: 0,
                    costo_unidad: 0,
                    costo_desperdicio: 0,
                    costo_adicionales: 0,
                    utilidad:'0.35',
                    unit_med: 'SRV',
                    tipo_cambio: '',
                    moneda:'MXN',
                    precioTotal: 0,
                    adicionales: [],
                    tipo: 'SR'
                },
                currentAdicionales: {
                    idcotadicionales: '',
                    cotad_nombre: '',
                    cotad_cantidad: 1,
                    cotad_precio: 0,
                    cotad_total: 0
                },
                adic_precio: 0,
                idcliente: '',
                cliente: '',
                cli_empresa: '',
                cli_puesto:'',
                cli_telefono: '',
                cli_email: '',
                idconcepto: '',
                cot_concepto: '',
                cot_encabezado: ''
                ,productos:<?php echo json_encode($productos);?>
                ,clientes:<?php echo json_encode($clientes);?>
                ,asesor:<?php echo json_encode($asesor);?>
                ,utilidad:<?php echo json_encode($utilidad);?>
                ,adicional:<?php echo json_encode($adicional);?>
                ,conceptos:<?php echo json_encode($conceptos);?>
            },
            mounted() {
                this.obtener_valor_dolar();
                $(function () {
                    // Inicializa el select2 de clientes
                    //Inicia Select2 Elements
                    $('.select2cliente').select2()

                    //Initialize Select2 Elements
                    $('.select2bs4').select2({
                    theme: 'bootstrap4'
                    })
                });

                $('#modal-productos').on('shown.bs.modal', function () {
                    //inicializa el select2 de productos, de esta forma xq esta dentro de un modal
                    $('.select2').select2({
                        dropdownParent: $('#modal-productos')
                    });
                });

                // destruye y vuelve a inicializar select2 para evitar conflictos
                $('#modal-productos').on('hidden.bs.modal', function () {
                    $('.select2').each(function () {
                        if($(this).hasClass('select2-hidden-accessible')) {
                            $(this).select2('destroy')
                        }
                    });
                });

                // Maneja el evento 'change' de select2
                $(this.$refs.productoSelect).on('change', this.selectProducto);
                $(this.$refs.clienteselect).on('change', this.selectCliente);
                $(this.$refs.adicionalselect).on('change', this.selectAdicional);
                $(this.$refs.textoselect).on('change', this.selectTexto);
            },
            methods: {
                obtener_valor_dolar() {
                    axios.get('/api/dolar/obtener')
                    .then(response => {
                        // El valor de la serie contiene la tasa de cambio del dólar.
                        const valorDolar = response.data.valorDolar;
                        console.log(`El valor del dólar es: ${valorDolar}`);
                        this.tipo_cambio = valorDolar;
                    })
                    .catch(error => {
                        console.error('Error al obtener el valor del dólar:', error);
                        Swal.fire({
                            title:'Error al obtener el valor del dolar',
                            text: 'Hubo un error al obtener el tipo de cambio, se tendra que ingresar manual. porfavor notificame de este error',
                            icon: 'error'
                        });
                    });
                },
                cotizar_sig: function() {
                    if( this.tipo_cambio != '' ) {
                        this.estadoform = true;
                    }
                    else {
                        Swal.fire({
                        title: "Hubo Un Error",
                        text: "La seccion de datos iniciales son importantes para continuar con la cotizacion, asegurate de llenarlos",
                        icon: "error"
                        });
                    }
                },
                actualizarValor() {
                    axios.get('/api/valor-dolar')
                    .then(response => {
                        // El valor de la serie contiene la tasa de cambio del dólar.
                        const valorDolar = parseFloat(response.data.bmx.series[0].datos[0].dato);
                        this.tipo_cambio = valorDolar + .60;

                        if(valorDolar > 0) {
                            datos = {
                                tipo_cambio: this.tipo_cambio
                            };
                            axios.post('/api/dolar/save', datos).then(response => {
                                console.log('nuevo valor => ' + response.data.valor);
                                this.tipo_cambio = '';
                                this.obtener_valor_dolar();
                            }).catch(error => {
                                console.log('este es el error que brinda el servidor => ' + error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Hubo un error al guardar el valor del tipo de cambio, pero no te preocupes sigue con tu proceso, ingresa el valor de forma manual. No olvides notificarme del error',
                                    icon:'error'
                                });
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener el valor del dólar:', error);
                        Swal.fire({
                            title:'Error al obtener el valor del dolar',
                            text: 'Hubo un error al obtener el tipo de cambio, se tendra que ingresar manual. porfavor notificame de este error',
                            icon: 'error'
                        });
                    });
                },
                selectCliente(event){
                    this.idcliente = this.$refs.clienteselect.value;
                    const selectedOption = event.target.selectedOptions[0];
                    this.cliente = selectedOption.dataset.nombre;
                    this.cli_puesto = selectedOption.dataset.puesto;
                    this.cli_telefono = selectedOption.dataset.telefono;
                    this.cli_email = selectedOption.dataset.email;
                    this.cli_empresa = selectedOption.dataset.empresa;
                },
                selectTexto(event){
                    const selectedOption = event.target.selectedOptions[0];
                    this.cot_concepto = selectedOption.dataset.texto;
                    this.idconcepto = selectedOption.value;
                },
                selectProducto(event, index) {
                    // metodo lee y completa datos al seleccionar un producto
                    const selectedOption = event.target.selectedOptions[0];
                    this.currentProducto.clave = selectedOption.dataset.cve_producto;
                    this.currentProducto.nombre = selectedOption.dataset.nombre;
                    this.currentProducto.marca = selectedOption.dataset.marca;
                    this.currentProducto.modelo = selectedOption.dataset.modelo;
                    this.currentProducto.proveedor = selectedOption.dataset.proveedor;
                    this.currentProducto.id = selectedOption.dataset.id_pr;
                    this.currentProducto.unit_med = selectedOption.dataset.unit_med;
                    this.currentProducto.costo_unidad = parseFloat(selectedOption.dataset.costo_unidad);
                    this.currentProducto.iva = 0; // Reset IVA to default
                    this.calculateTotal(index);
                },
                selectAdicional(event, index_add) {
                    const selectedOption = event.target.selectedOptions[0];
                    this.currentAdicionales.idcotadicionales = selectedOption.dataset.idcotadicionales;
                    this.currentAdicionales.cotad_nombre = selectedOption.dataset.cotad_nombre;
                    this.currentAdicionales.cotad_precio = parseFloat(selectedOption.dataset.cotad_precio);
                    this.calculateTotal_add(index_add);
                },
                // Productos
                calculateTotal(index) {
                    // calcula el precio total del producto tomando en cuenta todos los datos y si esta o no en dolares
                    const producto = this.productosList[index] || this.currentProducto;
                    var precio = 0;
                    let unidad = parseFloat(producto.costo_unidad);
                    let desperdicio = parseFloat(producto.costo_desperdicio);
                    let adicion = parseFloat(producto.costo_adicionales);
                    if ( producto.isDollar == true) {
                        precio_1 = unidad + desperdicio + adicion;
                        precio = precio_1 * this.tipo_cambio;
                    }
                    else {
                        precio = unidad + desperdicio + adicion;
                    }
                    producto.precioTotal = (producto.cantidad * precio).toFixed(2);
                },
                addProducto() {
                    let errorMessage = '';

                    if (!this.currentProducto.nombre) {
                        errorMessage = "El nombre del producto no puede estar vacío.";
                    } else if (this.currentProducto.costo_unidad <= 0) {
                        errorMessage = "El precio unitario debe ser mayor que cero.";
                    } else if (!this.currentProducto.unit_med) {
                        errorMessage = "La unidad de medición no puede estar vacía.";
                    }
                    if (errorMessage) {
                        Swal.fire({
                        title: "Hubo Un Error",
                        text: errorMessage,
                        icon: "error"
                        });
                    }
                    else{
                        this.calculateTotal();
                        if(this.currentProducto.isDollar == true){
                            this.currentProducto.moneda = 'USD';
                            this.currentProducto.costo_u_document = this.currentProducto.costo_unidad * this.tipo_cambio;
                        }
                        else{
                            this.currentProducto.moneda = 'MXN';
                            this.currentProducto.costo_u_document = this.currentProducto.costo_unidad;
                        }
                        if (this.currentProducto.clave < 0 || this.currentProducto.clave == null){
                            this.currentProducto.clave = 0;
                        }
                        if ( this.currentProducto.id == null) {
                            this.currentProducto.id = 0;
                        }
                        this.currentProducto.tipo_cambio = this.tipo_cambio;
                        this.productosList.push({...this.currentProducto});
                        this.resetCurrentProducto();
                        console.log(this.productosList);
                        $('#modal-productos').modal('hide');
                    }
                },
                removeProducto(index) {
                    this.productosList.splice(index, 1);
                },
                resetCurrentProducto() {
                    // resetea los valores de producto para insertar el iguiente
                    this.currentProducto = {
                        clave: 0,
                        nombre: '',
                        marca: '',
                        modelo: '',
                        proveedor: '',
                        id: '',
                        precio_dolar: 0,
                        costo_unidad: 0,
                        costo_desperdicio: 0,
                        costo_adicionales: 0,
                        cantidad: 1,
                        tipo_cambio: '',
                        utilidad: '0.25',
                        unit_med: '',
                        moneda:'',
                        isDollar: false,
                        iva: 0,
                        tipo:'PR'
                    };
                    $(this.$refs.productoSelect).val(null).trigger('change');
                },
                registrarNuevo() {
                    // registra un nuevo usuario
                    datos = {
                        operacion: 'Agregar',
                        cliente: this.cliente,
                        cli_empresa: this.cli_empresa,
                        cli_puesto: this.cli_puesto,
                        cli_telefono: this.cli_telefono,
                        cli_email: this.cli_email
                    };

                    let errorMessage = '';

                    if (!this.cliente) {
                        errorMessage = "El nombre del cliente no puede estar vacío.";
                    } else if (!this.cli_empresa) {
                        errorMessage = "La empresa no puede estar vacío.";
                    } else if (!this.cli_puesto) {
                        errorMessage = "El pusto no puede estar vacío.";
                    } else if (!this.cli_telefono) {
                        errorMessage = "El telefono no puede estar vacío.";
                    } else if (!this.cli_email) {
                        errorMessage = "El email no puede estar vacío.";
                    }
                    if (errorMessage) {
                        Swal.fire({
                        title: "Hubo Un Error",
                        text: errorMessage,
                        icon: "error"
                        });
                    }
                    else {
                        axios.post('/catalogos/clientes/save', datos).then(response => {
                            Swal.fire({
                               title: 'Agregado!!!',
                               text: response.data.mensaje,
                               icon: 'success'
                            }).then(result => {
                                this.reg_nuevo_user = false;
                                this.idcliente = response.data.idcliente;
                            });
                        }).catch(error => {
                            console.log('Este es el error que brinda el servidor => ' + error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un error al guardar el cliente, por favor contactame',
                                icon: 'error'
                            });
                        });
                    }
                },
                // Servicios
                addServicio() {
                    if(this.servicios.nombre !== ''){
                        this.servicios.tipo_cambio = this.tipo_cambio;
                        this.servicios.costo_u_document = this.servicios.precioTotal;
                        this.serviciosList.push({...this.servicios});
                        this.resetservicios();
                        $('#modal-lg').modal('hide');
                    }else {
                        Swal.fire({
                                    title: "Error",
                                    text: "Los campos no pueden estar vacios",
                                    icon: "error"
                                })
                    }
                },
                resetservicios() {
                    // restaura los valores para agregar el siguiente
                    this.servicios = {
                        id: 10,
                        nombre: '',
                        cantidad: 0,
                        costo_unidad: 0,
                        costo_desperdicio: 0,
                        costo_adicionales: 0,
                        utilidad:'0.35',
                        unit_med: 'SRV',
                        moneda:'MXN',
                        precioTotal: 0,
                        adicionales: [],
                        tipo: 'SR'
                        };
                        this.index_adicional = null;
                        this.adicionalesList = [];
                },
                removeServicio(index_serv) {
                    this.serviciosList.splice(index_serv, 1);
                },
                calculateTotal_serv(index_serv) {
                    // metodo para actualizar el total de los servicios
                    const servicio = this.serviciosList[index_serv] || this.servicios;
                    servicio.precioTotal = servicio.cantidad * servicio.costo_unidad;
                    if (servicio.adicionales && servicio.adicionales.length > 0) {
                        // El precio actualiza si se agregan adicionals
                        servicio.adicionales.forEach(adicional => {
                            servicio.precioTotal += parseFloat(adicional.cotad_total);
                        });
                        servicio.costo_u_document = servicio.precioTotal;
                    }
                },
                // Adicionales
                openModal(servicio, index_serv) {
                    this.index_adicional = index_serv;
                    // this.adicionalesList = servicio.adicionales.map(adicional => adicional.idcotadicionales);
                    this.adicionalesList = servicio.adicionales.map(adicional => ({
                        ...adicional
                    }));
                    $('#modal-adicionales').modal('show');
                },
                saveAdicionales() {
                    // agrega los adicionales al servicio en cuestion
                    if (this.index_adicional !== null) {
                        const servicio = this.serviciosList[this.index_adicional];
                        // servicio.adicionales = this.adicionalesList.map(idcotadicionales => {
                        //     return this.adicional.find(adicional => adicional.idcotadicionales === idcotadicionales);
                        // });
                        servicio.adicionales = this.adicionalesList.map(adicional => {
                            return {
                                ...adicional,
                                cotad_cantidad: adicional.cotad_cantidad || 1,
                                cotad_precio: adicional.cotad_precio || 0,
                                cotad_total: (adicional.cotad_cantidad * adicional.cotad_precio).toFixed(2)
                            };
                        });
                        this.calculateTotal_serv(this.index_adicional);
                        this.resetservicios();
                    }
                    $('#modal-adicionales').modal('hide');
                },
                addAdicional() {
                    if(this.index_adicional !== null) {
                        if (this.currentAdicionales.cotad_nombre !== '' && this.currentAdicionales.cotad_cantidad !== 0 && this.currentAdicionales.cotad_precio !== 0) {
                            this.adicionalesList.push({...this.currentAdicionales});
                            this.currentAdicionales = {
                            idcotadicionales: '',
                            cotad_nombre: '',
                            cotad_cantidad: 1,
                            cotad_precio: 0,
                            cotad_total: 0
                            }
                            $(this.$refs.adicionalselect).val(null).trigger('change');
                        }
                        else{
                            Swal.fire({
                            title: "Hubo Un Error",
                            text: "Los campos no pueden estar vacios",
                            icon: "error"
                            });
                        }

                    }
                },
                calculateTotal_add(index_add){
                    const adicional = this.adicionalesList[index_add] || this.currentAdicionales;
                    adicional.cotad_total = (adicional.cotad_cantidad * adicional.cotad_precio).toFixed(2);
                },
                removeAdicional(index_add) {
                    this.adicionalesList.splice(index_add, 1);
                },
                // Formulario
                enviarForm(event){
                    if ( this.cliente !== '' && this.cli_empresa !== '' && this.cli_puesto !== '' && this.crm !== '' && this.cot_concepto !== '') {

                        this.cotizando = true;
                        let datos = {
                            productos: this.productosList,
                            servicios: this.serviciosList,
                            idconcepto: this.idconcepto,
                            idcliente: this.idcliente,
                            cot_nombre_cli: this.cliente,
                            cot_concepto: this.cot_concepto,
                            cot_encabezado: this.cot_encabezado,
                            cot_empresa_cli: this.cli_empresa,
                            cot_puesto_cli: this.cli_puesto,
                            cot_telefono_cli: this.cli_telefono,
                            cot_correo_cli: this.cli_email,
                            crm: this.crm,
                            show_detalle: this.show_detalle,
                        }

                        Swal.fire({
                            title: "Estas Seguro de guardar?",
                            text: "Al darle aceptar por el momento ya no se podra modificar",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Si, Guardar!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post('/cotizacion/guardar', datos)
                                .then(response => {
                                    if (response.data.status == 'OK') {
                                        Swal.fire({
                                            title: "Guardado",
                                            text: response.data.message,
                                            icon: "success"
                                        }).then(() => {
                                            if (response.data.file) {
                                                window.location.href = response.data.file;
                                            }
                                            setTimeout(() => {
                                                window.location.href = "{{url('/cotizacion/listado')}}";
                                            }, 2000);
                                        });
                                    }
                                    else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.data.message,
                                            icon: "error"
                                        });
                                    }
                                    this.cotizando = false;
                                })
                                .catch(error => {
                                    console.error('Error al enviar la cotización:', error);
                                    this.cotizando = false;
                                    Swal.fire({
                                        title: "Error",
                                        text: "Hubo un error al guardar la cotización.",
                                        icon: "error"
                                    });
                                });
                            }
                            else {
                                this.cotizando = false;
                            }
                        });

                    }
                    else {
                        event.preventDefault();
                        Swal.fire({
                            title: "Hubo Un Error",
                            text: "No se puede hacer guardar la cotizacion, los campos no pueden estar vacíos",
                            icon: "error"
                            });
                    }
                },

            }
        });
    </script>
@endsection

