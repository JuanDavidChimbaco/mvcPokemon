<?php

namespace Database\Seeders;

use Core\Seeder;

class ImpuestoSeeder extends Seeder
{
    public function run(): void
    {
        $impuestos = [
            ['nombre' => 'IVA', 'porcentaje' => 19.00, 'estado' => 'A'],
            ['nombre' => 'Impuesto al Consumo', 'porcentaje' => 8.00, 'estado' => 'A'],
            ['nombre' => 'Exento', 'porcentaje' => 0.00, 'estado' => 'A'],
        ];
        
        foreach ($impuestos as $imp) {
            $exists = $this->db->selectOne("SELECT id FROM impuestos WHERE nombre = ?", [$imp['nombre']]);
            if (!$exists) {
                $this->insert('impuestos', $imp);
                echo "    Created tax: {$imp['nombre']} ({$imp['porcentaje']}%)\n";
            } else {
                echo "    Tax exists: {$imp['nombre']}\n";
            }
        }
    }
}