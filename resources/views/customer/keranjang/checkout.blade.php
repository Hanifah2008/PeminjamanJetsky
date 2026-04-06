@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-clipboard-check"></i> Konfirmasi Pesanan
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.keranjang.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active">Konfirmasi Pesanan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- INFO PEMBELI -->
                <div class="card shadow-sm mb-4" style="border-radius: 10px; border: none;">
                    <div class="card-header bg-white border-bottom" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-circle text-primary"></i> Informasi Pembeli
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Nama Lengkap</label>
                                    <h6 class="font-weight-bold">{{ $user->name }}</h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Email</label>
                                    <h6 class="font-weight-bold">{{ $user->email }}</h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Nomor Telepon</label>
                                    <h6 class="font-weight-bold">{{ $user->phone ?? '-' }}</h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Tanggal Bergabung</label>
                                    <h6 class="font-weight-bold">{{ $user->created_at->format('d M Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALAMAT PENGIRIMAN -->
                <div class="card shadow-sm mb-4" style="border-radius: 10px; border: none;">
                    <div class="card-header bg-white border-bottom" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt text-danger"></i> Alamat Pengiriman
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->address)
                            <div class="address-box p-3 bg-light rounded mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="font-weight-bold mb-2">{{ $user->name }}</h6>
                                        <p class="mb-2">
                                            <i class="fas fa-phone text-muted"></i> 
                                            <strong>{{ $user->phone ?? 'Nomor belum diisi' }}</strong>
                                        </p>
                                        <p class="mb-0" style="color: #333; line-height: 1.6;">
                                            <i class="fas fa-map-pin text-muted"></i>
                                            <strong>{{ $user->address }}</strong>
                                        </p>
                                    </div>
                                    <span class="badge badge-primary">Alamat Utama</span>
                                </div>
                            </div>
                            <a href="{{ route('customer.profil.edit') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Ubah Alamat
                            </a>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Alamat belum diisi!</strong> 
                                Silakan <a href="{{ route('customer.profil.edit') }}">lengkapi alamat pengiriman Anda</a> terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- METODE PENGIRIMAN -->
                <div class="card shadow-sm mb-4" style="border-radius: 10px; border: none;">
                    <div class="card-header bg-white border-bottom" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-truck text-info"></i> Metode Pengiriman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="shipping-option p-3 border rounded mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success" style="font-size: 1.5rem; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Gratis Ongkir</h6>
                                    <p class="text-muted small mb-0">Pengiriman standar 3-5 hari kerja</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">
                            <i class="fas fa-info-circle"></i> Ongkos kirim: <strong class="text-success">GRATIS</strong>
                        </p>
                    </div>
                </div>

                <!-- METODE PEMBAYARAN -->
                <div class="card shadow-sm mb-4" style="border-radius: 10px; border: none;">
                    <div class="card-header bg-white border-bottom" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-credit-card text-warning"></i> Metode Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-option p-3 border rounded mb-3 bg-light" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-university text-primary" style="font-size: 1.5rem; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Transfer Bank</h6>
                                    <p class="text-muted small mb-0">BNI, BCA, Mandiri, OVO, DANA</p>
                                </div>
                                <span class="badge badge-primary ml-auto">Direkomendasikan</span>
                            </div>
                        </div>
                        <div class="payment-option p-3 border rounded" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-success" style="font-size: 1.5rem; margin-right: 15px;"></i>
                                <div>
                                    <h6 class="font-weight-bold mb-1">Bayar Saat Tiba (COD)</h6>
                                    <p class="text-muted small mb-0">Pembayaran langsung saat barang sampai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DAFTAR ALAT YANG DISEWA -->
                <div class="card shadow-sm" style="border-radius: 10px; border: none;">
                    <div class="card-header bg-white border-bottom" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-boxes text-success"></i> Detail Alat ({{ $totalItem }} item)
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($keranjangs as $item)
                            <div class="item-box p-3 mb-3 border-bottom" style="display: flex; gap: 15px; align-items: flex-start;">
                                <!-- Equipment Image -->
                                <div style="flex-shrink: 0;">
                                    @if($item->alat->gambar)
                                        <img src="{{ asset('uploads/images/' . $item->alat->gambar) }}" 
                                             alt="{{ $item->alat->name }}"
                                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #e0e0e0;">
                                    @else
                                        <div style="width: 100px; height: 100px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Equipment Info -->
                                <div style="flex-grow: 1;">
                                    <h6 class="font-weight-bold mb-1">{{ $item->alat->name }}</h6>
                                    <p class="text-muted small mb-2">Kategori: {{ $item->alat->kategori->name ?? 'N/A' }}</p>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-hourglass-half"></i> 
                                        Durasi: {{ $item->durasi_jam }} jam
                                    </p>
                                    
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <p class="small mb-0">
                                                <span class="text-muted">Tarif/Jam:</span>
                                                <strong>Rp {{ number_format($item->alat->harga, 0, ',', '.') }}</strong>
                                            </p>
                                        </div>
                                        <div class="col-6 text-right">
                                            <p class="small mb-0">
                                                <span class="text-muted">Jumlah:</span>
                                                <strong>{{ $item->qty }}</strong>
                                            </p>
                                        </div>
                                    </div>

                                    @if($item->diskon_persen > 0)
                                        <div class="p-2 bg-warning rounded mb-2" style="opacity: 0.9;">
                                            <small class="font-weight-bold">
                                                <i class="fas fa-tag"></i> Diskon {{ $item->diskon_persen }}%
                                            </small><br>
                                            <small>
                                                Harga Original: Rp {{ number_format($item->harga_original * $item->qty, 0, ',', '.') }}
                                            </small>
                                        </div>
                                    @endif

                                    <div class="p-2 bg-light rounded">
                                        <h6 class="mb-0 text-danger font-weight-bold">
                                            Subtotal: Rp {{ number_format($item->harga_setelah_diskon * $item->qty, 0, ',', '.') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RINGKASAN PEMBAYARAN -->
            <div class="col-lg-4">
                <div class="card shadow-sm" style="border-radius: 10px; border: none; position: sticky; top: 20px;">
                    <div class="card-header bg-gradient-danger text-white" style="border-radius: 10px 10px 0 0;">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-receipt"></i> Ringkasan Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Detail Harga -->
                        <div class="price-breakdown mb-3">
                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <span class="text-muted">Subtotal Barang ({{ $totalItem }} item)</span>
                                <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <span class="text-muted">Ongkos Kirim</span>
                                <strong class="text-success">Gratis</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <span class="text-muted">Biaya Layanan</span>
                                <strong>Rp 0</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <span class="text-muted">Diskon</span>
                                <strong class="text-success">Rp 0</strong>
                            </div>
                        </div>

                        <!-- TOTAL PAYMENT -->
                        <div class="bg-light p-3 rounded mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">TOTAL BAYAR</span>
                                <h4 class="mb-0 text-danger font-weight-bold">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <form action="{{ route('customer.keranjang.proses') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg btn-block font-weight-bold mb-2" 
                                    style="background: linear-gradient(135deg, #ee4d2d 0%, #c54c1d 100%); border: none; padding: 12px; font-size: 16px;">
                                <i class="fas fa-check-circle"></i> BUAT PESANAN
                            </button>
                        </form>

                        <!-- Info Box -->
                        <div class="info-box p-3 bg-primary bg-opacity-10 rounded mb-3" style="border-left: 4px solid #007bff;">
                            <p class="small mb-0">
                                <i class="fas fa-clock text-info"></i>
                                <strong>Waktu Proses:</strong> Pesanan Anda akan dikonfirmasi admin dalam waktu <strong>1x24 jam</strong>
                            </p>
                        </div>

                        <div class="info-box p-3 bg-success bg-opacity-10 rounded" style="border-left: 4px solid #28a745;">
                            <p class="small mb-0">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>Garansi:</strong> Pesanan aman & terjamin. Hubungi CS jika ada kendala.
                            </p>
                        </div>

                        <!-- Back Button -->
                        <a href="{{ route('customer.keranjang.index') }}" class="btn btn-outline-secondary btn-block mt-3">
                            <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    .card-header {
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0 !important;
    }

    .btn-danger {
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(238, 77, 45, 0.4);
    }

    .address-box {
        border-left: 4px solid #dc3545;
    }

    .shipping-option {
        border-left: 4px solid #17a2b8;
        transition: all 0.3s ease;
    }

    .shipping-option:hover {
        background-color: #f8f9fa;
    }

    .payment-option {
        border-left: 4px solid #ffc107;
        transition: all 0.3s ease;
    }

    .payment-option:hover {
        background-color: #f8f9fa;
        border-left-color: #007bff;
    }

    .item-box {
        background-color: #fafafa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .item-box:hover {
        background-color: #f0f0f0;
    }

    .price-breakdown strong {
        font-size: 15px;
    }

    .bg-opacity-10 {
        opacity: 0.1;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #ee4d2d 0%, #c54c1d 100%) !important;
    }

    @media (max-width: 768px) {
        .item-box {
            flex-direction: column;
        }

        .sticky {
            position: relative !important;
            top: 0 !important;
        }
    }
</style>
@endisset
