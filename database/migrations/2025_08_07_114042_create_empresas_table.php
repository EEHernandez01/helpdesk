<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Crear tabla empresas
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('rfc', 13)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Insertar las 6 empresas iniciales
        DB::table('companies')->insert([
            ['nombre' => 'Genbio'],
            ['nombre' => 'Genbio Banco de Sangre'],
            ['nombre' => 'Medialisis'],
            ['nombre' => 'Novagenic'],
            ['nombre' => 'TelRx'],
            ['nombre' => 'Velastra'],
        ]);

        // Agregar relación en la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Revertir cambios
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });

        Schema::dropIfExists('companies');
    }
};
