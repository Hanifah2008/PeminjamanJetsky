<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-box mr-2"></i> Daftar Alat
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Diskon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alats as $alat)
                        <tr>
                            <td>#{{ $alat->id }}</td>
                            <td>{{ $alat->name }}</td>
                            <td>{{ $alat->kategori->name ?? 'N/A' }}</td>
                            <td>Rp. {{ number_format($alat->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $alat->stok > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $alat->stok }}
                                </span>
                            </td>
                            <td>{{ $alat->diskon_persen }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada alat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $alats->links() }}
            </div>
        </div>
    </div>
</div>
