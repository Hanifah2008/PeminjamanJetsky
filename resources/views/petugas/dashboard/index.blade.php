<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $transaksiCount }}</h3>
                <p>Transaksi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $alatCount }}</h3>
                <p>Total Alat Tersedia</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Total Customer</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Petugas</h3>
            </div>
            <div class="card-body">
                <p><strong>Selamat datang di Dashboard Petugas Kasir!</strong></p>
                <ul>
                    <li>Monitor transaksi pinjaman alat secara real-time</li>
                    <li>Lihat status ketersediaan alat</li>
                    <li>Kelola data customer dan transaksi</li>
                    <li>Cetak laporan transaksi</li>
                </ul>
                <p style="margin-top: 15px; color: #666; font-size: 13px;">
                    <i class="fas fa-user-circle"></i> Logged in as: <strong>{{ auth()->user()->name }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>
