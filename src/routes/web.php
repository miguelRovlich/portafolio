<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('/spa')->group(function () {
    Route::get('', 'HomeController@index')
        ->name('home')
        ->where('any', '.*');

    Route::get('/{any}', 'HomeController@index')->where('any', '.*');
});


/**
 * Cambia las variables de session. Cambia el participante que ve el usuario
 */
Route::get('/setParticipante', function (Request $req) {
    $idParticipante = $req->input('id');

    $role = $req->user()->roles()->wherePivot('id_Participante', $idParticipante)->first();

    if (!$role) {
        return redirect('spa/administracion');
    }

    session([
        'id_participante' => $role->pivot->id_Participante,
        'role' => $role->Nombre
    ]);


    //return redirect('spa/administracion');
});
