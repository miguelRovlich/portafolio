<?php

namespace App\Http\Controllers;
use Validator;
use App\InstruccionPago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstruccionPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //
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
    public function filtrar(request $request)
    {
        $validator = Validator::make($request ->all(),[
            'id_Matriz' => 'required|exists:MatrizPago,id',
            'FechaMaxPago' => 'nullable|date'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $data = InstruccionPago::where('id_Matriz',$request->input
        ('id_Matriz'))->join('MatrizPago','InstruccionPago.id_Matriz','=','MatrizPago.id')->join('VentanaFacturacion','MatrizPago.id_VentanaFacturacion','=','VentanaFacturacion.id');
        if ($request->has('FechaMaxPago')){
            $data = $data->where('FechaMaxPago', '>=',$request->input
            ('FechaMaxPago'));
        }
        $Fecha = $request->has('FechaMaxPago') ? Carbon::parse($request->input
        ('FechaMaxPago')) : Carbon::now()->day(1); 
        return response()->json($data->get(),200);
    
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