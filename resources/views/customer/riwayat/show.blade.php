@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pembelian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.riwayat.index') }}">Riwayat Pembelian</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Info Transaksi -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title">
                            <i class="fas fa-receipt"></i> Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Tanggal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Tanggal Transaksi</p>
                                <h5>{{ $transaksi->created_at->format('d M Y') }}</h5>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Waktu</p>
                                <h5>{{ $transaksi->created_at->format('H:i') }}</h5>
                            </div>
                        </div>

                        <hr>

                        <!-- Daftar Alat -->
                        <h5 class="mb-3">Detail Alat</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Alat</th>
                                        <th class="text-center">Durasi</th>
                                        <th class="text-right">Harga (Durasi × Harga/Jam)</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Diskon</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksi->details as $detail)
                                        <tr>
                                            <td>
                                                <strong>{{ $detail->alat->name ?? 'Alat Dihapus' }}</strong>
                                                @if($detail->alat)
                                                    <br>
                                                    <small class="text-muted">Kategori: {{ $detail->alat->kategori->name ?? 'N/A' }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <small>{{ $detail->durasi_jam }} jam</small>
                                            </td>
                                            <td class="text-right">
                                                Rp {{ number_format($detail->harga_setelah_diskon ?? $detail->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $detail->qty }}</span>
                                            </td>
                                            <td class="text-right">
                                                @if($detail->diskon_persen > 0)
                                                    <span class="badge badge-warning">{{ $detail->diskon_persen }}%</span><br>
                                                    <small class="text-muted">-Rp {{ number_format($detail->harga_original - $detail->harga_setelah_diskon, 0, ',', '.') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                Rp {{ number_format($detail->harga_setelah_diskon * $detail->qty, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                Tidak ada detail transaksi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Kasir Info -->
                        @if($transaksi->kasir_name)
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-muted mb-1">Dikerjakan oleh</p>
                                    <h5>{{ $transaksi->kasir_name }}</h5>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-md-4">
                <!-- Status Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Status Transaksi</h5>
                    </div>
                    <div class="card-body text-center">
                        @if($transaksi->status == 'selesai')
                            <div class="mb-3">
                                <i class="fas fa-check-circle fa-3x text-success"></i>
                            </div>
                            <h4 class="text-success font-weight-bold">Selesai</h4>
                            <p class="text-muted small">
                                Transaksi ini telah selesai dan telah diproses
                            </p>
                        @else
                            <div class="mb-3">
                                <i class="fas fa-clock fa-3x text-warning"></i>
                            </div>
                            <h4 class="text-warning font-weight-bold">Pending</h4>
                            <p class="text-muted small">
                                Transaksi sedang diproses. Mohon tunggu
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Ringkasan Harga -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah Item</span>
                            <strong>{{ $transaksi->details->sum('qty') }} item</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Total Pembayaran</h5>
                            <h5 class="mb-0 text-success font-weight-bold">
                                Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <!-- Rating Section -->
                @if($transaksi->status == 'selesai')
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star"></i> Berikan Ulasan
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Bagikan pengalaman Anda tentang alat yang disewa</p>
                            
                            @foreach($transaksi->details as $detail)
                                @php
                                    $userRating = \App\Models\Rating::where('user_id', auth()->id())
                                                                     ->where('alat_id', $detail->alat_id)
                                                                     ->first();
                                @endphp
                                <div class="rating-form mb-3">
                                    <p class="font-weight-bold small mb-2">{{ $detail->alat->name }}</p>
                                    <form action="{{ route('customer.rating.store') }}" method="POST" class="rating-submit-{{ $detail->alat_id }}">
                                        @csrf
                                        <input type="hidden" name="alat_id" value="{{ $detail->alat_id }}">
                                        
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Rating</label>
                                            <div class="star-rating" data-alat-id="{{ $detail->alat_id }}" style="font-size: 24px;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="far fa-star rating-star" 
                                                       data-value="{{ $i }}" 
                                                       style="cursor: pointer; color: #ffc107; margin-right: 5px;"
                                                       @if($userRating && $userRating->bintang >= $i) class="fas fa-star" @endif></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="bintang" class="rating-input-{{ $detail->alat_id }}" value="{{ $userRating->bintang ?? 0 }}">
                                        </div>

                                        <div class="form-group mb-2">
                                            <label for="komentar-{{ $detail->alat_id }}" class="small font-weight-bold">Komentar (Opsional)</label>
                                            <textarea class="form-control" 
                                                      id="komentar-{{ $detail->alat_id }}" 
                                                      name="komentar" 
                                                      rows="2" 
                                                      placeholder="Bagikan pengalaman Anda..."
                                                      maxlength="1000">{{ $userRating->komentar ?? '' }}</textarea>
                                            <small class="text-muted">Maksimal 1000 karakter</small>
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-paper-plane"></i> 
                                            {{ $userRating ? 'Update Ulasan' : 'Kirim Ulasan' }}
                                        </button>
                                    </form>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Aksi -->
                <div class="mt-3">
                    <a href="{{ route('customer.riwayat.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        border-radius: 8px;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .rating-star {
        transition: color 0.2s ease;
    }

    .rating-star:hover,
    .rating-star.active {
        color: #ffc107 !important;
        text-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
    }
</style>

<script>
    document.querySelectorAll('.star-rating').forEach(container => {
        const alatId = container.dataset.alatId;
        const stars = container.querySelectorAll('.rating-star');
        const input = document.querySelector('.rating-input-' + alatId);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                input.value = value;

                // Update visual state
                stars.forEach((s, index) => {
                    if (index + 1 <= value) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });

            star.addEventListener('mouseover', function() {
                const value = this.dataset.value;
                stars.forEach((s, index) => {
                    if (index + 1 <= value) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
        });

        // Reset on mouse leave
        container.addEventListener('mouseleave', function() {
            const currentValue = input.value || 0;
            stars.forEach((s, index) => {
                if (index + 1 <= currentValue) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });

    // Show success message
    @if(session('success'))
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(alert => {
                if (alert.innerText.includes('Rating')) {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }, 3000);
                }
            });
        }, 100);
    @endif
</script>@endisset