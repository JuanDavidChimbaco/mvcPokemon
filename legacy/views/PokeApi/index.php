<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets_Front/img/Squirtle.png">
    <title>PokéTienda - Tu Tienda Pokémon</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6bf421ec3d.js" crossorigin="anonymous"></script>

    <!-- Hoja de estilos Local -->
    <link rel="stylesheet" href="assets_Front/css/style.css">
</head>

<body onload="printCategorias(), searchPokemon(), getPokemon(), loginVerificate()">

    <!-- Header Principal -->
    <header class="poke-header fixed-top" role="banner">
        <div class="container-fluid px-3 px-md-4">
            <nav class="navbar navbar-expand-lg p-0" aria-label="Navegación principal">
                <!-- Logo/Brand -->
                <a class="navbar-brand poke-brand d-flex align-items-center" href="#" aria-label="PokéTienda - Inicio">
                    <img src="assets_Front/img/Squirtle.png" alt="" class="poke-logo me-2" width="48" height="48">
                    <span class="poke-title">PokéTienda</span>
                </a>

                <!-- Categorías Dropdown -->
                <div class="poke-nav-item">
                    <div class="dropdown">
                        <button class="btn poke-btn-category dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Categorías de Pokémon">
                            <i class="fas fa-layer-group"></i>
                            <span class="d-none d-sm-inline">Categorías</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end poke-dropdown" id="pokemon-categoria"
                            role="menu" aria-label="Lista de categorías">
                            <li><span class="dropdown-header text-muted px-3 py-2">Tipos de Pokémon</span></li>
                            <!-- Contenido dinámico de categorías -->
                        </ul>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="poke-search-wrapper flex-grow-1 mx-3 mx-md-4">
                    <div class="input-group poke-search">
                        <span class="input-group-text poke-search-icon" id="search-icon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="search" class="form-control poke-search-input" name="txtBuscar" id="txtBuscar"
                            placeholder="Buscar Pokémon, objetos..." aria-label="Buscar Pokémon"
                            aria-describedby="search-icon" onkeyup="autoCompletePokemon()">
                        <div id="listaPokemon" class="poke-autocomplete" role="listbox" aria-label="Sugerencias de búsqueda"></div>
                    </div>
                </div>

                <!-- Usuario -->
                <div class="poke-nav-item">
                    <div class="dropdown">
                        <button class="btn poke-btn-user dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            aria-label="Menú de usuario" onclick="loginVerificate()">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end poke-dropdown" id="op" role="menu"
                            aria-label="Opciones de usuario">
                            <!-- Contenido dinámico login/logout -->
                        </ul>
                    </div>
                </div>

                <!-- Carrito -->
                <div class="poke-nav-item">
                    <button class="btn poke-btn-cart position-relative d-flex align-items-center justify-content-center"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                        aria-controls="offcanvasRight" aria-label="Carrito de compras" onclick="pintarCarrito()"
                        ondrop="drop(event)" ondragover="allowDrop(event)">
                        <i class="fas fa-shopping-bag fa-lg poke-cart-icon"></i>
                        <span id="carrito-numero" class="poke-cart-badge" aria-live="polite">0</span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Espaciador para header fijo -->
    <div class="poke-header-spacer" aria-hidden="true"></div>

    <main class="poke-main" role="main">
        <div class="container-fluid px-3 px-md-4">

            <!-- Hero Carousel -->
            <section class="poke-hero-section mb-4" aria-labelledby="hero-title">
                <div id="carouselExampleCaptions" class="carousel poke-carousel slide" data-bs-ride="carousel"
                    data-bs-interval="5000" role="region" aria-label="Pokémon destacados">
                    <div class="carousel-indicators poke-carousel-indicators" aria-label="Navegación del carrusel">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>

                    <div class="carousel-inner poke-carousel-inner" role="list">
                        <div class="carousel-item active poke-carousel-item">
                            <div class="poke-carousel-content">
                                <div class="poke-carousel-image">
                                    <img src="assets_Front/img/Squirtle.png" class="d-block w-100" alt="Squirtle - Pokémon tipo Agua"
                                        loading="eager">
                                </div>
                                <div class="carousel-caption poke-carousel-caption">
                                    <h5 class="poke-carousel-name">Squirtle</h5>
                                    <p class="poke-carousel-desc">Pokémon de tipo Agua de la primera generación. Uno de los
                                        iniciales de Kanto junto a Bulbasaur y Charmander.</p>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item poke-carousel-item">
                            <div class="poke-carousel-content">
                                <div class="poke-carousel-image">
                                    <img src="assets_Front/img/Eevee.png" class="d-block w-100" alt="Eevee - Pokémon tipo Normal"
                                        loading="lazy">
                                </div>
                                <div class="carousel-caption poke-carousel-caption">
                                    <h5 class="poke-carousel-name">Eevee</h5>
                                    <p class="poke-carousel-desc">Pokémon de tipo Normal con 8 evoluciones posibles
                                        (Eeveeluciones). Adaptable a cualquier entorno.</p>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item poke-carousel-item">
                            <div class="poke-carousel-content">
                                <div class="poke-carousel-image">
                                    <img src="assets_Front/img/Vaporeon.png" class="d-block w-100" alt="Vaporeon - Evolución de Eevee tipo Agua"
                                        loading="lazy">
                                </div>
                                <div class="carousel-caption poke-carousel-caption">
                                    <h5 class="poke-carousel-name">Vaporeon</h5>
                                    <p class="poke-carousel-desc">Evolución de Eevee de tipo Agua. Su composición
                                        celular es similar a la del agua, permitiéndole fundirse con ella.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev poke-carousel-control" type="button"
                        data-bs-target="#carouselExampleCaptions" data-bs-slide="prev" aria-label="Anterior">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next poke-carousel-control" type="button"
                        data-bs-target="#carouselExampleCaptions" data-bs-slide="next" aria-label="Siguiente">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </section>

            <!-- Banner Promocional -->
            <section class="poke-promo-section mb-4" aria-labelledby="promo-title">
                <div class="poke-promo-card">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-6 poke-promo-image">
                            <img src="assets_Front/img/singulares.png" alt="Pokémon Singulares" class="img-fluid w-100 h-100 object-fit-cover"
                                loading="lazy">
                        </div>
                        <div class="col-md-6 poke-promo-content">
                            <h3 id="promo-title" class="poke-promo-title">Pokémon Singulares</h3>
                            <p class="poke-promo-text">Encuentra Pokémon legendarios y singulares a precios
                                increíbles. Edición limitada.</p>
                            <button class="btn poke-btn-primary" onclick="scrollToCategory('singulares')">
                                Ver Colección <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Carrusel de Categorías -->
            <section class="poke-category-section mb-4" aria-labelledby="category-title">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 id="category-title" class="poke-section-title">Explorar por Tipo</h2>
                    <div class="poke-carousel-nav d-none d-md-flex" role="group" aria-label="Navegación categorías">
                        <button class="btn poke-btn-nav" data-bs-target="#featureCarousel" data-bs-slide="prev"
                            aria-label="Categoría anterior">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn poke-btn-nav" data-bs-target="#featureCarousel" data-bs-slide="next"
                            aria-label="Categoría siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div id="featureCarousel" class="carousel poke-category-carousel slide" data-bs-ride="carousel"
                    data-bs-interval="false" role="region" aria-label="Carrusel de categorías">
                    <div class="carousel-inner" id="carouselCategorias" role="list">
                        <!-- Contenido dinámico de categorías -->
                    </div>
                </div>

                <!-- Navegación móvil -->
                <div class="poke-carousel-nav d-md-none mt-3" role="group" aria-label="Navegación categorías móvil">
                    <button class="btn poke-btn-nav" data-bs-target="#featureCarousel" data-bs-slide="prev"
                        aria-label="Categoría anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn poke-btn-nav" data-bs-target="#featureCarousel" data-bs-slide="next"
                        aria-label="Categoría siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>

            <!-- Productos Pokémon -->
            <section class="poke-products-section mb-4" aria-labelledby="products-title">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 id="products-title" class="poke-section-title">Todos los Pokémon</h2>
                    <div class="poke-view-toggle" role="group" aria-label="Cambiar vista">
                        <button class="btn poke-btn-outline active" data-view="grid" aria-label="Vista en cuadrícula"
                            aria-pressed="true"><i class="fas fa-th"></i></button>
                        <button class="btn poke-btn-outline" data-view="list" aria-label="Vista en lista"
                            aria-pressed="false"><i class="fas fa-list"></i></button>
                    </div>
                </div>

                <!-- Paginación superior -->
                <div id="pagination-container" class="poke-pagination mb-3" role="navigation"
                    aria-label="Paginación de productos"></div>

                <!-- Contenedor de productos -->
                <div id="pokemon-container" class="poke-products-grid" role="list" aria-label="Lista de Pokémon">
                    <!-- Productos cargados dinámicamente -->
                </div>

                <!-- Paginación inferior -->
                <div id="pagination-container-bottom" class="poke-pagination mt-3" role="navigation"
                    aria-label="Paginación de productos"></div>
            </section>

            <!-- Sección Decorativa Pokéballs -->
            <section class="poke-decorative-section mb-4" aria-hidden="true">
                <div class="poke-ball-row">
                    <div class="poke-ball-wrapper">
                        <img src="assets_Front/img/masterball.png" alt="" class="poke-ball-img master-ball"
                            loading="lazy" aria-hidden="true">
                    </div>
                    <div class="poke-ball-wrapper center">
                        <img src="assets_Front/img/pokeball.png" alt="" class="poke-ball-img poke-ball"
                            loading="lazy" aria-hidden="true">
                    </div>
                    <div class="poke-ball-wrapper">
                        <img src="assets_Front/img/masterball1.png" alt="" class="poke-ball-img master-ball"
                            loading="lazy" aria-hidden="true">
                    </div>
                </div>
            </section>

            <!-- Pokémon por Categorías -->
            <section class="poke-by-category-section mb-5" aria-labelledby="by-category-title">
                <h2 id="by-category-title" class="poke-section-title text-center mb-4">Pokémon por Categorías</h2>
                <div id="listPokemonCategoria" class="row g-3" role="list" aria-label="Pokémon agrupados por categoría">
                    <!-- Contenido dinámico -->
                </div>
            </section>

        </div>
    </main>

    <!-- Footer -->
    <footer class="poke-footer" role="contentinfo">
        <div class="container-fluid px-3 px-md-4">
            <div class="row">
                <!-- Equipo -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="poke-footer-title">Nuestro Equipo</h3>
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <article class="poke-team-card">
                                <div class="poke-team-avatar">
                                    <img src="https://www.w3schools.com/bootstrap4/img_avatar3.png" alt="Developer"
                                        loading="lazy">
                                </div>
                                <div class="poke-team-info">
                                    <h5 class="poke-team-name">Developer</h5>
                                    <p class="poke-team-role">Backend & API</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-6 col-md-4">
                            <article class="poke-team-card">
                                <div class="poke-team-avatar">
                                    <img src="https://www.w3schools.com/bootstrap4/img_avatar1.png" alt="Designer"
                                        loading="lazy">
                                </div>
                                <div class="poke-team-info">
                                    <h5 class="poke-team-name">Designer</h5>
                                    <p class="poke-team-role">UI/UX & Graphics</p>
                                </div>
                            </article>
                        </div>
                        <div class="col-6 col-md-4">
                            <article class="poke-team-card">
                                <div class="poke-team-avatar">
                                    <img src="https://www.w3schools.com/bootstrap4/img_avatar6.png" alt="Manager"
                                        loading="lazy">
                                </div>
                                <div class="poke-team-info">
                                    <h5 class="poke-team-name">Manager</h5>
                                    <p class="poke-team-role">Project Lead</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- Redes y Copyright -->
                <div class="col-lg-6">
                    <div class="poke-social-section">
                        <h3 class="poke-footer-title">Síguenos</h3>
                        <div class="poke-social-links">
                            <a href="https://www.facebook.com/" class="poke-social-link" aria-label="Facebook"
                                target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/" class="poke-social-link" aria-label="Twitter"
                                target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://www.instagram.com/" class="poke-social-link" aria-label="Instagram"
                                target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://discord.com/" class="poke-social-link" aria-label="Discord"
                                target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-discord"></i>
                            </a>
                        </div>
                    </div>

                    <div class="poke-copyright mt-4">
                        <p class="mb-1">&copy; 2024 <span class="poke-highlight">PokéTienda</span> - Todos los
                            derechos reservados</p>
                        <p class="mb-0 small text-muted">Pokémon © Nintendo/Game Freak/Creatures. Este es un proyecto
                            educativo no oficial.</p>
                    </div>

                    <div class="poke-footer-badge mt-3">
                        <img src="assets_Front/img/balls.jpg" alt="Pokéballs decorativo" class="img-fluid"
                            style="max-width: 200px;" loading="lazy" aria-hidden="true">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Offcanvas Carrito -->
    <div class="offcanvas poke-offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
        aria-labelledby="offcanvasRightLabel" aria-hidden="true">
        <div class="offcanvas-header poke-offcanvas-header">
            <h5 class="offcanvas-title poke-offcanvas-title" id="offcanvasRightLabel">
                <i class="fas fa-shopping-bag me-2"></i>Tu Carrito
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Cerrar carrito"></button>
        </div>
        <div class="offcanvas-body poke-offcanvas-body" id="contenidoCarrito">
            <!-- Contenido dinámico del carrito -->
            <div class="poke-empty-cart text-center py-5" style="display: none;">
                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tu carrito está vacío</p>
                <button class="btn poke-btn-primary" data-bs-dismiss="offcanvas">Ir a comprar</button>
            </div>
        </div>
        <div class="poke-offcanvas-footer">
            <div class="poke-cart-total d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold fs-5">Total:</span>
                <span id="total" class="poke-price fs-4 fw-bold">$0.00</span>
            </div>
            <div class="d-grid gap-2">
                <button type="button" class="btn poke-btn-danger" onclick="limpiarCarrito()">
                    <i class="fas fa-trash me-2"></i>Limpiar Carrito
                </button>
                <button type="button" class="btn poke-btn-primary" onclick="realizarCompra()">
                    <i class="fas fa-credit-card me-2"></i>Realizar Compra
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Detalle Pokémon -->
    <div class="modal fade poke-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content poke-modal-content">
                <div class="modal-header poke-modal-header">
                    <h5 class="modal-title fs-3 poke-modal-title" id="exampleModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Detalle del Pokémon
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="detallePokemon" class="p-4">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
                <div class="modal-footer poke-modal-footer">
                    <button type="button" class="btn poke-btn-outline" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Scroll to Top -->
    <button class="poke-scroll-top" id="scrollTopBtn" onclick="scrollToTop()" aria-label="Volver arriba"
        title="Volver arriba">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="assets_Front/js/script.js"></script>
    <script src="assets/js/logout.js"></script>
    <script>
        // Scroll to top functionality
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // View toggle for products
        document.querySelectorAll('.poke-view-toggle .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.poke-view-toggle .btn').forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
                const container = document.getElementById('pokemon-container');
                container.classList.toggle('poke-products-list', this.dataset.view === 'list');
                container.classList.toggle('poke-products-grid', this.dataset.view === 'grid');
            });
        });
    </script>
</body>

</html>