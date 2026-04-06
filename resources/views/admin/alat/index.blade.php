<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark"><i class="fas fa-box"></i> {{ $title }}</h5>
            <a href="/admin/alat/create" class="btn btn-primary btn-sm font-weight-bold shadow-sm px-3 py-2">
                <i class="fas fa-plus"></i> Tambah Alat
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">Gambar</th>
                            <th>Nama Alat</th>
                            <th>Tarif Sewa</th>
                            <th>Stok</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alat as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            </td>
                            <td class="text-start">{{ $item->name }}</td>
                            <td class="text-center">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->stok }}</td>
                            <td class="text-center">
                                <a href="/admin/alat/{{ $item->id }}/edit" class="btn btn-sm btn-outline-primary mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger mx-1" onclick="confirmDelete({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $alat->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title text-dark"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p class="text-dark">Apakah Anda yakin ingin menghapus alat ini?</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        let form = document.getElementById('deleteForm');
        form.action = `/admin/alat/${id}`;
        $('#deleteModal').modal('show');
    }
</script>
