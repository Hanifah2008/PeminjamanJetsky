<!-- Statistics Row -->
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
            <a href="{{ route('petugas.transaksi.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingApproval }}</h3>
                <p>Menunggu Approval</p>
            </div>
            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <a href="{{ route('petugas.transaksi.pending-approval') }}" class="small-box-footer">
                Proses Sekarang <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalApproved }}</h3>
                <p>Disetujui</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('petugas.transaksi.approval-history') }}" class="small-box-footer">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalRejected }}</h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('petugas.transaksi.approval-history') }}" class="small-box-footer">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Status & Charts Row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i> Status Approval
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartApprovalStatus" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i> Status Transaksi
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartTransaksiStatus" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Daily Loans & Nilai Peminjaman -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i> Peminjaman 7 Hari Terakhir
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartDailyLoans" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-bill mr-2"></i> Nilai Peminjaman
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nilai Selesai</span>
                                <span class="info-box-number">Rp. {{ number_format($totalNilaiPeminjaman, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nilai Pending</span>
                                <span class="info-box-number">Rp. {{ number_format($totalNilaiPending, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Loans & Today's Loans -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i> Peminjaman Aktif (Top 10)
                </h3>
            </div>
            <div class="card-body p-0">
                @if($activeLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeLoans as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>
                                    <strong>Rp. {{ number_format($loan->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.monitoring.detail', $loan->id) }}" class="btn btn-info btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-inbox"></i> Tidak ada peminjaman aktif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-calendar-day mr-2"></i> Peminjaman Hari Ini
                </h3>
            </div>
            <div class="card-body p-0">
                @if($todayLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayLoans as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>
                                    <strong>Rp. {{ number_format($loan->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($loan->approval_status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($loan->approval_status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-inbox"></i> Tidak ada peminjaman hari ini
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Top Customers -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-star mr-2"></i> Top 5 Customer (Sering Pinjam)
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($topCustomers as $customer)
                    <div class="col-md-6 col-lg-2 mb-3">
                        <div class="card card-outline card-success text-center">
                            <div class="card-body">
                                <h5 class="card-title">{{ $customer->name }}</h5>
                                <p class="text-muted mb-0">{{ $customer->email }}</p>
                                <hr>
                                <p class="card-text">
                                    <strong class="text-success">{{ $customer->transaksis_count }}</strong>
                                    <br><small>Kali Pinjam</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Approval Status
    var ctxApproval = document.getElementById('chartApprovalStatus').getContext('2d');
    var chartApprovalStatus = {!! $chartApprovalStatus !!};
    var chartApprovalLabels = ['Pending', 'Approved', 'Rejected'];
    var chartApprovalColors = ['#ffc107', '#28a745', '#dc3545'];
    
    new Chart(ctxApproval, {
        type: 'doughnut',
        data: {
            labels: chartApprovalLabels,
            datasets: [{
                data: Object.values(chartApprovalStatus),
                backgroundColor: chartApprovalColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart Transaksi Status
    var ctxTransaksi = document.getElementById('chartTransaksiStatus').getContext('2d');
    var chartTransaksiStatus = {!! $chartTransaksiStatus !!};
    var chartTransaksiLabels = ['Selesai', 'Pending'];
    var chartTransaksiColors = ['#28a745', '#ffc107'];
    
    new Chart(ctxTransaksi, {
        type: 'doughnut',
        data: {
            labels: chartTransaksiLabels,
            datasets: [{
                data: Object.values(chartTransaksiStatus),
                backgroundColor: chartTransaksiColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart Daily Loans
    var ctxDaily = document.getElementById('chartDailyLoans').getContext('2d');
    var chartDailyLoans = {!! $chartDailyLoans !!};
    
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: Object.keys(chartDailyLoans),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: Object.values(chartDailyLoans),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
