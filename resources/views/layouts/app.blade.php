<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'PokéTienda')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="@asset('img/Squirtle.png')">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ session()->token() }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Pokemon+Solid&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Vite Assets -->
    @vite('resources/js/app.js')
    
    @yield('styles')
</head>
<body class="@yield('body-class', '')"
      data-cart-add-url="{{ route('shop.cart.add') }}"
      data-cart-update-url="{{ route('shop.cart.update') }}"
      data-cart-clear-url="{{ route('shop.cart.clear') }}"
      data-wishlist-toggle-url="{{ route('shop.wishlist.toggle') }}">
    <header class="store-header">
        <nav class="store-nav container" aria-label="Navegación principal">
            <a class="store-brand" href="{{ route('home') }}" aria-label="PokéTienda, inicio">
                <img src="@asset('img/Squirtle.png')" alt="" width="44" height="44">
                <span>Poké<span>Tienda</span></span>
            </a>

            <div class="store-links">
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('shop.categories') }}">Categorías</a>
                <a href="{{ route('shop.wishlist') }}">Favoritos</a>
            </div>

            <form class="store-search" action="{{ route('shop.search') }}" method="get" role="search"
                  data-search-url="{{ route('api.products.search', ['term' => 'SEARCH_TERM']) }}"
                  data-product-url="{{ route('shop.product', ['id' => 'PRODUCT_ID']) }}"
                  data-image-prefix="{{ asset('img/') }}">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" id="txtBuscar" name="q" placeholder="Busca tu Pokémon favorito" aria-label="Buscar Pokémon"
                       aria-autocomplete="list" aria-controls="listaPokemon" autocomplete="off">
                <button type="submit">Buscar</button>
                <div id="listaPokemon" class="search-suggestions" role="listbox" aria-label="Sugerencias de Pokémon" aria-live="polite"></div>
            </form>

            <div class="store-actions">
                <a class="store-account" href="{{ route('auth.login') }}">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                    <span>Mi cuenta</span>
                </a>
                <a class="store-cart" href="{{ route('shop.cart') }}" aria-label="Ver carrito">
                    <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                    <span>Carrito</span>
                    <span id="cart-count" class="cart-count" aria-live="polite">{{ array_sum(array_column(session()->get('shop_cart', []), 'cantidad')) }}</span>
                </a>
            </div>
        </nav>
    </header>

    @include('partials.flash-messages')
    
    @yield('content')

    <footer class="store-footer">
        <div class="container store-footer-inner">
            <a class="store-brand" href="{{ route('home') }}">
                <img src="@asset('img/Squirtle.png')" alt="" width="36" height="36">
                <span>Poké<span>Tienda</span></span>
            </a>
            <p>Tu próxima aventura Pokémon comienza aquí.</p>
            <small>Proyecto educativo no oficial. Pokémon © Nintendo, Game Freak y Creatures.</small>
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>