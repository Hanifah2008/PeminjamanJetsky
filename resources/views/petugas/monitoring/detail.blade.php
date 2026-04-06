<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice mr-2"></i> Informasi Peminjaman
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID Transaksi:</strong> #{{ $transaksi->id }}</p>
                        <p><strong>Nama Customer:</strong> {{ $transaksi->user->name }}</p>
                        <p><strong>Email:</strong> {{ $transaksi->user->email }}</p>
                        <p><strong>Telepon:</strong> {{ $transaksi->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Nilai:</strong> <span class="text-success font-weight-bold">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</span></p>
                        <p><strong>Tanggal Pinjam:</strong> {{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : '-' }}</p>
                        <p><strong>Status Transaksi:</strong> 
                            @if($transaksi->status === 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-boxes mr-2"></i> Detail Alat yang Dipinjam:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Alat</th>
                                <th>Kategori</th>
                                <th>Qty</th>
                                <th>Durasi</th>
                                <th>Harga/Hari</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->details as $detail)
                            <tr>
                                <td>{{ $detail->alat->name ?? 'N/A' }}</td>
                                <td>{{ $detail->alat->kategori->name ?? 'N/A' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->durasi ?? '-' }} Hari</td>
                                <td>Rp. {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Approval Status -->
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i> Status Approval
                </h5>
            </div>
            <div class="card-body text-center">
                @if($transaksi->approval_status === 'pending')
                    <div class="alert alert-warning" role="alert">
                        <h4><i class="fas fa-hourglass-half mr-2"></i> Menunggu Approval</h4>
                        <p class="mb-0">Belum diproses oleh petugas</p>
                    </div>
                @elseif($transaksi->approval_status === 'approved')
                    <div class="alert alert-success" role="alert">
                        <h4><i class="fas fa-check-circle mr-2"></i> Disetujui</h4>
                        <p class="mb-0">{{ $transaksi->approvedBy->name ?? 'N/A' }}</p>
                        <p class="mb-0">{{ $transaksi->approved_at ? $transaksi->approved_at->format('d-m-Y H:i') : '-' }}</p>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4><i class="fas fa-times-circle mr-2"></i> Ditolak</h4>
                        <p class="mb-0">{{ $transaksi->approvedBy->name ?? 'N/A' }}</p>
                        <p class="mb-0">{{ $transaksi->approved_at ? $transaksi->approved_at->format('d-m-Y H:i') : '-' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval Notes -->
        @if($transaksi->approval_notes)
        <div class="card mt-3">
            <div class="card-header bg-secondary">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sticky-note mr-2"></i> Catatan Approval
                </h5>
            </div>
            <div class="card-body">
                <p style="white-space: pre-wrap;">{{ $transaksi->approval_notes }}</p>
            </div>
        </div>
        @endif

        <!-- Timeline -->
        <div class="card mt-3">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history mr-2"></i> Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <!-- Created -->
                    <div class="time-label">
                        <span class="bg-blue">{{ $transaksi->created_at ? $transaksi->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div>
                        <i class="fas fa-file-invoice bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $transaksi->created_at ? $transaksi->created_at->format('H:i') : '-' }}</span>
                            <h3 class="timeline-header">Peminjaman Dibuat</h3>
                            <div class="timeline-body">
                                Transaksi pinjaman dibuat oleh customer
                            </div>
                        </div>
                    </div>

                    <!-- Approval -->
                    @if($transaksi->approval_status !== 'pending')
                    <div class="time-label">
                        <span class="bg-{{ $transaksi->approval_status === 'approved' ? 'success' : 'danger' }}">{{ $transaksi->approved_at ? $transaksi->approved_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div>
                        <i class="fas fa-{{ $transaksi->approval_status === 'approved' ? 'check-circle' : 'times-circle' }} bg-{{ $transaksi->approval_status === 'approved' ? 'success' : 'danger' }}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{ $transaksi->approved_at ? $transaksi->approved_at->format('H:i') : '-' }}</span>
                            <h3 class="timeline-header">
                                {{ $transaksi->approval_status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                            </h3>
                            <div class="timeline-body">
                                Oleh: {{ $transaksi->approvedBy->name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-3">
            <a href="{{ route('petugas.monitoring.index') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Monitoring
            </a>
            <a href="{{ route('petugas.transaksi.show', $transaksi->id) }}" class="btn btn-info btn-block mt-2">
                <i class="fas fa-edit mr-2"></i> Detail Approval
            </a>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    height: 100%;
    width: 4px;
    background: #dee2e6;
}

.timeline-item {
    margin-bottom: 20px;
    margin-left: 60px;
}

.time-label {
    position: relative;
    margin-bottom: 5px;
    margin-left: 0;
}

.time-label span {
    position: relative;
    display: inline-block;
    padding: 5px 10px;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.timeline > div > i {
    position: absolute;
    left: 15px;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    color: white;
    border-radius: 50%;
}

.timeline > div > i.bg-blue {
    background: #007bff;
}

.timeline > div > i.bg-success {
    background: #28a745;
}

.timeline > div > i.bg-danger {
    background: #dc3545;
}

.timeline > div > i.bg-gray {
    background: #6c757d;
}
</style>
