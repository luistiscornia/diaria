<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddHabilitadoYEtiquetaToLugarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lugar', function (Blueprint $table) {
            $table->boolean('habilitado')->default(false);
            $table->string('etiqueta')->default('');
        });

        // ALTER TABLE p_sig_agentes.lugar
        // ADD habilitado TINYINT(1) NOT NULL DEFAULT 0,
        // ADD etiqueta VARCHAR(255) NOT NULL DEFAULT '';

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lugar', function (Blueprint $table) {
            $table->dropColumn('habilitado');
            $table->dropColumn('etiqueta');
        });
    }
}
