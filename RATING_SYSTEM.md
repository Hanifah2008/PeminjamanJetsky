# Sistem Rating & Ulasan Pembeli

## Deskripsi
Pelanggan dapat memberikan rating dan ulasan untuk setiap alat yang telah disewa setelah transaksi selesai. Sistem ini memungkinkan pelanggan untuk membagikan pengalaman mereka menggunakan alat rental.

## Fitur-Fitur

### 1. Rating di Halaman Detail Riwayat Transaksi
- **Lokasi**: `/customer/riwayat/{id}`
- **Persyaratan**: Transaksi harus memiliki status "selesai" (✓)
- **Fitur**:
  - Tampil ketika transaksi sudah selesai
  - Form rating untuk setiap alat dalam transaksi
  - Bintang interaktif 1-5 (hover effects)
  - Input komentar opsional (maksimal 1000 karakter)
  - Tombol "Kirim Ulasan" atau "Update Ulasan" (jika sudah rating)

### 2. Indikator Rating di Halaman Riwayat
- **Lokasi**: `/customer/riwayat`
- **Fitur**:
  - Badge hijau dengan bintang untuk alat yang sudah diberi rating
  - Badge abu-abu untuk alat yang belum diberi rating
  - Menampilkan jumlah bintang yang diberikan

### 3. Tampilan Rating di Halaman Detail Alat
- **Lokasi**: `/customer/belanja/{id}`
- **Fitur**:
  - Rating summary (rata-rata bintang dan jumlah ulasan)
  - Distribusi rating per bintang
  - Daftar ulasan terbaru dari pembeli lain
  - Opsi untuk memberikan rating dari halaman ini juga

## Alur Penggunaan

### Skenario 1: Memberikan Rating Setelah Selesai Sewa
1. Pelanggan menyelesaikan penyewaan alat
2. Transaksi status berubah menjadi "selesai"
3. Pelanggan membuka Riwayat Pembelian → Lihat Detail
4. Di bawah informasi transaksi, ada form "Berikan Ulasan"
5. Pelanggan memilih rating (klik bintang)
6. Pelanggan tulis komentar (opsional)
7. Klik "Kirim Ulasan"
8. Rating tersimpan dan ditampilkan di seluruh sistem

### Skenario 2: Update Rating yang Sudah Ada
1. Pelanggan sudah pernah rating alat tertentu
2. Kembali ke detail transaksi
3. Form rating menampilkan rating lama
4. Pelanggan dapat mengubah bintang dan komentar
5. Klik "Update Ulasan"
6. Rating berhasil diperbarui

### Skenario 3: Lihat Indikator Rating di Riwayat
1. Pelanggan membuka halaman "Riwayat Pembelian"
2. Setiap alat menampilkan badge rating
3. Hijau (✓) = sudah diberi rating
4. Abu-abu (-) = belum diberi rating
5. Memudahkan pelanggan melihat alat mana yang masih perlu dirating

## Database Structure

### Tabel: ratings
```sql
- id
- user_id (FK → users)
- alat_id (FK → alats)
- bintang (1-5)
- komentar (text, nullable)
- created_at
- updated_at
```

## Model Relationships

### User Model
```php
public function ratings() // hasMany(Rating)
public function riwayats() // hasMany(Transaksi)
```

### Alat Model
```php
public function ratings() // hasMany(Rating)
public function getRatingAverage() // avg bintang
public function getRatingCount() // count ratings
```

### Rating Model
```php
public function user() // belongsTo(User)
public function alat() // belongsTo(Alat)
```

## Validasi & Rules

### Rating Validation
- `alat_id`: required, harus ada di tabel alats
- `bintang`: required, integer, min 1, max 5
- `komentar`: nullable, max 1000 karakter

### Business Rules
- Setiap pelanggan hanya bisa rating 1 kali per alat
- Update rating: overwrite rating lama (bukan append)
- Rating hanya bisa diberikan untuk alat yang sudah disewa
- Rating hanya bisa diberikan setelah transaksi selesai

## UI/UX Features

### Star Rating Interactive
- **Hover Effect**: Bintang fill saat di-hover
- **Click**: Set rating value ke hidden input
- **Leavе**: Kembali ke rating terakhir yang di-save
- **Visual**: Bintang penuh (fas) = dipilih, bintang kosong (far) = belum

### Form Feedback
- Toast/Alert untuk "Rating berhasil ditambahkan!"
- Toast/Alert untuk "Rating berhasil diperbarui!"
- Auto-hide alert setelah 3 detik

### Status Badges
- **Hijau** (Selesai) = Transaksi selesai, bisa rating
- **Kuning** (Pending) = Transaksi pending, form rating tidak tampil
- **Success Badge** = Alat sudah diberi rating
- **Secondary Badge** = Alat belum diberi rating

## Routes

```
POST /customer/rating/store
- Controller: CustomerRatingController@store
- Middleware: auth, customer
- Request: alat_id, bintang, komentar
- Response: back()->with('success'/'error')
```

## File Structure

```
app/Models/
  ├─ Rating.php
  └─ Alat.php (rating methods)

app/Http/Controllers/
  └─ CustomerRatingController.php

resources/views/customer/
  ├─ riwayat/
  │  ├─ show.blade.php (rating form)
  │  └─ index.blade.php (rating indicators)
  └─ belanja/
     └─ show.blade.php (rating display)

database/migrations/
  └─ 2026_01_09_041050_create_ratings_table.php
```

## Testing Instructions

### Test 1: Berikan Rating Baru
1. Login sebagai customer
2. Buka Riwayat Pembelian
3. Klik "Lihat Detail" untuk transaksi selesai
4. Di bawah, cari "Berikan Ulasan"
5. Klik 5 bintang
6. Tulis komentar: "Alat bagus!"
7. Klik "Kirim Ulasan"
8. ✓ Harus muncul success message

### Test 2: Update Rating
1. Kembali ke detail transaksi yang sama
2. Form masih menampilkan rating lama (5 bintang)
3. Ubah ke 4 bintang
4. Ubah komentar
5. Klik "Update Ulasan"
6. ✓ Harus muncul "Rating berhasil diperbarui!"

### Test 3: Lihat Indikator Rating
1. Buka halaman Riwayat Pembelian
2. Cari alat yang sudah di-rating
3. ✓ Harus ada badge hijau dengan 5 bintang
4. Cari alat yang belum di-rating
5. ✓ Harus ada badge abu-abu

### Test 4: Lihat Rating di Halaman Detail Alat
1. Buka Belanja
2. Klik alat yang sudah punya rating
3. Scroll ke bawah "Rating & Ulasan"
4. ✓ Harus tampil rating summary (rata-rata)
5. ✓ Harus tampil ulasan dari pembeli

## Masa Depan (Future Features)

Fitur yang dapat ditambahkan:
1. Rating dengan foto/video lampiran
2. Helpful votes (berapa orang merasa ulasan ini membantu)
3. Reply dari admin terhadap ulasan negatif
4. Filter ulasan berdasarkan rating (tampilkan 5 bintang saja, dll)
5. Verified badge untuk buyer yang terbukti beli
6. Detailed rating (kualitas, kebersihan, service, dll)
7. Email notification saat ada rating baru
8. Rating moderation (admin review sebelum publish)
