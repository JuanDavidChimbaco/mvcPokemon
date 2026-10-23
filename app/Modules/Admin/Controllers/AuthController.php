<?php

namespace Modules\Admin\Controllers;

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
            'password' => 'required|string|min:6',
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

        // Verificar que es admin
        if (!$user->isAdmin()) {
            $this->flash('error', 'No tienes permisos de administrador');
            return $this->back()->withInput();
        }

        $this->login($user);
        
        if ($validated['remember'] ?? false) {
            $_SESSION['_remember'] = true;
        }

        $this->flash('success', 'Bienvenido, ' . $user->nombre_completo);
        
        return $this->redirect(route('admin.dashboard'));
    }

    public function performLogout(): \Core\Response
    {
        $this->logout();
        $this->flash('success', 'Sesión cerrada correctamente');
        return $this->redirect(route('auth.login'));
    }
}