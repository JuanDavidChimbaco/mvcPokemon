<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Shared\Models\Producto;
use Shared\Models\Categoria;
use Shared\Traits\CrudController;

class ProductController extends Controller
{
    use CrudController;
    
    protected string $modelClass = Producto::class;
    protected array $searchable = ['nombrePro', 'descripcion'];
    protected int $perPage = 10;
    protected string $viewPrefix = 'admin/products';
    protected string $routePrefix = 'admin.products';
    
    protected function model(): string
    {
        return Producto::class;
    }

    public function index(): \Core\Response
    {
        $search = trim((string)$this->request->query('search', ''));
        $category = $this->request->query('categoria', '');
        $status = $this->request->query('estado', '');

        $query = Producto::query()
            ->leftJoin('categorias', 'productos.categoria', '=', 'categorias.id')
            ->select(['productos.*', 'categorias.nombreCat as categoria_nombre'])
            ->orderBy('productos.nombrePro');

        if ($search !== '') {
            $query->where('productos.nombrePro', 'LIKE', "%{$search}%");
        }
        if ($category !== '') {
            $query->where('productos.categoria', '=', $category);
        }
        if ($status !== '') {
            $query->where('productos.estado', '=', $status);
        }

        $productos = $query->get(Producto::class);
        $categorias = Categoria::ordered()->get(Categoria::class);

        return $this->view('admin/products/index', compact('productos', 'categorias', 'search', 'category', 'status'));
    }

    public function store(): \Core\Response
    {
        $data = $this->validate($this->rules());
        $uploadError = $this->productImageUploadError();
        if ($uploadError !== null) {
            $this->flash('error', $uploadError);
            $this->flashInput($data);
            return $this->back();
        }
        if (!$this->isAllowedImageReference($data['urlFoto'] ?? '')) {
            $this->flash('error', 'La imagen seleccionada no es válida. Elige un archivo o usa el arte oficial de PokéAPI.');
            $this->flashInput($data);
            return $this->back();
        }

        $uploadedImage = $this->storeProductImageUpload();
        if ($uploadedImage !== null) {
            $data['urlFoto'] = $uploadedImage;
        }

        $this->beforeCreate($data);
        Producto::create($data);
        $this->flash('success', 'Producto creado correctamente.');
        return $this->redirect(route('admin.products.index'));
    }

    public function update(int $id): \Core\Response
    {
        $product = Producto::findOrFail($id);
        $data = $this->validate($this->rules());
        $uploadError = $this->productImageUploadError();
        if ($uploadError !== null) {
            $this->flash('error', $uploadError);
            $this->flashInput($data);
            return $this->back();
        }
        if (!$this->isAllowedImageReference($data['urlFoto'] ?? '')) {
            $this->flash('error', 'La imagen seleccionada no es válida. Elige un archivo o usa el arte oficial de PokéAPI.');
            $this->flashInput($data);
            return $this->back();
        }

        $uploadedImage = $this->storeProductImageUpload();
        if ($uploadedImage !== null) {
            $data['urlFoto'] = $uploadedImage;
        }

        $this->beforeUpdate($product, $data);
        $product->fill($data)->save();
        $this->flash('success', 'Producto actualizado correctamente.');
        return $this->redirect(route('admin.products.index'));
    }
    
    protected function rules(): array
    {
        return [
            'nombrePro' => 'required|string|min:2|max:100',
            'precioPro' => 'required|numeric|min:0',
            'cantidadPro' => 'required|integer|min:0',
            'categoria' => 'required|integer',
            'urlFoto' => 'string|max:500',
            'estado' => 'in:A,I',
        ];
    }
    
    public function create(): \Core\Response
    {
        $categorias = Categoria::ordered()->get(Categoria::class);
        return $this->view('admin/products/form', ['product' => null, 'categorias' => $categorias]);
    }
    
    public function edit(int $id): \Core\Response
    {
        $product = Producto::findOrFail($id);
        $categorias = Categoria::ordered()->get(Categoria::class);
        return $this->view('admin/products/form', compact('product', 'categorias'));
    }
    
    protected function beforeCreate(array &$data): void
    {
        $data['usuarioCreacion'] = $this->auth()?->id ?? 1;
    }
    
    protected function beforeUpdate(\Core\Model $model, array &$data): void
    {
        $data['usuarioModificacion'] = $this->auth()?->id ?? 1;
        $data['fechaModificacion'] = date('Y-m-d H:i:s');
    }

    private function productImageUploadError(): ?string
    {
        $file = $this->request->file('productImage');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return 'No se pudo recibir la imagen. Intenta seleccionarla de nuevo.';
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            return 'La imagen no puede pesar más de 5 MB.';
        }
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return 'El archivo de imagen recibido no es válido.';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $imageInfo = getimagesize($file['tmp_name']);
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (
            !is_string($mime)
            || !isset($allowedTypes[$mime])
            || $imageInfo === false
            || ($imageInfo['mime'] ?? null) !== $mime
        ) {
            return 'Formato no compatible. Sube una imagen JPG, PNG o WEBP.';
        }

        return null;
    }

    private function storeProductImageUpload(): ?string
    {
        $file = $this->request->file('productImage');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $extension = $extensions[$mime] ?? null;
        if ($extension === null) {
            throw new \RuntimeException('El tipo de imagen cambió durante la carga.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $directory = APP_ROOT . '/public/uploads/products';
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new \RuntimeException('No se pudo crear la carpeta de imágenes de productos.');
        }
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
            throw new \RuntimeException('No se pudo guardar la imagen del producto.');
        }

        return 'uploads/products/' . $filename;
    }

    private function isAllowedImageReference(string $image): bool
    {
        if ($image === '') {
            return true;
        }
        if (preg_match('#\Aassets_Front/img/[A-Za-z0-9_.-]+\.(?:png|jpe?g|webp|svg)\z#i', $image)) {
            return true;
        }
        if (preg_match('#\Auploads/products/[A-Za-z0-9_-]+\.(?:png|jpe?g|webp)\z#i', $image)) {
            return true;
        }

        $url = parse_url($image);
        return filter_var($image, FILTER_VALIDATE_URL)
            && ($url['scheme'] ?? '') === 'https'
            && strtolower($url['host'] ?? '') === 'raw.githubusercontent.com'
            && preg_match(
                '#\A/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/\d+\.png\z#',
                $url['path'] ?? ''
            ) === 1;
    }
}