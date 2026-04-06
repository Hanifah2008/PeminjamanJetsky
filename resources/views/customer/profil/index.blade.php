@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profil Saya</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Card Profil Utama -->
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <!-- Header Background -->
                    <div style="height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>

                    <!-- Profile Content -->
                    <div class="card-body text-center" style="margin-top: -50px; position: relative; z-index: 1;">
                        <!-- Avatar -->
                        <div class="mb-3">
                            @if($user->profile_picture && file_exists(public_path($user->profile_picture)))
                                <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" 
                                     class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px; background: #667eea; color: white; font-size: 40px; border: 4px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Nama -->
                        <h4 class="font-weight-bold mb-1">{{ $user->name }}</h4>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-calendar"></i> 
                            Bergabung {{ $user->created_at->format('M Y') }}
                        </p>

                        <!-- Edit Button -->
                        <a href="{{ route('customer.profil.edit') }}" class="btn btn-primary btn-sm w-100 mb-3">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>

                        <hr>

                        <!-- Statistik -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h5 class="font-weight-bold text-primary mb-1">{{ $totalBelanja }}</h5>
                                <small class="text-muted">Total Pembelian</small>
                            </div>
                            <div class="col-6">
                                <h5 class="font-weight-bold text-success mb-1">{{ $transaksiSelesai }}</h5>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card shadow-sm mt-3" style="border-radius: 10px;">
                    <div class="card-header bg-light" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle"></i> Status Member
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-star text-warning fa-2x mr-3"></i>
                            <div>
                                <h6 class="mb-0">Member Regular</h6>
                                <small class="text-muted">Level Member Anda</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-medal text-info fa-2x mr-3"></i>
                            <div>
                                <h6 class="mb-0">Terpercaya</h6>
                                <small class="text-muted">Kepercayaan pembeli</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Detail Profil -->
            <div class="col-md-8">
                <div class="card shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-light" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-edit"></i> Informasi Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Email -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p class="text-muted small mb-1">Email</p>
                            </div>
                            <div class="col-md-9">
                                <h6 class="mb-0">{{ $user->email }}</h6>
                                <small class="text-muted">Email akun Anda</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Nomor Telepon -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p class="text-muted small mb-1">Nomor Telepon</p>
                            </div>
                            <div class="col-md-9">
                                @if($user->phone)
                                    <h6 class="mb-0">{{ $user->phone }}</h6>
                                @else
                                    <p class="text-muted mb-0">
                                        <em>Belum diisi - <a href="{{ route('customer.profil.edit') }}">Tambahkan</a></em>
                                    </p>
                                @endif
                                <small class="text-muted">Nomor telepon untuk kontak</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Alamat -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p class="text-muted small mb-1">Alamat</p>
                            </div>
                            <div class="col-md-9">
                                @if($user->address)
                                    <h6 class="mb-0">{{ $user->address }}</h6>
                                @else
                                    <p class="text-muted mb-0">
                                        <em>Belum diisi - <a href="{{ route('customer.profil.edit') }}">Tambahkan</a></em>
                                    </p>
                                @endif
                                <small class="text-muted">Alamat tempat tinggal</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tanggal Lahir -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <p class="text-muted small mb-1">Tanggal Lahir</p>
                            </div>
                            <div class="col-md-9">
                                @if($user->birth_date)
                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($user->birth_date)->format('d M Y') }}</h6>
                                @else
                                    <p class="text-muted mb-0">
                                        <em>Belum diisi - <a href="{{ route('customer.profil.edit') }}">Tambahkan</a></em>
                                    </p>
                                @endif
                                <small class="text-muted">Tanggal kelahiran Anda</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Tanggal Bergabung -->
                        <div class="row mb-0">
                            <div class="col-md-3">
                                <p class="text-muted small mb-1">Bergabung</p>
                            </div>
                            <div class="col-md-9">
                                <h6 class="mb-0">{{ $user->created_at->format('d M Y H:i') }}</h6>
                                <small class="text-muted">Tanggal Anda bergabung</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik Pembelian -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm text-center" style="border-radius: 10px; border-left: 4px solid #667eea;">
                            <div class="card-body">
                                <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                                <h3 class="font-weight-bold text-primary">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</h3>
                                <p class="text-muted small mb-0">Total Belanja</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm text-center" style="border-radius: 10px; border-left: 4px solid #28a745;">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h3 class="font-weight-bold text-success">{{ $transaksiSelesai }}</h3>
                                <p class="text-muted small mb-0">Transaksi Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        border: none;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
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

    hr {
        border-color: #e3e6f0;
    }
</style>
@endisset
