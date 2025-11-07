<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\FormFechasController;
use App\Http\Controllers\principalController;
use App\Http\Controllers\RepresentacionesController;
use App\Http\Controllers\OrganismosController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ProductosterminadosController;
use App\Http\Controllers\InformesController;
use App\Http\Controllers\RegistroInformesController;
use App\Http\Controllers\TodoslosdocumentosController;
use App\Http\Controllers\ControlDeAvancesController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\EtapaController;

use App\Http\Controllers\FechaController;
use App\Http\Controllers\GrupoTrabajoController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\CorreoController;

Route::resource('reunion', ReunionController::class);

// Si solo quieres mostrar la vista directamente
Route::get('/', [GrupoTrabajoController::class, 'index'])->name('index');

//Route::get('/', function () {
//    return view('grupotrabajo.index');
//})->name('index');

// Rutas: routes/web.php
Route::get('/grupos-trabajo/reportes', [GrupoTrabajoController::class, 'reportes'])->name('grupotrabajo.reportes');
// Rutas: routes/web.php (continuación)
Route::post('/grupos-trabajo/reportes/guardar', [GrupoTrabajoController::class, 'guardarReporte'])->name('grupotrabajo.guardarReporte');
Route::delete('/grupos-trabajo/reportes/{anio}/{bimestre}', [GrupoTrabajoController::class, 'eliminarReporte'])->name('grupotrabajo.eliminarReporte');

Route::resource('grupotrabajo', App\Http\Controllers\GrupoTrabajoController::class);

// Vista 1: Lista de grupos
Route::get('/grupos-trabajo', [GrupoTrabajoController::class, 'index'])->name('grupotrabajo.index');
// Vista adicional: solo grupos fijos (APT/AFT/PPT/NP)
Route::get('/grupos-trabajo/fijos', [GrupoTrabajoController::class, 'indexFijos'])->name('grupotrabajo.fijos');
// Vista adicional: Agenda de Productos (solo APT/AFT/PPT/NP)
Route::get('/grupos-trabajo/agenda-productos', [GrupoTrabajoController::class, 'agendaProductos'])->name('grupotrabajo.agenda_productos');

// Vista 2: Formulario
Route::get('/grupos-trabajo/crear', [GrupoTrabajoController::class, 'create'])->name('grupotrabajo.create');
Route::post('/grupos-trabajo', [GrupoTrabajoController::class, 'store'])->name('grupotrabajo.store');

// Vista 3: Agenda
Route::get('/grupos-trabajo/agenda', [GrupoTrabajoController::class, 'agenda'])->name('grupotrabajo.agenda');
Route::post('/grupos-trabajo/reunion', [GrupoTrabajoController::class, 'guardarReunion'])->name('grupotrabajo.guardarReunion');
Route::get('/grupos-trabajo/reunion/{id}/edit', [GrupoTrabajoController::class, 'editReunion'])->name('grupotrabajo.editReunion');
Route::put('/grupos-trabajo/reunion/{id}', [GrupoTrabajoController::class, 'updateReunion'])->name('grupotrabajo.updateReunion');
Route::delete('/grupos-trabajo/reunion/{id}', [GrupoTrabajoController::class, 'deleteReunion'])->name('grupotrabajo.deleteReunion');

// Vista 4: Reporte
Route::get('/grupos-trabajo/reporte', [GrupoTrabajoController::class, 'reporte'])->name('grupotrabajo.reporte');
Route::post('/grupos-trabajo/{id}/observaciones', [GrupoTrabajoController::class, 'actualizarObservaciones'])->name('grupotrabajo.actualizarObservaciones');
Route::get('/grupos-trabajo/reporte/pdf', [GrupoTrabajoController::class, 'descargarPDF'])->name('grupotrabajo.pdf');




Route::get('/exportar-procesados-sql', [App\Http\Controllers\DocumentoController::class, 'exportarProcesadosSQL']);

Route::get('/exportar-sql', [App\Http\Controllers\DocumentoController::class, 'exportarSQL']);

Route::get('/exportar-todoslosdocumentos-sql', [App\Http\Controllers\DocumentoController::class, 'exportarTodoslosdocumentosSQL'])->name('exportar.todoslosdocumentos.sql');

Route::get('/fechas/form', [FechaController::class, 'create'])->name('formfechas');
Route::post('/fechas',      [FechaController::class, 'store'])->name('fechas.store');

//Route::get('/buscar-documentos', [App\Http\Controllers\DocumentoController::class, 'buscarDocumentos']);
//Route::get('/buscar-documentos', [DocumentoController::class, 'buscarDocumentos']);
//Route::post('/buscar-documentos', [TuControlador::class, 'buscar'])->name('buscar.documentos');
//Route::post('/buscar-documentos', [DocumentoController::class, 'buscarDocumentos'])->name('buscarDocumentos');
Route::get('/buscar-documentos', [DocumentoController::class, 'buscar']);


//Rutas para... etapas? 
Route::get('/documentos/{ID_doc}/etapas', [EtapaController::class, 'show'])->name('documentos.etapas');
Route::post('/documentos/{ID_doc}/etapas', [EtapaController::class, 'guardar'])->name('fechas.guardar');
Route::get('/documentos/{ID_doc}/etapas', [DocumentoController::class, 'etapas'])->name('documentos.etapas');
//Route::get('/etapas/{ID_doc}', [EtapaController::class, 'show'])->name('etapas.show');
//Route::post('/etapas/{ID_doc}', [EtapaController::class, 'guardar'])->name('fechas.guardar');

Route::get('/formulario', [FormularioController::class, 'create'])
    ->name('formulario');
Route::get('/fechas', [DocumentoController::class, 'fechas'])->name('fechas');
Route::get('/representaciones', [DocumentoController::class, 'representaciones'])->name('representaciones');
Route::get('/organismos', [DocumentoController::class, 'organismos'])->name('organismos');
Route::get('/busqueda', [DocumentoController::class, 'busqueda'])->name('busqueda');
Route::get('/productosterminados', [DocumentoController::class, 'productosterminados'])->name('productosterminados');
Route::get('/informes', [DocumentoController::class, 'informes'])->name('informes');
Route::get('/registroinformes', [DocumentoController::class, 'registroinformes'])->name('registroinformes');
Route::get('/todoslosdocumentos', [DocumentoController::class, 'index'])->name('todoslosdocumentos');
Route::get('/controldeavances', [ControlDeAvancesController::class, 'index'])->name('controldeavances');
//Route::get('/documentos', [DocumentoController::class, 'index'])->name('todoslosdocumentos');

// Ruta para editar
Route::get('/documentos/{ID_doc}/edit', [DocumentoController::class, 'edit'])->name('documentos.edit');

// Ruta para archivar
Route::patch('/documentos/{ID_doc}/archive', [DocumentoController::class, 'archive'])->name('documentos.archive');

Route::put('/documentos/{ID_doc}', [DocumentoController::class, 'update'])->name('documentos.update');

// Rutas para guardar datos
Route::post('/formulario', [FormularioController::class, 'guardar'])->name('formulario.guardar');
Route::post('/fechas/guardar', [DocumentoController::class, 'guardarFechas'])->name('fechas.guardar');
Route::post('/representaciones/guardar', [DocumentoController::class, 'guardarRepresentaciones'])->name('representaciones.guardar');
Route::post('/organismos/guardar', [DocumentoController::class, 'guardarOrganismos'])->name('organismos.guardar');
Route::post('/busqueda/guardar', [DocumentoController::class, 'guardarBusqueda'])->name('busqueda.guardar');
Route::post('/productosterminados/guardar', [DocumentoController::class, 'guardarProductosterminados'])->name('productosterminados.guardar');
Route::post('/informes/guardar', [DocumentoController::class, 'guardarInformes'])->name('informes.guardar');
Route::post('/registroinformes/guardar', [DocumentoController::class, 'guardarInformes'])->name('registroinformes.guardar');
Route::post('/todoslosdocumentos/guardar', [DocumentoController::class, 'guardarTodoslosdocumentos'])->name('todoslosdocumentos.guardar');
Route::post('/controldeavances/guardar', [DocumentoController::class, 'guardarControldeavances'])->name('controldeavances.guardar');

// Configuración de correo para notificaciones
Route::get('/correo/config', [CorreoController::class, 'config'])->name('correo.config');
Route::post('/correo/guardar', [CorreoController::class, 'guardar'])->name('correo.guardar');


Route::get('/plantillasvistas', function (){
    return view('plantillasvistas');
});

Route::get('/ejemplo', function (){
    return view('ejemplo');
});

Route::get('/principal', function (){
    return view('principal');
});

Route::get('/formfechas', function () {
    return view('formfechas'); 
});


//Route::get('/formulario', [FormularioController::class, 'show'])->name('formulario');

//Route::post('/formulario', [FormularioController::class, 'procesar'])->name('formulario.enviar');


/*Route::post('/formulario', function (Request $request) {
    $request->validate([
        'nombre' => 'required',
        'email' => 'required|email',
    ]);

    return back()->with('success', 'Formulario procesado correctamente.');
}); */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/mi-vista', function () {
    return view('home');
});



// Rutas de perfil deshabilitadas al quitar autenticación
// Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Autenticación deshabilitada
// require __DIR__.'/auth.php';
 
// Gestión de tipos para creación de grupos de trabajo
Route::resource('grupos', App\Http\Controllers\GrupoController::class)->only(['index','store','update','destroy']);
// Gestión de selectores del formulario
Route::resource('libros', App\Http\Controllers\LibroController::class)->only(['index','store','update','destroy']);
Route::resource('temas', App\Http\Controllers\TemaController::class)->only(['index','store','update','destroy']);
Route::resource('origenes', App\Http\Controllers\OrigenController::class)
    ->parameters(['origenes' => 'origen'])
    ->only(['index','store','update','destroy']);
Route::resource('tipos', App\Http\Controllers\TipoController::class)->only(['index','store','update','destroy']);
 