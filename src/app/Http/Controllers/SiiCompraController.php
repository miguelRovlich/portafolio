<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\SiiCompra;

class SiiCompraController extends Controller
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
    public function SiiCompra(request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'id_participante' => 'required|exists:Participantes,id',
            'FechaDocumento' => 'nullable|date',
            'FechaRecepcion' => 'nullable|date'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $data = SiiCompra::where('id_Participante',$request->input
        ('id_Participante'));
        if ($request->has('FechaDocumento')){
            $data = $data->where('FechaDocumento', '>=',$request->input
            ('FechaDocumento'));
        }
        if ($request->has('FechaRecepcion')){
            $data = $data->where('FechaRecepcion', '=>',$request->input
            ('FechaRecepcion'));
        }
        $FechaDocumento = $request->has('FechaDocumento') ? Carbon::parse($request->input
        ('FechaDocumento')) : Carbon::now()->day(1);
        $FechaRecepcion = $request->has('FechaRecepcion') ? Carbon::parse($request->input
        ('FechaRecepcion')) : Carbon::now()->subday();
        $FechaRecepcion->hour = 23;
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
