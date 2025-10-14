<nav class="navbar navbar-dark bg-dark border-bottom border-secondary py-2">
    <div class="container-fluid">
        <!-- Left side - LibraFlow logo -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand text-white fw-bold fs-6 mb-0" href="{{ route('dashboard') }}">
                <i class="bi bi-book me-2"></i>LibraFlow
            </a>
        </div>

        <!-- Right side - Navigation buttons -->
        <div class="d-flex align-items-center ms-auto">
            <ul class="navbar-nav flex-row mb-0">
                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1" href="{{ route('admin.announcements.index') }}">
                            <i class="bi bi-megaphone me-1"></i>Announcements
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1" href="{{ route('borrowings.admin_borrow') }}">
                            <i class="bi bi-person-plus me-1"></i>Borrow for User
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item me-3">
                        <a class="nav-link text-white px-2 py-1 {{ request()->routeIs('admin.users.*') ? 'bg-primary rounded' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-1"></i>Users
                        </a>
                    </li>
                @endif






            </ul>

            <!-- User Account Dropdown -->
            <div class="dropdown ms-3">
                <button class="btn btn-outline-light border-0 dropdown-toggle d-flex align-items-center"
                        type="button" onclick="toggleDropdown(event)">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . auth()->user()->profile_photo) }}"
                             alt="Profile" class="rounded-circle me-2" width="24" height="24">
                    @else
                        <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                             style="width: 24px; height: 24px;">
                            <i class="bi bi-person text-white" style="font-size: 12px;"></i>
                        </div>
                    @endif
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down ms-1 dropdown-arrow"></i>
                </button>

                <div id="userDropdownMenu" class="dropdown-menu dropdown-menu-end"
                     style="display: none; min-width: 200px;">
                    <div class="px-3 py-2 border-bottom">
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>

                    <a class="dropdown-item" href="{{ route('settings') }}">
                        <i class="bi bi-gear me-2"></i>Account Settings
                    </a>

                    @if(auth()->user()->isAdmin())
                        <div class="border-top my-1"></div>
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="bi bi-gear-fill me-2"></i>Admin Settings
                        </a>
                    @endif

                    <div class="border-top my-1"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
