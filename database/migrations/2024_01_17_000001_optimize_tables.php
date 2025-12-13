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
        try {
            // Optimizar tabla alumnos
            Schema::table('alumnos', function (Blueprint $table) {
                // Índices adicionales para búsquedas (si no existen ya)
                try {
                    $table->index('nombres', 'alumnos_nombres_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('apellidos', 'alumnos_apellidos_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('email', 'alumnos_email_index');
                } catch (\Exception $e) {}
                
                // Índice compuesto para búsquedas comunes
                try {
                    $table->index(['activo', 'grado_id'], 'alumnos_busqueda_index');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {
            // Ignorar si ya existen
        }

        try {
            // Optimizar tabla asistencias
            Schema::table('asistencias', function (Blueprint $table) {
                // Índice compuesto para consultas por fecha y alumno
                try {
                    $table->index(['fecha', 'alumno_id'], 'asistencias_fecha_alumno_index');
                } catch (\Exception $e) {}
                
                // Índice para consultas por mes/año
                try {
                    $table->index('created_at', 'asistencias_created_at_index');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {
            // Ignorar si ya existen
        }

        try {
            // Optimizar tabla instituciones
            Schema::table('instituciones', function (Blueprint $table) {
                try {
                    $table->index('nombre', 'instituciones_nombre_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('activo', 'instituciones_activo_index');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {
            // Ignorar si ya existen
        }

        try {
            // Optimizar tabla sedes
            Schema::table('sedes', function (Blueprint $table) {
                try {
                    $table->index('nombre', 'sedes_nombre_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('activo', 'sedes_activo_index');
                } catch (\Exception $e) {}
                
                // Índice compuesto para búsquedas por institución y activo
                try {
                    $table->index(['institucion_id', 'activo'], 'sedes_institucion_activo_index');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {
            // Ignorar si ya existen
        }

        try {
            // Optimizar tabla grados
            Schema::table('grados', function (Blueprint $table) {
                try {
                    $table->index('nombre', 'grados_nombre_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('activo', 'grados_activo_index');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('orden', 'grados_orden_index');
                } catch (\Exception $e) {}
                
                // Índice compuesto para búsquedas por sede y activo
                try {
                    $table->index(['sede_id', 'activo'], 'grados_sede_activo_index');
                } catch (\Exception $e) {}
            });
        } catch (\Exception $e) {
            // Ignorar si ya existen
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grados', function (Blueprint $table) {
            $table->dropIndex('grados_sede_activo_index');
            $table->dropIndex('grados_orden_index');
            $table->dropIndex('grados_activo_index');
            $table->dropIndex('grados_nombre_index');
        });

        Schema::table('sedes', function (Blueprint $table) {
            $table->dropIndex('sedes_institucion_activo_index');
            $table->dropIndex('sedes_activo_index');
            $table->dropIndex('sedes_nombre_index');
        });

        Schema::table('instituciones', function (Blueprint $table) {
            $table->dropIndex('instituciones_activo_index');
            $table->dropIndex('instituciones_nombre_index');
        });

        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropIndex('asistencias_created_at_index');
            $table->dropIndex('asistencias_fecha_index');
            $table->dropIndex('asistencias_fecha_alumno_index');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropIndex('alumnos_busqueda_index');
            $table->dropIndex('alumnos_email_index');
            $table->dropIndex('alumnos_apellidos_index');
            $table->dropIndex('alumnos_nombres_index');
            $table->dropUnique('alumnos_codigo_unique');
        });
    }

};

