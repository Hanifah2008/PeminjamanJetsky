<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i> Riwayat Approval Pinjaman
                </h3>
            </div>
            <div class="card-body">
                @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Nama Customer</th>
                                <th style="width: 10%">Total</th>
                                <th style="width: 15%">Status</th>
                                <th style="width: 15%">Diproses oleh</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 15%">Aksi</th>
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
                                    @if($transaksi->approval_status === 'approved')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $transaksi->approvedBy->name ?? 'N/A' }}
                                </td>
                                <td>{{ $transaksi->approved_at ? $transaksi->approved_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('petugas.transaksi.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    @if($transaksi->approval_status === 'approved')
                                    <a href="{{ route('petugas.transaksi.print-receipt', $transaksi->id) }}" class="btn btn-warning btn-sm" target="_blank">
                                        <i class="fas fa-print mr-1"></i> Struk
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle mr-2"></i> Tidak ada riwayat approval
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
.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}
.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
}
</style>
