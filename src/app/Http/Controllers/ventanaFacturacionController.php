<?php

namespace App\Http\Controllers;
use App\VentanaFacturacion;
use Illuminate\Http\Request;
use Validator;  
class ventanaFacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listarDeudor(request $request)
    {
        $validator = Validator::make($request ->all(), [
            'id_VentanaFacturacion' => 'required|exists:MatrizPago,id',
             'Periodos' => 'nullable|date'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = ventanaFacturacion::where(
            'id_VentanaFacturacion',
            $request->input('id_VentanaFacturacion')
        )
        ->join('MatrizPago', 'VentanaFacturacion.id', '=', 'MatrizPago.id_VentanaFacturacion')
        ->join('InstruccionPago', 'MatrizPago.id', '=', 'InstruccionPago.id_Matriz')
        ->join('Participantes', 'InstruccionPago.id_Deudor', '=', 'Participantes.id');

       if ($request->has('Periodos')){
            $data = $data->where('Periodos', '>=',$request->input
            ('Periodos'));
        } 
        return response()->json($data->get(),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
