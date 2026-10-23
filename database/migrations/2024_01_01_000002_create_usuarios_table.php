<?php

namespace Database\Migrations;

use Core\Migration;

class CreateUsuariosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('usuarios', function ($table) {
            $table->id();
            $table->string('tipoDoc', 20);
            $table->bigInteger('identificacion')->unique();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('correo', 100)->unique();
            $table->string('direccion', 255);
            $table->string('telefono', 20);
            $table->enum('genero', ['M', 'F']);
            $table->string('password', 255);
            $table->foreignId('idRol')->constrained('roles')->onDelete('RESTRICT');
            $table->enum('estado', ['A', 'I'])->default('A');
            $table->timestamps();
            
            $table->index('correo');
            $table->index('estado');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('usuarios');
    }
}