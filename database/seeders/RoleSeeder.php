<?php

namespace Database\Seeders;

use Core\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombreRol' => 'SuperAdmin', 'descripcion' => 'Super administrador con acceso total', 'estado' => 'A'],
            ['nombreRol' => 'Administrador', 'descripcion' => 'Administrador del sistema', 'estado' => 'A'],
            ['nombreRol' => 'Gerente', 'descripcion' => 'Gerente de tienda', 'estado' => 'A'],
            ['nombreRol' => 'Vendedor', 'descripcion' => 'Vendedor de mostrador', 'estado' => 'A'],
            ['nombreRol' => 'Cliente', 'descripcion' => 'Cliente de la tienda online', 'estado' => 'A'],
        ];
        
        foreach ($roles as $role) {
            $exists = $this->db->selectOne("SELECT id FROM roles WHERE nombreRol = ?", [$role['nombreRol']]);
            if (!$exists) {
                $this->insert('roles', $role);
                echo "    Created role: {$role['nombreRol']}\n";
            } else {
                echo "    Role exists: {$role['nombreRol']}\n";
            }
        }
    }
}