<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\User_Role;
use Illuminate\Http\data;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->middleware('auth:api')->group(function () {
    /**
     * Rutas relacionadas a conjunto de datos
     */
    Route::get('/sii/venta', 'SiiVentaController@siiCompra');
    Route::get('/sii/compra', 'SiiCompraController@SiiCompra');
    Route::get('/libroDiario', 'libroDiarioController@libroDiario');
    Route::get('/InstruccionPago', 'InstruccionPagoController@filtrar');
    Route::get('/ventanaFacturacion', 'ventanaFacturacionController@listarDeudor');

    Route::prefix('/bce')->group(function () {
        Route::get('', 'BceController@balance');
        Route::get('/ingreso','ingresoController@ingreso');
    });
    Route::prefix('/importacion')->group(function () {
        Route::post('/instruccion','importarController@import');
    });


    //rutas user
    Route::post('/registeruser', 'Auth\RegisterController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/delete', 'UserController@delete');

    Route::get('/listar', 'UserController@listar');

    Route::get('/listpart','UserController@listarpart');
    Route::get('/participanteuser','UserController@miparticipante');




 






});
