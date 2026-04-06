<div class="container-fluid mt-2">
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Hallo {{ auth()->user()->name }}! Selamat Datang di Halaman Hani Jestky Jogja!!
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info clickable-card" onclick="location.href='/admin/alat';" style="cursor: pointer;">
            <div class="inner">
                <h3>{{ $alatCount }}</h3>
                <p>Total Alat</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success clickable-card" onclick="location.href='/admin/transaksi';" style="cursor: pointer;">
            <div class="inner">
                <h3>{{ $transaksiCount }}</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning clickable-card" onclick="location.href='/admin/kategori';" style="cursor: pointer;">
            <div class="inner">
                <h3>{{ $kategoriCount }}</h3>
                <p>Total Kategori</p>
            </div>
            <div class="icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger clickable-card" onclick="location.href='/admin/user';" style="cursor: pointer;">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Total User</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </div>
        </div>
    </div>
</div>

<!-- Penjualan Cards -->
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-6">
        <div class="card bg-gradient-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="div">
                            <span>Penjualan Hari Ini</span>
                            <h3 class="fg-white">Rp. {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="clearfix">
                            <span class="float-left"><i class="fas fa-calendar-day fa-3x opacity-10"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row align-items-center">
                    <div class="col-12 text-right">
                        <span class="text-muted text-sm"><i class="fas fa-redo mr-1"></i> Diperbarui setiap real-time</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card bg-gradient-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="div">
                            <span>Penjualan Bulan Ini</span>
                            <h3 class="fg-white">Rp. {{ number_format($penjualanBulanIni, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="clearfix">
                            <span class="float-left"><i class="fas fa-calendar-alt fa-3x opacity-10"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row align-items-center">
                    <div class="col-12 text-right">
                        <span class="text-muted text-sm"><i class="fas fa-chart-line mr-1"></i> Trend stabil</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i> Penjualan 30 Hari Terakhir
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative mb-4">
                    <canvas id="chartHarian" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i> Penjualan 12 Bulan Terakhir
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative mb-4">
                    <canvas id="chartBulanan" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="row" style="margin-top: 20px;">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> 5 Transaksi Terbaru
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $transaksi)
                            <tr>
                                <td>
                                    <a href="/admin/transaksi/{{ $transaksi->id }}" target="_blank">
                                        #{{ $transaksi->id }}
                                    </a>
                                </td>
                                <td>{{ $transaksi->user->name ?? 'N/A' }}</td>
                                <td>
                                    <strong>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-success">Selesai</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Harian (30 hari)
    const ctxHarian = document.getElementById('chartHarian').getContext('2d');
    const chartHarian = new Chart(ctxHarian, {
        type: 'line',
        data: {
            labels: {!! $hariLabels !!},
            datasets: [{
                label: 'Penjualan Harian (Rp)',
                data: {!! $penjualanPerHari !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        boxWidth: 15,
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });

    // Chart Bulanan (12 bulan)
    const ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
    const chartBulanan = new Chart(ctxBulanan, {
        type: 'bar',
        data: {
            labels: {!! $bulanLabels !!},
            datasets: [{
                label: 'Penjualan Bulanan (Rp)',
                data: {!! $penjualanPerBulan !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(13, 148, 136, 0.8)',
                    'rgba(220, 38, 38, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(99, 102, 241, 0.8)',
                ],
                borderColor: '#3b82f6',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        boxWidth: 15,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, #3b82f6, #1e40af);
        color: white !important;
    }

    .bg-gradient-success {
        background: linear-gradient(to right, #10b981, #047857);
        color: white !important;
    }

    .bg-gradient-primary .card-body {
        color: white;
    }

    .bg-gradient-success .card-body {
        color: white;
    }

    .bg-gradient-primary .fg-white,
    .bg-gradient-success .fg-white {
        color: white;
        font-size: 28px;
        font-weight: bold;
        margin-top: 10px;
    }

    .opacity-10 {
        opacity: 0.1;
        color: white;
    }
    /* Hover effect untuk clickable cards */
    .small-box {
        transition: all 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .small-box-footer {
        display: block;
        padding: 10px 15px;
        text-align: right;
        background-color: rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .small-box-footer:hover {
        background-color: rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }