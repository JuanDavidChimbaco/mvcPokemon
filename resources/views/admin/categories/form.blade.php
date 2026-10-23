@extends('layouts.admin')

@section('title', $categoria ? 'Editar Categoría' : 'Nueva Categoría')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas {{ $categoria ? 'fa-edit' : 'fa-plus' }} me-2 text-warning"></i>
                {{ $categoria ? 'Editar Categoría' : 'Nueva Categoría' }}
            </h1>
            <p class="text-muted mb-0">{{ $categoria ? 'Modifica la información del tipo de Pokémon' : 'Agrega un nuevo tipo de Pokémon al catálogo' }}</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Información de la Categoría</h5>
        </div>
        <div class="card-body">
            <form action="{{ $categoria ? route('admin.categories.update', $categoria->id) : route('admin.categories.store') }}" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="_token" value="{{ session()->token() }}">
                @if($categoria)
                <input type="hidden" name="_method" value="PUT">
                @endif
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nombreCat" class="form-label fw-medium">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" 
                                   id="nombreCat" name="nombreCat" placeholder="Ej: Fire, Water, Electric..." 
                                   value="{{ old('nombreCat', $categoria->nombreCat ?? '') }}" required maxlength="50">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="estado" class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="A" {{ (old('estado', $categoria->estado ?? 'A') === 'A') ? 'selected' : '' }}>Activa</option>
                            <option value="I" {{ (old('estado', $categoria->estado ?? '') === 'I') ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="descripcion" class="form-label fw-medium">Descripción</label>
                        <textarea class="form-control" 
                                  id="descripcion" name="descripcion" rows="4" placeholder="Describe este tipo de Pokémon..." maxlength="255">{{ old('descripcion', $categoria->descripcion ?? '') }}</textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium">Color del Tipo</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="color-preview" id="colorPreview" style="width: 60px; height: 40px; border-radius: 8px; background: {{ $categoria ? $categoria->getGradient() : 'linear-gradient(135deg, #6C757D, #495057)' }}; border: 1px solid #dee2e6;"></div>
                            <input type="hidden" name="color_hex" id="colorHex" value="{{ $categoria ? $categoria->getColor() : '#6C757D' }}">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#colorModal">
                                <i class="fas fa-palette me-2"></i>Seleccionar Color
                            </button>
                            <span class="text-muted small">Se usa para badges, gradientes y filtros</span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-poke-primary">
                        <i class="fas fa-{{ $categoria ? 'save' : 'plus' }} me-2"></i>
                        {{ $categoria ? 'Actualizar' : 'Crear' }} Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Color Picker Modal -->
    <div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="colorModalLabel"><i class="fas fa-palette me-2"></i>Seleccionar Color del Tipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2" id="colorPalette">
                        @foreach(['Fire' => '#F08030', 'Water' => '#6890F0', 'Electric' => '#F8D030', 'Grass' => '#78C850', 'Ice' => '#98D8D8', 'Fighting' => '#C03028', 'Poison' => '#A040A0', 'Ground' => '#E0C068', 'Flying' => '#A890F0', 'Psychic' => '#F85888', 'Bug' => '#A8B820', 'Rock' => '#B8A038', 'Ghost' => '#705898', 'Dragon' => '#7038F8', 'Dark' => '#705848', 'Steel' => '#B8B8D0', 'Fairy' => '#EE99AC', 'Normal' => '#A8A878'] as $name => $color)
                        <div class="col-3">
                            <button type="button" class="btn color-option w-100 p-3" style="background: {{ $color }}; border-color: {{ $color }}; color: {{ in_array($name, ['Electric', 'Normal', 'Fairy', 'Grass', 'Ground', 'Bug', 'Steel']) ? '#000' : '#fff' }}" data-color="{{ $color }}" data-name="{{ $name }}" title="{{ $name }}">
                                <strong>{{ $name }}</strong>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Color Personalizado (Hex)</label>
                            <input type="color" class="form-control form-control-color" id="customColor" value="#6C757D" title="Elige un color">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código Hex</label>
                            <input type="text" class="form-control" id="customHex" placeholder="#RRGGBB" maxlength="7" pattern="#[0-9A-Fa-f]{6}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-poke-primary" id="applyColor">Aplicar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPreview = document.getElementById('colorPreview');
    const colorHexInput = document.getElementById('colorHex');
    const customColor = document.getElementById('customColor');
    const customHex = document.getElementById('customHex');
    const applyColorBtn = document.getElementById('applyColor');
    
    // Color palette buttons
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const color = this.dataset.color;
            const name = this.dataset.name;
            updateColorPreview(color, name);
        });
    });
    
    // Custom color picker
    if (customColor) {
        customColor.addEventListener('input', function() {
            customHex.value = this.value;
            updateColorPreview(this.value, 'Personalizado');
        });
    }
    
    if (customHex) {
        customHex.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                customColor.value = this.value;
                updateColorPreview(this.value, 'Personalizado');
            }
        });
    }
    
    // Apply color
    if (applyColorBtn) {
        applyColorBtn.addEventListener('click', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('colorModal'));
            modal?.hide();
        });
    }
    
    function updateColorPreview(color, name) {
        const gradient = `linear-gradient(135deg, ${color}, ${darkenColor(color, 15)})`;
        colorPreview.style.background = gradient;
        colorHexInput.value = color;
        customColor.value = color;
        customHex.value = color;
        
        // Add tooltip with name
        colorPreview.title = name;
    }
    
    function darkenColor(hex, percent) {
        hex = hex.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        
        const darkR = Math.max(0, Math.floor(r * (1 - percent / 100)));
        const darkG = Math.max(0, Math.floor(g * (1 - percent / 100)));
        const darkB = Math.max(0, Math.floor(b * (1 - percent / 100)));
        
        return '#' + [darkR, darkG, darkB].map(x => x.toString(16).padStart(2, '0')).join('');
    }
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection