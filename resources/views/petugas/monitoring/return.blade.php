<!-- Statistics Row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalApproved }}</h3>
                <p>Total Peminjaman Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('petugas.monitoring.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pendingReturn }}</h3>
                <p>Menunggu Pengembalian</p>
            </div>
            <div class="icon">
                <i class="fas fa-inbox"></i>
            </div>
            <a href="#pending-returns" class="small-box-footer">
                Proses Sekarang <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $overdue }}</h3>
                <p>Overdue (Telat)</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="#overdue-loans" class="small-box-footer">
                Lihat Daftar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $returned }}</h3>
                <p>Sudah Dikembalikan</p>
            </div>
            <div class="icon">
                <i class="fas fa-undo"></i>
            </div>
            <a href="#returned-loans" class="small-box-footer">
                Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i> Status Pengembalian
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartReturnStatus" height="80"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i> Pengembalian 7 Hari Terakhir
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartDailyReturns" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Returns & Overdue -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">
                    <i class="fas fa-hourglass-end mr-2"></i> Akan Jatuh Tempo (3 Hari ke Depan)
                </h3>
            </div>
            <div class="card-body p-0">
                @if($upcomingReturns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Due Date</th>
                                <th>Hari Tersisa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingReturns as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->due_date ? $loan->due_date->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @php
                                        $daysLeft = \Carbon\Carbon::today()->diffInDays($loan->due_date, false);
                                    @endphp
                                    <span class="badge badge-warning">{{ abs($daysLeft) }} hari</span>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.monitoring.return-detail', $loan->id) }}" class="btn btn-info btn-xs">
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
                    <i class="fas fa-inbox"></i> Tidak ada pengembalian yang akan jatuh tempo
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title" id="overdue-loans">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Peminjaman Overdue
                </h3>
            </div>
            <div class="card-body p-0">
                @if($overdueLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Due Date</th>
                                <th>Telat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueLoans as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->due_date ? $loan->due_date->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @php
                                        $daysOverdue = \Carbon\Carbon::today()->diffInDays($loan->due_date);
                                    @endphp
                                    <span class="badge badge-danger">{{ $daysOverdue }} hari</span>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.monitoring.return-detail', $loan->id) }}" class="btn btn-info btn-xs">
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
                    <i class="fas fa-inbox"></i> Tidak ada peminjaman yang overdue
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Pending Returns & Returned Loans -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title" id="pending-returns">
                    <i class="fas fa-inbox mr-2"></i> Menunggu Pengembalian
                </h3>
            </div>
            <div class="card-body p-0">
                @if($pendingReturns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Alat</th>
                                <th>Due Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingReturns as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>
                                    @foreach($loan->details as $detail)
                                        <small>{{ $detail->alat->name ?? 'N/A' }}</small><br>
                                    @endforeach
                                </td>
                                <td>{{ $loan->due_date ? $loan->due_date->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('petugas.monitoring.return-detail', $loan->id) }}" class="btn btn-info btn-xs">
                                        <i class="fas fa-check"></i> Proses
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pendingReturns->links() }}
                @else
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-inbox"></i> Tidak ada peminjaman yang menunggu pengembalian
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title" id="returned-loans">
                    <i class="fas fa-undo mr-2"></i> Riwayat Pengembalian
                </h3>
            </div>
            <div class="card-body p-0">
                @if($returnedLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Kondisi</th>
                                <th>Dikembalikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returnedLoans as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>
                                    @php
                                        $kondisiColor = [
                                            'baik' => 'success',
                                            'rusak_ringan' => 'warning',
                                            'rusak_berat' => 'danger',
                                        ];
                                        $kondisiLabel = [
                                            'baik' => 'Baik',
                                            'rusak_ringan' => 'Rusak Ringan',
                                            'rusak_berat' => 'Rusak Berat',
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $kondisiColor[$loan->kondisi] ?? 'secondary' }}">
                                        {{ $kondisiLabel[$loan->kondisi] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $loan->returned_at ? $loan->returned_at->format('d-m-Y H:i') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $returnedLoans->links() }}
                @else
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-inbox"></i> Belum ada peminjaman yang dikembalikan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Return Status
    var ctxReturn = document.getElementById('chartReturnStatus').getContext('2d');
    var chartReturnStatus = {!! $chartReturnStatus !!};
    var chartReturnLabels = ['Pending', 'Returned', 'Overdue'];
    var chartReturnColors = ['#ffc107', '#28a745', '#dc3545'];
    
    new Chart(ctxReturn, {
        type: 'doughnut',
        data: {
            labels: chartReturnLabels,
            datasets: [{
                data: Object.values(chartReturnStatus),
                backgroundColor: chartReturnColors,
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

    // Chart Daily Returns
    var ctxDaily = document.getElementById('chartDailyReturns').getContext('2d');
    var chartDailyReturns = {!! $chartDailyReturns !!};
    
    new Chart(ctxDaily, {
        type: 'line',
        data: {
            labels: Object.keys(chartDailyReturns),
            datasets: [{
                label: 'Jumlah Pengembalian',
                data: Object.values(chartDailyReturns),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#28a745',
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
