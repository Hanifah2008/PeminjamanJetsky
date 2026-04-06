<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="text-dark font-weight-bold">{{ $title }}</h5>
                <hr>

                @if(isset($kategori))
                    <form action="/admin/kategori/{{ $kategori->id }}" method="POST">
                        @method('PUT')
                @else
                    <form action="/admin/kategori" method="POST">
                @endif
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label text-dark">Nama Kategori</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Nama Kategori" value="{{ isset($kategori) ? $kategori->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/admin/kategori" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


