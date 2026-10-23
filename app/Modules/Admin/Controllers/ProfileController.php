<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;
use Shared\Models\Usuario;

class ProfileController extends Controller
{
    public function edit(): Response
    {
        return $this->view('admin/profile/edit', ['usuario' => $this->auth()]);
    }

    public function update(): Response
    {
        $usuario = $this->auth();
        if (!$usuario) {
            return $this->redirect(route('auth.login'));
        }

        $data = $this->validate([
            'nombre' => 'required|string|min:2|max:50',
            'apellido' => 'required|string|min:2|max:50',
            'correo' => 'required|email|max:100|unique:usuarios,correo,' . $usuario->id . ',id',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $usuario->fill($data)->save();
        $this->flash('success', 'Tu perfil se actualizó correctamente.');

        return $this->redirect(route('admin.profile.edit'));
    }
}
