@isset($content)
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Pembelian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/customer/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Pembelian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Status -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('customer.riwayat.index') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="status" class="mr-2">Filter Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Semua Transaksi</option>
                                    <option value="pending" {{ ($status == 'pending') ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="selesai" {{ ($status == 'selesai') ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('customer.riwayat.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Pembelian Anda</h3>
                    </div>
                    <div class="card-body table-responsive">
                        @forelse($riwayats as $transaksi)
                            <div class="card mb-3 border-left-{{ ($transaksi->status == 'selesai') ? 'success' : 'warning' }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5 class="card-title">
                                                Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}
                                            </h5>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-calendar"></i> 
                                                {{ $transaksi->created_at->format('d M Y H:i') }}
                                            </p>
                                            
                                            <!-- Daftar Alat -->
                                            <div class="mb-2">
                                                <small class="text-muted">Alat yang disewa:</small>
                                                <div class="list-group list-group-sm mt-2">
                                                    @foreach($transaksi->details as $detail)
                                                        @php
                                                            $userRating = \App\Models\Rating::where('user_id', auth()->id())
                                                                                             ->where('alat_id', $detail->alat_id)
                                                                                             ->first();
                                                        @endphp
                                                        <div class="list-group-item py-2 d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <strong>{{ $detail->alat->name ?? 'Alat Dihapus' }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    ⏱️ {{ $detail->durasi_jam }} jam × {{ $detail->qty }} unit = Rp {{ number_format($detail->harga * $detail->qty, 0, ',', '.') }}
                                                                </small>
                                                            </div>
                                                            @if($userRating)
                                                                <span class="badge badge-success" title="Sudah diberi rating">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        @if($i <= $userRating->bintang)
                                                                            <i class="fas fa-star"></i>
                                                                        @else
                                                                            <i class="far fa-star"></i>
                                                                        @endif
                                                                    @endfor
                                                                </span>
                                                            @else
                                                                <span class="badge badge-secondary" title="Belum diberi rating">
                                                                    <i class="fas fa-star-half-alt"></i> -
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 text-right">
                                            <!-- Status Badge -->
                                            <div class="mb-3">
                                                @if($transaksi->status == 'selesai')
                                                    <span class="badge badge-success" style="font-size: 14px; padding: 8px;">
                                                        <i class="fas fa-check-circle"></i> Selesai
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning" style="font-size: 14px; padding: 8px;">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Total Harga -->
                                            <div class="mb-3">
                                                <p class="text-muted small mb-1">Total Transaksi</p>
                                                <h4 class="text-success font-weight-bold mb-0">
                                                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                                </h4>
                                            </div>

                                            <!-- Kasir Name -->
                                            @if($transaksi->kasir_name)
                                                <p class="text-muted small mb-3">
                                                    <i class="fas fa-user"></i> Kasir: {{ $transaksi->kasir_name }}
                                                </p>
                                            @endif

                                            <!-- Detail Button -->
                                            <a href="{{ route('customer.riwayat.show', $transaksi->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle"></i> 
                                Belum ada riwayat pembelian
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($riwayats->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $riwayats->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .list-group-sm .list-group-item {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e3e6f0;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        border-radius: 8px;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
</style>
@endisset
