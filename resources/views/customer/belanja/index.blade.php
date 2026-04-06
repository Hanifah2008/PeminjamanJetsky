@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pinjam Alat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Pinjam Alat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter & Search -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('customer.belanja.index') }}" method="GET" class="form-inline">
                            <!-- Search -->
                            <div class="form-group mr-2 flex-grow-1">
                                <input type="text" name="search" class="form-control w-100" 
                                       placeholder="Cari alat..." value="{{ $search ?? '' }}">
                            </div>

                            <!-- Filter Kategori -->
                            <div class="form-group mr-2">
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" 
                                                {{ ($selectedKategori == $kategori->id) ? 'selected' : '' }}>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Button -->
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('customer.belanja.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alat Grid -->
        <div class="row">
            @forelse($alats as $alat)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm hover-shadow" style="transition: all 0.3s;">
                    <!-- Gambar Alat -->
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        @if($alat->gambar && file_exists(public_path($alat->gambar)))
                            <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->name }}" 
                                 class="card-img-top" style="height: 100%; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 100%;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Stok -->
                        @if($alat->stok > 0)
                            <span class="badge badge-success position-absolute" style="top: 10px; right: 10px;">
                                Stok: {{ $alat->stok }}
                            </span>
                        @else
                            <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px;">
                                Habis
                            </span>
                        @endif
                    </div>

                    <div class="card-body">
                        <!-- Nama Alat -->
                        <h5 class="card-title" style="min-height: 50px;">
                            <a href="{{ route('customer.belanja.show', $alat->id) }}" 
                               class="text-dark text-decoration-none">
                                {{ $alat->name }}
                            </a>
                        </h5>

                        <!-- Kategori -->
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag"></i> {{ $alat->kategori->name ?? 'Uncategorized' }}
                        </p>

                        <!-- Rating Bintang -->
                        <div class="mb-3">
                            @php
                                $avgRating = $alat->getRatingAverage();
                                $ratingCount = $alat->getRatingCount();
                            @endphp
                            <div class="d-flex align-items-center">
                                <div style="display: flex; gap: 1px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRating))
                                            <i class="fas fa-star" style="color: #ffc107; font-size: 14px; line-height: 1;"></i>
                                        @elseif($i - $avgRating < 1)
                                            <i class="fas fa-star-half-alt" style="color: #ffc107; font-size: 14px; line-height: 1;"></i>
                                        @else
                                            <i class="far fa-star" style="color: #ddd; font-size: 14px; line-height: 1;"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="small text-muted ml-2">
                                    <strong style="color: #333;">{{ number_format($avgRating, 1) }}</strong> 
                                    <span style="color: #999;">({{ $ratingCount }})</span>
                                </span>
                            </div>
                        </div>

                        <!-- Tarif Sewa -->
                        <div class="mb-3">
                            <h4 class="text-primary font-weight-bold mb-1">
                                Rp {{ number_format($alat->harga, 0, ',', '.') }}/jam
                            </h4>
                            @if($alat->diskon_persen > 0)
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 12px;">
                                    <i class="fas fa-tag"></i> 
                                    <strong>Promo!</strong> Diskon {{ $alat->diskon_persen }}%
                                    @if($alat->deskripsi_promo)
                                        <br>{{ $alat->deskripsi_promo }}
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Buttons -->
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('customer.belanja.show', $alat->id) }}" 
                               class="btn btn-sm btn-info flex-grow-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            @if($alat->stok > 0)
                            <button type="button" class="btn btn-sm btn-success flex-grow-1"
                                    data-toggle="modal" data-target="#addToCartModal{{ $alat->id }}">
                                <i class="fas fa-calendar-check"></i> Pinjam
                            </button>
                            @else
                            <button type="button" class="btn btn-sm btn-secondary flex-grow-1" disabled>
                                <i class="fas fa-ban"></i> Habis
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Pinjam -->
                @if($alat->stok > 0)
                <div class="modal fade" id="addToCartModal{{ $alat->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pinjam Alat</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Alat:</strong> {{ $alat->name }}</p>
                                <p><strong>Tarif Sewa:</strong> Rp {{ number_format($alat->harga, 0, ',', '.') }}/jam</p>
                                <p><strong>Stok Tersedia:</strong> {{ $alat->stok }}</p>
                                <form id="orderForm{{ $alat->id }}">
                                    <div class="form-group">
                                        <label for="jumlah{{ $alat->id }}">Jumlah Pinjam:</label>
                                        <input type="number" id="jumlah{{ $alat->id }}" name="jumlah" 
                                               class="form-control" min="1" max="{{ $alat->stok }}" 
                                               value="1" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="durasi{{ $alat->id }}" class="font-weight-bold">Durasi Sewa (Jam):</label>
                                        <div class="btn-group btn-group-sm btn-group-toggle mb-2 d-flex" data-toggle="buttons">
                                            <label class="btn btn-outline-primary flex-fill">
                                                <input type="radio" name="durasi_quick{{ $alat->id }}" value="1"> 1 Jam
                                            </label>
                                            <label class="btn btn-outline-primary flex-fill">
                                                <input type="radio" name="durasi_quick{{ $alat->id }}" value="2"> 2 Jam
                                            </label>
                                            <label class="btn btn-outline-primary flex-fill">
                                                <input type="radio" name="durasi_quick{{ $alat->id }}" value="3"> 3 Jam
                                            </label>
                                        </div>
                                        <select id="durasi{{ $alat->id }}" name="durasi" class="form-control form-control-sm" required>
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

                                    <div class="form-group">
                                        <label>Total Tarif:</label>
                                        <h4 id="totalPrice{{ $alat->id }}" class="text-success">
                                            Rp {{ number_format($alat->harga, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-success btn-confirm-pinjam" data-alat-id="{{ $alat->id }}">
                                    <i class="fas fa-check"></i> Sewa Langsung
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    (function() {
                        const harga = {{ $alat->harga }};
                        const alatId = {{ $alat->id }};
                        const jumlahInput = document.getElementById('jumlah{{ $alat->id }}');
                        const durasiSelect = document.getElementById('durasi{{ $alat->id }}');
                        const totalPriceEl = document.getElementById('totalPrice{{ $alat->id }}');
                        
                        function getProgressivePrice(durationInHours) {
                            let pricePerUnit;
                            if (durationInHours <= 1) {
                                pricePerUnit = harga * durationInHours;
                            } else if (durationInHours < 3) {
                                pricePerUnit = harga * durationInHours * 1.21;
                            } else {
                                pricePerUnit = harga * durationInHours * 0.78;
                            }
                            const diskon_promo = {{ $alat->diskon_persen }};
                            pricePerUnit = pricePerUnit * (100 - diskon_promo) / 100;
                            return pricePerUnit;
                        }
                        
                        function calculateTotal() {
                            const jumlah = parseInt(jumlahInput.value) || 1;
                            const durasi = parseFloat(durasiSelect.value) || 1;
                            const total = getProgressivePrice(durasi) * jumlah;
                            totalPriceEl.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
                        }
                        
                        jumlahInput.addEventListener('change', calculateTotal);
                        jumlahInput.addEventListener('input', calculateTotal);
                        durasiSelect.addEventListener('change', calculateTotal);
                        
                        // Quick select buttons
                        document.querySelectorAll('input[name="durasi_quick{{ $alat->id }}"]').forEach(radio => {
                            radio.addEventListener('change', function() {
                                durasiSelect.value = this.value;
                                calculateTotal();
                            });
                        });
                        
                        // Button submit
                        document.querySelector('.btn-confirm-pinjam[data-alat-id="{{ $alat->id }}"]').addEventListener('click', function() {
                            const jumlah = parseInt(jumlahInput.value) || 1;
                            const durasi = parseFloat(durasiSelect.value) || 1;
                            
                            if (durasi < 0.5) {
                                alert('Durasi sewa minimal 0.5 jam (30 menit)!');
                                return;
                            }
                            
                            // Redirect ke konfirmasi
                            window.location.href = `{{ route("customer.belanja.tampil-konfirmasi", $alat->id) }}?qty=${jumlah}&durasi_jam=${durasi}`;
                        });
                    })();
                </script>
                @endif
            </div>
            @empty
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Alat tidak ditemukan
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($alats->hasPages())
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-center">
                {{ $alats->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .hover-shadow:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.2) !important;
        transform: translateY(-5px);
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .btn-group .btn {
        border-radius: 5px;
        margin: 0 2px;
    }

    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }
</style>
@endisset
