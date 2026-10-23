<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        $settings = [
            'Aplicación' => config('app.name', 'Pokémon Store'),
            'Entorno' => config('app.env', 'local'),
            'Idioma' => config('app.locale', 'es'),
            'Zona horaria' => config('app.timezone', 'America/Mexico_City'),
            'Conexión de base de datos' => config('database.default', 'No definida'),
        ];

        return $this->view('admin/settings/index', compact('settings'));
    }
}
