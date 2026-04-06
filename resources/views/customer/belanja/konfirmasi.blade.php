@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-clipboard-check"></i> Konfirmasi Peminjaman
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.belanja.index') }}">Belanja</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.belanja.show', $alat->id) }}">{{ $alat->name }}</a></li>
                    <li class="breadcrumb-item active">Konfirmasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Gambar Alat -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                        @if($alat->gambar && file_exists(public_path($alat->gambar)))
                            <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->name }}" 
                                 class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="fas fa-image fa-5x text-muted"></i>
                                <p class="text-muted mt-3">Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Info Alat</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Nama:</strong><br>
                            {{ $alat->name }}
                        </p>
                        <p class="mb-2">
                            <strong>Kategori:</strong><br>
                            <span class="badge badge-info">{{ $alat->kategori->name ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Stok Tersedia:</strong><br>
                            <span class="badge badge-success">{{ $alat->stok }} unit</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Peminjaman -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> Detail Peminjaman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Jumlah Unit</label>
                                    <h4 class="font-weight-bold text-primary">{{ $qty }} Unit</h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Durasi Sewa</label>
                                    <h4 class="font-weight-bold text-success">
                                        ⏱️ {{ $durasi_jam }} Jam
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Perhitungan Harga -->
                        <div class="mb-4">
                            <h5 class="font-weight-bold mb-3">Perhitungan Harga</h5>
                            
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span class="text-muted">Harga per jam:</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>Rp {{ number_format($alat->harga, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span class="text-muted">Durasi:</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>{{ $durasi_jam }} jam</strong>
                                </div>
                            </div>

                            @if($durasi_jam >= 3)
                                <div class="row mb-2 text-success">
                                    <div class="col-md-6">
                                        <span class="text-muted">Diskon 3+ jam:</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong>× 0.78 (Diskon 22%)</strong>
                                    </div>
                                </div>
                            @elseif($durasi_jam >= 2)
                                <div class="row mb-2 text-warning">
                                    <div class="col-md-6">
                                        <span class="text-muted">Harga 2 jam:</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong>× 1.21 (Naik 21%)</strong>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3 border-top pt-3">
                                <div class="col-md-6">
                                    <span class="text-muted">Subtotal ({{ $qty }} × durasi × harga):</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>Rp {{ number_format($harga_original * $qty, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            @if($diskon_persen > 0)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <span class="badge badge-warning">Promo Alat</span>
                                        <span class="text-muted">{{ $diskon_persen }}% Diskon:</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-danger">
                                            -Rp {{ number_format($harga_original * $qty - $harga_setelah_diskon * $qty, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <!-- Total Harga -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="font-weight-bold">Total Pembayaran</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <h3 class="font-weight-bold text-success">
                                    Rp {{ number_format($total_harga, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penyewa -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user"></i> Informasi Penyewa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Nama</strong><br>
                                    {{ auth()->user()->name }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Email</strong><br>
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>No. Telepon</strong><br>
                                    {{ auth()->user()->phone ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0">
                                    <strong>Alamat</strong><br>
                                    {{ auth()->user()->address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('customer.belanja.proses-pinjaman', $alat->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="qty" value="{{ $qty }}">
                            <input type="hidden" name="durasi_jam" value="{{ $durasi_jam }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-check-circle"></i> Konfirmasi Peminjaman
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('customer.belanja.show', $alat->id) }}" class="btn btn-secondary btn-lg btn-block">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endisset
