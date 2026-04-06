<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt mr-2"></i> Daftar Transaksi
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                        <tr>
                            <td>#{{ $transaksi->id }}</td>
                            <td>{{ $transaksi->user->name ?? 'N/A' }}</td>
                            <td>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
                            <td><span class="badge badge-success">Selesai</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</div>
