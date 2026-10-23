<?php

namespace Database\Migrations;

use Core\Migration;

class CreateCategoriasTable extends Migration
{
    public function up(): void
    {
        $this->createTable('categorias', function ($table) {
            $table->id();
            $table->string('nombreCat', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['A', 'I'])->default('A');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        $this->dropTable('categorias');
    }
}