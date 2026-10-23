<nav class="navbar navbar-expand-lg navbar-dark bg-poke-primary fixed-top admin-navbar" role="navigation" aria-label="Navegación principal admin">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}" aria-label="PokéTienda Admin - Dashboard">
            <img src="@asset('img/Squirtle.png')" alt="" width="36" height="36" class="rounded">
            <span class="admin-brand-text">PokéTienda <small class="text-warning">Admin</small></span>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <!-- User Menu -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notificaciones">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li><a class="dropdown-item" href="#">Nuevo pedido #1234</a></li>
                        <li><a class="dropdown-item" href="#">Stock bajo: Pikachu</a></li>
                        <li><a class="dropdown-item" href="#">Usuario nuevo registrado</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                    </ul>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-dark"></i>
                        </div>
                        <span class="d-none d-md-inline fw-medium">{{ auth()->nombre_completo ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ auth()->correo ?? 'admin@pokemonstore.com' }}</h6></li>
                        <li><span class="dropdown-item-text">Rol: {{ auth()->rol->nombreRol ?? 'Administrador' }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-cog me-2"></i>Perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>