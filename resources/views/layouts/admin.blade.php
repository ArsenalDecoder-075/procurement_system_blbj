<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    @stack('styles')
  </head>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
              <i class="fas fa-bars"></i>
            </a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Dropdown -->
          @auth
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="bi bi-person-vcard"></i>
                {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="bi bi-person-circle mr-2"></i>{{ __('Profile') }}
              </a>

              <div class="dropdown-divider"></div>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                  <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Log Out') }}
                </button>
              </form>
            </div>
          </li>
          @endauth
        </ul>
      </nav>

      <!-- Sidebar -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link">
          <i class="bi bi-highlights"></i>
          <span class="brand-text font-weight-bold">PT. BLBJ</span>
          <p class="brand-text-sm">Besi Leleh Buatan Jakarta</p>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar Menu -->
          <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Dashboard -->
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <!-- Master Data -->
              <li class="nav-header">MASTER DATA</li>

              <!-- Supplier -->
              <li class="nav-item {{ request()->routeIs('suppliers.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i>
                  <p>
                    Supplier
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data Supplier</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('suppliers.create') }}" class="nav-link {{ request()->routeIs('suppliers.create') ? 'active' : '' }}">
                      <i class="bi bi-plus-circle nav-icon"></i>
                      <p>Tambah Supplier</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Raw Materials -->
              <li class="nav-item {{ request()->routeIs('raw-materials.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('raw-materials.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box"></i>
                  <p>
                    Bahan Baku
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('raw-materials.index') }}" class="nav-link {{ request()->routeIs('raw-materials.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data Bahan Baku</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('raw-materials.create') }}" class="nav-link {{ request()->routeIs('raw-materials.create') ? 'active' : '' }}">
                      <i class="bi bi-plus-circle nav-icon"></i>
                      <p>Tambah Bahan Baku</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Warehouses -->
              <li class="nav-item {{ request()->routeIs('warehouses.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shop"></i>
                  <p>
                    Gudang
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('warehouses.index') }}" class="nav-link {{ request()->routeIs('warehouses.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data Gudang</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('warehouses.create') }}" class="nav-link {{ request()->routeIs('warehouses.create') ? 'active' : '' }}">
                      <i class="bi bi-plus-circle nav-icon"></i>
                      <p>Tambah Gudang</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Transaksi -->
              <li class="nav-header">TRANSAKSI</li>

              <!-- Purchase Orders -->
              <li class="nav-item {{ request()->routeIs('purchase-orders.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-receipt"></i>
                  <p>
                    Purchase Order
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data PO</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('purchase-orders.create') }}" class="nav-link {{ request()->routeIs('purchase-orders.create') ? 'active' : '' }}">
                      <i class="bi bi-plus-circle nav-icon"></i>
                      <p>Buat PO Baru</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Receivings -->
              <li class="nav-item {{ request()->routeIs('receivings.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('receivings.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-truck"></i>
                  <p>
                    Penerimaan Barang
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('receivings.index') }}" class="nav-link {{ request()->routeIs('receivings.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data Penerimaan</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('receivings.create') }}" class="nav-link {{ request()->routeIs('receivings.create') ? 'active' : '' }}">
                      <i class="bi bi-plus-circle nav-icon"></i>
                      <p>Tambah Penerimaan</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Manajemen Stok -->
              <li class="nav-header">MANAJEMEN STOK</li>

              <!-- Stocks -->
              <li class="nav-item {{ request()->routeIs('stocks.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-boxes"></i>
                  <p>
                    Stok Gudang
                    <i class="right bi bi-chevron-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.index') ? 'active' : '' }}">
                      <i class="bi bi-list-ul nav-icon"></i>
                      <p>Data Stok</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('stocks.adjustment') }}" class="nav-link {{ request()->routeIs('stocks.adjustment') ? 'active' : '' }}">
                      <i class="bi bi-sliders nav-icon"></i>
                      <p>Penyesuaian Stok</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('stocks.transfer') }}" class="nav-link {{ request()->routeIs('stocks.transfer') ? 'active' : '' }}">
                      <i class="bi bi-arrow-left-right nav-icon"></i>
                      <p>Transfer Stok</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Content Wrapper -->
      <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">@yield('title')</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  @yield('breadcrumb', View::hasSection('breadcrumb') ? View::getSection('breadcrumb') : '')
                </ol>
              </div>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @yield('content')
          </div>
        </section>
      </div>

      <!-- Footer -->
      <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">PT. Besi Leleh Buatan Jakarta</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <span>Kelompok Arsa</span> | <b>Version</b> 1.0.0
        </div>
      </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
  </body>
</html>

<style>
/* Custom styles for better appearance */
.brand-text-sm {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-top: -5px;
    display: block;
}

.nav-header {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
    color: #6c757d;
    letter-spacing: 1px;
    margin-top: 10px;
}

.nav-icon {
    font-size: 1.1rem;
}

.nav-link.active {
    background-color: #007bff;
}

.nav-link:hover:not(.active) {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>
