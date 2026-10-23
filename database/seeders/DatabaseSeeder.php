<?php

namespace Database\Seeders;

use Core\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "Running database seeders...\n\n";
        
        // Orden importante: roles -> categorias -> usuarios -> productos -> impuestos
        $seeders = [
            RoleSeeder::class,
            CategoriaSeeder::class,
            UsuarioSeeder::class,
            ProductoSeeder::class,
            ImpuestoSeeder::class,
        ];
        
        foreach ($seeders as $seederClass) {
            $seeder = new $seederClass();
            $seeder->run();
        }
        
        echo "\nAll seeders completed!\n";
    }
}