<?php

namespace Database\Migrations;

use Core\Migration;

class CreateComprasTable extends Migration
{
    public function up(): void
    {
        $this->createTable('compras', function ($table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('RESTRICT');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'recibida', 'cancelada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->index('usuario_id');
            $table->index('estado');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('compras');
    }
}