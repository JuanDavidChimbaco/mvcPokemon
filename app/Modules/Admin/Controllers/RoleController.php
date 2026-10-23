<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;
use Shared\Models\Rol;
use Shared\Traits\CrudController;

class RoleController extends Controller
{
    use CrudController;
    
    protected string $modelClass = Rol::class;
    protected array $searchable = ['nombreRol'];
    protected int $perPage = 15;
    protected string $viewPrefix = 'admin/roles';
    protected string $routePrefix = 'admin.roles';
    
    protected function model(): string
    {
        return Rol::class;
    }

    public function index(): Response
    {
        $roles = Rol::query()->orderBy('nombreRol')->get(Rol::class);
        return $this->view('admin/roles/index', compact('roles'));
    }

    public function create(): Response
    {
        return $this->view('admin/roles/form', ['rol' => null]);
    }

    public function store(): Response
    {
        Rol::create($this->validate($this->rules()));
        $this->flash('success', 'Rol creado correctamente');
        return $this->redirect(route('admin.roles.index'));
    }

    public function edit(int $id): Response
    {
        return $this->view('admin/roles/form', ['rol' => Rol::findOrFail($id)]);
    }

    public function update(int $id): Response
    {
        $rol = Rol::findOrFail($id);
        $validated = $this->validate([
            'nombreRol' => 'required|string|min:2|max:50|unique:roles,nombreRol,' . $id . ',id',
            'descripcion' => 'string|max:255',
            'estado' => 'required|in:A,I',
        ]);
        $rol->fill($validated)->save();
        $this->flash('success', 'Rol actualizado correctamente');
        return $this->redirect(route('admin.roles.index'));
    }

    public function destroy(int $id): Response
    {
        $rol = Rol::findOrFail($id);
        if ($rol->usuarios()->count() > 0) {
            $this->flash('error', 'No se puede eliminar un rol que está asignado a usuarios');
            return $this->redirect(route('admin.roles.index'));
        }
        $rol->delete();
        $this->flash('success', 'Rol eliminado correctamente');
        return $this->redirect(route('admin.roles.index'));
    }
    
    protected function rules(): array
    {
        return [
            'nombreRol' => 'required|string|min:2|max:50|unique:roles,nombreRol',
            'descripcion' => 'string|max:255',
            'estado' => 'in:A,I',
        ];
    }
}