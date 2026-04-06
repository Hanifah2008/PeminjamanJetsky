<div class="container-fluid py-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Profil Petugas</h5>
                </div>
                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div style="width: 150px; height: 150px; margin: 0 auto; border-radius: 10px; overflow: hidden; border: 3px solid #007bff;">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                         alt="Profile Picture" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="/vendor/admin/dist/img/user2-160x160.jpg" 
                                         alt="Default Profile" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <p class="mt-3 text-muted"><small>Role: <strong class="badge badge-success">Petugas</strong></small></p>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>Nama Lengkap:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">{{ auth()->user()->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>Email:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-envelope text-primary"></i> 
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>No. Telepon:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-phone text-success"></i> 
                                        {{ auth()->user()->phone ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>Tanggal Lahir:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-calendar text-info"></i> 
                                        {{ auth()->user()->birth_date ? \Carbon\Carbon::parse(auth()->user()->birth_date)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>Alamat:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        {{ auth()->user()->address ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>ID Petugas:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <code>#{{ str_pad(auth()->user()->id, 5, '0', STR_PAD_LEFT) }}</code>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"><strong>Bergabung:</strong></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-calendar-check text-warning"></i> 
                                        {{ auth()->user()->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end">
                        <a href="/petugas/profil/edit" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                        <a href="/petugas/dashboard" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-lg mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Role Akses</small>
                        <p><span class="badge badge-success bg-success">Petugas Kasir</span></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Status Akun</small>
                        <p><span class="badge badge-success bg-success">Aktif</span></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Terakhir Login</small>
                        <p class="small">Hari ini</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-lock"></i> Keamanan</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('petugas.profil.edit') }}" class="btn btn-outline-primary btn-sm btn-block mb-2">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                    <a href="/petugas/logout" class="btn btn-outline-danger btn-sm btn-block">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }

    .form-control-plaintext {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 0;
    }

    .card {
        border-radius: 0.5rem;
    }

    .btn-block {
        width: 100%;
    }
</style>
