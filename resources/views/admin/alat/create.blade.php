<!-- Tambahkan Bootstrap dan SweetAlert -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Form Alat -->
<div class="container mt-4">
    <div class="card shadow-sm border-0" style="background-color: #fff;">
        <div class="card-body">
            <h5 class="fw-bold text-center">{{ $title }}</h5>
            <hr>
            <form id="alatForm" action="{{ isset($alat) ? '/admin/alat/' . $alat->id : '/admin/alat' }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @isset($alat)
                    @method('PUT')
                @endisset
                
                <div class="mb-3">
                    <label class="form-label">Nama Alat</label>
                    <input type="text" name="name" class="form-control" 
                        placeholder="Nama alat" value="{{ isset($alat) ? $alat->name : old('name') }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" 
                                {{ (isset($alat) && $alat->kategori_id == $item->id) || old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tarif Sewa</label>
                            <input type="number" name="harga" class="form-control" placeholder="Tarif Sewa" 
                                value="{{ isset($alat) ? $alat->harga : old('harga') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" placeholder="Stok" 
                                value="{{ isset($alat) ? $alat->stok : old('stok') }}">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Gambar Alat</label>
                    <input type="file" name="gambar" class="form-control">
                    @isset($alat)
                        <img src="/{{ $alat->gambar }}" class="mt-2" width="100px" alt="">
                    @endisset
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="/admin/alat" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert Konfirmasi -->
<script>
    function submitForm() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('alatForm').submit();
            }
        });
    }
</script>
