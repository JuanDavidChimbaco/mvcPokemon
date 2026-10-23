# MVC Pokémon

El repositorio conserva la aplicación anterior y la tienda MVC nueva en áreas separadas.

## Aplicaciones

- `app/`, `resources/` y `public/`: tienda MVC actual.
- `legacy/controllers/`, `legacy/model/` y `legacy/views/`: aplicación clásica conservada.
- `database/`: migraciones y seeders de la aplicación nueva.
- `legacy/database/`: respaldo SQL de la aplicación clásica.
- `tests/manual/`: scripts de diagnóstico manual; no se ejecutan automáticamente.
- `tools/composer/`: instalador y archivo PHAR de Composer que estaban sueltos en la raíz.

La tienda se abre desde Laragon en `http://localhost/mvcPokemon/`. La versión clásica permanece accesible localmente en `http://localhost/mvcPokemon/clasico/`.

No ejecutes `tests/manual/test_drop_tables.php` contra una base de datos que quieras conservar: ese script elimina tablas.
