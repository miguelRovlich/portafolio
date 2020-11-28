<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\SiiVenta;
use Carbon\Carbon;
class SiiVentaController extends Controller
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
    public function siiCompra()
    {
                
        $validator = Validator::make($request ->all(),[
            'id_participante' => 'required|exists:Participantes,id',
            'FechaDocto' => 'nullable|date',
            'FechaRecepcion' => 'nullable|date',
            'FechaAcuse'=> 'nullable|date',
            'FechaReclamo'=> 'nullable|date'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $data = SiiVenta::where('id_Participante',$request->input
        ('id_Participante'));
        if ($request->has('FechaDocto')){
            $data = $data->where('FechaDocto', '>=',$request->input
            ('FechaDocto'));
        }
        if ($request->has('FechaRecepcion')){
            $data = $data->where('FechaRecepcion', '>=',$request->input
            ('FechaRecepcion'));
        }
        if ($request->has('FechaAcuse')){
            $data = $data->where('FechaAcuse', '>=',$request->input
            ('FechaAcuse'));
        }
        
        if ($request->has('FechaReclamo')){
            $data = $data->where('FechaReclamo', '>=',$request->input
            ('FechaReclamo'));
        }
        $FechaDocto = $request->has('FechaDocto') ? Carbon::parse($request->input
        ('FechaDocto')) : Carbon::now()->day(1);
        $FechaRecepcion = $request->has('FechaRecepcion') ? Carbon::parse($request->input
        ('FechaRecepcion')) : Carbon::now()->subday();
        $FechaRecepcion->hour = 23;
        $fechaAcuse = $request->has('FechaAcuse') ? Carbon::parse($request->input
        ('FechaAcuse')) : Carbon::now()->subday();
        $fechaAcuse->hour = 10;
        $fechaReclamo = $request->has('FechaReclamo') ? Carbon::parse($request->input
        ('FechaReclamo')) : Carbon::now()->subday();
        $fechaReclamo->hour = 10;
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
