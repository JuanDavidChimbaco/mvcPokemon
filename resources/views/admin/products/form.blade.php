@extends('layouts.admin')

@section('title', $product ? 'Editar Producto' : 'Nuevo Producto')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas {{ $product ? 'fa-edit' : 'fa-plus' }} me-2 text-warning"></i>
                {{ $product ? 'Editar Producto' : 'Nuevo Producto' }}
            </h1>
            <p class="text-muted mb-0">{{ $product ? 'Modifica la información del Pokémon' : 'Agrega un nuevo Pokémon al catálogo' }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Producto</h5>
        </div>
        <div class="card-body">
            <form id="product-form" action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="_token" value="{{ session()->token() }}">
                @if($product)
                <input type="hidden" name="_method" value="PUT">
                @endif
                
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-8">
                        <label for="nombrePro" class="form-label fw-medium">Nombre del Pokémon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" 
                                   id="nombrePro" name="nombrePro" placeholder="Ej: Pikachu, Charizard..." 
                                   value="{{ old('nombrePro', $product->nombrePro ?? '') }}" required maxlength="100">
                        </div>
                    </div>
                    
                    <!-- Categoría -->
                    <div class="col-md-4">
                        <label for="categoria" class="form-label fw-medium">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Seleccionar tipo...</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" 
                                    style="background: {{ $cat->getColor() }}; color: white;"
                                    {{ (old('categoria', $product->categoria ?? '') == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->nombreCat }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Precio -->
                    <div class="col-md-3">
                        <label for="precioPro" class="form-label fw-medium">Precio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" 
                                   id="precioPro" name="precioPro" placeholder="0.00" step="0.01" min="0" 
                                   value="{{ old('precioPro', $product->precioPro ?? '') }}" required>
                        </div>
                    </div>
                    
                    <!-- Stock -->
                    <div class="col-md-3">
                        <label for="cantidadPro" class="form-label fw-medium">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" 
                               id="cantidadPro" name="cantidadPro" placeholder="0" min="0" 
                               value="{{ old('cantidadPro', $product->cantidadPro ?? 0) }}" required>
                    </div>
                    
                    <div class="col-md-8">
                        <label for="productImage" class="form-label fw-medium">Imagen del producto</label>
                        <input type="hidden" id="urlFoto" name="urlFoto" value="{{ old('urlFoto', $product->urlFoto ?? '') }}">
                        <input type="file" id="productImage" name="productImage" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="form-text text-muted">Selecciona JPG, PNG o WEBP (máximo 5 MB). Si no eliges un archivo, se conserva la imagen actual.</small>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1" id="previewBtn">
                            <i class="fas fa-eye me-2"></i>Vista previa
                        </button>
                        <button type="button" class="btn btn-outline-primary flex-grow-1" id="useArtworkBtn">
                            <i class="fas fa-cloud-download-alt me-2"></i>Arte oficial
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="image-preview-container" id="imagePreview" style="display: none;">
                            <label class="form-label fw-medium">Vista previa</label>
                            <div class="image-preview">
                                <img id="previewImg" src="{{ product_image_url($product->urlFoto ?? null) }}" data-saved-image="{{ product_image_url($product->urlFoto ?? null) }}" data-pokemon-name="{{ $product->nombrePro ?? '' }}" alt="Vista previa del producto" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            <small id="imageStatus" class="form-text text-muted"></small>
                        </div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="A" {{ (old('estado', $product->estado ?? 'A') === 'A') ? 'selected' : '' }}>Activo</option>
                            <option value="I" {{ (old('estado', $product->estado ?? '') === 'I') ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-poke-primary">
                        <i class="fas fa-{{ $product ? 'save' : 'plus' }} me-2"></i>
                        {{ $product ? 'Actualizar' : 'Crear' }} Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Help Card -->
    <div class="card border-0 shadow-sm mt-4 bg-light">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2 text-warning"></i>Consejos</h6>
            <ul class="mb-0 small text-muted">
                <li>Usa imágenes oficiales de Pokémon para mejor presentación</li>
                <li>El precio se muestra con 2 decimales ($29.99)</li>
                <li>El stock bajo (≤5) se destaca automáticamente en rojo</li>
                <li>Las categorías usan los colores oficiales de cada tipo Pokémon</li>
                <li>Los productos inactivos no se muestran en la tienda pública</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // Category color preview
    const categoriaSelect = document.getElementById('categoria');
    categoriaSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.style.background) {
            this.style.background = selected.style.background;
            this.style.color = selected.style.color;
        }
    });
    
    // Trigger initial color
    if (categoriaSelect.value) {
        const selected = categoriaSelect.options[categoriaSelect.selectedIndex];
        if (selected.style.background) {
            categoriaSelect.style.background = selected.style.background;
            categoriaSelect.style.color = selected.style.color;
        }
    }
});
</script>
@endsection