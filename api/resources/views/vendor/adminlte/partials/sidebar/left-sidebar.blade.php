<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            {{-- Menú Gestión de Recursos --}}
            <li class="nav-item menu-open">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        Gestión de Recursos
                        <i class="right fas fa-angle-left"></i>  {{-- Icono para indicar submenú --}}
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    {{-- Ítem Autores --}}
                    <li class="nav-item">
                        <a href="{{ route('autores.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Autores</p>
                        </a>
                    </li>
                    {{-- Ítem Dibujantes --}}
                    <li class="nav-item">
                        <a href="{{ route('dibujantes.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-pencil-alt"></i>
                            <p>Dibujantes</p>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- Ítem Editoriales --}}
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-building"></i>
                    <p>Editoriales</p>
                </a>
            </li>
            {{-- Ítem Categorías --}}
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-tags"></i>
                    <p>Categorías</p>
                </a>
            </li>
            {{-- Ítem Mangas --}}
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Mangas</p>
                </a>
            </li>
        </ul>
        </nav>
    </div>

</aside>
