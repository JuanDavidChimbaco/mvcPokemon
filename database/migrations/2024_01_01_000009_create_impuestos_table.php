<?php

namespace Database\Migrations;

use Core\Migration;

class CreateImpuestosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('impuestos', function ($table) {
            $table->id();
            $table->string('nombre', 50);
            $table->decimal('porcentaje', 5, 2);
            $table->enum('estado', ['A', 'I'])->default('A');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        $this->dropTable('impuestos');
    }
}