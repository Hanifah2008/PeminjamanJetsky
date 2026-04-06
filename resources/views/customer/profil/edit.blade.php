@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Profil</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.profil.index') }}">Profil</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-light" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-edit"></i> Edit Informasi Profil
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('customer.profil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Foto Profil -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-image"></i> Foto Profil
                                </label>
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div style="text-align: center;">
                                            <div id="previewImage">
                                                @if($user->profile_picture && file_exists(public_path($user->profile_picture)))
                                                    <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" 
                                                         class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #667eea;">
                                                @else
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 100px; height: 100px; background: #f0f0f0; color: #999; font-size: 30px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" name="profile_picture" id="profilePicture" 
                                               class="form-control" accept="image/*">
                                        <small class="text-muted d-block mt-2">
                                            Format: JPG, PNG, GIF | Ukuran max: 2MB
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Nama Lengkap -->
                            <div class="form-group mb-3">
                                <label for="name" class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       placeholder="Masukkan email" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="form-group mb-3">
                                <label for="phone" class="font-weight-bold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="form-group mb-3">
                                <label for="address" class="font-weight-bold">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Masukkan alamat lengkap Anda">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="form-group mb-3">
                                <label for="birth_date" class="font-weight-bold">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" 
                                       value="{{ old('birth_date', $user->birth_date) }}">
                                @error('birth_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <hr>

                            <!-- Buttons -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('customer.profil.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Preview gambar sebelum upload
    document.getElementById('profilePicture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').innerHTML = 
                    '<img src="' + e.target.result + '" alt="Preview" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #667eea;">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-control {
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        padding: 10px 15px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>
@endisset
