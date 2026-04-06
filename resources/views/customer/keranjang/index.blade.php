@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-shopping-cart"></i> Keranjang Saya
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Keranjang</li>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($keranjangs->isEmpty())
            <!-- Keranjang Kosong -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card text-center shadow-sm" style="border-radius: 10px;">
                        <div class="card-body py-5">
                            <i class="fas fa-shopping-cart fa-5x text-muted mb-4" style="opacity: 0.5;"></i>
                            <h4 class="text-muted mb-3">Keranjang Anda Kosong</h4>
                            <p class="text-muted mb-4">Belum ada alat yang ditambahkan ke keranjang peminjaman</p>
                            <a href="{{ route('customer.belanja.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-bag"></i> Mulai Pinjam Alat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <!-- Daftar Alat di Keranjang -->
                <div class="col-lg-8">
                    <div class="card shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-light" style="border-radius: 10px 10px 0 0;">
                            <h5 class="card-title mb-0">
                                Alat ({{ $keranjangs->count() }} item)
                            </h5>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                            @foreach($keranjangs as $item)
                                <div class="row mb-4 pb-3 border-bottom">
                                    <!-- Checkbox -->
                                    <div class="col-1">
                                        <input type="checkbox" class="form-check-input" style="width: 20px; height: 20px;">
                                    </div>

                                    <!-- Gambar Alat -->
                                    <div class="col-md-2">
                                        @if($item->alat->gambar && file_exists(public_path($item->alat->gambar)))
                                            <img src="{{ asset($item->alat->gambar) }}" alt="{{ $item->alat->name }}" 
                                                 class="img-fluid" style="height: 100px; object-fit: cover; border-radius: 5px;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 100px; border-radius: 5px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info Alat -->
                                    <div class="col-md-4">
                                        <h6 class="font-weight-bold mb-1">
                                            {{ $item->alat->name }}
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            {{ $item->alat->kategori->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-hourglass-half"></i> 
                                            Durasi: {{ $item->durasi_jam }} jam
                                        </p>
                                        @if($item->diskon_persen > 0)
                                            <p class="text-warning small mb-1">
                                                <i class="fas fa-tag"></i> Diskon {{ $item->diskon_persen }}%
                                            </p>
                                            <h5 class="text-danger font-weight-bold">
                                                <del class="text-muted">Rp {{ number_format($item->harga_original, 0, ',', '.') }}</del>
                                                Rp {{ number_format($item->harga_setelah_diskon, 0, ',', '.') }}
                                            </h5>
                                        @else
                                            <h5 class="text-danger font-weight-bold">
                                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </h5>
                                        @endif
                                    </div>

                                    <!-- Qty & Harga -->
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <form action="{{ route('customer.keranjang.update', $item->id) }}" 
                                                  method="POST" class="d-flex" style="width: 100%;">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="updateQty(this, -1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="qty" class="form-control text-center mx-2" 
                                                       value="{{ $item->qty }}" min="1" max="999" 
                                                       style="width: 60px; font-weight: bold;">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="updateQty(this, 1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="submit" style="display: none;"></button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Total & Delete -->
                                    <div class="col-md-2 text-right">
                                        <h6 class="font-weight-bold text-danger mb-2">
                                            Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}
                                        </h6>
                                        <a href="{{ route('customer.keranjang.delete', $item->id) }}" 
                                           class="btn btn-sm btn-link text-danger" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Lanjut Belanja -->
                    <div class="mt-3">
                        <a href="{{ route('customer.belanja.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>

                <!-- Ringkasan Belanja -->
                <div class="col-lg-4">
                    <div class="card shadow-sm" style="border-radius: 10px; position: sticky; top: 20px;">
                        <div class="card-header bg-light" style="border-radius: 10px 10px 0 0;">
                            <h5 class="card-title mb-0">Ringkasan Belanja</h5>
                        </div>
                        <div class="card-body">
                            <!-- Subtotal -->
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                            </div>

                            <!-- Ongkir -->
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Ongkos Kirim</span>
                                <strong class="text-success">Gratis</strong>
                            </div>

                            <hr>

                            <!-- Total -->
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0">Total</h5>
                                <h5 class="mb-0 text-danger font-weight-bold">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </h5>
                            </div>

                            <!-- Checkout Button -->
                            <a href="{{ route('customer.keranjang.checkout') }}" 
                               class="btn btn-danger btn-lg btn-block" 
                               style="background: linear-gradient(135deg, #ee4d2d 0%, #c54c1d 100%); border: none;">
                                <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                            </a>

                            <!-- Info -->
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Dengan melanjutkan, Anda menyetujui syarat & ketentuan kami
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
    function updateQty(button, change) {
        const form = button.closest('form');
        const qtyInput = form.querySelector('input[name="qty"]');
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;

        if (newQty > 0 && newQty < 1000) {
            qtyInput.value = newQty;
            form.submit();
        }
    }
</script>

<style>
    .card {
        border: none;
    }

    .btn-danger {
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(238, 77, 45, 0.4);
    }

    .border-bottom {
        border-bottom: 1px solid #e3e6f0 !important;
    }
</style>
@endisset
