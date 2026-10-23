<?php

namespace Database\Migrations;

use Core\Migration;

class CreatePedidosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('pedidos', function ($table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('RESTRICT');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'])->default('pendiente');
            $table->text('direccion_envio')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->index('usuario_id');
            $table->index('estado');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('pedidos');
    }
}