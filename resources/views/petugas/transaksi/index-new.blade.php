<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt mr-2"></i> Manajemen Pinjaman & Approval
                </h3>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('petugas.transaksi.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama customer..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="approval_status" class="form-control">
                                <option value="">-- Status Approval --</option>
                                <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">-- Status Transaksi --</option>
                                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Quick Links -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <a href="{{ route('petugas.transaksi.pending-approval') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-hourglass-half mr-2"></i> 
                            Pinjaman Pending 
                            <span class="badge badge-light float-right">{{ \App\Models\Transaksi::pendingApproval()->count() }}</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas.transaksi.approval-history') }}" class="btn btn-info btn-block">
                            <i class="fas fa-history mr-2"></i> 
                            Riwayat Approval
                            <span class="badge badge-light float-right">{{ \App\Models\Transaksi::whereIn('approval_status', ['approved', 'rejected'])->count() }}</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('petugas.transaksi.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-list mr-2"></i> 
                            Semua Transaksi
                            <span class="badge badge-light float-right">{{ \App\Models\Transaksi::count() }}</span>
                        </a>
                    </div>
                </div>

                <!-- Table -->
                @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Nama Customer</th>
                                <th style="width: 10%">Total</th>
                                <th style="width: 12%">Status Transaksi</th>
                                <th style="width: 12%">Status Approval</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 30%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $transaksi)
                            <tr>
                                <td>#{{ $transaksi->id }}</td>
                                <td>
                                    <strong>{{ $transaksi->user->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $transaksi->user->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($transaksi->status === 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaksi->approval_status === 'pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @elseif($transaksi->approval_status === 'approved')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('petugas.transaksi.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                    @if($transaksi->approval_status === 'approved')
                                        <a href="{{ route('petugas.transaksi.print-receipt', $transaksi->id) }}" class="btn btn-warning btn-sm" target="_blank">
                                            <i class="fas fa-print mr-1"></i> Struk
                                        </a>
                                    @endif
                                    @if($transaksi->approval_status === 'pending')
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal{{ $transaksi->id }}">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Quick Approve Modal -->
                            @if($transaksi->approval_status === 'pending')
                            <div class="modal fade" id="approveModal{{ $transaksi->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">Setujui Pinjaman #{{ $transaksi->id }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <form action="{{ route('petugas.transaksi.approve', $transaksi->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>Customer:</strong> {{ $transaksi->user->name }}</p>
                                                <p><strong>Total:</strong> Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                                                <div class="form-group">
                                                    <label for="notes{{ $transaksi->id }}">Catatan (Opsional):</label>
                                                    <textarea class="form-control" id="notes{{ $transaksi->id }}" name="approval_notes" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Setujui</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle mr-2"></i> Tidak ada transaksi ditemukan
                </div>
                @endif
            </div>
            <div class="card-footer clearfix">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.table-responsive {
    overflow-x: auto;
}
.badge {
    padding: 0.5em 0.75em;
}
</style>
