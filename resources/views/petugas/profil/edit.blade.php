<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Profil Petugas</h5>
                </div>
                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> Ada kesalahan:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="/petugas/profil/update" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label"><strong>Foto Profil:</strong></label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <div style="width: 150px; height: 150px; border-radius: 10px; overflow: hidden; border: 2px solid #007bff; margin-bottom: 10px;">
                                        <img id="previewImage" 
                                             src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : '/vendor/admin/dist/img/user2-160x160.jpg' }}" 
                                             alt="Profile Picture" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                                <input type="file" id="profilePicture" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max: 2MB)</small>
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Nama Lengkap -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>Nama Lengkap:</strong></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>Email:</strong></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                <small class="text-muted">Email tidak bisa diubah</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- No. Telepon -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>No. Telepon:</strong></label>
                            <div class="col-md-9">
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xx-xxxx-xxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>Tanggal Lahir:</strong></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       name="birth_date" value="{{ old('birth_date', auth()->user()->birth_date ? auth()->user()->birth_date->format('Y-m-d') : '') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>Alamat:</strong></label>
                            <div class="col-md-9">
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          name="address" rows="4" placeholder="Masukkan alamat lengkap Anda">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Ubah Password -->
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label"><strong>Password Baru (Opsional):</strong></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <small class="text-muted">Minimal 8 karakter</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label"><strong>Konfirmasi Password:</strong></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" 
                                       name="password_confirmation" placeholder="Ulangi password baru">
                                <small class="text-muted">Jika mengubah password, konfirmasi password wajib diisi</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Buttons -->
                        <div class="text-end">
                            <a href="/petugas/profil" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }

    .form-control {
        border-radius: 0.375rem;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>

<script>
    // Preview image sebelum upload
    document.getElementById('profilePicture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImage').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
