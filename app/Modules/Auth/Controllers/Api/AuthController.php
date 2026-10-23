<?php

namespace Modules\Auth\Controllers\Api;

use Core\ApiController;
use Shared\Models\Usuario;
use Shared\Models\Rol;

class AuthController extends ApiController
{
    public function performLogin(): \Core\Response
    {
        $validated = $this->validate([
            'correo' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean',
        ]);
        
        $user = Usuario::authenticate($validated['correo'], $validated['password']);
        
        if (!$user) {
            return $this->unauthorized('Credenciales inválidas');
        }
        
        if ($user->estado !== Usuario::ESTADO_ACTIVO) {
            return $this->forbidden('Cuenta desactivada');
        }
        
        $this->login($user);
        
        if ($validated['remember'] ?? false) {
            $_SESSION['_remember'] = true;
        }
        
        return $this->success([
            'user' => $user,
            'token' => $this->session->token(),
        ], 'Login exitoso');
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
        ]);
        
        if (Usuario::byEmail($validated['correo'])->first()) {
            return $this->error('El correo ya está registrado', 422);
        }
        
        $rol = Rol::query()->where('nombreRol', '=', Rol::CLIENTE)->first();
        
        $user = Usuario::create([
            ...$validated,
            'idRol' => $rol?->id ?? 1,
            'estado' => Usuario::ESTADO_ACTIVO,
        ]);
        
        $this->login($user);
        
        return $this->created([
            'user' => $user,
            'token' => $this->session->token(),
        ], 'Registro exitoso');
    }

    public function performLogout(): \Core\Response
    {
        $this->logout();
        return $this->success(null, 'Logout exitoso');
    }

    public function user(): \Core\Response
    {
        $user = $this->auth();
        
        if (!$user) {
            return $this->unauthorized('No autenticado');
        }
        
        return $this->success($user);
    }

    public function passwordEmail(): \Core\Response
    {
        // TODO: Implementar envío de email para reset password
        return $this->success(null, 'Si el correo existe, recibirás instrucciones');
    }

    public function passwordReset(): \Core\Response
    {
        // TODO: Implementar reset de password con token
        return $this->success(null, 'Password actualizado');
    }
}