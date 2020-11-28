<?php

namespace App\Imports;

use App\InstruccionPago;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importInstruccion implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return InstruccionPago|null
     */
     
     
    protected $guarded = ["monto_neto","monto_bruto","rut","EsImportada"]; 
    
    public function model(array $row)
    {
        $rut = InstruccionPago::find(all);
        return new InstruccionPago ([
            "monto_neto" => $row["monto_neto"],
            "monto_bruto" =>$row["monto_bruto"],
            "rut" =>$row["rut"],
            "EsImportada" => 1
        ]);
    }
    
}