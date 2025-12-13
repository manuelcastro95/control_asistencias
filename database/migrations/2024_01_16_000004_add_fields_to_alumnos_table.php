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
        Schema::table('alumnos', function (Blueprint $table) {
            $table->foreignId('grado_id')->nullable()->after('id')->constrained('grados')->onDelete('set null');
            $table->string('email')->nullable()->after('apellidos');
            $table->string('telefono')->nullable()->after('email');
            $table->date('fecha_nacimiento')->nullable()->after('telefono');
            $table->enum('genero', ['M', 'F', 'O'])->nullable()->after('fecha_nacimiento');
            $table->string('documento_identidad')->nullable()->after('genero');
            $table->text('direccion')->nullable()->after('documento_identidad');
            $table->string('nombre_acudiente')->nullable()->after('direccion');
            $table->string('telefono_acudiente')->nullable()->after('nombre_acudiente');
            $table->text('observaciones')->nullable()->after('telefono_acudiente');
            $table->boolean('activo')->default(true)->after('observaciones');
            
            $table->index('grado_id');
            $table->index('documento_identidad');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropForeign(['grado_id']);
            $table->dropIndex(['grado_id']);
            $table->dropIndex(['documento_identidad']);
            $table->dropIndex(['activo']);
            $table->dropColumn([
                'grado_id',
                'email',
                'telefono',
                'fecha_nacimiento',
                'genero',
                'documento_identidad',
                'direccion',
                'nombre_acudiente',
                'telefono_acudiente',
                'observaciones',
                'activo'
            ]);
        });
    }
};

