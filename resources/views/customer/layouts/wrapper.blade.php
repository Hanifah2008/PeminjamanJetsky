<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Dashboard - Hani Jestky Jogja</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/vendor/admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/vendor/admin/dist/css/adminlte.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/customer/dashboard" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <!-- Keranjang Badge -->
                <li class="nav-item">
                    <a href="{{ route('customer.keranjang.index') }}" class="nav-link position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        @php
                            $keranjangCount = \App\Models\Keranjang::where('user_id', auth()->id())->count();
                        @endphp
                        @if($keranjangCount > 0)
                            <span class="badge badge-danger position-absolute" 
                                  style="top: 0; right: 0; font-size: 10px; padding: 2px 5px;">
                                {{ $keranjangCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/customer/logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/" class="brand-link">
                <i class="fas fa-shopping-bag"></i>
                <span class="brand-text font-weight-light">Hani Jestky Jogja</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="/customer/dashboard" class="nav-link {{ Request::is('customer/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.belanja.index') }}" class="nav-link {{ Request::is('customer/belanja*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>Belanja</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.keranjang.index') }}" class="nav-link {{ Request::is('customer/keranjang*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Keranjang
                                    @php
                                        $keranjangCount = \App\Models\Keranjang::where('user_id', auth()->id())->count();
                                    @endphp
                                    @if($keranjangCount > 0)
                                        <span class="badge badge-danger right">{{ $keranjangCount }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.riwayat.index') }}" class="nav-link {{ Request::is('customer/riwayat*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Pembelian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.profil.index') }}" class="nav-link {{ Request::is('customer/profil*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Profil</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content -->
        <div class="content-wrapper">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="fas fa-exclamation-circle"></i> 
                    <strong>Terjadi Kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(isset($content) && $content == 'customer.dashboard.index')
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Dashboard</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="content">
                    <div class="container-fluid">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h3 class="card-title">Selamat Datang</h3>
                                    </div>
                                    <div class="card-body">
                                        <h4>Halo, {{ auth()->user()->name }}! 👋</h4>
                                        <p class="text-muted">Ini adalah dashboard customer Anda. Anda dapat melihat riwayat pembelian, status pesanan, dan informasi akun Anda.</p>
                                        
                                        <div class="mt-4">
                                            <h5>Fitur yang Tersedia:</h5>
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <i class="fas fa-shopping-cart text-primary"></i> 
                                                    <a href="{{ route('customer.belanja.index') }}">Pinjam alat dari katalog kami</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="fas fa-history text-info"></i> 
                                                    <a href="{{ route('customer.riwayat.index') }}">Lihat riwayat peminjaman Anda</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="fas fa-user text-success"></i> 
                                                    <a href="{{ route('customer.profil.index') }}">Kelola profil akun Anda</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card bg-light">
                                    <div class="card-header bg-white">
                                        <h3 class="card-title">Informasi Akun</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Nama:</strong>
                                            <p>{{ auth()->user()->name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Email:</strong>
                                            <p>{{ auth()->user()->email }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Role:</strong>
                                            <p><span class="badge badge-success">{{ ucfirst(auth()->user()->role) }}</span></p>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Terdaftar Sejak:</strong>
                                            <p>{{ auth()->user()->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @else
                @include($content ?? 'customer.belanja.index')
            @endif
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="/">Reza Rohanifah, Jetski Bntul</a>.</strong> All rights reserved.
        </footer>
    </div>

    <script src="/vendor/admin/plugins/jquery/jquery.min.js"></script>
    <script src="/vendor/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/admin/dist/js/adminlte.min.js"></script>
</body>
</html>
