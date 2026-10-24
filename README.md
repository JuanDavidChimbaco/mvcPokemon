# MVC Pokémon

PokéTienda es una aplicación PHP MVC para una tienda de productos Pokémon. El repositorio también conserva la aplicación clásica anterior.

## Estructura del proyecto

- `app/`, `resources/` y `public/`: aplicación MVC actual.
- `legacy/controllers/`, `legacy/model/` y `legacy/views/`: aplicación clásica conservada.
- `database/migrations/` y `database/seeders/`: estructura y datos iniciales de la aplicación actual.
- `legacy/database/pokemon33-legacy.sql`: respaldo SQL de la aplicación clásica; no se necesita para instalar la aplicación actual.
- `tests/manual/`: scripts de diagnóstico manual; no se ejecutan automáticamente.

## Requisitos

- PHP 8.5 recomendado, con las extensiones PDO y PDO MySQL habilitadas. `composer.json` declara PHP 8.1 como mínimo técnico, pero las ramas que ya no reciben actualizaciones de seguridad no deben usarse para desplegar el proyecto.
- Composer.
- MySQL o MariaDB.
- Node.js y npm.
- Un servidor web con PHP, por ejemplo Laragon/Apache.

Si usas Laragon, selecciona la versión de PHP instalada desde `Menu > PHP > Version` y reinicia los servicios para que Apache use la versión elegida. Conserva las versiones anteriores hasta comprobar que la tienda y los proyectos que dependan de ellas siguen funcionando.

## Instalación local

Clona el repositorio y entra en su carpeta:

```bash
git clone https://github.com/JuanDavidChimbaco/mvcPokemon.git
cd mvcPokemon
```

Instala las dependencias de PHP y JavaScript:

```bash
composer install
npm ci
```

Si no tienes Composer instalado globalmente, el repositorio incluye `tools/composer/composer.phar`; puedes usarlo con `php tools/composer/composer.phar install`.

Crea el archivo de entorno desde la plantilla. En PowerShell:

```powershell
Copy-Item .env.example .env
```

En macOS/Linux:

```bash
cp .env.example .env
```

Edita `.env` para que los valores coincidan con tu instalación. La plantilla usa MySQL en `127.0.0.1:3306`, la base `pokemon33`, el usuario `root` y contraseña vacía. Cambia especialmente `DB_NAME`, `DB_USER` y `DB_PASS` si tu MySQL usa otros valores. Ajusta `APP_URL` a la URL local en la que publicarás el proyecto; con Laragon y la carpeta `www/mvcPokemon`, el valor usual es `http://localhost/mvcPokemon/`.

Crea la base de datos vacía antes de ejecutar las migraciones. Por ejemplo, desde MySQL o phpMyAdmin:

```sql
CREATE DATABASE pokemon33
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Ejecuta las migraciones y carga los datos de demostración:

```bash
php artisan migrate --seed
```

El comando `migrate` crea las tablas de la aplicación actual y `--seed` agrega roles, categorías, productos, impuestos y usuarios de ejemplo. Las credenciales de demostración incluyen `admin@pokemonstore.com` con contraseña `admin123` y `cliente@demo.com` con contraseña `cliente123`. Son solo para desarrollo local: cámbialas o elimínalas antes de publicar el sitio.

Construye los archivos de frontend:

```bash
npm run build
```

## Ejecutar la aplicación

Configura Apache/Laragon para servir la carpeta del proyecto, inicia Apache y MySQL y abre:

```text
http://localhost/mvcPokemon/
```

El archivo `index.php` de la raíz redirige la ejecución al controlador frontal en `public/index.php`. La aplicación clásica está conservada por separado y puede abrirse en:

```text
http://localhost/mvcPokemon/clasico/
```

Si cambias la ubicación o el dominio local, actualiza `APP_URL` en `.env` y usa esa URL en el navegador. Para desarrollo de frontend con recarga en caliente, ejecuta `npm run dev` en otra terminal en lugar de `npm run build`.

## Comandos útiles

```bash
php artisan migrate          # Ejecutar migraciones pendientes
php artisan migrate --seed   # Migrar y cargar datos de demostración
php artisan migrate:status   # Consultar el estado de las migraciones
php artisan migrate --fresh  # Rehacer las migraciones (elimina las tablas existentes)
php artisan db:seed          # Volver a ejecutar los seeders
npm run build                # Generar los assets para servir la aplicación
```

**Precaución:** `php artisan migrate --fresh` revierte las migraciones registradas y las ejecuta de nuevo, lo que puede eliminar tablas y datos. No lo ejecutes contra una base de datos que quieras conservar. Tampoco ejecutes `tests/manual/test_drop_tables.php` contra una base de datos con información importante.
