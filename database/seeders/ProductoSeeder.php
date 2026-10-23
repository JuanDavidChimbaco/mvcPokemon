<?php

namespace Database\Seeders;

use Core\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = $this->db->select("SELECT id, nombreCat FROM categorias WHERE estado = 'A'");
        $catMap = [];
        foreach ($categorias as $cat) {
            $catMap[strtolower($cat['nombreCat'])] = $cat['id'];
        }
        
        $admin = $this->db->selectOne("SELECT id FROM usuarios WHERE correo = 'admin@pokemonstore.com'");
        $userId = $admin['id'] ?? 1;
        
        $productos = [
            // Water
            ['nombrePro' => 'Squirtle', 'precioPro' => 29.99, 'cantidadPro' => 25, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Squirtle.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Wartortle', 'precioPro' => 39.99, 'cantidadPro' => 15, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Wartortle.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Blastoise', 'precioPro' => 59.99, 'cantidadPro' => 10, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Blastoise.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Psyduck', 'precioPro' => 24.99, 'cantidadPro' => 30, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Psyduck.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Vaporeon', 'precioPro' => 49.99, 'cantidadPro' => 12, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Vaporeon.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Gyarados', 'precioPro' => 89.99, 'cantidadPro' => 5, 'categoria' => $catMap['water'] ?? 3, 'urlFoto' => 'assets_Front/img/Gyarados.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Fire
            ['nombrePro' => 'Charmander', 'precioPro' => 29.99, 'cantidadPro' => 25, 'categoria' => $catMap['fire'] ?? 2, 'urlFoto' => 'assets_Front/img/Charmander.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Charmeleon', 'precioPro' => 39.99, 'cantidadPro' => 15, 'categoria' => $catMap['fire'] ?? 2, 'urlFoto' => 'assets_Front/img/Charmeleon.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Charizard', 'precioPro' => 89.99, 'cantidadPro' => 8, 'categoria' => $catMap['fire'] ?? 2, 'urlFoto' => 'assets_Front/img/Charizard.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Vulpix', 'precioPro' => 24.99, 'cantidadPro' => 20, 'categoria' => $catMap['fire'] ?? 2, 'urlFoto' => 'assets_Front/img/Vulpix.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Arcanine', 'precioPro' => 79.99, 'cantidadPro' => 6, 'categoria' => $catMap['fire'] ?? 2, 'urlFoto' => 'assets_Front/img/Arcanine.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Electric
            ['nombrePro' => 'Pikachu', 'precioPro' => 34.99, 'cantidadPro' => 30, 'categoria' => $catMap['electric'] ?? 4, 'urlFoto' => 'assets_Front/img/Pikachu.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Raichu', 'precioPro' => 44.99, 'cantidadPro' => 12, 'categoria' => $catMap['electric'] ?? 4, 'urlFoto' => 'assets_Front/img/Raichu.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Jolteon', 'precioPro' => 49.99, 'cantidadPro' => 10, 'categoria' => $catMap['electric'] ?? 4, 'urlFoto' => 'assets_Front/img/Jolteon.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Zapdos', 'precioPro' => 129.99, 'cantidadPro' => 3, 'categoria' => $catMap['electric'] ?? 4, 'urlFoto' => 'assets_Front/img/Zapdos.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Grass
            ['nombrePro' => 'Bulbasaur', 'precioPro' => 29.99, 'cantidadPro' => 25, 'categoria' => $catMap['grass'] ?? 5, 'urlFoto' => 'assets_Front/img/Bulbasaur.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Ivysaur', 'precioPro' => 39.99, 'cantidadPro' => 15, 'categoria' => $catMap['grass'] ?? 5, 'urlFoto' => 'assets_Front/img/Ivysaur.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Venusaur', 'precioPro' => 79.99, 'cantidadPro' => 8, 'categoria' => $catMap['grass'] ?? 5, 'urlFoto' => 'assets_Front/img/Venusaur.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Oddish', 'precioPro' => 19.99, 'cantidadPro' => 35, 'categoria' => $catMap['grass'] ?? 5, 'urlFoto' => 'assets_Front/img/Oddish.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Normal
            ['nombrePro' => 'Eevee', 'precioPro' => 39.99, 'cantidadPro' => 20, 'categoria' => $catMap['normal'] ?? 1, 'urlFoto' => 'assets_Front/img/Eevee.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Snorlax', 'precioPro' => 99.99, 'cantidadPro' => 4, 'categoria' => $catMap['normal'] ?? 1, 'urlFoto' => 'assets_Front/img/Snorlax.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Ditto', 'precioPro' => 29.99, 'cantidadPro' => 18, 'categoria' => $catMap['normal'] ?? 1, 'urlFoto' => 'assets_Front/img/Ditto.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Psychic
            ['nombrePro' => 'Abra', 'precioPro' => 24.99, 'cantidadPro' => 22, 'categoria' => $catMap['psychic'] ?? 11, 'urlFoto' => 'assets_Front/img/Abra.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Alakazam', 'precioPro' => 69.99, 'cantidadPro' => 7, 'categoria' => $catMap['psychic'] ?? 11, 'urlFoto' => 'assets_Front/img/Alakazam.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Mewtwo', 'precioPro' => 199.99, 'cantidadPro' => 1, 'categoria' => $catMap['psychic'] ?? 11, 'urlFoto' => 'assets_Front/img/Mewtwo.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            
            // Dragon
            ['nombrePro' => 'Dratini', 'precioPro' => 59.99, 'cantidadPro' => 10, 'categoria' => $catMap['dragon'] ?? 15, 'urlFoto' => 'assets_Front/img/Dratini.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Dragonair', 'precioPro' => 89.99, 'cantidadPro' => 5, 'categoria' => $catMap['dragon'] ?? 15, 'urlFoto' => 'assets_Front/img/Dragonair.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
            ['nombrePro' => 'Dragonite', 'precioPro' => 149.99, 'cantidadPro' => 2, 'categoria' => $catMap['dragon'] ?? 15, 'urlFoto' => 'assets_Front/img/Dragonite.png', 'estado' => 'A', 'usuarioCreacion' => $userId],
        ];
        
        foreach ($productos as $prod) {
            $exists = $this->db->selectOne("SELECT id FROM productos WHERE nombrePro = ? AND categoria = ?", [$prod['nombrePro'], $prod['categoria']]);
            if (!$exists) {
                $this->insert('productos', $prod);
                echo "    Created product: {$prod['nombrePro']}\n";
            } else {
                echo "    Product exists: {$prod['nombrePro']}\n";
            }
        }
    }
}