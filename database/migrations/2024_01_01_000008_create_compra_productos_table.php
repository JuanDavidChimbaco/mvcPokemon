<?php

namespace Database\Migrations;

use Core\Migration;

class CreateCompraProductosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('compra_productos', function ($table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->onDelete('CASCADE');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('RESTRICT');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            
            $table->index('compra_id');
            $table->index('producto_id');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('compra_productos');
    }
}