@extends('layouts.app')

@section('title', $categoria->nombreCat . ' - PokéTienda')

@section('styles')
@endsection

@section('content')
<!-- Category Hero -->
<section class="category-hero" style="background: {{ $categoria->getGradient() }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="Breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shop.categories') }}">Tipos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $categoria->nombreCat }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold text-white mb-2">{{ $categoria->nombreCat }}</h1>
                <p class="lead text-white-50 mb-0">{{ $categoria->descripcion ?? 'Explora todos los Pokémon de tipo ' . $categoria->nombreCat }}</p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <img src="{{ $categoria->getIconUrl() }}" alt="{{ $categoria->nombreCat }}" class="category-hero-icon">
            </div>
        </div>
    </div>
</section>

<!-- Filters & Products -->
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <aside class="col-lg-3">
                <div class="filters-card sticky-top" style="top: 100px;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" id="filterForm">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Rango de Precio</label>
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="price_min" placeholder="Mín" min="0" step="0.01" value="{{ $priceMin }}">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="price_max" placeholder="Máx" min="0" step="0.01" value="{{ $priceMax }}">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Disponibilidad</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" {{ $inStock ? 'checked' : '' }} id="inStock">
                                        <label class="form-check-label" for="inStock">Solo en stock</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Ordenar por</label>
                                    <select class="form-select form-select-sm" name="sort">
                                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Más nuevos</option>
                                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                                        <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                                    </select>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-poke-primary">
                                        <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                    </button>
                                    <a href="{{ route('shop.category', $categoria->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Limpiar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Products Grid -->
            <main class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="h4 mb-1">
                            <span class="badge me-2" style="background: {{ $categoria->getColor() }}">{{ $categoria->nombreCat }}</span>
                            {{ $productos['total'] ?? 0 }} Pokémon{{ $productos['total'] !== 1 ? 's' : '' }} encontrado{{ $productos['total'] !== 1 ? 's' : '' }}
                        </h2>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-view="grid" aria-label="Vista cuadrícula">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-view="list" aria-label="Vista lista">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if(empty($productos['data']))
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron Pokémon</h4>
                    <p class="text-muted">Intenta ajustar los filtros o explora otras categorías</p>
                    <a href="{{ route('shop.categories') }}" class="btn btn-poke-primary mt-3">Ver otras categorías</a>
                </div>
                @else
                <div class="row g-4" id="productsGrid">
                    @foreach($productos['data'] as $producto)
                    @include('shop.partials.product-card', ['producto' => $producto])
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($productos['last_page'] > 1)
                <nav aria-label="Paginación" class="mt-5">
                    <ul class="pagination justify-content-center">
                        @if($productos['current_page'] > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $productos['current_page'] > 1 ? route('shop.category', ['id' => $categoria->id, 'page' => $productos['current_page'] - 1]) : '#' }}" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        @endif
                        
                        @for($i = max(1, $productos['current_page'] - 2); $i <= min($productos['last_page'], $productos['current_page'] + 2); $i++)
                        <li class="page-item {{ $i === $productos['current_page'] ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('shop.category', ['id' => $categoria->id, 'page' => $i]) }}">{{ $i }}</a>
                        </li>
                        @endfor
                        
                        @if($productos['current_page'] < $productos['last_page'])
                        <li class="page-item">
                            <a class="page-link" href="{{ route('shop.category', ['id' => $categoria->id, 'page' => $productos['current_page'] + 1]) }}" aria-label="Siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                @endif
                @endif
            </main>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// View toggle
document.querySelectorAll('[data-view]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('[data-view]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const grid = document.getElementById('productsGrid');
        grid.classList.toggle('row-cols-1', this.dataset.view === 'list');
        grid.classList.toggle('row-cols-2', this.dataset.view === 'grid');
        grid.classList.toggle('row-cols-md-3', this.dataset.view === 'grid');
        grid.classList.toggle('row-cols-lg-4', this.dataset.view === 'grid');
    });
});

// Auto-submit filters on change
document.querySelectorAll('#filterForm select, #filterForm input[type="checkbox"]').forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Price inputs - submit on Enter
document.querySelectorAll('#filterForm input[type="number"]').forEach(el => {
    el.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
});
</script>
@endsection