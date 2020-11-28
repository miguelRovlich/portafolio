<?php
namespace App\Http\Controllers;
use Validator;
use App\LibroDiario;
use Illuminate\Http\Request;
use Carbon\Carbon;


class libroDiarioController extends Controller
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

    public function libroDiario(request $request)
    {
        $validator = Validator::make($request ->all(),[
            'id_Owner' => 'required|exists:Participantes,id',
            'Fecha' => 'nullable|date'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $data = libroDiario::where('id_Owner',$request->input
        ('id_Owner'));
        if ($request->has('Fecha')){
            $data = $data->where('Fecha', '>=',$request->input
            ('Fecha'));
        }
        $Fecha = $request->has('Fecha') ? Carbon::parse($request->input
        ('Fecha')) : Carbon::now()->day(1); 
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
