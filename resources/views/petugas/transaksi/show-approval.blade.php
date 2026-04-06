<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice mr-2"></i> Detail Pinjaman
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID Transaksi:</strong> #{{ $transaksi->id }}</p>
                        <p><strong>Nama Customer:</strong> {{ $transaksi->user->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $transaksi->user->email ?? 'N/A' }}</p>
                        <p><strong>Telepon:</strong> {{ $transaksi->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total:</strong> <span class="text-success font-weight-bold">Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</span></p>
                        <p><strong>Tanggal Pinjam:</strong> {{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : '-' }}</p>
                        <p><strong>Status Transaksi:</strong> 
                            @if($transaksi->status === 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </p>
                        <p><strong>Status Approval:</strong> 
                            @if($transaksi->approval_status === 'pending')
                                <span class="badge badge-warning">Menunggu</span>
                            @elseif($transaksi->approval_status === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
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

                @if($transaksi->approval_status !== 'pending')
                <hr>
                <h5 class="mb-3"><i class="fas fa-check-circle mr-2"></i> Informasi Approval:</h5>
                <div class="alert alert-{{ $transaksi->approval_status === 'approved' ? 'success' : 'danger' }}" role="alert">
                    <p><strong>Diproses oleh:</strong> {{ $transaksi->approvedBy->name ?? 'N/A' }}</p>
                    <p><strong>Tanggal Approval:</strong> {{ $transaksi->approved_at ? $transaksi->approved_at->format('d-m-Y H:i') : 'N/A' }}</p>
                    <p><strong>Catatan:</strong></p>
                    <p style="white-space: pre-wrap;">{{ $transaksi->approval_notes ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($transaksi->approval_status === 'pending')
        <div class="card">
            <div class="card-header bg-success">
                <h5 class="card-title mb-0">
                    <i class="fas fa-thumbs-up mr-2"></i> Setujui Pinjaman
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('petugas.transaksi.approve', $transaksi->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="approval_notes">Catatan (Opsional):</label>
                        <textarea class="form-control" id="approval_notes" name="approval_notes" rows="4" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block btn-lg">
                        <i class="fas fa-check mr-2"></i> Setujui Pinjaman
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger">
                <h5 class="card-title mb-0">
                    <i class="fas fa-thumbs-down mr-2"></i> Tolak Pinjaman
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('petugas.transaksi.reject', $transaksi->id) }}" method="POST" id="rejectForm">
                    @csrf
                    <div class="form-group">
                        <label for="reject_notes">Alasan Penolakan <span class="text-danger">*</span>:</label>
                        <textarea class="form-control @error('approval_notes') is-invalid @enderror" 
                                  id="reject_notes" name="approval_notes" rows="4" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                        @error('approval_notes')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger btn-block btn-lg" onclick="return confirm('Tolak pinjaman ini? Tindakan ini tidak dapat dibatalkan!')">
                        <i class="fas fa-times mr-2"></i> Tolak Pinjaman
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i> Status Pinjaman
                </h5>
            </div>
            <div class="card-body text-center">
                @if($transaksi->approval_status === 'approved')
                    <div class="alert alert-success" role="alert">
                        <h4><i class="fas fa-check-circle mr-2"></i> Sudah Disetujui</h4>
                        <p class="mb-0">Pinjaman ini telah disetujui oleh {{ $transaksi->approvedBy->name ?? 'N/A' }}</p>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4><i class="fas fa-times-circle mr-2"></i> Ditolak</h4>
                        <p class="mb-0">Pinjaman ini telah ditolak oleh {{ $transaksi->approvedBy->name ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            @if($transaksi->approval_status === 'approved')
            <a href="{{ route('petugas.transaksi.print-receipt', $transaksi->id) }}" class="btn btn-warning btn-block" target="_blank">
                <i class="fas fa-print mr-2"></i> Cetak Struk Peminjaman
            </a>
            @endif
            <a href="{{ route('petugas.transaksi.pending-approval') }}" class="btn btn-secondary btn-block mt-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
        @endif
    </div>
</div>
