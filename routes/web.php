<?php

use App\Http\Controllers\AdicionalesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReunionesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RolxPermisoController;
use App\Http\Controllers\TextosController;
use App\Mail\ReunionMailable;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

// Route::get('/', function () {
//     return view('home');
// })->middleware('auth');
Route::get('/agregar/productos', [GlobalController::class, 'guardar_prods']);
Route::middleware(['auth', 'auth.status'])->group(function () {
Route::get('/pruebas',[PruebaController::class, 'index'])->name('prueba');
Route::get('/', [LoginController::class, 'inicio'])->name('inicio');
Route::get('/perfil/usuario', [LoginController::class, 'perfilData'])->name('perfil');
Route::post('/perfil/actualizar/datos', [RegisterController::class, 'actualizar_datos'])->name('perfil.actualizar');

//Buscador
Route::get('/buscador', [BuscadorController::class, 'buscador'])->middleware('candado:BUSCADOR')->name('buscadorGeneral');

//Cotizaciones
Route::get('/cotizacion/listado', [CotizacionController::class, 'listado_cotizaciones'])->middleware('candado:COTIZACIONES')->name('cotizacion.listado');
Route::get('/cotizacion/listado/{crm}', [CotizacionController::class, 'cotizacion_x_crm'])->middleware('candado:COTIZACRM')->name('cotizacion.listado_crm');
Route::get('/cotizacion/listado/detalle/{idcotizacion}', [CotizacionController::class, 'cotizacion_detalle'])->middleware('candado:COTDETALLE')->name('cotizacion.listado_crm_detalle');
Route::match(['get','post'],'/cotizacion', [CotizacionController::class, 'showForm'])->middleware('candado:FORMCOTIZA')->name('cotizacion.showForm');
Route::get('/cotizacion/editar/{idcotizacion}', [CotizacionController::class, 'showForm_editar'])->middleware('candado:COTEDITAR')->name('cotizacion.showForm_editar');
Route::post('/cotizacion/guardar', [CotizacionController::class, 'save_form'])->name('cotizacion.save_form');
Route::post('/cotizacion/finalizar', [CotizacionController::class, 'cerrar_cotizacion'])->middleware('candado:COTFINALIZAR')->name('cotizacion.cerrar');
Route::post('/cotizacion/actualizar/precios', [CotizacionController::class, 'actualizar_precios'])->middleware('candado:COTACTPRECIOS');
Route::get('/cotizacion/obtener_clientes', [CotizacionController::class, 'getClientes']);
Route::post('/cotizacion/rechazar', [CotizacionController::class, 'rechazar_cotizaciones']);
Route::get('/cotizacion/refresh-prods', [CotizacionController::class, 'refresh_prods'])->name('cotizacion.refresh');
Route::get('/cotizacion/prueba', [CotizacionController::class, 'prueba']);

// catalogos clientes
Route::get('/catalogos/listado/clientes', [ClienteController::class, 'show_clientes'])->middleware('candado:CLIENTES')->name('catalogos.listado_clientes');
Route::match(['get', 'post'], '/catalogos/clientes', [ClienteController::class, 'show_form'])->middleware('candado:FORMCLIENTE')->name('catalogos.show_form');
Route::post('/catalogos/clientes/save', [ClienteController::class, 'save_form'])->name('catalagos.clientessave');

//catalogo productos
Route::get('/catalogos/listado/productos', [ProductosController::class, 'listado'])->middleware('candado:PRODUCTOS')->name('catalogos.listado_productos');
Route::match(['get', 'post'],'/catalogos/form-productos', [ProductosController::class, 'form_productos'])->middleware('candado:FORMPRODUCTO')->name('catalogos.form_productos');
Route::post('/catalogos/form-produdctos/save', [ProductosController::class, 'save_productos'])->name('catalogos.save_productos');
Route::get('/catalogos/buscar/productos', [ProductosController::class, 'buscarProductos'])->name('catalogos.buscar_productos');
Route::post('/catalogos/productos/api/save', [ProductosController::class, 'api_save_producto'])->name('catalogos.prod_api_save');

// catalogos adicionales
Route::get('/catalogos/listado/adicionales', [AdicionalesController::class, 'listado'])->middleware('candado:ADICIONALES')->name('catalogos.listado_adicionales');
Route::match(['get','post'], '/catalogos/form-adicionales', [AdicionalesController::class,'form_adicionales'])->middleware('candado:FORMADICIONALES')->name('catalogos.form_adicionales');
Route::post('catalogos/form-adicionales/save', [AdicionalesController::class, 'save_adicionales'])->name('catalogos.save_adicionales');

// catalogo conceptos
Route::get('/catalogos/listado/conceptos', [TextosController::class, 'listado'])->middleware('candado:CONCEPTOS')->name('catalogos.listado_conceptos');
Route::match(['get', 'post'], '/catalogos/conceptos', [TextosController::class, 'showForm'])->middleware('candado:FORMCONCEPTOS')->name('catalogos.conceptos');
Route::post('/catalogos/conceptos/save',[ TextosController::class, 'saveForm'])->name('catalogos.ConceptossaveForm');

// catalogo marcas
Route::get('/catalogos/listado/marcas', [MarcaController::class, 'show_marcas'])->middleware('candado:MARCAS')->name('catalogos.listado_marcas');
Route::match(['get', 'post'], '/catalogos/marcas', [MarcaController::class, 'show_form'])->middleware('candado:FORMMARCAS')->name('catalogos.marcas');
Route::post('/catalogos/marcas/save', [MarcaController::class, 'save_form'])->name('catalogos.saveMarcas');

// catalogos proveedores
Route::get('/catalogos/listado/proveedores', [ProveedorController::class, 'show_proveedores'])->middleware('candado:PROVEEDORES')->name('catalogos.listado_proveedores');
Route::match(['get', 'post'], '/catalogos/proveedores', [ProveedorController::class, 'show_form'])->middleware('candado:FORMPROVEEDORES')->name('catalogos.proveedores');
Route::post('/catalogos/proveedores/save', [ProveedorController::class, 'save_form'])->name('catalogos.saveProveedores');

// catalogo salas/reuniones
Route::get('/catalogos/listado/salas', [ReunionesController::class, 'listado_salas'])->middleware('candado:SALAS')->name('catalogos.listado_salas');
Route::match(['get', 'post'],'/catalogos/salas', [ReunionesController::class, 'show_form_salas'])->middleware('candado:FORMSALAS')->name('catalogos.form_salas');
Route::post('/catalogos/salas/save', [ReunionesController::class, 'save_salas'])->name('catalogos.save_salas');
Route::get('/catalogos/calendario', [ReunionesController::class, 'show_calendario'])->middleware('candado:CALENDARIO')->name('catalogos.calendario');
Route::post('/catalogos/calendario/save', [ReunionesController::class, 'save_reunion'])->middleware('candado:FORMCALENDARIO')->name('catalogos.calendario_save');
Route::get('/catalogos/obtener/eventos', [ReunionesController::class, 'get_Evento'])->name('catalogos.obtener_evento');
Route::post('/catalogos/calendario/evento/eliminar', [ReunionesController::class, 'delete_event'])->name('catalogos.eliminar_evento');

// rutas de descargas
Route::get('/descargar-cotizacion/{file}', function ($file) {
    // Decodificar el nombre del archivo
    $decodedFilename = urldecode($file.'.xlsx');
    $path = storage_path().'/app/documentos/' . $decodedFilename;
    // dd(scandir(storage_path('app/documentos')));
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->download($path);
});

//Mails
Route::post('/enviar/cotizacion/{idcotizacion}', [MailController::class, 'enviar_cotizacion'])->name('enviar.cotizacion');
Route::get('/reuniones', function() {
    $usuarios = User::where('status', 'AC')->get();
    foreach ($usuarios as $user) {
        if($user->idrol == 1) {
            $subject = 'Administrador';
        }
        else {
            $subject = 'No es administrador';
        }
        $context = new \stdClass();
        $context->usuario = $user->name;
        $context->email = $user->email;
        $context->contenido = 'Este es el contenido solo te notificamos que hay una nueva reunion agendada';
        Mail::to($user->email)->send(new ReunionMailable($context, $subject));
    }
    return 'Mensajes enviados';
});

//Rutas de administrador
// usuarios
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth.admin')->name('admin.index');
Route::get('/admin/listado/usuarios', [AdminController::class, 'show_usuarios'])->middleware('auth.admin')->name('admin.listado_usuario');
Route::get('/admin/catalogos/usuario/{idusuario}', [AdminController::class, 'consultar_usuario'])->middleware('auth.admin')->name('admin.search_usuario');
Route::post('/admin/catalogos/usuario/actualizar', [AdminController::class, 'update_usuario'])->middleware('auth.admin')->name('admin.usuario_update');

// roles
Route::get('/admin/listado/roles', [RolController::class, 'show_roles'])->middleware('auth.admin')->name('admin.listado_roles');
Route::match(['get', 'post'],'/admin/roles', [RolController::class, 'show_form'])->middleware('auth.admin')->name('admin.roles');
Route::post('/admin/roles/save', [RolController::class, 'save_form'])->middleware('auth.admin')->name('admin.roles_save');
Route::get('/admin/usuarios/filtrar/{idrol}', [RolController::class, 'filter_user'])->middleware('auth.admin')->name('admin.filtrar_user');
Route::get('/admin/roles/permisos', [RolxPermisoController::class, 'formulario'])->middleware('auth.admin')->name('admin.permisos');
Route::post('/admin/roles/permisos/save', [RolxPermisoController::class, 'save'])->middleware('auth.admin')->name('admin.rolxpermiso');
});

//Globalcontroller
Route::get('/api/dolar/obtener', [GlobalController::class, 'valor_dolar']);
Route::post('/api/dolar/save', [GlobalController::class, 'valor_dolar_save']);

//Rutas de login y registro => guest sirve para validar que si estas logueado no te redirija nuevamente al login o registro
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login.index');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest')->name('login.store');
Route::get('/logout', [LoginController::class, 'destroy'])->name('login.destroy');
Route::get('/registrarse', [RegisterController::class, 'index'])->middleware('guest')->name('register.index');
Route::post('/registro', [RegisterController::class, 'store'])->middleware('guest')->name('register.store');

//pruebas
Route::get('/check-memory-limit', function () {
    // return ini_get('memory_limit');
    phpinfo();
});

//}Apis que no requieren iniciar sesion para funcionar
Route::get('/api/valor-dolar', function () {
    $token = '43695a51107d9fabe57589a32c7498776c286be5954b5031b06989acf74c173c';
    $response = Http::withHeaders([
        'Bmx-Token' => $token,
    ])->get('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno');

    return $response->json();
});
