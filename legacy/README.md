# Aplicación clásica

Esta es la aplicación PHP anterior. Se conserva separada de la tienda MVC nueva y se sirve localmente bajo `/clasico/`.

- `controllers/`: endpoints PHP de la aplicación clásica.
- `model/`: modelos y conexión de esa versión.
- `views/`: páginas, estilos, scripts e imágenes.
- `database/pokemon33-legacy.sql`: respaldo SQL asociado a la versión anterior.

Las reglas del `.htaccess` raíz conectan la subruta clásica con estas carpetas. Mantén juntas las carpetas `controllers/` y `model/`: los controladores antiguos incluyen modelos mediante rutas relativas.
