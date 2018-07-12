<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Intervention extends Model
{

    protected $table = 'intervencio';
    protected $primaryKey = 'Codi_procedim';

    protected $fillable = ['Codi_nom_cirujia', 'updated_at', 'created_at'];
    public $timestamps = false;

    public function scopeCheckCentre($query, $centre)
    {
        return $query->where('Servei', $centre);
    }

    public function scopeOfTheDay($query, $day)
    {
        return $query->where('Data_hora_Intervencio', 'like', $day . '%');
    }

    public function scopeGetIntervention($query, $id)
    {
        return $query->where('Codi_procedim', $id);
    }

    public function quirofan()
    {
        return $this->hasOne('App\Models\Quirofan', 'Codi_RegQuir', 'Codi_RegQuir_interv');
    }

    public function urpa()
    {
        return $this->hasOne('App\Models\Urpa', 'Codi_RegURPA', 'Codi_RegURPA_interv');
    }

    public function registrePre()
    {
        return $this->hasOne('App\Models\RegistrePre', 'Codi_RegPRE', 'Codi_RegPRE_interv');
    }

    public function pacient()
    {
        return $this->hasOne('App\Models\Patient', 'id_NHC_pacient', 'Codi_NHC_Pacient');
    }

    public static function searchInterventions($day, $centre)
    {
        $interventions = Intervention::withAuxiliar()->checkCentre($centre)->ofTheDay($day)->paginate(20);

        return $interventions;
    }

    public function scopeWithAuxiliar($query)
    {
        return $query->with(['quirofan.auxiliar_in', 'quirofan.auxiliar_out', 'urpa']);
    }

    public static function searchQuirofans($day)
    {
        $intervencions = Intervention::withAuxiliar()->ofTheDay($day);

        return $intervencions;
    }

    public static function searchPatients($day)
    {
        $intervencions = Intervention::WithPatient()->ofTheDay($day);

        return $intervencions;
    }

    public function scopeWithPatient($query)
    {
        return $query->with(['pacient', 'urpa']);
    }

    public function createIntervention($idQuirofan, $startTime)
    {
        /**
         * Exemple URL per creacio de pacient fals: http://127.0.0.1:8000/create_intervention/QUI10/2018-07-01
         */

        //$query = (new Intervention)->insertPatient($day);

        $codi_NHC = rand(0, 9999999);
        $startTime = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(0, 60) . ' minutes' . ' + ' . rand(6, 23) . ' hours'));

        $auxiliar_in = new RegProcAuxiliar();
        $auxiliar_in->Temps_creacio = $startTime;
        $auxiliar_in->Tipus = 'A';
        $auxiliar_in->save();

        $quirofan = new Quirofan();
        $quirofan->idQuirofan = $idQuirofan;
        $quirofan->T_entrada_quirofan_H2 = $startTime;
        $quirofan->T_entrada_quirofan_H2_Real = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(1, 30) . ' minutes')); // Codi_proc_auxiliar_entrada_Temps_fi?
        $quirofan->Codi_proc_auxiliar_entrada = $auxiliar_in->Codi_Proc;
// TEST        $quirofan->Codi_proc_auxiliar_entrada_Temps_fi = RegProcAuxiliar::getCodiProc($auxiliar_in->Codi_Proc);
        $quirofan->T_inici_cirugia_H3 = date('Y-m-d H:i:s', strtotime($startTime . ' + ' . rand(30, 60) . ' minutes'));
        $quirofan->T_fi_cirugia_H4 = date('Y-m-d H:i:s', strtotime($quirofan->T_inici_cirugia_H3 . ' + ' . rand(2, 120) . ' minutes'));
        $quirofan->T_sortida_Quirofan_H5 = date('Y-m-d H:i:s', strtotime($quirofan->T_fi_cirugia_H4 . ' + ' . rand(1, 3) . ' hours'));
        $quirofan->T_sortida_Quirofan_H5_Real = date('Y-m-d H:i:s', strtotime($quirofan->T_sortida_Quirofan_H5 . ' + ' . rand(1, 60) . ' minutes'));
        $quirofan->save();

        $urpa = new Urpa();
        $urpa->T_URPA_programat_min = rand(0, 60);
        $urpa->T_URPA_programat_planning = rand(61, 120);
        $urpa->T_entrada_URPA_H6 = date('Y-m-d H:i:s', strtotime($quirofan->T_sortida_Quirofan_H5 . ' + ' . rand(1, 3) . ' hours'));
        $urpa->T_sortida_URPA_H8 = date('Y-m-d H:i:s', strtotime($quirofan->T_entrada_URPA_H6 . ' + ' . rand(1, 10) . ' hours'));
        $urpa->save();

        $registrePre = new RegistrePre();
        $registrePre->save();

        $pacient = new Patient();
        $pacient->id_NHC_pacient = $codi_NHC;
        $pacient->Nom_Pacient = 'userTest' . rand(1, 22);
        $pacient->Sala_origen = 'sala';
        $pacient->save();

        /*$auxiliar_out = new RegProcAuxiliar();
        $auxiliar_out->Temps_creacio = $startTime;
        $auxiliar_out->Tipus = 'A';
        $auxiliar_out->save();*/

        $intervencio = new Intervention();
        $intervencio->Codi_NHC_Pacient = $codi_NHC;
        $intervencio->Data_hora_Intervencio = $startTime;
        $intervencio->Codi_RegQuir_interv = $quirofan->Codi_RegQuir;
        $intervencio->Codi_RegURPA_interv = $urpa->Codi_RegURPA;
        $intervencio->Codi_RegPRE_interv = $registrePre->Codi_RegPRE;
        $intervencio->Codi_nom_ciruja = '13501';
        $intervencio->Prestacion = 'O01322';
        $intervencio->Sala_origen = 'S404';
        $intervencio->Sala_desti = 'M040';
        $intervencio->missatgeFam = '15:30';
        $intervencio->Episodi = rand(1000000, 100000000);
        $intervencio->Servei = 'HCPB';
        $intervencio->serveis_id = rand(0, 99);

        $intervencio->save();
        dd($intervencio);
    }

    public function deleteIntervention($codiProcedim)
    {
        $query_interv = DB::table('intervencio')
            ->select('Codi_NHC_Pacient', 'Codi_RegPRE_interv', 'Codi_RegQuir_interv', 'Codi_RegURPA_interv', 'Codi_procedim')
            ->where('Codi_procedim', '=', $codiProcedim)->get();
        $query_interv = $query_interv->toArray();

        if ($query_interv[0]->Codi_procedim == null) {
            return -1;
        }

        // dd($query_interv[0]->Codi_procedim);

        // Eliminamos la intervenció
//        $delete_interv = DB::table('intervencio')
//            ->where('Codi_procedim', '=', $codiProcedim)->delete();

        // Comprobamos que los registros no tengan ningún Procedimiento Auxiliar asignado, si lo tienen lo eliminamos
        // A) Registre PRE
        $query_regPre = DB::table('registrepre')
            ->select('Codi_Proc_auxiliar_P')
            ->where('Codi_RegPRE', '=', $query_interv[0]->Codi_RegPRE_interv)->get();

        // Eliminamos el RegistrePre de esta intervencion
//        $delete_regPre = DB::table('registrepre')
//            ->where('Codi_RegPRE', '=', $query_interv[0]->Codi_RegPRE_interv)->delete();
        if ($query_regPre != null) {
            $Codi_Proc_auxiliar_P = $query_regPre->toArray();
            if ($Codi_Proc_auxiliar_P[0]->Codi_Proc_auxiliar_P != null) {
                // Eliminamos proc auxiliar si existe
//                DB::table('regprocauxiliar')
//                    ->where('Codi_Proc', '=', $Codi_Proc_auxiliar_P[0]->Codi_Proc_auxiliar_P)->delete();

            }
        }

        // B) RegistreQuirofan
        $query_regQuir = DB::table('registrequirofan')
            ->select('Codi_proc_auxiliar_entrada', 'Codi_proc_auxiliar_sortida', 'Codi_proc_auxiliar_ajuda', 'Codi_proc_auxiliar_est', 'Codi_proc_auxiliar_net')
            ->where('Codi_RegQuir', '=', $query_interv[0]->Codi_RegQuir_interv)->get();

        echo "Eliminar intervenció; ";
        // Eliminamos RegistreQuirofan
//        $delete_quir = DB::table('registrequirofan')
//            ->where('Codi_RegQuir', '=', $query_interv[0]->Codi_RegQuir_interv)->delete();

        if ($query_regQuir != null) {
            $Codi_Proc_auxiliar_Q = $query_regQuir->toArray();
            echo "Codi_Proc_auxiliar_Q; ";
            echo $Codi_Proc_auxiliar_Q[0]->Codi_proc_auxiliar_entrada;
            if ($Codi_Proc_auxiliar_Q[0]->Codi_proc_auxiliar_entrada != null) {
                echo "\nWe are into the if; ";



                // Eliminamos proc auxiliar si existe
                $GCM_code = DB::table('usuari_cam')
                    ->join('regprocauxiliar', 'usuari_cam.Nom_usuari', '=', 'regprocauxiliar.Nom_usuari_Reg')
                    ->select('Registration_GCM_id')
                    ->whereNotNull('regprocauxiliar.Temps_inici')
                    //->whereNull('regprocauxiliar.Temps_fi')
                    ->where([
                        ['regprocauxiliar.Codi_Proc', '=', $Codi_Proc_auxiliar_Q[0]->Codi_proc_auxiliar_entrada],
                        ['usuari_cam.online', '=', 1]
                    ])->get();
                    /*->where('regprocauxiliar.Codi_Proc', '=', $Codi_Proc_auxiliar_Q[0]->Codi_proc_auxiliar_entrada)
                    ->where('usuari_cam.online', '=', 1)->get();*/

                echo "\This is the gcm";
                if ($GCM_code)
                {
                    $GCM_code = $GCM_code->toArray();
                    dd($GCM_code);
                }
            }
        }


        //dd($Codi_Proc_auxiliar_Q[0]->Codi_proc_auxiliar_entrada);


        return $query_interv;
    }

    /*public function insertPatient($day)
    {
        DB::table('intervencio')->insert(
            [ '' => '']
        );
    }*/

    /*public function getidQuirofanAttribute($value)
    {
        $this->attributes['idQuirofan'] = strtolower($value);
        //return strtolower($value);
    }*/

}