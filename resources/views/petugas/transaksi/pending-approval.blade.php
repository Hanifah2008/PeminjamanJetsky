<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">
                    <i class="fas fa-hourglass-half mr-2"></i> Pinjaman Menunggu Approval
                </h3>
                <span class="float-right">
                    Total Pending: <strong class="badge badge-danger">{{ $transaksis->total() }}</strong>
                </span>
            </div>
            <div class="card-body">
                @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Nama Customer</th>
                                <th style="width: 10%">Total</th>
                                <th style="width: 15%">Tanggal Pinjam</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 40%">Aksi</th>
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
                                <td>{{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('petugas.transaksi.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat Detail
                                    </a>
                                    <form action="{{ route('petugas.transaksi.approve', $transaksi->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui pinjaman ini?')">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle mr-2"></i> Tidak ada pinjaman yang menunggu approval
                </div>
                @endif
            </div>
            <div class="card-footer clearfix">
                <div class="pagination-wrapper">
                    {{ $transaksis->links() }}
                </div>
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
