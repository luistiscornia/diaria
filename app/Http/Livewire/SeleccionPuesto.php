<?php

namespace App\Http\Livewire;

use App\PlanillaHorarioDet;
use App\PuestoMigratorio;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SeleccionPuesto extends Component
{

    public $planillaID; // id_planilla_horarios_det
    public $nombreAgente;
    public $currentPuesto;
    public $isRedirecting = false;

    public function mount()
    {

        if(request()->input('planilla')==null||request()->input('agente')==null) throw new \Exception("Planilla o agente no enviados");

        $this->planillaID = request()->input('planilla'); // id_planilla_horarios_det
        $this->nombreAgente = request()->input('agente');

    }

    public function render()
    {
        return view('livewire.seleccion-puesto',[
        ] + $this->resultPuestos());
    }


    /**
     * Obtiene los puestos de tabla LUGAR, con la etiqueta según planilla, validando que el usuario tenga permisos para modificar dicha planilla
     * @return array
     */
    private function resultPuestos() : array
    {
        // en el request no esta enviando planilla

        // Obtiene la planilla del id_planilla_horarios_det enviado por request en planillaID
        // para obtener la etiqueta (S/S Sup./E./ E. Sup.)
        $planilla = DB::select("SELECT * FROM vw_horario_actual_laravel
            WHERE id_planilla_horarios_det = {$this->planillaID}
            AND {$this->getWhereForMe()}
            AND id_agente NOT IN (
                SELECT id_agente FROM licencias
                WHERE (fecha between fecha_desde AND fecha_hasta)
                OR (fecha = fecha_14f_1 OR fecha = fecha_14f_2 OR fecha = fecha_14f_3)
            )
            LIMIT 1");



        // Solo si la planilla es válida
        if($planilla !== null && count($planilla) === 1 ){
            $planilla = $planilla[0];
            $andWhereEtiqueta = "";
            if(in_array($planilla->etiqueta, ["E", "E Sup."] )){
                $andWhereEtiqueta = " AND etiqueta = 'E'";
            }else if(in_array($planilla->etiqueta, ["S", "S Sup." ])){
                $andWhereEtiqueta = " AND etiqueta = 'S'";
            }else{
                return [
                    "error" => "Etiqueta desconocida."
                ];
            }

            // dd($planilla);


            // DB::enableQueryLog();

            // $lugares = DB::select("SELECT * FROM lugar
            //     JOIN (SELECT id as grupo__id, nombre as grupo__nombre FROM lugar_grupo) grupo ON lugar.id_lugar_grupo = grupo.grupo__id
            //     LEFT JOIN (SELECT id_planilla_horarios_det as planilla__id, puesto as planilla__puesto, id_franja_horaria as planilla__id_franja_horaria
            //         FROM planilla_horarios_det WHERE id_franja_horaria = (?) GROUP BY planilla__puesto) planilla ON (lugar.id = planilla.planilla__puesto)
            //     $andWhereEtiqueta",[$planilla->id_franja_horaria]);


            $lugares = DB::select("SELECT * FROM lugar
                JOIN (SELECT id as grupo__id, nombre as grupo__nombre FROM lugar_grupo) grupo ON lugar.id_lugar_grupo = grupo.grupo__id
                LEFT JOIN (SELECT id_planilla_horarios_det as vw_horario__id, puesto as vw_horario__puesto, ingreso as vw_horario__id_franja_horaria, fecha as vw_horario__fecha
                    FROM vw_horario_actual_laravel WHERE ingreso = (?) AND fecha = (?) GROUP BY vw_horario__puesto) vw_horario ON (lugar.id = vw_horario.vw_horario__puesto)
                WHERE id_puesto_migratorio = (?)
                $andWhereEtiqueta",[$planilla->ingreso,$planilla->fecha,$planilla->id_puesto_migratorio]);


            // dd($planilla,DB::getQueryLog(), $lugares);

            // dd( $lugares, $this->planillaID);
            // Take all the different grupo_nombre values into the array
            $grupos = array_unique(array_column($lugares, 'grupo__nombre'));

            return [ "lugares" => $lugares, "grupos" =>  $grupos, "currentPuestoId" => $planilla->puesto ];

        }


        return [
            "error" => "Planilla no encontrada o sin permisos."
        ];


    }

    /**
     * Obtiene el where para filtrar los puestos migratorios que le corresponden al usuario
     * @return string
     */
    private function getWhereForMe() : string
    {

        // Obtiene el id_puesto_migratorio de User y sus vinculados, para verificar que le corresponda modificar la planilla
        $supervisor = User::find(Auth::id());
        $puestos = PuestoMigratorio::puestosMigratorios($supervisor->id_puesto_migratorio)->first();
        if(empty($puestos->puestos_vinculados) || is_null($puestos->puestos_vinculados)){
            $integerIDs =$supervisor->id_puesto_migratorio;
        }else{

            $integerIDs =array_map('intval', explode(';', $puestos->puestos_vinculados.';'. $supervisor->id_puesto_migratorio));
        }

        return "id_puesto_migratorio IN (".implode(',',$integerIDs).")";

    }

    /**
     * Setea el puesto actual, para luego habilitarlo/asignarlo
     */
    public function setPuestoYAsignar($lugarID, $lugarNombre)
    {

        $this->setPuesto($lugarID, $lugarNombre);
        return $this->asignarPuestoConRelevoTempranoComo(null);
    }

    public function setPuesto($lugarID, $lugarNombre)
    {

        $this->currentPuesto = [
            "id" => $lugarID,
            "nombre" => $lugarNombre,
        ];

    }



    /**
     * Habilita el puesto actualmente seleccionado
     */
    public function habilitarPuesto()
    {
        DB::table('lugar')
            ->where('id', $this->currentPuesto['id'])
            ->update(['habilitado' => 1]);
    }


    /**
     * Asigna el puesto actualmente seleccionado a tabla planilla_horarios_det,y si es relevo temprano o no
     * validando que el puesto esté habilitado
     *  @param #esRelevo bool|null
     */
    public function asignarPuestoConRelevoTempranoComo($esRelevo = null)
    {
        // Actualiza presente del agente
        $planilla = PlanillaHorarioDet::where('id_planilla_horarios_det', '=', $this->planillaID)
            ->first();

        $planilla->update([
                'presente' => "SI",
                'puesto' => $this->currentPuesto['id'],
                'rel_temp' => (($esRelevo === null) ? null : ($esRelevo == true ? "SI" : "NO"))
            ]);

        $this->isRedirecting = true;
        // return redirect()->to('/home');

    }

}
