<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title">
            <i class="fas fa-file-invoice mr-2"></i> Laporan Peminjaman
        </h3>
        <div class="card-tools">
            <a href="{{ route('petugas.report.print-loan') }}?{{ request()->getQueryString() }}" class="btn btn-sm btn-danger" target="_blank">
                <i class="fas fa-print mr-2"></i> Cetak
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3" id="filterForm">
            <div class="form-group mr-3">
                <label for="date_from" class="mr-2">Dari Tanggal:</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}" />
            </div>
            <div class="form-group mr-3">
                <label for="date_to" class="mr-2">Sampai Tanggal:</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}" />
            </div>
            <div class="form-group mr-3">
                <label for="status" class="mr-2">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('petugas.report.loan') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>ID Peminjaman</th>
                        <th>Nama Penyewa</th>
                        <th>Total Alat</th>
                        <th>Total Biaya</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ ($loans->currentPage() - 1) * $loans->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>#{{ $loan->id }}</strong>
                            </td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->details->count() }}</td>
                            <td>Rp. {{ number_format($loan->total, 0, ',', '.') }}</td>
                            <td>{{ $loan->created_at ? $loan->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $loan->tgl_kembali ? $loan->tgl_kembali->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if ($loan->status === 'aktif')
                                    <span class="badge badge-info">Aktif</span>
                                @elseif ($loan->status === 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($loan->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if ($loans instanceof \Illuminate\Pagination\Paginator)
                    Menampilkan {{ $loans->from() ?? 0 }} hingga {{ $loans->to() ?? 0 }} dari {{ $loans->total() }} data
                @else
                    Total: {{ count($loans) }} data
                @endif
            </div>
            <div>
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
