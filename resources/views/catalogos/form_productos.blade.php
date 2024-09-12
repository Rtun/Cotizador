@extends('layouts.app')

@section('content_tittle')
    Agregar Producto
@endsection


@section('content')
<div class="card card-default" id="app">
    <div class="card-header">
        <h3 class="card-tittle">Formulario Productos</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ url('/catalogos/form-produdctos/save') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <input type="hidden" class="form-control" id="idproducto" name="idproducto" value="{{ $producto->idproductos }}">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Clave Producto:</label>
                        <input type="text" class="form-control" v-model="productos.prod_cve_producto" id="cve_prod" name="cve_prod" value="" readonly>
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
            }
        });
    </script>
@endsection
