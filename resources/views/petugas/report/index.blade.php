<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalTransaksi }}</h3>
                <p>Total Peminjaman</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <a href="{{ route('petugas.report.loan') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalApproved }}</h3>
                <p>Peminjaman Disetujui</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('petugas.report.approval') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalReturned }}</h3>
                <p>Sudah Dikembalikan</p>
            </div>
            <div class="icon">
                <i class="fas fa-undo"></i>
            </div>
            <a href="{{ route('petugas.report.return') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $overdue }}</h3>
                <p>Overdue</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('petugas.report.overdue') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-file-pdf mr-2"></i> Daftar Laporan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-file-invoice mr-2"></i> Laporan Peminjaman
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>Laporan lengkap semua peminjaman alat dengan filter berdasarkan tanggal dan status.</p>
                                <a href="{{ route('petugas.report.loan') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-eye mr-2"></i> Lihat Laporan Peminjaman
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-check-circle mr-2"></i> Laporan Approval
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>Laporan approval peminjaman dengan informasi petugas yang menyetujui dan tanggal approval.</p>
                                <a href="{{ route('petugas.report.approval') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-eye mr-2"></i> Lihat Laporan Approval
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-undo mr-2"></i> Laporan Pengembalian
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>Laporan pengembalian alat dengan informasi kondisi alat dan tanggal pengembalian.</p>
                                <a href="{{ route('petugas.report.return') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-eye mr-2"></i> Lihat Laporan Pengembalian
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Laporan Overdue
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>Laporan peminjaman yang telat/overdue belum dikembalikan sesuai dengan due date.</p>
                                <a href="{{ route('petugas.report.overdue') }}" class="btn btn-danger btn-block">
                                    <i class="fas fa-eye mr-2"></i> Lihat Laporan Overdue
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
