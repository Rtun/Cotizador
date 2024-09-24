@extends('layouts.app')

@section('content_tittle')
    Agregar Producto
@endsection

@section('modulo')
    Productos
@endsection

@section('styles')
    <style>
        .producto-opcion {
            cursor: pointer; /* Cambia el cursor a un puntero */
            user-select: none; /* Evita la selección de texto */
            transition: background-color 0.3s ease; /* Transición suave para el fondo */
        }

        .producto-opcion:hover {
            background-color: #f0f0f0; /* Cambia el fondo cuando pasas el mouse */
        }
        .resultados-contenedor {
            max-height: 600px; /* Altura máxima para el contenedor */
            overflow-y: auto;  /* Añadir scroll vertical cuando sea necesario */
            border: 1px solid #ddd; /* Añadir un borde para separar visualmente el contenedor */
            padding: 10px; /* Espaciado interno */
        }
    </style>
@endsection


@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Productos @{{formulario}}</h3>
        <div class="card-tools">
            <div class="form-group clearfix">
                @if ($operacion == 'Agregar')
                    <div class="icheck-success d-inline">
                    <input v-model="formulario" type="radio" name="r3" checked="" id="radioSuccess1" value="Local">
                    <label for="radioSuccess1">
                        Local
                    </label>
                    </div>
                    <div class="icheck-success d-inline">
                    <input v-model="formulario" type="radio" name="r3" id="radioSuccess2" value="Syscom">
                    <label for="radioSuccess2">
                        Syscom
                    </label>
                    </div>
                    <div class="icheck-success d-inline">
                    <input v-model="formulario" type="radio" name="r3" id="radioSuccess3" value="Tvc">
                    <label for="radioSuccess3">
                        Tvc
                    </label>
                    </div>
                @endif
              </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ url('/catalogos/form-produdctos/save') }}" method="POST" v-if="formulario == 'Local'">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idproducto" name="idproducto" value="{{ $producto->idproductos }}">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Clave Producto:</label>
                        <input type="text" class="form-control" v-model="productos.prod_cve" id="cve_prod" name="cve_prod" value="" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Producto:</label>
                        <input type="text" class="form-control" id="nombre_prod" name="nombre_prod" v-model="productos.prod_nombre" placeholder="Nombre del producto" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Medición:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" id="medicion_prod" name="medicion_prod" v-model="productos.prod_medicion" placeholder="Medicion (pz, bol, bob, etc....)" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Precio Bruto:</label>
                        <input type="text" class="form-control" id="precio_prod" name="precio_prod" v-model="productos.prod_precio_brut" placeholder="Precio del producto" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="idmarca">Marca:</label>
                        <select v-model="productos.idmarca" class="custom-select rounded-0" id="idmarca" name="idmarca">
                            <option v-for="marca in marcas" :value="marca.idmarca">@{{marca.m_nombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Modelo:</label>
                        <input type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" id="modelo" name="modelo" v-model="productos.modelo" placeholder="Precio del producto" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="idproveedor">Proveedor:</label>
                        <select v-model="productos.idproveedor" class="custom-select rounded-0" id="idproveedor" name="idproveedor">
                            <option v-for="proveedor in proveedores" :value="proveedor.idproveedor">@{{proveedor.prv_nombre}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_prod">Tipo:</label>
                        <select class="custom-select rounded-0" id="tipo_prod" name="tipo_prod">
                            <option selected disabled value="{{$producto->prod_tipo}}">{{ $producto->prod_tipo == $producto->prod_tipo ? $producto->prod_tipo : 'Selecciona una opcion' }}</option>
                            <option value="PRODUCTO">Producto</option>
                            <option value="SERVICIO">Servicio</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" name="operacion" value="{{ $operacion }}">
                    @if ($operacion == 'Editar')
                    <input type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
                    @endif
                    <input type="submit" class="btn btn-warning" name="operacion" value="Cancelar">
                </div>
            </div>
        </form>

        <div class="container-fluid" v-if="formulario == 'Syscom' && productoSeleccionado.length == 0">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form @submit.prevent="searchProducts">
                        <div class="input-group input-group-lg">
                            <input v-model="texto" type="search" class="form-control form-control-lg" placeholder="Escribe el modelo, categoria o nombre del producto" >
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-10 offset-md-1 resultados-contenedor">
                    <div class="list-group" v-show="!buscando">
                        <div class="list-group-item" v-for="prod in productosApi" @click="seleccionarProducto(prod)">
                            <div class="row producto-opcion">
                                <div class="col-auto">
                                    <img class="img-fluid" :src="prod.img_portada" alt="Photo" style="max-height: 160px;">
                                </div>
                                <div class="col px-4">
                                    <div>
                                        <div class="float-right">@{{prod.unidad_de_medida.nombre}}</div>
                                        <h3>@{{prod.titulo}}</h3>
                                        <p class="mb-0"><span class="font-weight-bold">Clave: </span> @{{prod.producto_id}}</p>
                                        <p class="mb-0"><span class="font-weight-bold">Clave SAT: </span> @{{prod.sat_key}}</p>
                                        <p class="mb-0"><span class="font-weight-bold">Modelo: </span> @{{prod.modelo}}</p>
                                        <p class="mb-0"><span class="font-weight-bold">Marca: </span> @{{prod.marca}}</p>
                                        <p class="mb-0"><span class="font-weight-bold">Precio: </span>$USD @{{prod.precios.precio_descuento}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status" v-show="buscando">
                          <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ url('/catalogos/productos/api/save') }}" method="POST" v-if="formulario == 'Syscom' && Object.keys(productoSeleccionado).length > 0">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="key" name="key" value="syscom">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Clave Producto:</label>
                        <input type="text" class="form-control" v-model="productoSeleccionado.producto_id" id="cve_prod" name="cve_prod" value="" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Producto:</label>
                        <input type="text" class="form-control" id="nombre_prod" name="nombre_prod" v-model="productoSeleccionado.titulo" placeholder="Nombre del producto" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Medición:</label>
                        <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" id="medicion_prod" name="medicion_prod" v-model="productoSeleccionado.unidad_de_medida.nombre" placeholder="Medicion (pz, bol, bob, etc....)" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Precio Bruto en dolares:</label>
                        <input type="text" class="form-control" id="precio_prod" name="precio_prod" v-model="productoSeleccionado.precios.precio_descuento" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Marca:</label>
                        <input type="text" class="form-control" id="idmarca" name="idmarca" v-model="productoSeleccionado.marca" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Modelo:</label>
                        <input type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" id="modelo" name="modelo" v-model="productoSeleccionado.modelo" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label >Proveedor:</label>
                        <input type="text" class="form-control" id="idproveedor" name="idproveedor" value="Syscom">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipo_prod">Tipo:</label>
                        <input type="text" class="form-control" id="tipo" name="tipo_prod" value="PRODUCTO">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" name="operacion" value="{{ $operacion }}">
                    <input type="submit" class="btn btn-warning" name="operacion" value="Cancelar">
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
    <script>
        new Vue({
            el:'#app',
            data:{
                productos:<?php echo json_encode($producto);?>,
                marcas:<?php echo json_encode($marcas);?>,
                proveedores:<?php echo json_encode($proveedores);?>,
                formulario: 'Local',
                productosApi: [],
                productoSeleccionado: [],
                texto: '',
                buscando: false
            },
            methods: {
                async searchProducts() {
                    // Petición a la API
                    try {
                        this.buscando = true;
                        const response = await axios.get('/catalogos/buscar/productos', {
                            params: { texto: this.texto, key: 'syscom' }
                        });
                        this.productosApi = response.data.productos;
                        this.buscando = false;
                    } catch (error) {
                        console.error("Error buscando productos", error);
                        Swal.fire({
                            title: 'Hubo un error',
                            text: 'Hubo un error al momento de hacer la peticion, contactame',
                            icon: 'error'
                        });
                    }
                },
                seleccionarProducto(prod) {
                    this.productoSeleccionado = prod;
                },
            },
        });
    </script>
@endsection
