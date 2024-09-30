<?php

namespace App\Http\Controllers;

use App\Mail\CotizacionMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function enviar_cotizacion ($idcotizacion) {
        $cotizacion = DB::table('cot_encabezado')
                        ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
                        ->join('users', 'users.id', '=', 'cot_encabezado.idusuario')
                        ->select(
                            "idcotizacion",
                            "cot_clientes.cli_nombre as cliente",
                            "cot_clientes.cli_correo as email",
                            "users.name as usuario",
                            "cot_encabezado as encabezado",
                            "cot_fecha_creacion as fecha_creacion",
                            "cot_fecha_cierre as fecha_fin",
                            "cot_prod_cantidad as cantidad",
                            "cot_documento as documento"
                        )->where('idcotizacion', $idcotizacion)
                        ->first();

        $rutaArchivo = storage_path().'/app/documentos/' . $cotizacion->documento.'.xlsx';

        $mail = new \stdClass();

        $mail->nombre_cliente = $cotizacion->cliente;
        $mail->usuario = $cotizacion->usuario;
        $mail->cotizacion_encabezado = $cotizacion->encabezado;
        $mail->fecha_creacion = $cotizacion->fecha_creacion;
        $mail->fecha_cierre = $cotizacion->fecha_fin;
        $mail->cantidad = $cotizacion->cantidad;
        $nombreArchivo = 'Cotizacion_'.$idcotizacion.'_'.$cotizacion->cliente.'_'.time();

        Mail::to('russell.tun@comsitec.com.mx')->send(new CotizacionMailable($mail, $rutaArchivo, $nombreArchivo));
        return 'mensaje enviado';
    }
}
