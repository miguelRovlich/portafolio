<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Validator;
use App;
use Log;

use App\Participantes;
use App\VentanaFacturacion;
use App\MatrizPago;
use App\SiiCompra;

class BceController extends Controller
{
    protected $instrucciones;
    protected $libroDiario;
    protected $sii;
    protected $modo;

    public function balance(Request $request)
    {
        set_time_limit(0);

        $validator = Validator::make($request ->all(), [
            'id_participante' => 'required|exists:Participantes,id',
            'year' => 'required',
            'month' => 'required',
            'modo' => 'required' # d o a
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->modo = $request->input('modo');

        # fechas mes
        $fecha_inicio = Carbon::now();
        $fecha_inicio->year = $request->input('year');
        $fecha_inicio->month = $request->input('month');
        $fecha_inicio->day = 1;

        $fecha_fin = $fecha_inicio->copy()->endOfMonth();

        # participante (empresa)
        $participante = Participantes::findOrFail($request->input('id_participante'));

        $data = $this->getDataBalance($participante, $fecha_inicio, $fecha_fin);

        # match listo
        return response()->json([
            'participante' => $participante,
            'data' => $data,
        ], 200);
    }

    private function getDataBalance($participante, $fecha_inicio, $fecha_fin)
    {
        # obtengo instrucciones de deuda o acreedor.
        $this->instrucciones = $this->getInstrucciones(
            $participante,
            $fecha_inicio
        );

        $this->libroDiario = $this->getLibroDiario(
            $participante,
            $fecha_inicio
        );

        // $this->sii = $this->getSii(
        //     $participante,
        //     $fecha_inicio,
        //     $fecha_fin
        // );

        // for test
        // if (App::environment('local') || App::environment('development')) {
        //     $this->instrucciones = $this->instrucciones->take(100);
        // }

        # comienza a procesar cada instruccion
        foreach ($this->instrucciones as $k => $i) {
            if ($this->instrucciones[$k]['libroDiarioPago'] == null ||
                $this->instrucciones[$k]['libroDiarioContab'] == null) {
                $this->matchLibroDiario(
                    $this->instrucciones[$k]
                );
            }
        }

        return $this->instrucciones;
    }

    private function getInstrucciones($participante, $fecha_ini)
    {
        $ventanas = VentanaFacturacion::where(
            'Periodos',
            'LIKE',
            '%'.$fecha_ini->toDateString().'%'
        )->with('matrizPago')->get();

        $matrices_id = [];

        foreach ($ventanas as $v) {
            foreach ($v->matrizPago as $m) {
                array_push($matrices_id, $m->id);
            }
        }

        # instrucciones de pago. Filtro si quiere ver como deudor o acreedor
        if ($this->modo == 'd') {
            $instrucciones = $participante
                ->Deudor()
                ->with('acreedor:id,Nombre,Rut,DV')
                ->with('siiCompra:id,Folio,FechaDocumento,FechaRecepcion,FechaAcuse');
        } elseif ($this->modo == 'a') {
            $instrucciones = $participante
                ->Acreedor()
                ->with('deudor:id,Nombre,Rut,DV')
                ->with('siiVenta:id,Folio,FechaDocumento,FechaRecepcion,FechaAcuse');
        }

        $instrucciones = $instrucciones
            ->with('libroDiarioPago:id,Folio,Debe,Haber,Fecha,Correlativo')
            ->with('libroDiarioContab:id,Folio,Debe,Haber,Fecha,Correlativo')
            ->with('matrizPago:id,NaturalKey,TipoPago')
            ->whereIn('id_Matriz', $matrices_id);

        return $instrucciones->get([
            'id',
            'MontoNeto',
            'MontoBruto',
            'EstaPagado',
            'EstadoPago',
            'MontoPagado',
            'id_Acreedor',
            'id_Deudor',
            'id_Matriz',
            'id_LibroDiario',
            'id_LibroDiarioContab',
            'id_SiiCompra',
            'id_SiiVenta'
        ]);
    }

    private function getLibroDiario(
        $participante,
        $fecha_inicio
    ) {
        $libro = $participante->libroDiario()
            ->where('Fecha', '>=', $fecha_inicio);

        if ($this->modo == 'd') {
            $libro = $libro
                ->whereIn('id_TipoComprobante', [1, 3]) # traspaso y egreso
                ->get([
                    'id',
                    'Debe',
                    'Haber',
                    'Fecha',
                    'Folio',
                    'Correlativo',
                    'Rut_Proveedor',
                    'id_TipoComprobante'
                ]);

            return $libro;
        } else {
            $libro = $libro
                ->whereIn('id_TipoComprobante', [1, 2])
                ->get([
                    'id',
                    'Debe',
                    'Haber',
                    'Fecha',
                    'Folio',
                    'Correlativo',
                    'Rut_Cliente',
                    'id_TipoComprobante'
                ]);

            return $libro;
        }
    }

    private function getSii(
        $participante,
        $fecha_inicio,
        $fecha_fin
    ) {
        # obtener compra si veo la data como deudor (input modo)
        # obtener venta si veo la data como acreedor (input modo)
        if ($this->modo == 'd') {
            $Sii = $participante->SiiCompra();
        } else {
            $Sii = $participante->SiiVenta();
        }

        $Sii = $Sii
            ->whereNotNull('MontoNeto')
            ->whereMonth('FechaDocumento', $fecha_inicio->month)
            ->whereYear('FechaDocumento', $fecha_inicio->year)
            ->orWhere(function ($q) use ($fecha_inicio) {
                $q->whereMonth('FechaRecepcion', $fecha_inicio->month)->whereYear('FechaRecepcion', $fecha_inicio->year);
            });

        return $Sii->get();
    }

    /**
     * Procesamiento por instruccion (match => rut y montoNeto +- 5pesos)
     */
    private function matchLibroDiario(&$instruccion)
    {
        $key_libro = $this->modo == 'd' ? 'Rut_Proveedor' : 'Rut_Cliente';
        $inst_rut = $this->modo == 'd' ? $instruccion->acreedor->Rut : $instruccion->deudor->Rut;

        $libro_filtrado = $this->libroDiario
            ->filter(function ($v, $k) use ($instruccion, $key_libro, $inst_rut) {
                if ($v[$key_libro] !== $inst_rut) {
                    return false;
                }

                $control = abs($v->Debe - $v->Haber);
                $match = $control >= $instruccion->MontoBruto - 5 && $control <= $instruccion->MontoBruto + 5;

                if (!$match) {
                    return false;
                }

                unset($this->libroDiario[$k]);
                return $match;
            });

        $pago = $libro_filtrado->first(function ($l) {
            return $l->id_TipoComprobante == 3;
        });

        $ingreso = $libro_filtrado->first(function ($l) {
            return $l->id_TipoComprobante == 2;
        });

        $contabilizacion = $libro_filtrado->first(function ($l) {
            return $l->id_TipoComprobante == 1;
        });

        if ($pago !== null || $contabilizacion !== null) {
            if ($pago) {
                $instruccion->id_LibroDiario = $pago->id;
            }

            if ($contabilizacion) {
                $instruccion->id_LibroDiarioContab = $contabilizacion->id;
            }

            $instruccion->save();
            $instruccion
                ->load('libroDiarioPago:id,Debe,Haber,Fecha,Correlativo')
                ->load('libroDiarioContab:id,Debe,Haber,Fecha,Correlativo');
        }
    }

    private function matchSII(&$instruccion)
    {
        # filtramos conjunto sii
        $siiFiltrado = $this->sii
            ->where('RutProveedor', $instruccion->Acreedor->Rut);

        $regSII = $siiFiltrado->first(function($v) use ($instruccion) {
            $control = $v->MontoNeto;
            return $control >= $instruccion->MontoNeto - 5 && $control <= $instruccion->MontoNeto + 5;
        });

        $instruccion->sii_compra = null;

        if ($regSII) {
            $instruccion->sii_compra = [
                    'Folio' => $regSII->Folio,
                    'FechaAcuse' => $regSII->FechaAcuse,
                    'FechaRecepcion' => $regSII->FechaRecepcion,
                    'FechaDocumento' => $regSII->FechaDocumento,
                    'MontoNeto' => $regSII->MontoNeto,
                    'MontoTotal' => $regSII->MontoTotal,
                    'RutProveedor'=>$regSII->RutProveedor
                    ];
        }
    }

}