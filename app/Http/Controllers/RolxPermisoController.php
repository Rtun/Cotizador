<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\RolxPermiso;
use Illuminate\Http\Request;

class RolxPermisoController extends Controller
{
    function formulario(Request $r){
        $datos=$r->all();
        $info = array();
        $permisos = Permiso::all();
        $asignadas = RolxPermiso::where('idrol',$datos['idrol'])->get();
        $rol = Rol::find($datos['idrol']);

        for ($i = 0; $i < count($permisos); $i++) {
          $bandera=false;
          foreach($asignadas as $elemento){
                  if($elemento->idpermiso == $permisos[$i]->idpermiso) {
                    $bandera=true;
                  }
          }
          $permisos[$i]->asignada = $bandera;
        }

        $info['permisos'] = $permisos;
        $info['rol'] = $rol;

        return view('admin.form_rolxpermiso')->with($info);
  }

  function save(Request $r){
    $datos=$r->all();

    switch ($datos['operacion']) {
        case 'Guardar':
            //borrar todos los permisos del rol seleccionado
            RolxPermiso::where('idrol',$datos['idrol'])->delete();

            //validacion cuando no existe el permiso
            if(isset($datos['idpermiso'])){

            //insertar los permisos de la peticion
            foreach($datos['idpermiso'] as $permiso){
                $matxtip=new RolxPermiso();
                $matxtip->idpermiso=$permiso;
                $matxtip->idrol=$datos['idrol'];
                $matxtip->save();
            }
            }
            session()->forget('permisosUsuario');

            return $this->formulario($r);
            break;

        default:
            return redirect()->to('/admin/listado/roles');
            break;
    }

  }
}
