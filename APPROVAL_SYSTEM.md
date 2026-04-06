# Sistem Approval Pinjaman untuk Petugas

## Deskripsi Fitur
Sistem ini memungkinkan petugas (officer/staff) untuk menyetujui atau menolak pinjaman yang dilakukan oleh customer.

## Fitur-Fitur

### 1. **Dashboard Approval**
- Melihat jumlah pinjaman yang menunggu approval
- Navigasi cepat ke berbagai menu approval
- Quick stats dari sistem

### 2. **Daftar Pinjaman Menunggu Approval**
- Melihat semua pinjaman yang belum disetujui
- Filter dan search
- Aksi cepat approve/detail
- Notifikasi badge dengan jumlah pending

### 3. **Detail Pinjaman & Approval Form**
- Informasi lengkap customer
- Detail alat yang dipinjam
- Form persetujuan dengan catatan
- Form penolakan dengan alasan wajib
- Konfirmasi sebelum aksi

### 4. **Riwayat Approval**
- Melihat semua pinjaman yang sudah disetujui/ditolak
- Informasi siapa yang menyetujui dan kapan
- Filter dan search
- Manajemen approval history

### 5. **Search & Filter**
- Filter berdasarkan approval status (pending, approved, rejected)
- Filter berdasarkan transaksi status (selesai, pending)
- Search berdasarkan nama customer
- Combine multiple filters

## Database Schema

### Kolom Baru di Tabel `transaksis`:
```sql
- approval_status (enum: pending, approved, rejected) DEFAULT 'pending'
- approved_by (foreign key ke users) NULLABLE
- approved_at (timestamp) NULLABLE
- approval_notes (text) NULLABLE
```

## Model & Relationship

### Transaksi Model
```php
public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

// Scopes
public function scopePendingApproval($query)
public function scopeApproved($query)
public function scopeRejected($query)
```

## Routes

```php
// Petugas Transaksi Routes
GET     /petugas/transaksi                    - Daftar semua transaksi dengan filter
GET     /petugas/transaksi/pending-approval   - Transaksi menunggu approval
GET     /petugas/transaksi/approval-history   - Riwayat approval
GET     /petugas/transaksi/{id}               - Detail pinjaman
POST    /petugas/transaksi/{id}/approve       - Setujui pinjaman
POST    /petugas/transaksi/{id}/reject        - Tolak pinjaman
```

## File Structure

```
app/Http/Controllers/
  └─ PetugasTransaksiController.php

database/migrations/
  └─ 2026_03_10_000000_add_approval_columns_to_transaksis_table.php

resources/views/petugas/transaksi/
  ├─ index.blade.php (old - kept for reference)
  ├─ index-new.blade.php (NEW - main index with filters)
  ├─ pending-approval.blade.php (NEW - pending list)
  ├─ show-approval.blade.php (NEW - detail & approval form)
  └─ approval-history.blade.php (NEW - history view)

resources/views/petugas/layouts/
  └─ sidebar.blade.php (UPDATED - new menu items)
```

## Alur Penggunaan

### Untuk Menyetujui Pinjaman:
1. Login sebagai petugas
2. Klik "Manajemen Pinjaman" di sidebar
3. Klik "Menunggu Approval" untuk melihat list
4. Klik "Lihat Detail" untuk melihat informasi lengkap
5. Isi catatan (opsional) dan klik "Setujui Pinjaman"
6. Konfirmasi persetujuan
7. Pinjaman akan berubah status menjadi "Disetujui"

### Untuk Menolak Pinjaman:
1. Login sebagai petugas
2. Klik "Manajemen Pinjaman" di sidebar
3. Klik "Menunggu Approval" untuk melihat list
4. Klik "Lihat Detail" untuk melihat informasi lengkap
5. Scroll ke bawah ke form "Tolak Pinjaman"
6. Isi alasan penolakan (wajib)
7. Klik "Tolak Pinjaman"
8. Konfirmasi penolakan
9. Pinjaman akan berubah status menjadi "Ditolak"

### Untuk Melihat Riwayat:
1. Login sebagai petugas
2. Klik "Manajemen Pinjaman" di sidebar
3. Klik "Riwayat Approval" untuk melihat semua approval yang sudah diproses
4. Lihat informasi siapa yang approve dan kapan

## Business Logic

### Saat Pinjaman Disetujui (Approve):
- Status approval berubah dari "pending" → "approved"
- Field `approved_by` diisi dengan user ID petugas
- Field `approved_at` diisi dengan waktu saat ini
- Field `approval_notes` disimpan jika ada
- Status transaksi berubah menjadi "selesai"

### Saat Pinjaman Ditolak (Reject):
- Status approval berubah dari "pending" → "rejected"
- Field `approved_by` diisi dengan user ID petugas
- Field `approved_at` diisi dengan waktu saat ini
- Field `approval_notes` diisi wajib dengan alasan penolakan
- Status transaksi tetap "pending"

### Validasi:
- Hanya transaksi dengan status approval "pending" yang bisa diproses
- Alasan penolakan wajib diisi
- Catatan persetujuan opsional

## UI/UX Features

### Badge Status
- **Warning (Kuning)**: Pending approval
- **Success (Hijau)**: Disetujui
- **Danger (Merah)**: Ditolak

### Quick Navigation
- Badge dengan angka di sidebar menunjukkan jumlah pending
- Quick stats dashboard
- Navigation breadcrumb pada detail halaman

### Modal Approval
- Quick approve modal dari list view
- Confirmation dialog sebelum reject
- Feedback success/error message

## Middleware & Permission

Routes dilindungi oleh middleware:
- `auth` - User harus login
- `petugas` - User harus memiliki role "petugas"

## Testing Instructions

### Test 1: Approve Pinjaman
1. Login sebagai admin dan buat pinjaman dengan customer
2. Logout dan login sebagai petugas
3. Buka menu "Menunggu Approval"
4. Klik detail transaksi
5. Tambahkan catatan dan klik "Setujui"
6. ✓ Status harus berubah menjadi "Disetujui"

### Test 2: Reject Pinjaman
1. Ikuti langkah 1-4 dari Test 1
2. Scroll ke form "Tolak Pinjaman"
3. Isi alasan penolakan
4. Klik "Tolak Pinjaman"
5. ✓ Status harus berubah menjadi "Ditolak"

### Test 3: View History
1. Login sebagai petugas
2. Klik "Riwayat Approval"
3. ✓ Lihat semua transaksi yang sudah diapprove/reject
4. Klik detail untuk melihat informasi lebih lengkap

### Test 4: Filter & Search
1. Server "Semua Transaksi"
2. Coba search nama customer
3. Coba filter berdasarkan approval status
4. Coba kombinasi filter
5. ✓ Hasil filter harus akurat

## Future Enhancements

1. **Notification System**
   - Email/SMS notifikasi ke customer saat pinjaman disetujui/ditolak
   - Notifikasi push ke petugas saat ada pinjaman baru

2. **Batch Approval**
   - Menyetujui multiple pinjaman sekaligus
   - Bulk action pada list view

3. **Approval Comments**
   - Sistem komentar antara petugas dan admin
   - Discussion thread untuk edge cases

4. **Approval Timeline**
   - Timeline history perubahan status
   - Audit log untuk compliance

5. **Advanced Analytics**
   - Dashboard analytics approval rate
   - Average approval time
   - Petugas performance metrics

6. **Automatic Approval**
   - Rule-based auto approval untuk low-risk transactions
   - Configurable approval thresholds

## Setup Checklist

✅ Database migration executed
✅ Model updated dengan relationships & scopes
✅ Controller created: PetugasTransaksiController
✅ Routes added untuk approval management
✅ Views created (4 blade templates)
✅ Sidebar menu updated
✅ UI styling implemented
✅ Permission middleware applied
✅ Validation implemented

## Notes

- Approval hanya bisa dilakukan oleh user dengan role "petugas"
- Setiap approval tercatat dengan siapa yang approve dan kapannya
- Catatan approval untuk audit trail dan komunikasi
- Proses approval tidak bisa dibatalkan setelah diproses
