<?php

namespace App\Http\Livewire\Planillas;

use App\Exports\PlanillaExport;
use App\PlanillaHorarioDet;
use App\PuestoMigratorio;
use App\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Historial;

class Lista  extends Component
{

    use WithPagination;

    const ETIQUETAS_ENTRADA = ['E', 'E Sup.'];
    const ETIQUETAS_SALIDA = ['S', 'S Sup.'];

    public $headers;
    public $perPage = 500;
    public $sortApellidoDirection = 'asc';
    public $sortColumn = 'nombre_agente';
    public $sortDirection = 'asc';
    public Int $anio;
    public Int $mes;
    public Int $dia;
    public $fechaInput;
    public $franjaSelecionada;
    public $franjas = [];
    public $sectores = [];
    public $franjaInicial;
    public $detalleAgente;
    public $nota;
    public $franjaId;
    public $idSector;
    public $idInicial;
    public $agenteId;
    public $planillaID;
    public $presente = 'NO';
    public $guardia;
    public $guardia_temporal;
    public $fecha_form;
    public $nombre_franja;
    public $sectorEtiqueta;
    public $nombreAgente;
    public $ausentes; // listado de todos los ausentes posibles
    public $ausentePorAsignar; // ausente que se va a asignar si se confirma
    public $esPlanillaYaExportada; // si la planilla ya fue exportada muestra mensaje

    //listener
    protected $listeners = ['fechaSeleccionada', 'limpiarDetalle', 'refreshComponent' => '$refresh'];

    //funcion header de la tabla
    private function headerConfig()
    {
        return [
            'nombre_agente' => 'AGENTE',
             'guardia' => 'GUARDIA',
            'nota' => 'DETALLE',
            'dia' => 'DIA',
        ];
    }
    //ordernar columnas
   public function sort($column)
{
    if ($column === 'nombre_agente') {
        $this->sortApellidoDirection = $this->sortApellidoDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }
}


    // se ejecuta al momento de abrir la pagina
    public function mount()
    {
        $this->guardia=session('guardia');
        $this->guardia_temporal=session('guardia');
        $this->fecha_form = session('ultima-fecha', now()->format('Y-m-d')) ;
        $this->fechaInput = $this->fecha_form ;
        $fechaAc = explode("-", $this->fecha_form );
        $this->anio = intval($fechaAc[0]);
        $this->mes = $this->deleteCero($fechaAc[1]);
        $this->franjaIni();
        $this->dia = $this->deleteCero($fechaAc[2]);
        $this->detalleAgente = [];
        $this->headers = $this->headerConfig();
        $this->sectorEtiqueta = session("sector-etiqueta");
        $this->ausentes = $this->selectAusentes();
        // dd($this->ausentes);
    }

    // datos para la tablas en livewire
    public function resultData($nombre_franja = null)
    {
        $supervisor = User::find(Auth::id());
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(empty($puestos->puestos_vinculados) || is_null($puestos->puestos_vinculados)){
            $integerIDs =$supervisor->id_puesto_migratorio;
        }else{

            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
        }
        // $integerIDs = [2];
        // dd($integerIDs);
        $cabeceras=DB::table('planilla_horarios_cab as pc')
        ->select('pc.id_planilla_horarios_cab','pc.mes_correspondiente',
        'pc.ano_correspondiente','pc.id_puesto_migratorio')
        ->where('pc.mes_correspondiente', '=', $this->mes)
        ->where('pc.ano_correspondiente', '=', $this->anio)
        ->whereIn('pc.id_puesto_migratorio', $integerIDs)
        ->get();

        $cabeceras_ids=[];
        foreach ($cabeceras as $cabe) {
            array_push($cabeceras_ids,$cabe->id_planilla_horarios_cab);
        }

        // Según sector seleccionado al loggearse, agrega where para filtrar las etiquetas
        $andWhereEtiqueta = "";
        if($this->sectorEtiqueta == "S"){
            $andWhereEtiqueta = "AND etiqueta in ('".implode("','",self::ETIQUETAS_ENTRADA)."')";
        }else if($this->sectorEtiqueta == "E"){
            $andWhereEtiqueta = "AND etiqueta in ('".implode("','",self::ETIQUETAS_SALIDA)."')";
        }

        $data=DB::select("SELECT * FROM vw_horario_actual_laravel
        LEFT JOIN (SELECT id as lugar__id, nombre as lugar__nombre FROM lugar) lugar ON vw_horario_actual_laravel.puesto = lugar.lugar__id
        WHERE (dia = $this->dia OR  0 = $this->dia AND fecha='".$this->fecha_form."') AND ((guardia_temporal='".$this->guardia_temporal."'
                 OR guardia='".$this->guardia_temporal."')
                 $andWhereEtiqueta
                 AND id_planilla_horarios_cab in(".implode(',',$cabeceras_ids).")
                 AND actual=1)
                    AND id_puesto_migratorio IN (".implode(',',$integerIDs).")
                    AND id_agente NOT IN (SELECT id_agente
            FROM licencias
                    WHERE (fecha between fecha_desde AND fecha_hasta)
                    OR (fecha = fecha_14f_1 OR fecha = fecha_14f_2 OR fecha = fecha_14f_3))

                            and ingreso='".$this->nombre_franja."'
        ORDER BY orden_principal desc, ingreso asc");
        //->orderBy($this->sortColumn, $this->sortDirection);
        $data = DB::select("SELECT * FROM vw_horario_actual_laravel
    LEFT JOIN (SELECT id as lugar__id, nombre as lugar__nombre FROM lugar) lugar ON vw_horario_actual_laravel.puesto = lugar.lugar__id
    WHERE (dia = $this->dia OR  0 = $this->dia AND fecha='".$this->fecha_form."') AND ((guardia_temporal='".$this->guardia_temporal."'
             OR guardia='".$this->guardia_temporal."')
             $andWhereEtiqueta
             AND id_planilla_horarios_cab in(".implode(',',$cabeceras_ids).")
             AND actual=1)
                AND id_puesto_migratorio IN (".implode(',',$integerIDs).")
                AND id_agente NOT IN (SELECT id_agente
        FROM licencias
                WHERE (fecha between fecha_desde AND fecha_hasta)
                OR (fecha = fecha_14f_1 OR fecha = fecha_14f_2 OR fecha = fecha_14f_3))"
                . ($nombre_franja == null ? "" : "AND ingreso='".$nombre_franja."'"));

if ($this->sortColumn === 'nombre_agente') {
    $data = collect($data)->sortBy('id_agente', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
} else {
    $data = collect($data)->sortByDesc('orden_principal')->sortBy('ingreso')->toArray();
}

$data = new Paginator($data, 2000);

        //->paginate($this->perPage);
        //dd($data);
      /*  $data = DB::table('planilla_horarios_cab as pc')
            ->select(
                'pd.id_planilla_horarios_det',
                'pd.id_planilla_horarios_cab',
                'pc.id_planilla_horarios_cab',
                'pd.dia',
                'pd.id_franja_horaria',
                'pd.id_agente',
                'pd.sector',
                'pd.nota',
                'pd.presente',
                'pc.id_puesto_migratorio',
                'pc.mes_correspondiente',
                'pc.ano_correspondiente',
                'fh.hora_inicio',
                'se.nombre',
                'se.etiqueta',
                'a.guardia',
                'a.activo',
                'pc.actual',
                'se.etiqueta as detalle',
                DB::raw('CONCAT_WS(" ",a.apellidos,a.nombres) as  agente')
            )->join('planilla_horarios_det as pd', 'pd.id_planilla_horarios_cab', '=', 'pc.id_planilla_horarios_cab')
            ->join('franjas_horarias as fh', 'fh.id', '=', 'pd.id_franja_horaria')
            ->join('sectores as se', 'se.id', '=', 'pd.sector')
            ->join('agentes  as a', 'a.id_agente', '=', 'pd.id_agente')
            ->where('pd.id_franja_horaria', '=', $this->franjaSelecionada)
            ->where('pd.dia', '=', $this->dia)
            ->where('a.activo', '=', 1)
            ->where('pc.actual', '=', 1)
            ->where('pc.mes_correspondiente', '=', $this->mes)
            ->where('pc.ano_correspondiente', '=', $this->anio)
            ->whereIn('pc.id_puesto_migratorio', $integerIDs)
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);*/
        // dd($data);
        return $data;
    }

    //actualiza fecha seleccionada
    public function fechaSeleccionada($fecha)
    {
        $this->limpiarDetalle();
        $this->fechaInput = $fecha;
        session()->put('ultima-fecha', $fecha);
        $fechaAc = explode("-", $fecha);
        $this->anio = $fechaAc[0];
        $this->mes = $this->deleteCero($fechaAc[1]);
        $this->dia = $this->deleteCero($fechaAc[2]);
        $this->nuevaFranja($this->franjaId);
        $this->emit('refreshComponent');
    }

    public function setSectorFranja($id, $planilla)
    {
        $this->agenteId = $id;
        $this->planillaID = $planilla;
        $this->selectSectores();
    }

    //actualizar Sector
    public function actualizarSector()
    {
        PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $this->planillaID)
            ->first()->update(["sector" => $this->idSector]);
    }
    //set id para utilizar en planillas
    public function setPlanillaID($id)
    {
        $this->nota = ' ';
        $this->planillaID = $id;
        $this->findPlanillaNota();
    }

    //opciones Sectores
    public function selectSectores()
    {
        $supervisor = User::find(Auth::id());
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(empty($puestos->puestos_vinculados) || is_null($puestos->puestos_vinculados)){
            $integerIDs =$supervisor->id_puesto_migratorio;
        }else{

            //$integerIDs =implode(',', array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio)));
            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
        }


        $data = DB::table('sectores as se')
             ->select('se.nombre', 'se.id', 'se.etiqueta')
            ->where('se.id_franja_horaria', '=', $this->agenteId)
            //->whereIn('se.id_puesto_migratorio',$integerIDs)
            ->pluck('se.nombre', 'se.id');
            //dd($data);
        /*$data = DB::table('franjas_horarias as fh')
            ->join('sectores as se', 'se.id_franja_horaria', '=', 'fh.id')
            ->select('se.nombre', 'se.id', 'fh.activo')
            ->where('fh.activo', '=', 1)
            ->where('fh.id', '=', $this->franjaSelecionada)
            ->whereIn('fh.id_puesto_migratorio', $integerIDs)
            ->pluck('se.nombre', 'se.id');*/
        $this->sectores = $data;
    }

    //busca nota para editar
    public function findPlanillaNota()
    {
        $this->nota = ' ';
        $data = DB::table('planilla_horarios_det')
            ->select(
                'id_planilla_horarios_det',
                'nota',
            )->where('id_planilla_horarios_det', '=', $this->planillaID)
            ->first();
        $this->nota = $data->nota;
    }

    //Actualizando Nota
    public function actualizarNota()
    {
        $this->nuevaNota = $this->nota;
        $this->validate([
            'nota' => 'max:100',
        ]);

        PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $this->planillaID)
            ->first()->update(["nota" => $this->nuevaNota]);

        $this->nota = ' ';
        $this->emit('notaActualizada'); //cerar modal
    }

    public function setDetalleId($id)
    {
        $this->planillaID = $id;

        $this->verDetalles();
    }
    

    //ver detalle de agente
    public function verDetalles()
    {
        $this->detalleAgente = DB::table("historial_2")->where('id_planilla', '=', $this->planillaID)->get();
    }

    //set id para buscar nota
    public function setPlanillaNota($id)
    {
        $this->planillaID = $id;
    }

    public function setPlanillaNotaYAgente($id, $nombre_agente)
    {
        $this->planillaID = $id;
        $this->nombreAgente = $nombre_agente;
    }

    // //actualizar presente
    // public function actualizarPresente($respuesta)
    // {
    //     // Valida respuesta SI o NO
    //     validator(["respuesta" => $respuesta], [
    //         'respuesta' => 'required|in:SI,NO',
    //     ])->validate();

    //     // Actualiza presente
    //     $this->presente = $respuesta;

    //     PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $this->planillaID)
    //         ->first()->update(["presente" => $this->presente]);
    // }
    public function actualizarPresenteNo($planillaID)
    {
        PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $planillaID)
            ->first()->update(["presente" => "NO", "puesto" => 0, "rel_temp" => null]);
    }



    //actuliza fecha seleccionada
    public function nuevaFranja($franjaId)
    {
        $this->franjaId = $franjaId;
        $this->franjaSelecionada = $this->franjaId;
        $this->franjaInicial = $this->franjaId;
        $supervisor = User::find(Auth::id());
        //$supervisor = User::find(38);
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(empty($puestos->puestos_vinculados) || is_null($puestos->puestos_vinculados)){
            $integerIDs =  [$supervisor->id_puesto_migratorio];
        }else{
            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
        }

        $data = DB::table('franjas_horarias as fh')
        ->join('sectores as se', 'se.id_franja_horaria', '=', 'fh.id')
        ->join('planilla_horarios_det as pd', 'pd.id_franja_horaria', '=', 'fh.id')
        ->join('planilla_horarios_cab as pc', 'pc.id_planilla_horarios_cab', '=', 'pd.id_planilla_horarios_cab')
        ->join('agentes  as a', 'a.id_agente', '=', 'pd.id_agente')
        ->select('fh.nombre', 'fh.id')
        ->where('fh.activo', '=', 1)
        //->where('fh.cantidad_veces_agentes', '>', 0)
        ->where('fh.id', '=', $this->franjaSelecionada)
        ->whereIn('se.id_puesto_migratorio', $integerIDs)
        ->groupBy('fh.nombre')
        ->orderBy('fh.nombre', 'asc')->first();
        $this->franjaInicial = $data->nombre;
        $this->nombre_franja = $data->nombre;
        $this->idInicial = $data->id;
        session()->put('ultima-franja-id', $data->id);
    }

    //franja inicial
    public function franjaIni()
    {
        $supervisor = User::find(Auth::id());
        //$supervisor = User::find(38);
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(is_null($puestos->puestos_vinculados)){
            $integerIDs =  [$supervisor->id_puesto_migratorio];
        }else{
            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
            //$integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados));

        }
        //dd($integerIDs);
        /*$data=DB::table('franjas_horarias as fh')
        ->select('fh.nombre', 'fh.id','fh.hora_inicio','fh.cantidad_veces_agentes')
        ->where('fh.activo', '=', 1)
        ->whereIn('fh.id_puesto_migratorio', [$integerIDs])
        ->where('fh.cantidad_veces_agentes', '>', 0)
        ->orderBy('fh.nombre', 'asc');
        */
        $data = DB::table('franjas_horarias as fh')
        ->join('sectores as se', 'se.id_franja_horaria', '=', 'fh.id')
        ->join('planilla_horarios_det as pd', 'pd.id_franja_horaria', '=', 'fh.id')
        ->join('planilla_horarios_cab as pc', 'pc.id_planilla_horarios_cab', '=', 'pd.id_planilla_horarios_cab')
        ->join('agentes  as a', 'a.id_agente', '=', 'pd.id_agente')
        ->select('fh.nombre', 'fh.id')
        ->where('fh.activo', '=', 1)
        ->where('fh.cantidad_veces_agentes', '>', 0)
        ->whereIn('se.id_puesto_migratorio', $integerIDs)
        ->groupBy('fh.nombre')
        ->orderBy('fh.nombre', 'asc');

        $data = $data->get();

        // session()->put('ultima-franja-id', 59);
        $franjaInicial = $data->where('id', session('ultima-franja-id') )->first() ?? $data[0];

        $this->franjaInicial = $franjaInicial->nombre;
        $this->nombre_franja = $franjaInicial->nombre;
        $this->franjaId = $franjaInicial->id;
        $this->franjaSelecionada = $franjaInicial->id;
        $this->franjas = $data->pluck('nombre', 'id'); // ->prepend('Seleccione', '0');

        // dd($this->franjaInicial,$this->nombre_franja, $this->franjaId, $this->franjaSelecionada, $this->franjas);
    }

    //funcion que renderiza la vista
    public function render()
    {
        return view('livewire.planillas.lista', [
            'data' => $this->resultData($this->nombre_franja),
            'headers' => $this->headerConfig()
        ]);
    }

    //funcion eliminar cero
    private function deleteCero($numero)
    {
        if ($numero < 10) {
            return intval(ltrim($numero, '0'));
        } else {
            return intval($numero);
        }
    }
    //funcion para limpiar
    public function limpiarDetalle()
    {
        $this->detalleAgente = [];
    }

    public function obtener_dia_final_mes_real($DVAR_ano, $DVAR_mes){

        $DVAR_dia_final = cal_days_in_month(CAL_GREGORIAN, $DVAR_mes, $DVAR_ano);

        return $DVAR_dia_final;

    }
    
   public function actualizarHistorial()
{
    // Obtén la fecha que deseas filtrar
    $fechaStr = Carbon::createFromFormat('j \de F \de Y'); // Ajusta esta línea según cómo obtengas la fecha

    // Llama a mostrarHistorial() con el argumento $fechaStr
    $this->mostrarHistorial($fechaStr);
}
    
    public function mostrarHistorial($fechaStr)
{
    // Convierte $fechaStr a un objeto Carbon
    $fecha = Carbon::createFromFormat('j \de F \de Y', $fechaStr);
    

    // Filtra los registros de Historial basados en la fecha de cobertura
    $historial = Historial::whereDate('fecha_cobertura', $fecha)->get();
    
    return view('historial', compact('historial'));
}
    /**
     * Selecciona las diferentes ausencias de la tabla ausentes
     */
    public function selectAusentes(){
        $data = DB::table("ausentes")->get()->map(function ($object) {
            return (array) $object; // devuelve array de arrays
        })->all();
        return $data;
    }

    public function setAusentePorAsignar($id){
        if($id == null) $this->ausentePorAsignar = null;
        $this->ausentePorAsignar = collect($this->ausentes)->where('id', $id)->first();
    }

    public function actualizarAusente(){
        $ausenteNombre = $this->ausentePorAsignar['nombre'];
        $this->setAusentePorAsignar(null);
        PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $this->planillaID)
            ->first()->update(["presente" => $ausenteNombre, "puesto" => 0, "rel_temp" => null]);
    }


    /**
     *  Setea esPlanillaYaExportada para mostrar mensaje de planilla ya exportada
     */
    public function setEsPlanillaYaExportada($esPlanillaYaExportada){
        $this->esPlanillaYaExportada = $esPlanillaYaExportada;
    }

    /**
     * Exporta planilla a pdf o excel
     * @param string $type Tipo de exportación: pdf o excel
     * @return \Illuminate\Http\Response
     *
     */
    public function exportarPlanilla($type)
    {
        // Obtiene data a imprimir
        $data = $this->resultData()->items();
        $jefeDeTurno = $this->getJefeDeTurno();
        $agentesConLicencia = $this->getAgentesConLicencias();
        $supervisor = User::find(Auth::id());

        $agentesTotal = count($data);
        $sector = $this->sectorEtiqueta == "E" ? "ENTRADA" : ($this->sectorEtiqueta == "S" ? "SALIDA" : "ENTRADA/SALIDA");

        // order data by etiqueta & group data by ingreso property
        $data = collect($data)->sortBy('etiqueta')->groupBy('ingreso')->toArray();
        ksort($data);

        $fecha = Carbon::parse($this->fechaInput);
        $name = "Planilla $this->fechaInput";
        $fechaStr = $fecha->locale('en')->isoFormat('D [de] MMMM [de] YYYY');
        
        // Obtener historial filtrado

    // Llama a mostrarHistorial() con el argumento $fechaStr
    $fechaStr = Carbon::parse($this->fechaInput);
    $fechaStr = $fecha->locale('en')->isoFormat('D [de] MMMM [de] YYYY');
    $historial = $this->mostrarHistorial($fechaStr);


        if($type == 'excel'){
            // Setea es planilla exportada y devuelve el excel
            $this->setEsPlanillaYaExportada(true);
            return (new PlanillaExport($data,$agentesTotal,$sector,$fechaStr,$jefeDeTurno,$agentesConLicencia))->download("$name.xlsx", \Maatwebsite\Excel\Excel::XLSX);
        }else{

            // Crea string con ruta temporal
            $tempDir = sys_get_temp_dir();
            $pdfPath = "$tempDir/$name.pdf";

            // Crea el pdf
            $pdf = Pdf::loadView('pdf.planilla', [
                'data' => $data,
                'sector' => $sector,
                'agentesTotal' => $agentesTotal,
                'fechaStr' => $fechaStr,
                'jefeDeTurno' => $jefeDeTurno,
                'agentesConLicencia' => $agentesConLicencia,
                'supervisor' => $supervisor,
                'historial' => $historial,
            ]);

            // Guarda en ruta temporal
            $pdf->save($pdfPath);

            // Setea es planilla exportada y devuelve el pdf
            $this->setEsPlanillaYaExportada(true);
            return response()->download($pdfPath)->deleteFileAfterSend(true);
        }


    }
    

    public function getJefeDeTurno(){
        $jefe = DB::select("SELECT * FROM vw_horario_actual_laravel WHERE fecha = '$this->fechaInput' AND id_puesto_migratorio = 4");
        if(count($jefe) == 0){
            return  null;
        }
        return $jefe[0];
    }
    
    public function getAgentesConLicencias($nombre_franja = null)
    {
        $supervisor = User::find(Auth::id());
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(empty($puestos->puestos_vinculados) || is_null($puestos->puestos_vinculados)){
            $integerIDs =$supervisor->id_puesto_migratorio;
        }else{

            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
        }
        //dd($integerIDs);
        $cabeceras=DB::table('planilla_horarios_cab as pc')
        ->select('pc.id_planilla_horarios_cab','pc.mes_correspondiente',
        'pc.ano_correspondiente','pc.id_puesto_migratorio')
        ->where('pc.mes_correspondiente', '=', $this->mes)
        ->where('pc.ano_correspondiente', '=', $this->anio)
        ->whereIn('pc.id_puesto_migratorio', $integerIDs)
        ->get();

        $cabeceras_ids=[];
        foreach ($cabeceras as $cabe) {
            array_push($cabeceras_ids,$cabe->id_planilla_horarios_cab);
        }

        // Según sector seleccionado al loggearse, agrega where para filtrar las etiquetas
        $andWhereEtiqueta = "";
        if($this->sectorEtiqueta == "S"){
            $andWhereEtiqueta = "AND etiqueta in ('".implode("','",self::ETIQUETAS_ENTRADA)."')";
        }else if($this->sectorEtiqueta == "E"){
            $andWhereEtiqueta = "AND etiqueta in ('".implode("','",self::ETIQUETAS_SALIDA)."')";
        }

        $data = DB::select("SELECT * FROM vw_horario_actual_laravel
        LEFT JOIN (SELECT id as lugar__id, nombre as lugar__nombre FROM lugar) lugar ON vw_horario_actual_laravel.puesto = lugar.lugar__id
        LEFT JOIN (
            SELECT id as licencia__id, id_agente as licencia__id_agente, tipo_licencia as licencia__tipo_licencia, fecha_desde as licencia__fecha_desde, fecha_hasta as licencia__fecha_hasta, fecha_14f_1 as licencia__fecha_14f_1, fecha_14f_2 as licencia__fecha_14f_2, fecha_14f_3 as licencia__fecha_14f_3, tipolicencia__nombre
            FROM licencias
            LEFT JOIN (SELECT id as tipolicencia__id, nombre as tipolicencia__nombre FROM tipo_licencia) tipolicencia ON licencias.tipo_licencia = tipolicencia.tipolicencia__id
        ) licencias ON vw_horario_actual_laravel.id_agente = licencias.licencia__id_agente
        WHERE (dia = $this->dia OR  0 = $this->dia AND fecha='".$this->fecha_form."') AND ((guardia_temporal='".$this->guardia_temporal."'
                OR guardia='".$this->guardia_temporal."')
                $andWhereEtiqueta
                AND id_planilla_horarios_cab in(".implode(',',$cabeceras_ids).")
                AND actual=1)
                AND id_puesto_migratorio IN (".implode(',',$integerIDs).")
                AND (vw_horario_actual_laravel.fecha between licencia__fecha_desde AND licencias.licencia__fecha_hasta) OR (vw_horario_actual_laravel.fecha = licencia__fecha_14f_1 OR vw_horario_actual_laravel.fecha = licencia__fecha_14f_2 OR vw_horario_actual_laravel.fecha = licencia__fecha_14f_3)
                    ". ($nombre_franja == null ? "" : "AND ingreso='".$nombre_franja."'"));

        if ($this->sortColumn === 'nombre_agente') {
            $data = collect($data)->sortBy('id_agente', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
        } else {
            $data = collect($data)->sortByDesc('orden_principal')->sortBy('ingreso')->toArray();
        }
        return $data;
    }
    
}
