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
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Agenda</span>
                </a>
            </li>

            <li class="sidebar-item {{ Route::is('banner.index') ? 'active' : '' }}">
                <a href="{{ Route('banner.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Banner</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('category.index') ? 'active' : '' }}">
                <a href="{{ Route('category.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('subcategory.index') ? 'active' : '' }}">
                <a href="{{ Route('subcategory.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Subkategori</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('content.index') ? 'active' : '' }}">
                <a href="{{ Route('content.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Konten</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('feature.index') ? 'active' : '' }}">
                <a href="{{ Route('feature.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Fitur</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('gallery.index') ? 'active' : '' }}">
                <a href="{{ Route('gallery.index') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Galeri Konten</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ Route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ Route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Databases
            <li class="sidebar-item {{ Route::is('master.*') ? 'active' : '' }} has-sub">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-collection-fill"></i>
                    <span>Databases</span>
                </a>
                <ul class="submenu {{ Route::is('master.*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('master.client.index') ? 'active' : '' }}">
                        <a href="{{ route('master.client.index') }}" class="submenu-link">Client</a>
                    </li>

                    <li class="submenu-item {{ Route::is('master.project.index') ? 'active' : '' }}">
                        <a href="{{ route('master.project.index') }}" class="submenu-link">Project</a>
                    </li>
                    <li class="submenu-item {{ Route::is('master.production.index') ? 'active' : '' }}">
                        <a href="{{ route('master.production.index') }}" class="submenu-link">Product</a>
                    </li>
                    <li class="submenu-item {{ Route::is('master.status.index') ? 'active' : '' }}">
                        <a href="{{ route('master.status.index') }}" class="submenu-link">Status</a>
                    </li>

                </ul>
            </li> --}}


            <!-- Projects -->
            {{-- <li class="sidebar-item {{ Route::is('projects.*') ? 'active' : '' }}  has-sub">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-card-checklist"></i>
                    <span>Projects</span>
                </a>
                <ul class="submenu {{ Route::is('projects.*') ? 'active' : '' }}">
                    <li class="submenu-item {{Route::is('projects.project.index')  ? 'active' : '' }}" >
                        <a href="{{route('projects.project.index')}}" class="submenu-link">Project</a>
                    </li>
                    <li class="submenu-item {{Route::is('projects.status.index') ? 'active' : '' }}" >
                        <a href="{{route('projects.status.index')}}" class="submenu-link">Status</a>
                    </li>
                </ul>
            </li> --}}

            <hr>
            <li class="sidebar-item">
                <a href="{{ route('logout') }}" class="sidebar-link">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                    <span>Logout</span>
                </a>
            </li>

            {{-- <li class="sidebar-item {{ Route::is('client.index') ? 'active' : '' }}">
            <a href="{{ route('client.index') }}" class="sidebar-link">
                <i class="bi bi-person"></i>
                <span>Client</span>
            </a>
        </li>
        <li class="sidebar-item {{ Route::is('project.index') ? 'active' : '' }}">
            <a href="{{ route('project.index') }}" class="sidebar-link">
                <i class="bi bi-kanban"></i>
                <span>Project</span>
            </a>
        </li>
        <li class="sidebar-item {{ Route::is('production.index') ? 'active' : '' }}">
            <a href="{{ route('production.index') }}" class="sidebar-link">
                <i class="bi bi-box-seam"></i>
                <span>Production</span>
            </a>
        </li>
        <li class="sidebar-item {{ Route::is('status.index') ? 'active' : '' }}">
            <a href="{{ route('status.index') }}" class="sidebar-link">
                <i class="bi bi-bar-chart"></i>
                <span>Status</span>
            </a>
        </li> --}}


    </div>
</div>
