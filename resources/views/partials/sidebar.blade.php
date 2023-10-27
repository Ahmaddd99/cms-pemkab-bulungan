<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center" style="align-content: center">
            <div class="logo mt-3">
                <h3>Administrator</h3>
            </div>
            <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" readonly/>
                    <label class="form-check-label"></label>
                </div>
            </div>
            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu</li>

            <li class="sidebar-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ Route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ Route::is('agenda.index') ? 'active' : '' }}">
                <a href="{{ Route('agenda.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-date"></i>
                    <span>Agenda</span>
                </a>
            </li>

            <li class="sidebar-item {{ Route::is('banner.index') ? 'active' : '' }}">
                <a href="{{ Route('banner.index') }}" class="sidebar-link">
                    <i class="bi bi-card-image"></i>
                    <span>Banner</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('category.index') ? 'active' : '' }}">
                <a href="{{ Route('category.index') }}" class="sidebar-link">
                    <i class="bi bi-diagram-2"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('subcategory.index') ? 'active' : '' }}">
                <a href="{{ Route('subcategory.index') }}" class="sidebar-link">
                    <i class="bi bi-diagram-3"></i>
                    <span>Subkategori</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('content.index') ? 'active' : '' }}">
                <a href="{{ Route('content.index') }}" class="sidebar-link">
                    <i class="bi bi-card-heading"></i>
                    <span>Konten</span>
                </a>
            </li>
            {{-- <li class="sidebar-item {{ Route::is('feature.index') ? 'active' : '' }}">
                <a href="{{ Route('feature.index') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-plus"></i>
                    <span>Fitur</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('attribute.index') ? 'active' : '' }}">
                <a href="{{ Route('attribute.index') }}" class="sidebar-link">
                    <i class="bi bi-columns-gap"></i>
                    <span>Atribut</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('gallery.index') ? 'active' : '' }}">
                <a href="{{ Route('gallery.index') }}" class="sidebar-link">
                    <i class="bi bi-collection-play"></i>
                    <span>Galeri Konten</span>
                </a>
            </li> --}}

            <li class="sidebar-item {{ Route::is('submenu.*') ? 'active' : '' }} has-sub">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-collection-fill"></i>
                    <span>Fitur Tambahan</span>
                </a>
                <ul class="submenu {{ Route::is('submenu.*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('submenu.feature.index') ? 'active' : '' }}">
                        <a href="{{ route('submenu.feature.index') }}" class="submenu-link">Fitur</a>
                    </li>
                    <li class="submenu-item {{ Route::is('submenu.attribute.index') ? 'active' : '' }}">
                        <a href="{{ route('submenu.attribute.index') }}" class="submenu-link">Label</a>
                    </li>
                    <li class="submenu-item {{ Route::is('submenu.gallery.index') ? 'active' : '' }}">
                        <a href="{{ route('submenu.gallery.index') }}" class="submenu-link">Galeri Konten</a>
                    </li>
                </ul>
            </li>
            <hr>
            <li class="sidebar-item">
                <a href="{{ route('logout') }}" class="sidebar-link">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                    <span>Logout</span>
                </a>
            </li>
    </div>
</div>
