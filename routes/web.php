<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\PetugasAuthController;
use App\Http\Controllers\CustomerProdukController;
use App\Http\Controllers\CustomerRiwayatController;
use App\Http\Controllers\CustomerProfilController;
use App\Http\Controllers\CustomerKeranjangController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAlatController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminTransaksiDetailController;
use App\Http\Controllers\CustomerRatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetugasTransaksiController;
use App\Http\Controllers\PetugasProfilController;
use App\Http\Controllers\PetugasReportController;
use Illuminate\Support\Facades\Route;

// ==== ADMIN LOGIN (Guest Only) ====
Route::get('/login', [AdminAuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login/do', [AdminAuthController::class, 'doLogin'])->middleware('guest');
Route::get('logout', [AdminAuthController::class, 'logout'])->middleware('auth');

// ==== CUSTOMER LOGIN (Guest Only) ====
Route::prefix('/customer')->middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'loginIndex'])->name('customer.login');
    Route::post('/login/do', [CustomerAuthController::class, 'doLogin']);
    Route::get('/register', [CustomerAuthController::class, 'registerIndex'])->name('customer.register');
    Route::post('/register/do', [CustomerAuthController::class, 'doRegister']);
});

// ==== CUSTOMER LOGOUT (Auth Only) ====
Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])->middleware('auth');

// ==== PETUGAS LOGIN (Guest Only) ====
Route::prefix('/petugas')->middleware('guest')->group(function () {
    Route::get('/login', [PetugasAuthController::class, 'loginIndex'])->name('petugas.login');
    Route::post('/login/do', [PetugasAuthController::class, 'doLogin']);
});

// ==== PETUGAS LOGOUT (Auth Only) ====
Route::get('/petugas/logout', [PetugasAuthController::class, 'logout'])->middleware('auth');

// ==== HOME ====
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'index'])->name('home');
// ==== DASHBOARD (Smart Redirect) ====
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    $role = auth()->user()->role;

    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'petugas') {
        return redirect('/petugas/dashboard');
    } elseif ($role === 'customer') {
        return redirect('/customer/dashboard');
    }

    return redirect('/login');
})->middleware('auth')->name('dashboard');

// ==== ADMIN DASHBOARD & ROUTES (Auth + Admin Role Only) ====
Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    Route::get('/transaksi/detail/selesai/{id}', [AdminTransaksiDetailController::class, 'done']);
    Route::get('/transaksi/detail/delete', [AdminTransaksiDetailController::class, 'delete']);
    Route::post('/transaksi/detail/create', [AdminTransaksiDetailController::class, 'create']);
    Route::resource('/transaksi', AdminTransaksiController::class);
    Route::resource('/alat', AdminAlatController::class);
    Route::resource('/kategori', AdminKategoriController::class);
    Route::resource('/user', AdminUserController::class);
    Route::get('/transaksi/print-struk/{id}', [AdminTransaksiDetailController::class, 'printStruk'])->name('transaksi.print-struk');
});

// ==== CUSTOMER ROUTES (Auth + Customer Role Only) ====
Route::prefix('/customer')->middleware(['auth', 'customer'])->group(function () {
    Route::get('/dashboard', function () {
        $alats = \App\Models\Alat::with('kategori')->limit(8)->get();
        $kategoris = \App\Models\Kategori::all();
        $totalBelanja = \App\Models\Transaksi::where('user_id', auth()->id())->sum('total');
        $jumlahPembelian = \App\Models\Transaksi::where('user_id', auth()->id())->count();
        $data = [
            'content' => 'customer.dashboard.index',
            'alats' => $alats,
            'kategoris' => $kategoris,
            'totalBelanja' => $totalBelanja,
            'jumlahPembelian' => $jumlahPembelian
        ];
        return view('customer.layouts.wrapper', $data);
    })->name('customer.dashboard');

    // Pinjam Alat
    Route::get('/belanja', [CustomerProdukController::class, 'index'])->name('customer.belanja.index');
    Route::get('/belanja/{id}', [CustomerProdukController::class, 'show'])->name('customer.belanja.show');
    Route::post('/belanja/konfirmasi/{alatId}', [CustomerProdukController::class, 'konfirmasiPinjaman'])->name('customer.belanja.konfirmasi');
    Route::get('/belanja/konfirmasi-detail/{alatId}', [CustomerProdukController::class, 'tampilKonfirmasi'])->name('customer.belanja.tampil-konfirmasi');
    Route::post('/belanja/proses-pinjaman/{alatId}', [CustomerProdukController::class, 'prosesPinjaman'])->name('customer.belanja.proses-pinjaman');

    // Keranjang Peminjaman
    Route::get('/keranjang', [CustomerKeranjangController::class, 'index'])->name('customer.keranjang.index');
    Route::post('/keranjang/add/{alatId}', [CustomerKeranjangController::class, 'add'])->name('customer.keranjang.add');
    Route::post('/keranjang/update/{id}', [CustomerKeranjangController::class, 'update'])->name('customer.keranjang.update');
    Route::get('/keranjang/delete/{id}', [CustomerKeranjangController::class, 'delete'])->name('customer.keranjang.delete');
    Route::get('/keranjang/checkout', [CustomerKeranjangController::class, 'checkout'])->name('customer.keranjang.checkout');
    Route::post('/keranjang/proses', [CustomerKeranjangController::class, 'proses'])->name('customer.keranjang.proses');

    // Riwayat Pembelian
    Route::get('/riwayat', [CustomerRiwayatController::class, 'index'])->name('customer.riwayat.index');
    Route::get('/riwayat/{id}', [CustomerRiwayatController::class, 'show'])->name('customer.riwayat.show');

    // Rating
    Route::post('/rating/store', [CustomerRatingController::class, 'store'])->name('customer.rating.store');

    // Profil
    Route::get('/profil', [CustomerProfilController::class, 'index'])->name('customer.profil.index');
    Route::get('/profil/edit', [CustomerProfilController::class, 'edit'])->name('customer.profil.edit');
    Route::post('/profil/update', [CustomerProfilController::class, 'update'])->name('customer.profil.update');

    // Tambahkan routes customer lainnya di sini
});

// ==== PETUGAS DASHBOARD & ROUTES (Auth + Petugas Role Only) ====
Route::prefix('/petugas')->middleware(['auth', 'petugas'])->group(function () {
    Route::get('/dashboard', function () {
        $alatCount = \App\Models\Alat::count();
        $transaksiCount = \App\Models\Transaksi::count();
        $kategoriCount = \App\Models\Kategori::count();
        $userCount = \App\Models\User::count();

        $data = [
            'content' => 'petugas.dashboard.index',
            'alatCount' => $alatCount,
            'transaksiCount' => $transaksiCount,
            'kategoriCount' => $kategoriCount,
            'userCount' => $userCount,
        ];

        return view('petugas.layouts.wrapper', $data);
    })->name('petugas.dashboard');

    // Profil Petugas
    Route::get('/profil', function () {
        $user = auth()->user();

        $data = [
            'content' => 'petugas.profil.index',
            'user' => $user,
        ];

        return view('petugas.layouts.wrapper', $data);
    })->name('petugas.profil');

    Route::get('/profil/edit', [PetugasProfilController::class, 'edit'])->name('petugas.profil.edit');
    Route::put('/profil/update', [PetugasProfilController::class, 'update'])->name('petugas.profil.update');

    // Transaksi & Approval Management
    Route::get('/transaksi', [PetugasTransaksiController::class, 'index'])->name('petugas.transaksi.index');
    Route::get('/transaksi/pending-approval', [PetugasTransaksiController::class, 'indexPending'])->name('petugas.transaksi.pending-approval');
    Route::get('/transaksi/approval-history', [PetugasTransaksiController::class, 'indexHistory'])->name('petugas.transaksi.approval-history');
    Route::get('/transaksi/{id}', [PetugasTransaksiController::class, 'show'])->name('petugas.transaksi.show');
    Route::post('/transaksi/{id}/approve', [PetugasTransaksiController::class, 'approve'])->name('petugas.transaksi.approve');
    Route::post('/transaksi/{id}/reject', [PetugasTransaksiController::class, 'reject'])->name('petugas.transaksi.reject');
    Route::get('/transaksi/{id}/print-receipt', [PetugasTransaksiController::class, 'printReceipt'])->name('petugas.transaksi.print-receipt');

    // Monitoring Peminjaman
    Route::get('/monitoring', [PetugasTransaksiController::class, 'monitoring'])->name('petugas.monitoring.index');
    Route::get('/monitoring/{id}', [PetugasTransaksiController::class, 'monitoringDetail'])->name('petugas.monitoring.detail');

    // Monitoring Pengembalian
    Route::get('/monitoring-return', [PetugasTransaksiController::class, 'monitoringReturn'])->name('petugas.monitoring.return');
    Route::get('/monitoring-return/{id}', [PetugasTransaksiController::class, 'returnDetail'])->name('petugas.monitoring.return-detail');
    Route::post('/monitoring-return/{id}/process', [PetugasTransaksiController::class, 'processReturn'])->name('petugas.monitoring.process-return');
    Route::post('/monitoring-return/{id}/overdue', [PetugasTransaksiController::class, 'markOverdue'])->name('petugas.monitoring.mark-overdue');

    // Laporan
    Route::get('/laporan', [PetugasReportController::class, 'index'])->name('petugas.report.index');
    Route::get('/laporan/peminjaman', [PetugasReportController::class, 'loanReport'])->name('petugas.report.loan');
    Route::get('/laporan/peminjaman/print', [PetugasReportController::class, 'printLoanReport'])->name('petugas.report.print-loan');
    Route::get('/laporan/approval', [PetugasReportController::class, 'approvalReport'])->name('petugas.report.approval');
    Route::get('/laporan/approval/print', [PetugasReportController::class, 'printApprovalReport'])->name('petugas.report.print-approval');
    Route::get('/laporan/pengembalian', [PetugasReportController::class, 'returnReport'])->name('petugas.report.return');
    Route::get('/laporan/pengembalian/print', [PetugasReportController::class, 'printReturnReport'])->name('petugas.report.print-return');
    Route::get('/laporan/overdue', [PetugasReportController::class, 'overdueReport'])->name('petugas.report.overdue');
    Route::get('/laporan/overdue/print', [PetugasReportController::class, 'printOverdueReport'])->name('petugas.report.print-overdue');

    // View Alat (Read-Only)
    Route::get('/alat', function () {
        $alats = \App\Models\Alat::with('kategori')->paginate(10);
        $data = [
            'content' => 'petugas.alat.index',
            'alats' => $alats,
        ];
        return view('petugas.layouts.wrapper', $data);
    })->name('petugas.alat.index');

    // View User (Read-Only)
    Route::get('/user', function () {
        $users = \App\Models\User::paginate(10);
        $data = [
            'content' => 'petugas.user.index',
            'users' => $users,
        ];
        return view('petugas.layouts.wrapper', $data);
    })->name('petugas.user.index');
});
