# 🛒 Sistem Peminjaman Alat - Checkout Flow

## Alur Lengkap Peminjaman Alat (Rental System)

### 1️⃣ **Halaman Detail Alat** (`/customer/belanja/{id}`)
- **File**: `resources/views/customer/belanja/show.blade.php`
- **Komponen**:
  - Gambar alat
  - Informasi alat (kategori, rating, stok)
  - Form peminjaman dengan opsi:
    - **Durasi Cepat**: Tombol quick-select (1 jam, 2 jam, 3 jam)
    - **Durasi Custom**: Dropdown atau input manual
    - **Jumlah Unit**: +/- buttons
  - **Real-time Price Calculation** dengan progressive pricing:
    - 1 jam: Harga normal
    - 2 jam: +20% (multiplier 1.2)
    - 3+ jam: -10% diskon (multiplier 0.9)
  - **Dua Tombol Aksi**:
    - "Ke Keranjang" (untuk checkout nanti)
    - "Sewa Langsung" → **Redirect ke halaman konfirmasi**

### 2️⃣ **Halaman Konfirmasi Peminjaman** (`/customer/belanja/konfirmasi-detail/{alatId}`)
- **File**: `resources/views/customer/belanja/konfirmasi.blade.php`
- **Route**: `GET /customer/belanja/konfirmasi-detail/{alatId}` → `customer.belanja.tampil-konfirmasi`
- **Controller**: `CustomerProdukController::tampilKonfirmasi()`
- **Komponen**:
  - **Bagian Kiri** (30%):
    - Gambar alat
    - Info alat (nama, kategori, stok tersedia)
  - **Bagian Kanan** (70%):
    - **Detail Peminjaman**:
      - Jumlah unit
      - Durasi sewa dalam jam
    - **Perhitungan Harga**:
      - Harga per jam
      - Durasi yang dipilih
      - Progressive pricing multiplier (jika ada)
      - Subtotal = (qty × durasi × harga)
      - Promo diskon (jika ada di alat)
      - **TOTAL PEMBAYARAN** (highlighted)
    - **Informasi Penyewa**:
      - Nama user
      - Email user
      - No. Telepon
      - Alamat
    - **Tombol Aksi**:
      - "✓ Konfirmasi Peminjaman" → POST
      - "← Kembali" → Kembali ke halaman detail alat

### 3️⃣ **Proses Peminjaman & Transaksi** (POST `/customer/belanja/proses-pinjaman/{alatId}`)
- **File Controller**: `app/Http/Controllers/CustomerProdukController::prosesPinjaman()`
- **Route**: `POST /customer/belanja/proses-pinjaman/{alatId}` → `customer.belanja.proses-pinjaman`
- **Proses**:
  1. ✅ Validasi durasi (minimal 0.5 jam)
  2. ✅ Validasi stok alat
  3. ✅ Hitung progressive pricing multiplier
  4. ✅ Hitung harga original = alat.harga × durasi × multiplier
  5. ✅ Hitung diskon promo = harga_original × (100 - diskon_persen) / 100
  6. ✅ Hitung total = harga_setelah_diskon × qty
  7. ✅ **CREATE Transaksi** dengan:
     - user_id = current user
     - total = total_harga (integer)
     - status = 'selesai'
     - kasir_name = null
  8. ✅ **CREATE TransaksiDetail** dengan:
     - transaksi_id = baru dibuat
     - alat_id = alat yang dipilih
     - qty = jumlah unit
     - durasi_jam = durasi sewa
     - harga = harga_original (integer)
     - harga_original = harga_original (integer)
     - harga_setelah_diskon = harga_setelah_diskon (integer)
     - diskon_persen = diskon_persen alat
  9. ✅ **KURANGI STOK ALAT** = alat.stok - qty
  10. ✅ **REDIRECT** ke halaman detail transaksi dengan success message

### 4️⃣ **Halaman Detail Transaksi** (`/customer/riwayat/{id}`)
- **File**: `resources/views/customer/riwayat/show.blade.php`
- **Display Informasi**:
  - Nomor transaksi (ID dengan format 5 digit)
  - Tanggal & waktu transaksi
  - **Tabel Detail Alat**:
    - Nama alat + kategori
    - Durasi sewa (dalam jam)
    - Harga setelah diskon
    - Jumlah unit
    - Persentase diskon
    - **SUBTOTAL** = harga_setelah_diskon × qty
  - **Total pembayaran keseluruhan**
  - Status transaksi (Selesai/Pending)

### 5️⃣ **Halaman Riwayat Peminjaman** (`/customer/riwayat`)
- **File**: `resources/views/customer/riwayat/index.blade.php`
- **Menampilkan**:
  - List semua transaksi user
  - Filter by status (Selesai/Pending)
  - **Setiap item menampilkan**:
    - Nomor transaksi
    - Tanggal/waktu transaksi
    - ⏱️ Durasi × qty = total harga
    - Status badge
    - Rating star (jika sudah diberi rating)

---

## 📊 Database Schema - Transaksi Details

### Columns yang Digunakan untuk Checkout Flow:

```
transaksi_details table:
├── id (primary key)
├── transaksi_id (FK) ← Menghubung ke transaksi
├── alat_id (FK) ← Alat yang disewa
├── qty (integer) ← Jumlah unit
├── durasi_jam (float) ← ⏱️ Durasi sewa dalam jam
├── harga_original (bigInteger) ← Harga asli × durasi × multiplier
├── harga_setelah_diskon (bigInteger) ← Harga setelah promo diskon alat
├── diskon_persen (integer) ← % diskon promo alat
├── harga (bigInteger) ← Alias untuk harga_original
├── created_at & updated_at
└── ... (kolom lainnya untuk fitur future)
```

---

## 🎯 Progressive Pricing Logic

```
IF durasi >= 3 jam:
  multiplier = 0.9 (DISKON 10%)
ELSE IF durasi >= 2 jam:
  multiplier = 1.2 (NAIK 20%)
ELSE:
  multiplier = 1.0 (HARGA NORMAL)

harga_original = alat.harga × durasi × multiplier
harga_setelah_diskon = harga_original × (100 - alat.diskon_persen) / 100
total_harga = harga_setelah_diskon × qty
```

---

## 🔄 Diagram Alur

```
┌─────────────────────────────┐
│  Halaman Detail Alat        │
│  (/customer/belanja/{id})   │
└──────────────┬──────────────┘
               │
        ┌──────▼────────┐
        │ Pilih Durasi  │ (Quick select atau custom)
        │ Pilih Qty     │ (Dengan +/- buttons)
        │ Lihat Harga   │ (Real-time calculation)
        └──────┬────────┘
               │
        ┌──────▼────────────────────────┐
        │ Klik "Sewa Langsung"          │ (JavaScript redirect)
        │ ?qty=X&durasi_jam=Y           │
        └──────┬─────────────────────────┘
               │
        ┌──────▼──────────────────────────────┐
        │  Halaman Konfirmasi Peminjaman      │
        │  (/customer/belanja/konfirmasi)     │
        │  - Review detail & pricing breakdown│
        │  - Lihat info penyewa               │
        │  - FORM dengan hidden: qty, durasi  │
        └──────┬───────────────────────────────┘
               │
        ┌──────▼──────────────────────────┐
        │ Klik "Konfirmasi Peminjaman"    │ (POST submit)
        │ Validasi & Create Transaksi     │
        │ Kurangi stok alat               │
        └──────┬───────────────────────────┘
               │
        ┌──────▼────────────────────────┐
        │  Halaman Detail Transaksi      │
        │  (/customer/riwayat/{id})      │
        │  - Lihat detail peminjaman     │
        │  - Durasi × qty = total harga  │
        │  - Status: Selesai ✓           │
        └────────────────────────────────┘
```

---

## ✅ Checklist Implementasi

- ✅ Script dengan `DOMContentLoaded` wrapper
- ✅ Form dengan proper event listeners
- ✅ Konfirmasi page dengan detail lengkap
- ✅ Controller methods untuk tampilKonfirmasi & prosesPinjaman
- ✅ Routes untuk konfirmasi & proses pinjaman
- ✅ Database columns untuk durasi & pricing
- ✅ Transaksi & TransaksiDetail creation dengan data lengkap
- ✅ Riwayat display dengan durasi info
- ✅ CSRF token di form & meta tag
- ✅ Alert/notification system

---

## 🚀 Cara Test Flow Lengkap

1. **Buka aplikasi**: `http://localhost:8000/customer/belanja`
2. **Pilih alat**: Klik salah satu alat untuk lihat detail
3. **Isi form peminjaman**:
   - Durasi: Pilih quick-select atau custom
   - Qty: Adjust dengan +/- button
   - Lihat harga update real-time
4. **Klik "Sewa Langsung"**: Harus redirect ke halaman konfirmasi
5. **Review detail**: Lihat breakdown harga & info penyewa
6. **Klik "Konfirmasi Peminjaman"**: Submit form
7. **Lihat transaksi**: Harus redirect ke halaman detail transaksi
8. **Verifikasi di riwayat**: `http://localhost:8000/customer/riwayat`

---

## 📝 Notes

- Semua harga di-**round integer** sebelum disimpan ke database
- Durasi minimal: **0.5 jam** (30 menit)
- Progressive pricing berlaku berdasarkan **durasi**
- Promo diskon alat berlaku **setelah** progressive pricing
- Stok otomatis berkurang setelah transaksi berhasil
- Status transaksi langsung **'selesai'** untuk rental (bukan pending payment)

