<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Model;
use Core\Response;
use Shared\Models\Impuesto;

class TaxController extends Controller
{
    public function index(): Response
    {
        $impuestos = Impuesto::query()->orderBy('nombre')->get(Impuesto::class);
        return $this->view('admin/taxes/index', compact('impuestos'));
    }

    public function create(): Response
    {
        return $this->view('admin/taxes/form', ['impuesto' => null]);
    }

    public function store(): Response
    {
        Impuesto::create($this->validatedData());
        $this->flash('success', 'Impuesto creado correctamente');
        return $this->redirect(route('admin.taxes.index'));
    }

    public function edit(int $id): Response
    {
        return $this->view('admin/taxes/form', ['impuesto' => Impuesto::findOrFail($id)]);
    }

    public function update(int $id): Response
    {
        $impuesto = Impuesto::findOrFail($id);
        $impuesto->fill($this->validatedData())->save();
        $this->flash('success', 'Impuesto actualizado correctamente');
        return $this->redirect(route('admin.taxes.index'));
    }

    public function destroy(int $id): Response
    {
        Impuesto::findOrFail($id)->delete();
        $this->flash('success', 'Impuesto eliminado correctamente');
        return $this->redirect(route('admin.taxes.index'));
    }

    private function validatedData(): array
    {
        return $this->validate([
            'nombre' => 'required|string|min:2|max:50',
            'porcentaje' => 'required|numeric|min:0',
            'estado' => 'required|in:A,I',
        ]);
    }
}
