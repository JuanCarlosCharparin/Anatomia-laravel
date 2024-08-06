<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\CrearEstudioController;
use App\Http\Controllers\EstudioController;
use App\Http\Controllers\ExportarController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';





Route::get('/estudios', [CrearEstudioController::class, 'index'])->name('estudios.index');

// Ruta para mostrar el formulario de creación de estudio (create)
Route::get('/estudios/create', [CrearEstudioController::class, 'create'])->name('estudios.create');

// Ruta para guardar un nuevo estudio (store)
Route::post('/estudios', [CrearEstudioController::class, 'store'])->name('estudios.store');


Route::get('/search-patient', [CrearEstudioController::class, 'searchPatient'])->name('estudios.searchPatient');


//Editar
// Ruta para mostrar el formulario de edición
Route::get('estudios/{nro_servicio}/edit', [EstudioController::class, 'edit'])->name('estudios.edit');

// Ruta para actualizar el estudio
Route::post('estudios/{nro_servicio}', [EstudioController::class, 'update'])->name('estudios.update');
//Ruta para finalizar el estudio
Route::post('/estudios/{nro_servicio}/finally', [EstudioController::class, 'finally'])->name('estudios.finally');

Route::post('/estudios/{nro_servicio}/finalizar', [EstudioController::class, 'reFinally'])->name('estudios.finalizar');

// Ruta para ampliar el informe
Route::post('/estudios/ampliar-informe/{nro_servicio}', [EstudioController::class, 'ampliarInforme'])->name('estudios.ampliarInforme');


Route::get('estudios/exportar-datos/{nro_servicio}', [ExportarController::class, 'exportarDatos'])->name('exportar.datos');



