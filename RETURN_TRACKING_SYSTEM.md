# Sistem Monitoring Pengembalian Peminjaman untuk Petugas

## Deskripsi Fitur
Sistem monitoring pengembalian memungkinkan petugas untuk memantau status pengembalian alat peminjaman, tracking overdue, dan mengkonfirmasi kondisi alat yang dikembalikan.

## Fitur-Fitur Utama

### 1. **Dashboard Pengembalian** (`/petugas/monitoring-return`)
Menampilkan:
- **4 Statistic Cards**: 
  - Total Peminjaman Aktif (Approved)
  - Menunggu Pengembalian (Pending)
  - OVERDUE (Telat)
  - Sudah Dikembalikan
  
- **2 Interactive Charts**:
  - 📊 Status Pengembalian (Pie Chart)
  - 📈 Pengembalian 7 Hari (Line Chart)

- **Upcoming Returns**: Peminjaman akan jatuh tempo dalam 3 hari ke depan
- **Overdue Loans**: List peminjaman yang telat/belum dikembalikan
- **Pending Returns**: List menunggu pengembalian dengan pagination
- **Riwayat Pengembalian**: List alat yang sudah dikembalikan

### 2. **Detail Pengembalian** (`/petugas/monitoring-return/{id}`)
- Informasi customer lengkap
- Detail alat yang dipinjam
- Status pengembalian badge
- **Form Konfirmasi Pengembalian**:
  - Pilih kondisi alat (Baik / Rusak Ringan / Rusak Berat)
  - Catatan pengembalian (opsional)
  - Tombol submit konfirmasi
- **Mark as Overdue** untuk tepat waktu
- Timeline visual

## Database Schema

### Kolom Baru di Tabel `transaksis`:
```sql
- due_date (date) NULLABLE - Tanggal jatuh tempo pengembalian
- return_status (enum: pending/returned/overdue) DEFAULT 'pending' - Status pengembalian
- returned_at (timestamp) NULLABLE - Tanggal & waktu pengembalian aktual
- kondisi (enum: baik/rusak_ringan/rusak_berat) DEFAULT 'baik' - Kondisi alat saat dikembalikan
- return_notes (text) NULLABLE - Catatan pengembalian/kondisi alat
```

## Model & Scopes

### Transaksi Model
```php
// Scopes untuk return tracking
public function scopePendingReturn($query)  // return_status = pending
public function scopeOverdue($query)        // return_status = overdue
public function scopeReturned($query)       // return_status = returned

// Casts
protected $casts = [
    'due_date' => 'date',
    'returned_at' => 'datetime',
];
```

## Controller Methods

### PetugasTransaksiController

```php
// Dashboard monitoring pengembalian dengan statistik & charts
public function monitoringReturn()

// Halaman detail untuk konfirmasi pengembalian
public function returnDetail($id)

// Proses konfirmasi pengembalian alat
public function processReturn(Request $request, $id)

// Mark peminjaman sebagai overdue
public function markOverdue(Request $request, $id)
```

## Routes

```php
GET  /petugas/monitoring-return              - Dashboard return (petugas.monitoring.return)
GET  /petugas/monitoring-return/{id}         - Detail return (petugas.monitoring.return-detail)
POST /petugas/monitoring-return/{id}/process - Process return (petugas.monitoring.process-return)
POST /petugas/monitoring-return/{id}/overdue - Mark overdue (petugas.monitoring.mark-overdue)
```

## Views

### monitoring/return.blade.php
- 4 Statistics cards dengan badges
- Pie chart status pengembalian
- Line chart pengembalian 7 hari
- List akan jatuh tempo (upcoming)
- List overdue dengan hari telat
- List menunggu pengembalian (pagination)
- List riwayat pengembalian (pagination)
- Chart.js integration

### monitoring/return-detail.blade.php
- Informasi peminjaman lengkap
- Detail alat dengan table
- Catatan pengembalian jika ada
- Status badge
- Badge kondisi (jika sudah dikembalikan)
- Form konfirmasi pengembalian:
  - Select kondisi (required)
  - Textarea catatan (optional)
- Mark as Overdue button
- Action button kembali

## Business Logic

### Return Status Tracking:
- **Pending**: Alat sudah disetujui tapi belum dikembalikan
- **Returned**: Alat sudah dikembalikan & dikonfirmasi petugas
- **Overdue**: Alat belum dikembalikan & sudah lewat due date

### Saat Mengonfirmasi Pengembalian:
- `return_status` berubah dari "pending" → "returned"
- `returned_at` diisi dengan waktu saat ini
- `kondisi` diisi sesuai pilihan (baik/rusak_ringan/rusak_berat)
- `return_notes` disimpan jika ada

### Mark as Overdue:
- `return_status` berubah dari "pending" → "overdue"
- Digunakan jika sudah lewat due_date tapi belum dikembalikan
- Bisa di-override saat konfirmasi pengembalian

## UI Components

### Statistics Cards
- Icon dan warna berbeda untuk setiap status
- Jumlah real-time
- Quick link ke halaman terkait

### Chart Visualization
- Pie chart untuk status distribution (pending/returned/overdue)
- Line chart untuk daily trends (7 hari terakhir)
- Menggunakan Chart.js

### Status Badges
- 🟡 Warning (Pending / Upcoming)
- 🔴 Danger (Overdue)
- 🟢 Success (Returned)

### Kondisi Badges
- 🟢 Baik (Green/Success)
- 🟡 Rusak Ringan (Yellow/Warning)
- 🔴 Rusak Berat (Red/Danger)

## File Structure

```
app/Http/Controllers/
  └─ PetugasTransaksiController.php (UPDATED)
     ├─ monitoringReturn()
     ├─ returnDetail()
     ├─ processReturn()
     └─ markOverdue()

app/Models/
  └─ Transaksi.php (UPDATED)
     ├─ scopePendingReturn()
     ├─ scopeOverdue()
     ├─ scopeReturned()
     └─ casts (due_date, returned_at)

database/migrations/
  └─ 2026_03_10_000001_add_return_tracking_to_transaksis_table.php (NEW)

resources/views/petugas/monitoring/
  ├─ return.blade.php (NEW - dashboard)
  └─ return-detail.blade.php (NEW - detail & form)

resources/views/petugas/layouts/
  └─ sidebar.blade.php (UPDATED - add return menu)

routes/
  └─ web.php (UPDATED - 4 new routes)
```

## Alur Penggunaan

### Melihat Dashboard Pengembalian:
1. Login sebagai petugas
2. Klik "Monitoring Pengembalian" di sidebar
3. Lihat overview dengan 4 metric
4. Lihat list:
   - Akan jatuh tempo (3 hari ke depan)
   - Overdue (telat)
   - Menunggu pengembalian
   - Riwayat pengembalian

### Konfirmasi Pengembalian:
1. Di dashboard atau dari list, klik "Proses" atau detail
2. Scroll ke form "Konfirmasi Pengembalian"
3. Pilih kondisi alat (wajib):
   - ✓ Baik
   - ⚠ Rusak Ringan
   - ⚠ Rusak Berat
4. Isi catatan jika ada kerusakan (opsional)
5. Klik "Konfirmasi Pengembalian"
6. Status berubah ke "Returned"

### Mark as Overdue:
1. Saat melihat detail pengembalian
2. Jika sudah lewat due date tapi belum dikembalikan
3. Klik "Mark as Overdue"
4. Konfirmasi
5. Status berubah ke "Overdue"

## Data Query

### Statistik diambil dari:
- Total Approved: `Transaksi::approved()->count()`
- Pending Return: `Transaksi::pendingReturn()->count()`
- Overdue: `Transaksi::overdue()->count()`
- Returned: `Transaksi::returned()->count()`

### List Data:
- Upcoming: `due_date` > today AND due_date <= today + 3 days
- Overdue: `return_status` = 'overdue'
- Pending: `return_status` = 'pending'
- Returned: `return_status` = 'returned'

### Chart Data:
- Last 7 days: Loop untuk setiap hari
- Return distribution: Grouped by return_status

## Middleware & Permission

Routes dilindungi oleh:
- `auth` - User harus login
- `petugas` - User harus memiliki role "petugas"

## Styling & UI

- Bootstrap admin theme
- Font Awesome icons
- Responsive tables
- Custom badge styling untuk kondisi
- Chart.js untuk visualisasi

## Testing Instructions

### Test 1: Akses Dashboard
1. Login sebagai petugas
2. Klik "Monitoring Pengembalian" di sidebar
3. ✓ Harus muncul dashboard dengan 4 metric
4. ✓ Charts harus load dengan data
5. ✓ Lists harus tampil

### Test 2: Konfirmasi Pengembalian
1. Di dashboard return, klik "Proses" pada peminjaman pending
2. Scroll ke form konfirmasi
3. Pilih kondisi "Baik"
4. Isi catatan "Alat dalam kondisi baik"
5. Klik "Konfirmasi Pengembalian"
6. ✓ Status harus berubah ke "Returned"
7. ✓ Returned at harus terisi
8. ✓ Redirect ke dashboard dengan success message

### Test 3: Mark as Overdue
1. Di dashboard return, lihat peminjaman pending
2. Klik detail
3. Scroll ke "Mark as Overdue"
4. Klik button & konfirmasi
5. ✓ Status berubah ke "Overdue"
6. ✓ Alert success muncul

### Test 4: Filter & Lists
1. Dashboard return
2. Check "Akan Jatuh Tempo" (3 hari)
3. Check "Overdue" list
4. Check "Menunggu Pengembalian" (with pagination)
5. Check "Riwayat Pengembalian"
6. ✓ Semua list harus sesuai dengan status

### Test 5: Upcoming Detection
1. Buat peminjaman dengan due_date besok
2. Login petugas
3. Buka monitoring return
4. ✓ Harus ada di "Akan Jatuh Tempo"
5. ✓ Harus show hari tersisa

## Future Enhancements

1. **Automatic Overdue Detection**
   - Cron job untuk auto-mark overdue
   - Real-time status update

2. **Denda (Fine System)**
   - Calculate denda based on kondisi
   - Track pembayaran denda
   - Report keuangan

3. **Notifications**
   - Email reminder sebelum due date
   - SMS/Push untuk overdue
   - Notification untuk petugas

4. **Damage Report**
   - Upload foto kondisi alat
   - Detailed damage description
   - Repair status tracking

5. **Return Form Customization**
   - Custom fields untuk setiap kategori alat
   - QR code scanning untuk verification
   - Digital signature confirmation

6. **Analytics**
   - Return rate analysis
   - Common damage patterns
   - Customer behavior insights
   - Staff performance metrics

7. **Integration**
   - Export return report
   - Print return slip
   - Integration dengan accounting system

## Setup Checklist

✅ Migration created (return_tracking columns)
✅ Migration executed successfully
✅ Model updated dengan scopes & casts
✅ Controller methods added (4 methods)
✅ Routes added (4 new routes)
✅ Views created (2 blade templates)
✅ Sidebar menu updated with return link + badge
✅ Chart.js integrated
✅ JavaScript for chart initialization
✅ UI styling completed
✅ Middleware applied
✅ No errors found

## Notes

- Return status otomatis pending saat transaksi approved
- Due date perlu di-set manual atau dari durasi peminjaman
- Kondisi alat untuk tracking damage report
- Overdue untuk peminjaman yang belum kembali & lewat due date
- Catatan pengembalian untuk dokumentasi

## Database Integrity

- Foreign key relationships maintained
- Cascade delete tidak ada (keep history)
- Timestamp otomatis oleh system
- Enum constraints di database untuk data integrity

## Performance

- Pagination untuk large datasets
- Eager loading dengan `with()`
- Index pada `return_status` dan `due_date`
- Limit query untuk dashboard data

## Security

- Routes protected by middleware
- No SQL injection risk
- Proper validation di controller
- Authorized user only (petugas role)
