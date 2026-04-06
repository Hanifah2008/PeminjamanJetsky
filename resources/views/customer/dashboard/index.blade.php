<!-- Dashboard Header with Statistics -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $jumlahPembelian }}</h3>
                    <p>Total Pembelian</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <a href="{{ route('customer.riwayat.index') }}" class="small-box-footer">Lihat Riwayat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h3>
                    <p>Total Belanja</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('customer.riwayat.index') }}" class="small-box-footer">Lihat Riwayat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\Keranjang::where('user_id', auth()->id())->count() }}</h3>
                    <p>Item Keranjang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cart-plus"></i>
                </div>
                <a href="{{ route('customer.keranjang.index') }}" class="small-box-footer">Lihat Keranjang <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\Transaksi::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h3>
                    <p>Pesanan Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <a href="{{ route('customer.riwayat.index') }}" class="small-box-footer">Lihat Pesanan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Alat Rekomendasi Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary">
                    <h5 class="card-title m-0">
                        <i class="fas fa-fire"></i> Alat Rekomendasi
                    </h5>
                </div>
                <div class="card-body">
                    @if($alats->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada alat tersedia</p>
                            <a href="{{ route('customer.belanja.index') }}" class="btn btn-primary btn-sm">Lihat Semua Alat</a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($alats as $alat)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 shadow-sm hover-shadow transition">
                                        <!-- Equipment Image -->
                                        <div class="position-relative overflow-hidden" style="height: 200px;">
                                            @if($alat->gambar)
                                                <img src="{{ asset('uploads/images/' . $alat->gambar) }}" 
                                                     class="card-img-top w-100 h-100 object-cover" 
                                                     style="object-fit: cover;"
                                                     alt="{{ $alat->name }}">
                                            @else
                                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Availability Badge -->
                                            @if($alat->stok <= 0)
                                                <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px;">Tidak Tersedia</span>
                                            @elseif($alat->stok < 5)
                                                <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;">Terbatas</span>
                                            @endif
                                        </div>

                                        <!-- Equipment Info -->
                                        <div class="card-body pb-2">
                                            <p class="text-muted small mb-1">{{ $alat->kategori->name ?? 'Uncategorized' }}</p>
                                            <h6 class="card-title" style="min-height: 40px;">
                                                <a href="{{ route('customer.belanja.show', $alat->id) }}" class="text-dark text-decoration-none">
                                                    {{ Str::limit($alat->name, 40) }}
                                                </a>
                                            </h6>
                                            
                                            <!-- Rating Bintang -->
                                            <div class="mb-2">
                                                @php
                                                    $avgRating = $alat->getRatingAverage();
                                                    $ratingCount = $alat->getRatingCount();
                                                @endphp
                                                <div style="display: flex; gap: 1px; margin-bottom: 3px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($avgRating))
                                                            <i class="fas fa-star" style="color: #ffc107; font-size: 13px; line-height: 1;"></i>
                                                        @elseif($i - $avgRating < 1)
                                                            <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 13px; line-height: 1;"></i>
                                                        @else
                                                            <i class="far fa-star" style="color: #ddd; font-size: 13px; line-height: 1;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <small class="text-muted">
                                                    <strong style="color: #333;">{{ number_format($avgRating, 1) }}</strong>
                                                    <span style="color: #999;">({{ $ratingCount }})</span>
                                                </small>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="text-danger m-0">Rp {{ number_format($alat->harga, 0, ',', '.') }}/jam</h5>
                                                <small class="text-muted">Tersedia: {{ $alat->stok }}</small>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="card-footer bg-white border-top pt-2">
                                            <form class="form-add-cart" method="POST" action="{{ route('customer.keranjang.add', $alat->id) }}">
                                                @csrf
                                                <input type="hidden" name="qty" class="qty-value" value="1">
                                                <div class="input-group input-group-sm mb-2">
                                                    <input type="number" class="form-control qty-input" value="1" min="1" max="{{ $alat->stok }}" data-alat-id="{{ $alat->id }}" {{ $alat->stok <= 0 ? 'disabled' : '' }}>
                                                <div class="input-group-append">
                                                        <button class="btn btn-primary btn-add-cart" type="submit" data-alat-id="{{ $alat->id }}" {{ $alat->stok <= 0 ? 'disabled' : '' }}>
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <a href="{{ route('customer.belanja.show', $alat->id) }}" class="btn btn-outline-primary btn-sm btn-block">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('customer.belanja.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-bag"></i> Lihat Semua Alat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<div class="modal fade" id="addCartModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah ke Keranjang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Produk berhasil ditambahkan ke keranjang!</p>
                <div id="modalMessage"></div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('customer.keranjang.index') }}" class="btn btn-primary">Lihat Keranjang</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Lanjut Belanja</button>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }

    .transition {
        transition: all 0.3s ease;
    }

    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 0 1px rgba(0, 0, 0, 0.125);
    }

    .small-box:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>

<script>
    $(document).ready(function() {
        // Add to Cart Form Submit
        $('.form-add-cart').on('submit', function(e) {
            e.preventDefault();
            
            let qty = $(this).find('.qty-input').val();

            if (!qty || qty <= 0) {
                alert('Masukkan jumlah yang valid');
                return false;
            }

            // Update hidden qty value
            $(this).find('.qty-value').val(qty);

            // Natural form submit
            this.submit();
        });

        // Quantity Input Validation
        $(document).on('change', '.qty-input', function() {
            let maxStock = $(this).attr('max');
            let value = $(this).val();

            if (value > maxStock) {
                $(this).val(maxStock);
            } else if (value < 1) {
                $(this).val(1);
            }
        });
    });
</script>
