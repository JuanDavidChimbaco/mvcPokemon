<?php

namespace Shared\Traits;

use Core\Controller;
use Core\Request;
use Core\Response;
use Core\Model;

/**
 * Trait CRUD para controladores de recursos
 * Uso: class ProductController extends Controller { use CrudController; ... }
 */
trait CrudController
{
    protected array $validationRules = [];
    
    // ===== Configuración requerida =====
    
    abstract protected function model(): string;
    abstract protected function rules(): array;
    
    // ===== Métodos CRUD =====
    
    public function index(): Response
    {
        $this->modelClass = $this->model();
        $this->validationRules = $this->rules();
        $this->viewPrefix = $this->viewPrefix ?: strtolower(class_basename($this->modelClass)) . 's';
        $this->routePrefix = $this->routePrefix ?: strtolower(class_basename($this->modelClass)) . 's';
        
        $search = $this->request->query('search', '');
        $page = (int)$this->request->query('page', 1);
        
        $query = $this->modelClass::query();
        
        if ($search && $this->searchable) {
            $query = $this->applySearch($query, $search);
        }
        
        $paginated = $query->paginate($this->perPage, $page);
        
        if ($this->request->isJson() || $this->request->isAjax()) {
            return $this->json($paginated);
        }
        
        return $this->view("{$this->viewPrefix}/index", [
            'items' => $paginated['data'],
            'pagination' => $paginated,
            'search' => $search,
        ]);
    }
    
    public function create(): Response
    {
        return $this->view("{$this->viewPrefix}/create");
    }
    
    public function store(): Response
    {
        $this->modelClass = $this->model();
        $this->validationRules = $this->rules();
        
        $validated = $this->validate($this->validationRules['create'] ?? $this->validationRules);
        $this->beforeCreate($validated);
        
        $item = $this->modelClass::create($validated);
        $this->afterCreate($item);
        
        if ($this->request->isJson() || $this->request->isAjax()) {
            return $this->json(['success' => true, 'data' => $item->toArray()], 201);
        }
        
        $this->flash('success', 'Creado exitosamente');
        return $this->redirect(route("{$this->routePrefix}.index"));
    }
    
    public function show(int $id): Response
    {
        $this->modelClass = $this->model();
        $item = $this->modelClass::findOrFail($id);
        
        if ($this->request->isJson() || $this->request->isAjax()) {
            return $this->json($item->toArray());
        }
        
        return $this->view("{$this->viewPrefix}/show", ['item' => $item]);
    }
    
    public function edit(int $id): Response
    {
        $this->modelClass = $this->model();
        $item = $this->modelClass::findOrFail($id);
        
        return $this->view("{$this->viewPrefix}/edit", ['item' => $item]);
    }
    
    public function update(int $id): Response
    {
        $this->modelClass = $this->model();
        $this->validationRules = $this->rules();
        
        $item = $this->modelClass::findOrFail($id);
        $validated = $this->validate($this->validationRules['update'] ?? $this->validationRules);
        
        $this->beforeUpdate($item, $validated);
        $item->fill($validated)->save();
        $this->afterUpdate($item);
        
        if ($this->request->isJson() || $this->request->isAjax()) {
            return $this->json(['success' => true, 'data' => $item->toArray()]);
        }
        
        $this->flash('success', 'Actualizado exitosamente');
        return $this->redirect(route("{$this->routePrefix}.index"));
    }
    
    public function destroy(int $id): Response
    {
        $this->modelClass = $this->model();
        $item = $this->modelClass::findOrFail($id);
        
        $this->beforeDelete($item);
        $item->delete();
        $this->afterDelete($item);
        
        if ($this->request->isJson() || $this->request->isAjax()) {
            return $this->json(['success' => true]);
        }
        
        $this->flash('success', 'Eliminado exitosamente');
        return $this->redirect(route("{$this->routePrefix}.index"));
    }
    
    // ===== Helpers =====
    
    protected function applySearch(\Core\QueryBuilder $query, string $search): \Core\QueryBuilder
    {
        $first = true;
        foreach ($this->searchable as $field) {
            if ($first) {
                $query->where($field, 'LIKE', "%{$search}%");
                $first = false;
            } else {
                $query->where($field, 'LIKE', "%{$search}%", 'or');
            }
        }
        return $query;
    }
    
    // Sobrescribir en controlador hijo para lógica personalizada
    protected function beforeCreate(array &$data): void {}
    protected function beforeUpdate(Model $model, array &$data): void {}
    protected function beforeDelete(Model $model): void {}
    protected function afterCreate(Model $model): void {}
    protected function afterUpdate(Model $model): void {}
    protected function afterDelete(Model $model): void {}
}