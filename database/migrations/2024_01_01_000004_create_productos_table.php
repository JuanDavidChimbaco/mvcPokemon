<?php

namespace Database\Migrations;

use Core\Migration;

class CreateProductosTable extends Migration
{
    public function up(): void
    {
        $this->createTable('productos', function ($table) {
            $table->id();
            $table->string('nombrePro', 100);
            $table->decimal('precioPro', 10, 2);
            $table->integer('cantidadPro')->default(0);
            $table->foreignId('categoria')->constrained('categorias')->onDelete('RESTRICT');
            $table->string('urlFoto', 500)->nullable();
            $table->enum('estado', ['A', 'I'])->default('A');
            $table->foreignId('usuarioCreacion')->nullable()->constrained('usuarios')->onDelete('SET NULL');
            $table->foreignId('usuarioModificacion')->nullable()->constrained('usuarios')->onDelete('SET NULL');
            $table->timestamp('fechaModificacion')->nullable();
            $table->timestamps();
            
            $table->index('nombrePro');
            $table->index('categoria');
            $table->index('estado');
        });
    }
    
    public function down(): void
    {
        $this->dropTable('productos');
    }
}