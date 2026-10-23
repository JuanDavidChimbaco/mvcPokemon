<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Shared\Models\Categoria;
use Shared\Traits\CrudController;

class CategoryController extends Controller
{
    use CrudController;
    
    protected string $modelClass = Categoria::class;
    protected array $searchable = ['nombreCat'];
    protected int $perPage = 15;
    protected string $viewPrefix = 'admin/categories';
    protected string $routePrefix = 'admin.categories';
    
    protected function model(): string
    {
        return Categoria::class;
    }

    public function index(): \Core\Response
    {
        $categorias = Categoria::query()->orderBy('nombreCat')->get(Categoria::class);
        return $this->view('admin/categories/index', compact('categorias'));
    }

    public function create(): \Core\Response
    {
        return $this->view('admin/categories/form', ['categoria' => null]);
    }

    public function edit(int $id): \Core\Response
    {
        return $this->view('admin/categories/form', ['categoria' => Categoria::findOrFail($id)]);
    }
    
    protected function rules(): array
    {
        return [
            'nombreCat' => 'required|string|min:2|max:50',
            'descripcion' => 'string|max:255',
            'estado' => 'in:A,I',
        ];
    }
}