<div class="card">
    <div class="card-header bg-success">
        <h3 class="card-title">
            <i class="fas fa-check-circle mr-2"></i> Laporan Approval
        </h3>
        <div class="card-tools">
            <a href="{{ route('petugas.report.print-approval') }}?{{ request()->getQueryString() }}" class="btn btn-sm btn-danger" target="_blank">
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
                <label for="approval_status" class="mr-2">Status Approval:</label>
                <select name="approval_status" id="approval_status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('petugas.report.approval') }}" class="btn btn-secondary ml-2">
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
                        <th>Tanggal Permohonan</th>
                        <th>Status Approval</th>
                        <th>Disetujui Oleh</th>
                        <th>Tanggal Approval</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvals as $approval)
                        <tr>
                            <td>{{ ($approvals->currentPage() - 1) * $approvals->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong>#{{ $approval->id }}</strong>
                            </td>
                            <td>{{ $approval->user->name }}</td>
                            <td>{{ $approval->details->count() }}</td>
                            <td>{{ $approval->created_at ? $approval->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                @if ($approval->approval_status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($approval->approval_status === 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif ($approval->approval_status === 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $approval->approvedBy?->name ?? '-' }}</td>
                            <td>{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $approval->approval_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data approval</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if ($approvals instanceof \Illuminate\Pagination\Paginator)
                    Menampilkan {{ $approvals->from() ?? 0 }} hingga {{ $approvals->to() ?? 0 }} dari {{ $approvals->total() }} data
                @else
                    Total: {{ count($approvals) }} data
                @endif
            </div>
            <div>
                {{ $approvals->links() }}
            </div>
        </div>
    </div>
</div>
