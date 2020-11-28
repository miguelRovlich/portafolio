<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\InstruccionPago;
use App\Imports\importInstruccion;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;

class importarController extends Controller
{
    public function import(Request $request) 
    {
        Excel::import(new importInstruccion, $request->file('filename'));
        return redirect('/')->with('success', 'All good!');
    }
}