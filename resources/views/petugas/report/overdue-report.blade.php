<div class="card">
    <div class="card-header bg-danger">
        <h3 class="card-title">
            <i class="fas fa-exclamation-triangle mr-2"></i> Laporan Overdue
        </h3>
        <div class="card-tools">
            <a href="{{ route('petugas.report.print-overdue') }}?{{ request()->getQueryString() }}" class="btn btn-sm btn-danger" target="_blank">
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
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('petugas.report.overdue') }}" class="btn btn-secondary ml-2">
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
                        <th>No Telepon</th>
                        <th>Email</th>
                        <th>Tanggal Due</th>
                        <th>Hari Overdue</th>
                        <th>Total Alat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($overdues as $overdue)
                        <tr>
                            <td>{{ ($overdues->currentPage() - 1) * $overdues->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>#{{ $overdue->id }}</strong>
                            </td>
                            <td>{{ $overdue->user->name }}</td>
                            <td>{{ $overdue->user->phone ?? '-' }}</td>
                            <td>{{ $overdue->user->email ?? '-' }}</td>
                            <td>{{ $overdue->tgl_kembali ? $overdue->tgl_kembali->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if ($overdue->tgl_kembali)
                                    <span class="badge badge-danger">
                                        {{ now()->diffInDays($overdue->tgl_kembali) }} hari
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $overdue->details->count() }}</td>
                            <td>
                                <span class="badge badge-danger">Overdue</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada peminjaman overdue</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if ($overdues instanceof \Illuminate\Pagination\Paginator)
                    Menampilkan {{ $overdues->from() ?? 0 }} hingga {{ $overdues->to() ?? 0 }} dari {{ $overdues->total() }} data
                @else
                    Total: {{ count($overdues) }} data
                @endif
            </div>
            <div>
                {{ $overdues->links() }}
            </div>
        </div>
    </div>
</div>
