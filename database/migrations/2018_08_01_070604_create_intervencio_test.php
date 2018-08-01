<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervencioTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervencio_test', function (Blueprint $table) {
            $table->increments('Codi_Procedim');
            $table->string('Codi_Nom_Ciruja');
            $table->integer('Codi_NHC_Pacient');
            $table->string('Anestesista');
            $table->timestamp('Data_Hora_Intervencio');
            $table->integer('Codi_RegQuir_Interv');
            $table->integer('Codi_RegURPA_Interv');
            $table->integer('Codi_RegPRE_Interv');
            $table->integer('Episodi');
            $table->string('Escala_Info');
            $table->string('Servei');
            $table->string('UOM_solic');
            $table->string('Proc_Principal');
            $table->string('Descripcio_Proc_Prin');
            $table->string('Prestacion');
            $table->string('Tipo_Anest');
            $table->integer('Duracion_Stack');
            $table->string('Sala_Origen');
            $table->string('Sala_Desti');
            $table->string('Missatge_Fam');
            $table->integer('Visibilitat_Familiars');
            $table->integer('Familiars_Confirmed');
            $table->integer('Cirujano_Confirmed');
            $table->integer('Duracio_Machine_Learning');
            $table->integer('Lnrls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervencio_test');
    }
}
