<?php

namespace Database\Migrations;

use Core\Migration;

class CreatePedidoProductosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('pedido_productos', function ($table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('CASCADE');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('RESTRICT');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->index('pedido_id');
            $table->index('producto_id');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('pedido_productos');
    }
}