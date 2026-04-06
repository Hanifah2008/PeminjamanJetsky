<div class="row p-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body">
                <h5 class="mb-3 text-dark"><b>Tambah Alat</b></h5>
                <div class="row">
                    <div class="col-md-4">
                        <label for="alat">Kode Alat</label>
                    </div>
                    <div class="col-md-8">
                        <form method="GET">
                            <div class="d-flex">
                                <select name="alat_id" class="form-control">
                                    <option value="">-- {{ isset($a_detail) ? $a_detail->name : 'Nama Alat' }} --</option>
                                    @foreach ($alat as $item)
                                        <option value="{{ $item->id }}">{{ $item->id }} - {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary ms-2">Pilih</button>
                            </div>
                        </form>
                    </div>
                </div>

                <form action="{{ url('/admin/transaksi/detail/create') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="transaksi_id" value="{{ Request::segment(3) }}">
                    <input type="hidden" name="alat_id" value="{{ $a_detail->id ?? '' }}">
                    <input type="hidden" name="alat_name" value="{{ $a_detail->name ?? '' }}">
                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">

                    <div class="mb-3">
                        <label>Nama Alat</label>
                        <input type="text" value="{{ isset($a_detail) ? $a_detail->name : '' }}" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Tarif Sewa</label>
                        <input type="text" value="{{ isset($a_detail) ? format_rupiah($a_detail->harga) : '' }}" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label>QTY</label>
                        <div class="d-flex">
                            <a href="?alat_id={{ request('alat_id') }}&act=min&qty={{ $qty }}" class="btn btn-outline-primary"><i class="fas fa-minus"></i></a>
                            <input type="number" value="{{ $qty }}" name="qty" class="form-control mx-2 text-center" style="width: 60px;">
                            <a href="?alat_id={{ request('alat_id') }}&act=plus&qty={{ $qty }}" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5>Subtotal: <strong class="text-primary">Rp. {{ format_rupiah($subtotal) }}</strong></h5>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="/admin/transaksi" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary">Tambah <i class="fas fa-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body">
                <h5 class="mb-3 text-dark"><b>Detail Transaksi</b></h5>
                <table class="table table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>QTY</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi_detail as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->alat_name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp. {{ format_rupiah($item->subtotal) }}</td>
                            <td>
                                <a href="/admin/transaksi/detail/delete?id={{ $item->id }}" class="text-danger"><i class="fas fa-times"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex gap-2">
                    <a href="/admin/transaksi/detail/selesai/{{ Request::segment(3) }}" class="btn btn-success"><i class="fas fa-check"></i> Selesai</a>
                    <a href="#" class="btn btn-warning"><i class="fas fa-file"></i> Pending</a>
                    <a href="{{ route('transaksi.print-struk', Request::segment(3)) }}?dibayarkan={{ request('dibayarkan') }}" target="_blank" class="btn btn-info">
    <i class="fas fa-print"></i> Cetak Struk
</a>


                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="row p-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body">
                <h5 class="mb-3 text-dark"><b>Pembayaran</b></h5>
                <form action="" method="GET">
                    <div class="mb-3">
                        <label>Total Sewa</label>
                        <input type="number" value="{{ $transaksi->total }}" name="total_belanja" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Dibayarkan</label>
                        <input type="number" value="{{ request('dibayarkan') }}" name="dibayarkan" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Hitung</button>
                </form>
                <hr>
                <div class="mb-3">
                    <label>Uang Kembalian</label>
                    <input type="text" value="Rp. {{ format_rupiah($kembalian) }}" class="form-control" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
