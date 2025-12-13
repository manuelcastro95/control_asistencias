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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->date('fecha');
            $table->timestamps();
            
            // Índices para optimización
            $table->index('fecha');
            $table->index('alumno_id');
            $table->index(['alumno_id', 'fecha']);
            
            // Evitar duplicados: un alumno solo puede tener una asistencia por día
            $table->unique(['alumno_id', 'fecha'], 'unique_alumno_fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};
