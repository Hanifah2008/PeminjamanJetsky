<div class="card">
    <div class="card-header bg-warning">
        <h3 class="card-title">
            <i class="fas fa-undo mr-2"></i> Laporan Pengembalian
        </h3>
        <div class="card-tools">
            <a href="{{ route('petugas.report.print-return') }}?{{ request()->getQueryString() }}" class="btn btn-sm btn-danger" target="_blank">
                <i class="fas fa-print mr-2"></i> Cetak
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3" id="filterForm">
            <div class="form-group mr-3">
                <label for="durasi_jam" class="mr-2">Durasi Peminjaman:</label>
                <select name="durasi_jam" id="durasi_jam" class="form-control">
                    <option value="">-- Semua Durasi --</option>
                    <option value="1" {{ request('durasi_jam') === '1' ? 'selected' : '' }}>1 Jam</option>
                    <option value="2" {{ request('durasi_jam') === '2' ? 'selected' : '' }}>2 Jam</option>
                    <option value="3" {{ request('durasi_jam') === '3' ? 'selected' : '' }}>3 Jam</option>
                </select>
            </div>
            <div class="form-group mr-3">
                <label for="return_status" class="mr-2">Status Pengembalian:</label>
                <select name="return_status" id="return_status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('return_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="returned" {{ request('return_status') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="overdue" {{ request('return_status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('petugas.report.return') }}" class="btn btn-secondary ml-2">
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
                        <th>Durasi Peminjaman</th>
                        <th>Jam Kembali</th>
                        <th>Jam Sekarang</th>
                        <th>Status Pengembalian</th>
                        <th>Kondisi Alat</th>
                        <th>Tanggal Dikonfirmasi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $return)
                        <tr>
                            <td>{{ ($returns->currentPage() - 1) * $returns->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>#{{ $return->id }}</strong>
                            </td>
                            <td>{{ $return->user->name }}</td>
                            <td>
                                @php
                                    $durasiDisplay = '';
                                    if($return->details->count() > 0) {
                                        $firstDuration = $return->details->first()->durasi_jam;
                                        $durasiDisplay = (int)$firstDuration . ' Jam' . ($firstDuration > 1 ? '' : '');
                                    }
                                @endphp
                                <span class="badge badge-primary">{{ $durasiDisplay ?: '-' }}</span>
                            </td>
                            <td>
                                <strong>{{ $return->due_date ? $return->due_date->format('H:i') : '-' }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ now()->format('H:i') }}</span>
                            </td>
                            <td>
                                @if ($return->return_status === 'pending')
                                    <span class="badge badge-info">Pending</span>
                                @elseif ($return->return_status === 'returned')
                                    <span class="badge badge-success">Dikembalikan</span>
                                @elseif ($return->return_status === 'overdue')
                                    <span class="badge badge-danger">Overdue</span>
                                @endif
                            </td>
                            <td>
                                @if ($return->kondisi === 'baik')
                                    <span class="badge badge-success">Baik</span>
                                @elseif ($return->kondisi === 'rusak_ringan')
                                    <span class="badge badge-warning">Rusak Ringan</span>
                                @elseif ($return->kondisi === 'rusak_berat')
                                    <span class="badge badge-danger">Rusak Berat</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $return->returned_at ? $return->returned_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $return->return_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if ($returns instanceof \Illuminate\Pagination\Paginator)
                    Menampilkan {{ $returns->from() ?? 0 }} hingga {{ $returns->to() ?? 0 }} dari {{ $returns->total() }} data
                @else
                    Total: {{ count($returns) }} data
                @endif
            </div>
            <div>
                {{ $returns->links() }}
            </div>
        </div>
    </div>
</div>
