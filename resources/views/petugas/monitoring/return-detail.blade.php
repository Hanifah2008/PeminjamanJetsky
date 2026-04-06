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
                        <p><strong>Due Date:</strong> <span class="badge badge-info">{{ $transaksi->due_date ? $transaksi->due_date->format('d-m-Y') : 'Belum ditentukan' }}</span></p>
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

                @if($transaksi->return_notes)
                <div class="alert alert-info mt-3">
                    <h5>📝 Catatan Pengembalian:</h5>
                    <p style="white-space: pre-wrap;">{{ $transaksi->return_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle mr-2"></i> Status Pengembalian
                </h5>
            </div>
            <div class="card-body text-center">
                @if($transaksi->return_status === 'pending')
                    <div class="alert alert-warning" role="alert">
                        <h4><i class="fas fa-hourglass-half mr-2"></i> Menunggu Pengembalian</h4>
                        <p class="mb-0">Belum dikembalikan oleh customer</p>
                    </div>
                @elseif($transaksi->return_status === 'returned')
                    <div class="alert alert-success" role="alert">
                        <h4><i class="fas fa-undo mr-2"></i> Sudah Dikembalikan</h4>
                        <p class="mb-0">{{ $transaksi->returned_at ? $transaksi->returned_at->format('d-m-Y H:i') : '-' }}</p>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4><i class="fas fa-exclamation-triangle mr-2"></i> OVERDUE</h4>
                        <p class="mb-0">Belum dikembalikan (Telat)</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kondisi Card -->
        @if($transaksi->kondisi)
        <div class="card mt-3">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-check mr-2"></i> Kondisi Alat
                </h5>
            </div>
            <div class="card-body">
                <p class="text-center">
                    @php
                        $kondisiColor = [
                            'baik' => 'success',
                            'rusak_ringan' => 'warning',
                            'rusak_berat' => 'danger',
                        ];
                        $kondisiLabel = [
                            'baik' => '✓ Baik',
                            'rusak_ringan' => '⚠ Rusak Ringan',
                            'rusak_berat' => '⚠ Rusak Berat',
                        ];
                    @endphp
                    <span class="badge badge-{{ $kondisiColor[$transaksi->kondisi] ?? 'secondary' }} badge-lg p-3">
                        {{ $kondisiLabel[$transaksi->kondisi] ?? 'N/A' }}
                    </span>
                </p>
            </div>
        </div>
        @endif

        <!-- Form Konfirmasi Pengembalian -->
        @if($transaksi->return_status === 'pending')
        <div class="card mt-3">
            <div class="card-header bg-success">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pengembalian
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('petugas.monitoring.process-return', $transaksi->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kondisi">Kondisi Alat <span class="text-danger">*</span>:</label>
                        <select name="kondisi" id="kondisi" class="form-control @error('kondisi') is-invalid @enderror" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik">✓ Baik</option>
                            <option value="rusak_ringan">⚠ Rusak Ringan</option>
                            <option value="rusak_berat">⚠ Rusak Berat</option>
                        </select>
                        @error('kondisi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="return_notes">Catatan (Opsional):</label>
                        <textarea name="return_notes" id="return_notes" class="form-control" rows="3" placeholder="Catatan kondisi alat, kerusakan, dll..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check mr-2"></i> Konfirmasi Pengembalian
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Mark as Overdue
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Gunakan jika peminjaman belum dikembalikan & sudah melewati due date</p>
                <form action="{{ route('petugas.monitoring.mark-overdue', $transaksi->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Tandai peminjaman ini sebagai overdue?')">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Mark as Overdue
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-3">
            <a href="{{ route('petugas.monitoring.return') }}" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Monitoring
            </a>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 16px;
    padding: 10px 20px !important;
}
</style>
