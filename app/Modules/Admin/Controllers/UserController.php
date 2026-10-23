<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Shared\Models\Usuario;
use Shared\Models\Rol;
use Shared\Traits\CrudController;

class UserController extends Controller
{
    use CrudController;
    
    protected string $modelClass = Usuario::class;
    protected array $searchable = ['nombre', 'apellido', 'correo', 'identificacion'];
    protected int $perPage = 15;
    protected string $viewPrefix = 'admin/users';
    protected string $routePrefix = 'admin.users';
    
    protected function model(): string
    {
        return Usuario::class;
    }

    public function index(): \Core\Response
    {
        $usuarios = Usuario::query()
            ->orderBy('id', 'desc')
            ->get(Usuario::class);

        return $this->view('admin/users/index', compact('usuarios'));
    }
    
    public function create(): \Core\Response
    {
        $roles = Rol::ordered()->get(Rol::class);
        return $this->view('admin/users/form', ['usuario' => null, 'roles' => $roles]);
    }
    
    public function edit(int $id): \Core\Response
    {
        $item = Usuario::findOrFail($id);
        $roles = Rol::ordered()->get(Rol::class);
        return $this->view('admin/users/form', ['usuario' => $item, 'roles' => $roles]);
    }
    
    protected function rules(): array
    {
        return [
            'create' => [
                'tipoDoc' => 'required|string|max:20',
                'identificacion' => 'required|numeric|unique:usuarios,identificacion',
                'nombre' => 'required|string|min:2|max:50',
                'apellido' => 'required|string|min:2|max:50',
                'correo' => 'required|email|max:100|unique:usuarios,correo',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'genero' => 'required|in:M,F',
                'password' => 'required|string|min:6|confirmed',
                'idRol' => 'required|integer',
                'estado' => 'in:A,I',
            ],
            'update' => [
                'tipoDoc' => 'required|string|max:20',
                'identificacion' => 'required|numeric|unique:usuarios,identificacion,' . ($this->request->route('id') ?? '') . ',id',
                'nombre' => 'required|string|min:2|max:50',
                'apellido' => 'required|string|min:2|max:50',
                'correo' => 'required|email|max:100|unique:usuarios,correo,' . ($this->request->route('id') ?? '') . ',id',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'genero' => 'required|in:M,F',
                'password' => 'nullable|string|min:6|confirmed',
                'idRol' => 'required|integer',
                'estado' => 'in:A,I',
            ],
        ];
    }
    
    protected function beforeCreate(array &$data): void
    {
        // Password se hashea automáticamente en el modelo
    }
    
    protected function beforeUpdate(\Core\Model $model, array &$data): void
    {
        // Si password está vacío, no actualizar
        if (empty($data['password'])) {
            unset($data['password']);
        }
    }
}