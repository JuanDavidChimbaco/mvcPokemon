<?php

namespace Database\Seeders;

use Core\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombreCat' => 'Normal', 'descripcion' => 'Pokémon de tipo Normal', 'estado' => 'A'],
            ['nombreCat' => 'Fire', 'descripcion' => 'Pokémon de tipo Fuego', 'estado' => 'A'],
            ['nombreCat' => 'Water', 'descripcion' => 'Pokémon de tipo Agua', 'estado' => 'A'],
            ['nombreCat' => 'Electric', 'descripcion' => 'Pokémon de tipo Eléctrico', 'estado' => 'A'],
            ['nombreCat' => 'Grass', 'descripcion' => 'Pokémon de tipo Planta', 'estado' => 'A'],
            ['nombreCat' => 'Ice', 'descripcion' => 'Pokémon de tipo Hielo', 'estado' => 'A'],
            ['nombreCat' => 'Fighting', 'descripcion' => 'Pokémon de tipo Lucha', 'estado' => 'A'],
            ['nombreCat' => 'Poison', 'descripcion' => 'Pokémon de tipo Veneno', 'estado' => 'A'],
            ['nombreCat' => 'Ground', 'descripcion' => 'Pokémon de tipo Tierra', 'estado' => 'A'],
            ['nombreCat' => 'Flying', 'descripcion' => 'Pokémon de tipo Volador', 'estado' => 'A'],
            ['nombreCat' => 'Psychic', 'descripcion' => 'Pokémon de tipo Psíquico', 'estado' => 'A'],
            ['nombreCat' => 'Bug', 'descripcion' => 'Pokémon de tipo Bicho', 'estado' => 'A'],
            ['nombreCat' => 'Rock', 'descripcion' => 'Pokémon de tipo Roca', 'estado' => 'A'],
            ['nombreCat' => 'Ghost', 'descripcion' => 'Pokémon de tipo Fantasma', 'estado' => 'A'],
            ['nombreCat' => 'Dragon', 'descripcion' => 'Pokémon de tipo Dragón', 'estado' => 'A'],
            ['nombreCat' => 'Dark', 'descripcion' => 'Pokémon de tipo Siniestro', 'estado' => 'A'],
            ['nombreCat' => 'Steel', 'descripcion' => 'Pokémon de tipo Acero', 'estado' => 'A'],
            ['nombreCat' => 'Fairy', 'descripcion' => 'Pokémon de tipo Hada', 'estado' => 'A'],
        ];
        
        foreach ($categorias as $cat) {
            $exists = $this->db->selectOne("SELECT id FROM categorias WHERE nombreCat = ?", [$cat['nombreCat']]);
            if (!$exists) {
                $this->insert('categorias', $cat);
                echo "    Created category: {$cat['nombreCat']}\n";
            } else {
                echo "    Category exists: {$cat['nombreCat']}\n";
            }
        }
    }
}