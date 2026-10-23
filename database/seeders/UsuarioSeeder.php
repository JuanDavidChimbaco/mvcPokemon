<?php

namespace Database\Seeders;

use Core\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener rol SuperAdmin
        $superAdmin = $this->db->selectOne("SELECT id FROM roles WHERE nombreRol = 'SuperAdmin'");
        $adminRole = $this->db->selectOne("SELECT id FROM roles WHERE nombreRol = 'Administrador'");
        $clienteRole = $this->db->selectOne("SELECT id FROM roles WHERE nombreRol = 'Cliente'");
        
        $usuarios = [
            [
                'tipoDoc' => 'CC',
                'identificacion' => 1000000001,
                'nombre' => 'Admin',
                'apellido' => 'Principal',
                'correo' => 'admin@pokemonstore.com',
                'direccion' => 'Calle Principal 123',
                'telefono' => '3001234567',
                'genero' => 'M',
                'password' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]),
                'idRol' => $superAdmin['id'] ?? 1,
                'estado' => 'A',
            ],
            [
                'tipoDoc' => 'CC',
                'identificacion' => 1000000002,
                'nombre' => 'Admin',
                'apellido' => 'Secundario',
                'correo' => 'admin2@pokemonstore.com',
                'direccion' => 'Carrera 45 #67-89',
                'telefono' => '3007654321',
                'genero' => 'F',
                'password' => password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]),
                'idRol' => $adminRole['id'] ?? 2,
                'estado' => 'A',
            ],
            [
                'tipoDoc' => 'CC',
                'identificacion' => 2000000001,
                'nombre' => 'Cliente',
                'apellido' => 'Demo',
                'correo' => 'cliente@demo.com',
                'direccion' => 'Avenida Demo 456',
                'telefono' => '3101112233',
                'genero' => 'M',
                'password' => password_hash('cliente123', PASSWORD_BCRYPT, ['cost' => 12]),
                'idRol' => $clienteRole['id'] ?? 5,
                'estado' => 'A',
            ],
        ];
        
        foreach ($usuarios as $user) {
            $exists = $this->db->selectOne("SELECT id FROM usuarios WHERE correo = ?", [$user['correo']]);
            if (!$exists) {
                $this->insert('usuarios', $user);
                echo "    Created user: {$user['correo']}\n";
            } else {
                echo "    User exists: {$user['correo']}\n";
            }
        }
    }
}