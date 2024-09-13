<!-- ========== TOP HEADER MODULE ========== -->
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">

            <div class="d-flex">

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                {{-- @if (session('user_warehouses') != null)
                    <div class="dropdown ms-1 header-item">
                        <button type="button" class="btn btn-secondary dropdown-toggle" id="warehouseDropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ session('user_warehouse')->name }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="warehouseDropdown">
                            @foreach (session('user_warehouses') as $item)
                                <a class="dropdown-item" href="{{ route('change_warehouse', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif --}}
                {{-- <div class="ms-1 header-item">
                    <a href="{{ route('sales.create') }}">
                        <button class="btn btn-success d-flex align-items-center justify-content-between">
                            <i class='bx bx-cart fs-22'></i> <span class="ps-1">POS</span>
                        </button>
                    </a>
                </div> --}}
            </div>

            <div class="d-flex align-items-center">

                {{-- <div class="ms-1 header-item d-none d-sm-flex">
                    <a href="{{ route('inventories.alert_stock') }}"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                        <i class='bx bx-bell fs-22'></i>
                    </a>
                </div> --}}
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">

                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('public/admin-assets/images/users/avatar-demo.png') }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->guard('admin')->user()->username }}
                                </span>
                            </span>
                        </span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Welcome {{ auth()->guard('admin')->user()->username }} !</h6>
                        <form action="{{ route('admin-logout') }}" method="post">
                            @csrf
                            <button class="dropdown-item" type="submit"><i
                                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle" data-key="t-logout">Logout</span></button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</header>
<!-- ========== --end-- TOP HEADER MODULE ========== -->
