@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Alat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.belanja.index') }}">Pinjam Alat</a></li>
                    <li class="breadcrumb-item active">{{ $alat->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Gambar Alat -->
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body" style="height: 400px; display: flex; align-items: center; justify-content: center;">
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
            </div>

            <!-- Info Alat -->
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Nama Alat -->
                        <h2 class="mb-3">{{ $alat->name }}</h2>

                        <!-- Kategori -->
                        <p class="text-muted mb-3">
                            <i class="fas fa-tag"></i> 
                            <span class="badge badge-info">{{ $alat->kategori->name ?? 'Uncategorized' }}</span>
                        </p>

                        <!-- Tarif Sewa -->
                        <div class="mb-4">
                            <h4 class="text-muted">Tarif Sewa:</h4>
                            <div class="mb-2">
                                <h2 class="text-success font-weight-bold" id="hargaDisplay">
                                    Rp {{ number_format($alat->harga, 0, ',', '.') }}/jam
                                </h2>
                                @if($alat->diskon_persen > 0)
                                    <small class="badge badge-warning">
                                        <i class="fas fa-tag"></i> Diskon {{ $alat->diskon_persen }}%
                                        @if($alat->deskripsi_promo)
                                            - {{ $alat->deskripsi_promo }}
                                        @endif
                                    </small>
                                @endif
                            </div>
                            <small class="text-muted">
                                Setengah jam (0.5) = Rp {{ number_format($alat->harga * 0.5, 0, ',', '.') }}
                            </small>
                        </div>

                        <!-- Stok -->
                        <div class="mb-4">
                            <h5 class="text-muted">Ketersediaan Unit:</h5>
                            @if($alat->stok > 0)
                                <span class="badge badge-success" style="font-size: 16px; padding: 8px 12px;">
                                    <i class="fas fa-check"></i> Tersedia ({{ $alat->stok }} unit)
                                </span>
                            @else
                                <span class="badge badge-danger" style="font-size: 16px; padding: 8px 12px;">
                                    <i class="fas fa-times"></i> Stok Habis
                                </span>
                            @endif
                        </div>

                        <hr>

                        <!-- Form Peminjaman -->
                        @if($alat->stok > 0)
                        <form id="orderForm">
                            @csrf
                            <div class="form-group">
                                <label for="qty" class="font-weight-bold">Jumlah Alat:</label>
                                <div class="input-group" style="max-width: 150px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseBtn">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" id="qty" name="qty" class="form-control text-center" 
                                           min="1" max="{{ $alat->stok }}" value="1" required 
                                           style="font-size: 18px; font-weight: bold;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseBtn">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Maksimal pinjam: {{ $alat->stok }} unit</small>
                            </div>

                            <!-- Durasi Sewa -->
                            <div class="form-group mt-4">
                                <label for="durasi_jam" class="font-weight-bold">Durasi Sewa:</label>
                                
                                <!-- Quick Select Buttons -->
                                <div class="btn-group btn-group-toggle mb-3 d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline-primary flex-fill">
                                        <input type="radio" name="durasi_quick" value="1"> 1 Jam
                                    </label>
                                    <label class="btn btn-outline-primary flex-fill">
                                        <input type="radio" name="durasi_quick" value="2"> 2 Jam
                                    </label>
                                    <label class="btn btn-outline-primary flex-fill">
                                        <input type="radio" name="durasi_quick" value="3"> 3 Jam
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <select id="durasi_jam" name="durasi_jam" class="form-control">
                                            <option value="0.5">30 Menit (0.5 jam)</option>
                                            <option value="1" selected>1 Jam</option>
                                            <option value="1.5">1.5 Jam</option>
                                            <option value="2">2 Jam</option>
                                            <option value="2.5">2.5 Jam</option>
                                            <option value="3">3 Jam</option>
                                            <option value="4">4 Jam</option>
                                            <option value="5">5 Jam</option>
                                            <option value="6">6 Jam</option>
                                            <option value="8">8 Jam (1 hari)</option>
                                            <option value="12">12 Jam</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" id="durasi_custom" name="durasi_custom" 
                                               class="form-control" placeholder="atau input custom (jam)"
                                               step="0.5" min="0.5">
                                        <small class="text-muted d-block mt-1">Contoh: 1.5, 2.5, dll</small>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <strong>📊 Perhitungan Tarif Progresif:</strong><br>
                                • <strong>1 jam</strong> = Rp <span id="harga1jam">{{ number_format($alat->harga, 0, ',', '.') }}</span><br>
                                • <strong>2 jam</strong> = Rp <span id="harga2jam">{{ number_format($alat->harga * 2 * 1.21, 0, ',', '.') }}</span> (naik 21%)<br>
                                • <strong>3+ jam</strong> = Rp <span id="harga3jam">{{ number_format($alat->harga * 3 * 0.78, 0, ',', '.') }}</span>/3jam (diskon 22%)<br>
                                <small class="text-muted d-block mt-2">Semakin lama sewa semakin hemat! 💰</small>
                            </div>

                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Total Biaya (Qty × Durasi × Harga/Jam):</label>
                                <h3 id="totalPrice" class="text-success font-weight-bold mb-0">
                                    Rp {{ number_format($alat->harga, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="mt-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success btn-lg btn-block" id="sewaLangsungBtn">
                                            <i class="fas fa-zap"></i> Sewa Langsung
                                        </button>
                                    </div>
                                </div>
                                <a href="{{ route('customer.belanja.index') }}" class="btn btn-secondary btn-lg btn-block mt-2">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Pinjam Alat
                                </a>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Alat ini sedang tidak tersedia
                        </div>
                        <a href="{{ route('customer.belanja.index') }}" class="btn btn-secondary btn-lg btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Pinjam Alat
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const harga = {{ $alat->harga }};
    const diskon_promo = {{ $alat->diskon_persen }};
    const maxStok = {{ $alat->stok }};
    const qtyInput = document.getElementById('qty');
    const durasiSelect = document.getElementById('durasi_jam');
    const durasiCustom = document.getElementById('durasi_custom');
    const totalPriceEl = document.getElementById('totalPrice');
    const decreaseBtn = document.getElementById('decreaseBtn');
    const increaseBtn = document.getElementById('increaseBtn');

    // Fungsi untuk menghitung harga berdasarkan durasi (progressive pricing)
    function getProgressivePrice(durationInHours) {
        let pricePerUnit;

        if (durationInHours <= 1) {
            // 1 jam atau kurang = harga dasar
            pricePerUnit = harga * durationInHours;
        } else if (durationInHours < 3) {
            // 2 jam = naik 21% per jam
            pricePerUnit = harga * durationInHours * 1.21;
        } else {
            // 3+ jam = diskon 22%
            pricePerUnit = harga * durationInHours * 0.78;
        }

        // Tambah promo diskon dari alat (Sky/Drone)
        pricePerUnit = pricePerUnit * (100 - diskon_promo) / 100;

        return pricePerUnit;
    }

    function calculatePrice() {
        const qty = parseInt(qtyInput.value) || 1;
        
        // Ambil durasi dari select atau custom input
        let durasi = parseFloat(durasiSelect.value) || 1;
        if (durasiCustom.value) {
            durasi = parseFloat(durasiCustom.value) || 1;
        }

        // Kalkulasi harga dengan progressive pricing
        const totalHarga = getProgressivePrice(durasi) * qty;

        totalPriceEl.textContent = 'Rp ' + Math.round(totalHarga).toLocaleString('id-ID');
    }

    decreaseBtn.addEventListener('click', function() {
        let current = parseInt(qtyInput.value) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
            calculatePrice();
        }
    });

    increaseBtn.addEventListener('click', function() {
        let current = parseInt(qtyInput.value) || 1;
        if (current < maxStok) {
            qtyInput.value = current + 1;
            calculatePrice();
        }
    });

    qtyInput.addEventListener('change', calculatePrice);
    qtyInput.addEventListener('input', calculatePrice);
    durasiSelect.addEventListener('change', function() {
        // Hapus custom input jika select dipilih
        durasiCustom.value = '';
        calculatePrice();
    });
    durasiCustom.addEventListener('input', function() {
        // Hapus select jika custom input diisi
        if (this.value) {
            durasiSelect.value = '';
        }
        calculatePrice();
    });

    // Quick select buttons untuk 1, 2, 3 jam
    document.querySelectorAll('input[name="durasi_quick"]').forEach(radio => {
        radio.addEventListener('change', function() {
            durasiSelect.value = this.value;
            durasiCustom.value = '';
            calculatePrice();
        });
    });

    // Inisialisasi harga saat halaman pertama kali load
    calculatePrice();

    // Validasi saat submit form
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const durasi = parseFloat(durasiSelect.value) || parseFloat(durasiCustom.value) || 1;
        if (durasi < 0.5) {
            e.preventDefault();
            alert('Durasi sewa minimal 0.5 jam (30 menit)!');
            return false;
        }
    });

    // Tombol Sewa Langsung
    const sewaLangsungBtn = document.getElementById('sewaLangsungBtn');
    if (sewaLangsungBtn) {
        sewaLangsungBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Sewa Langsung button clicked!');
            
            const durasi = parseFloat(durasiSelect.value) || parseFloat(durasiCustom.value) || 1;
            console.log('Durasi:', durasi);
            
            if (durasi < 0.5) {
                alert('Durasi sewa minimal 0.5 jam (30 menit)!');
                return false;
            }

            // Redirect ke halaman konfirmasi
            const qty = qtyInput.value;
            const url = `{{ route("customer.belanja.tampil-konfirmasi", $alat->id) }}?qty=${qty}&durasi_jam=${durasi}`;
            console.log('Redirecting to:', url);
            window.location.href = url;
        });
    } else {
        console.error('sewaLangsungBtn not found!');
    }
});
</script>

<!-- RATINGS SECTION -->
<div class="content-header mt-5">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h3 class="m-0">
                    <i class="fas fa-star"></i> Rating & Ulasan
                </h3>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Rating Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="text-warning font-weight-bold">
                            {{ number_format($alat->getRatingAverage(), 1) }}
                        </h2>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($alat->getRatingAverage()))
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - $alat->getRatingAverage() < 1)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted small">
                            {{ $alat->getRatingCount() }} Ulasan
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @for($stars = 5; $stars >= 1; $stars--)
                            @php
                                $countStars = $alat->ratings()->where('bintang', $stars)->count();
                                $percentage = $alat->getRatingCount() > 0 ? ($countStars / $alat->getRatingCount()) * 100 : 0;
                            @endphp
                            <div class="row align-items-center mb-2">
                                <div class="col-md-2">
                                    <span class="small">{{ $stars }} <i class="fas fa-star text-warning"></i></span>
                                </div>
                                <div class="col-md-8">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-right">
                                    <small class="text-muted">{{ $countStars }}</small>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Ulasan Pembeli</h5>
                    </div>
                    <div class="card-body">
                        @forelse($alat->ratings()->latest()->get() as $rating)
                            <div class="media mb-4 pb-3 border-bottom">
                                <div class="media-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ $rating->user->name }}</h6>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->bintang)
                                                        <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                                    @else
                                                        <i class="far fa-star text-warning" style="font-size: 12px;"></i>
                                                    @endif
                                                @endfor
                                                <span class="small text-muted ml-2">{{ $rating->bintang }} Bintang</span>
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($rating->komentar)
                                        <p class="mb-0">{{ $rating->komentar }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada ulasan untuk alat ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endisset
