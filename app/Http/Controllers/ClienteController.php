<?php

namespace App\Http\Controllers;

use App\Models\Cot_Clientes;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function show_clientes() {
        $clientes = Cot_Clientes::all();

        $informacion = [
            'clientes' => $clientes
        ];

        return view('listados.listado_clientes')->with($informacion);
    }

    public function show_form(Request $r) {
        $context = $r->all();

        if($r->isMethod('post')) {
            $cliente = new Cot_Clientes();
            $operacion = 'Agregar';
        }
        else {
            $cliente = Cot_Clientes::find($context['idcliente']);
            $operacion = 'Editar';
        }

        $informacion = [
            'clientes' => $cliente,
            'operacion' => $operacion
        ];

        return view('catalogos.form_clientes')->with($informacion);
    }

    public function save_form(Request $r) {
        $context = $r->all();
        $estatus = '';
        $mensaje = '';
        $idcliente = '';

        switch ($context['operacion']) {
            case 'Agregar':
                $cliente = new Cot_Clientes();
                $cliente->cli_nombre = $context['cliente'];
                $cliente->cli_telefono = $context['cli_telefono'];
                $cliente->cli_correo = $context['cli_email'];
                $cliente->cli_empresa = $context['cli_empresa'];
                $cliente->cli_puesto = $context['cli_puesto'];
                $cliente->accountname = $context['cliente'];
                $cliente->save();
                $estatus = 'OK';
                $mensaje = 'El cliente se a registrado con exito!';
                $idcliente = $cliente->idclientes;
                break;
            case 'Editar':
                $cliente = Cot_Clientes::find($context['idcliente']);
                $cliente->cli_nombre = $context['cliente'];
                $cliente->cli_telefono = $context['cli_telefono'];
                $cliente->cli_correo = $context['cli_email'];
                $cliente->cli_empresa = $context['cli_empresa'];
                $cliente->cli_puesto = $context['cli_puesto'];
                $cliente->accountname = $context['cliente'];
                $cliente->save();
                $estatus = 'OK';
                $mensaje = 'Los datos de de '.$cliente->cli_nombre. ' fueron editados con exito!';
                $idcliente = $cliente->idclientes;

                break;
            case 'Eliminar':
                $cliente = Cot_Clientes::find($context['idcliente']);
                $cliente->status = 'BJ';
                $cliente->save();
                $estatus = 'OK';
                $mensaje = 'Se le dio de baja al cliente '.$cliente->cli_nombre.' con exito!';
                $idcliente = $cliente->idclientes;
                break;
        }

        if($estatus == 'OK') {
            $parametros = [
                'tabla' => 'cot_clientes',
                'objeto_modificado' => $idcliente,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }


        if(isset($context['formulario']) || $context['operacion'] == 'Cancelar') {
            return redirect()->to('/catalogos/listado/clientes');
        }
        else {
            return response()->json([
                'estatus' => $estatus,
                'mensaje' => $mensaje,
                'idcliente' => $idcliente
            ]);
        }
    }
}
