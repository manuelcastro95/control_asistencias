<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Esta migración ya no es necesaria porque los índices y foreign keys
        // se agregaron directamente en las migraciones de creación de tablas.
        // Se mantiene vacía para no romper migraciones existentes.
        
        // Si las tablas ya existen sin estos índices, se pueden agregar aquí
        // pero es mejor hacerlo en una migración de optimización separada.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['alumno_id']);
            $table->dropIndex(['alumno_id', 'fecha']);
            $table->dropUnique('unique_alumno_fecha');
            $table->dropIndex(['fecha']);
            $table->dropIndex(['alumno_id']);
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropUnique(['codigo']);
            $table->dropIndex(['created_at']);
        });
    }
};

