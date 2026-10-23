<?php

namespace Modules\Auth\Controllers;

use Core\Controller;
use Shared\Models\Usuario;
use Shared\Models\Rol;

class AuthController extends Controller
{
    public function showLogin(): \Core\Response
    {
        if ($this->check()) {
            return $this->redirect(route('admin.dashboard'));
        }
        return $this->view('auth/login');
    }

    public function performLogin(): \Core\Response
    {
        $validated = $this->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $user = Usuario::authenticate($validated['correo'], $validated['password']);
        
        if (!$user) {
            $this->flash('error', 'Credenciales inválidas');
            $this->flashInput(['correo' => $validated['correo']]);
            return $this->back();
        }

        if ($user->estado !== Usuario::ESTADO_ACTIVO) {
            $this->flash('error', 'Cuenta desactivada');
            $this->flashInput(['correo' => $validated['correo']]);
            return $this->back();
        }

        $this->login($user);
        
        if ($validated['remember'] ?? false) {
            // Extender sesión a 30 días
            $_SESSION['_remember'] = true;
        }

        $this->flash('success', 'Bienvenido, ' . $user->nombre_completo);
        
        // Redirigir según rol
        if ($user->isAdmin()) {
            return $this->redirect(route('admin.dashboard'));
        }
        
        $redirect = session()->pull('_redirect_intended', route('home'));
        return $this->redirect($redirect);
    }

    public function showRegister(): \Core\Response
    {
        if ($this->check()) {
            return $this->redirect(route('home'));
        }
        return $this->view('auth/register');
    }

    public function register(): \Core\Response
    {
        $validated = $this->validate([
            'tipoDoc' => 'required|string|max:20',
            'identificacion' => 'required|numeric|unique:usuarios,identificacion',
            'nombre' => 'required|string|min:2|max:50',
            'apellido' => 'required|string|min:2|max:50',
            'correo' => 'required|email|max:100|unique:usuarios,correo',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'genero' => 'required|in:M,F',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted',
        ]);

        // Verificar si ya existe
        if (Usuario::byEmail($validated['correo'])->first()) {
            $this->flash('error', 'El correo ya está registrado');
            $this->flashInput(['correo' => $validated['correo']]);
            return $this->back();
        }

        // Rol por defecto: Cliente
        $rol = Rol::query()->where('nombreRol', '=', Rol::CLIENTE)->first();
        
        $user = Usuario::create([
            ...$validated,
            'idRol' => $rol?->id ?? 1,
            'estado' => Usuario::ESTADO_ACTIVO,
        ]);

        $this->login($user);
        $this->flash('success', 'Registro exitoso. Bienvenido, ' . $user->nombre_completo);
        
        return $this->redirect(route('home'));
    }

    public function performLogout(): \Core\Response
    {
        $this->logout();
        $this->flash('success', 'Sesión cerrada correctamente');
        return $this->redirect(route('home'));
    }
}