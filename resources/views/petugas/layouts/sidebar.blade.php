  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/petugas/dashboard" class="brand-link">
      <span class="brand-text font-weight-light"><i class="fas fa-user-tie"></i> Petugas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- User Panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/vendor/admin/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
          <span class="badge badge-success">Petugas</span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="/petugas/dashboard" class="nav-link {{ Request::is('petugas/dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/petugas/monitoring" class="nav-link {{ Request::is('petugas/monitoring') && !Request::is('petugas/monitoring/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Monitoring Pinjaman
                <i class="fas fa-arrow-circle-right right"></i>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/petugas/monitoring-return" class="nav-link {{ Request::is('petugas/monitoring-return*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-undo"></i>
              <p>
                Monitoring Pengembalian
                <span class="right badge badge-warning">{{ \App\Models\Transaksi::pendingReturn()->count() }}</span>
              </p>
            </a>
          </li>

          <li class="nav-header">TRANSAKSI & APPROVAL</li>
          <li class="nav-item has-treeview {{ Request::is('petugas/transaksi*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('petugas/transaksi*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Manajemen Pinjaman
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/petugas/transaksi/pending-approval" class="nav-link {{ Request::is('petugas/transaksi/pending-approval') ? 'active' : '' }}">
                  <i class="fas fa-hourglass-half nav-icon"></i>
                  <p>Menunggu Approval
                    <span class="right badge badge-danger">{{ \App\Models\Transaksi::pendingApproval()->count() }}</span>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/petugas/transaksi/approval-history" class="nav-link {{ Request::is('petugas/transaksi/approval-history') ? 'active' : '' }}">
                  <i class="fas fa-history nav-icon"></i>
                  <p>Riwayat Approval</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/petugas/transaksi" class="nav-link {{ Request::is('petugas/transaksi') && !Request::is('petugas/transaksi/*') ? 'active' : '' }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Semua Transaksi</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">DATA</li>
          <li class="nav-item">
            <a href="/petugas/alat" class="nav-link {{ Request::is('petugas/alat*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-box"></i>
              <p>Daftar Alat</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/petugas/user" class="nav-link {{ Request::is('petugas/user*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>Daftar Customer</p>
            </a>
          </li>

          <li class="nav-header">LAPORAN</li>
          <li class="nav-item">
            <a href="/petugas/laporan" class="nav-link {{ Request::is('petugas/laporan*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-pdf"></i>
              <p>
                Laporan & Cetak
                <i class="fas fa-arrow-circle-right right"></i>
              </p>
            </a>
          </li>

          <li class="nav-header">PENGATURAN</li>
          <li class="nav-item">
            <a href="/petugas/profil" class="nav-link {{ Request::is('petugas/profil*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>Profil Saya</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="content-wrapper">
