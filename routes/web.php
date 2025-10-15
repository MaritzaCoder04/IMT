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

Route::get('/exportar-procesados-sql', [App\Http\Controllers\DocumentoController::class, 'exportarProcesadosSQL']);

Route::get('/exportar-sql', [App\Http\Controllers\DocumentoController::class, 'exportarSQL']);

Route::get('/fechas/form', [FechaController::class, 'create'])->name('formfechas');
Route::post('/fechas',      [FechaController::class, 'store'])->name('fechas.store');

//Route::get('/buscar-documentos', [App\Http\Controllers\DocumentoController::class, 'buscarDocumentos']);
Route::get('/buscar-documentos', [DocumentoController::class, 'buscarDocumentos']);


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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mi-vista', function () {
    return view('home');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
 