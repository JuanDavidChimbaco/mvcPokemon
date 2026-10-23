<?php

namespace Database\Migrations;

use Core\Migration;

class CreateRolesTable extends Migration
{
    public function up(): void
    {
        $this->createTable('roles', function ($table) {
            $table->id();
            $table->string('nombreRol', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['A', 'I'])->default('A');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        $this->dropTable('roles');
    }
}