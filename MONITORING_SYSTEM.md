# Sistem Monitoring Peminjaman untuk Petugas

## Deskripsi Fitur
Sistem monitoring memungkinkan petugas untuk memantau semua kegiatan peminjaman secara real-time dengan dashboard interaktif, statistik lengkap, dan visualisasi data.

## Fitur-Fitur Utama

### 1. **Dashboard Overview**
- Statistik peminjaman real-time:
  - Total peminjaman
  - Peminjaman menunggu approval
  - Peminjaman disetujui
  - Peminjaman ditolak
  - Peminjaman selesai
  - Peminjaman pending

### 2. **Visualisasi Data (Charts)**
- **Chart Approval Status**: Distribusi pie chart status approval (pending/approved/rejected)
- **Chart Transaksi Status**: Distribusi pie chart status transaksi (selesai/pending)
- **Chart Daily Loans**: Line chart peminjaman 7 hari terakhir
- Semua chart interaktif dan responsive

### 3. **Nilai Peminjaman**
- Total nilai peminjaman yang selesai
- Total nilai peminjaman yang masih pending
- Info box dengan visualisasi breakdown

### 4. **List Peminjaman Aktif**
- Tampil top 10 peminjaman yang masih berjalan
- Quick action untuk melihat detail
- Customer info singkat

### 5. **Peminjaman Hari Ini**
- Real-time list peminjaman yang dilakukan hari ini
- Status approval badge
- Tracking aktivitas harian

### 6. **Top 5 Customer (Sering Pinjam)**
- Menampilkan customer yang paling sering melakukan peminjaman
- Jumlah kali pinjam untuk setiap customer
- Quick reference untuk customer terbaik

### 7. **Detail Monitoring**
- Halaman detail untuk setiap peminjaman
- Timeline lengkap peminjaman (created → approval)
- Informasi customer
- Status approval dengan informasi petugas
- Catatan approval jika ada
- Quick action buttons

## Database Schema
Menggunakan kolom yang sudah ada di tabel `transaksis`:
- `approval_status` - Status approval
- `approved_by` - ID petugas yang approve
- `approved_at` - Waktu approval
- `approval_notes` - Catatan approval
- `status` - Status transaksi
- `total` - Nilai peminjaman

## Model & Query

### Transaksi Model
```php
// Scopes
public function scopePendingApproval($query)
public function scopeApproved($query)
public function scopeRejected($query)

// Relationships
public function approvedBy()
public function user()
public function details()
```

### User Model
```php
public function transaksis()
{
    return $this->hasMany(Transaksi::class);
}
```

## Controller Methods

### PetugasTransaksiController

```php
// Dashboard monitoring dengan semua statistik dan chart
public function monitoring()

// Detail monitoring untuk transaksi spesifik
public function monitoringDetail($id)
```

## Routes

```php
GET  /petugas/monitoring           - Dashboard monitoring (petugas.monitoring.index)
GET  /petugas/monitoring/{id}      - Detail monitoring transaksi (petugas.monitoring.detail)
```

## Views

### monitoring/index.blade.php
- Statistics cards (4 main metrics)
- Status charts (Approval & Transaksi)
- Daily loans line chart
- Nilai peminjaman info boxes
- Active loans table
- Today's loans table
- Top 5 customers showcase

### monitoring/detail.blade.php
- Informasi peminjaman lengkap
- Detail alat yang dipinjam
- Status approval badge
- Catatan approval
- Timeline interaktif
- Action buttons

## UI Components

### Statistics Cards
Menampilkan 4 metric utama dengan:
- Icon
- Jumlah
- Deskripsi
- Quick link ke halaman terkait

### Chart Visualization
Menggunakan Chart.js untuk:
- Doughnut chart (pie chart) untuk approval status
- Doughnut chart untuk transaksi status
- Line chart untuk daily trends

### Tables
- Responsive tables untuk list peminjaman
- Hover effect untuk interaktivitas
- Action buttons (view detail)

### Timeline Component
- Visual timeline untuk perjalanan peminjaman
- Status badges dengan warna berbeda
- Information pada setiap milestone
- Last update indicator

## File Structure

```
app/Http/Controllers/
  └─ PetugasTransaksiController.php (UPDATED)
     ├─ monitoring()
     └─ monitoringDetail()

app/Models/
  ├─ Transaksi.php (UPDATED - dengan scopes)
  └─ User.php (UPDATED - dengan transaksis relationship)

database/
  └─ (No migration needed - uses existing columns)

resources/views/petugas/monitoring/
  ├─ index.blade.php (NEW - dashboard)
  └─ detail.blade.php (NEW - detail monitoring)

resources/views/petugas/layouts/
  └─ sidebar.blade.php (UPDATED - add monitoring menu)

routes/
  └─ web.php (UPDATED - 2 new routes)
```

## Alur Penggunaan

### Melihat Dashboard Monitoring:
1. Login sebagai petugas
2. Klik "Monitoring Pinjaman" di sidebar
3. Lihat overview dengan 4 metric utama
4. Scroll untuk melihat charts dan detail

### Melihat Detail Peminjaman:
1. Di dashboard monitoring, cari peminjaman di list
2. Klik "Lihat" atau ikon mata di list "Peminjaman Aktif"
3. Lihat detail lengkap dengan timeline dan catatan
4. Klik "Detail Approval" untuk edit/process

### Menggunakan Charts:
1. Hover di chart untuk melihat value
2. Click di legend untuk toggle series
3. Semua chart responsive dan live update

## JavaScript Dependencies

- **Chart.js**: Untuk visualisasi chart
- **CDN**: https://cdn.jsdelivr.net/npm/chart.js

## Data Real-time

### Statistics diambil dari:
- Total transaksi: `Transaksi::count()`
- Pending approval: `Transaksi::pendingApproval()->count()`
- Approved: `Transaksi::approved()->count()`
- Rejected: `Transaksi::rejected()->count()`
- Selesai: `Transaksi::where('status', 'selesai')->count()`
- Pending: `Transaksi::where('status', 'pending')->count()`

### Chart Data:
- Last 7 days: Loop untuk setiap hari
- Approval distribution: Grouped by approval_status
- Transaksi distribution: Grouped by status
- Top customers: withCount relationship

## Performance Considerations

1. **Query Optimization**:
   - Menggunakan `with()` untuk eager loading
   - Menggunakan `withCount()` untuk count efficient

2. **Data Limitation**:
   - Top 10 active loans (not all)
   - Top 5 customers
   - 7 days history

3. **Caching (Future)**:
   - Cache statistics untuk 5 menit
   - Cache chart data untuk 10 menit

## Middleware & Permission

Routes dilindungi oleh:
- `auth` - User harus login
- `petugas` - User harus memiliki role "petugas"

## Styling

- Bootstrap admin theme (existing)
- Font Awesome icons
- Custom CSS untuk timeline
- Responsive design

## Testing Instructions

### Test 1: Akses Dashboard
1. Login sebagai petugas
2. Klik "Monitoring Pinjaman"
3. ✓ Harus muncul dashboard dengan 4 metric
4. ✓ Charts harus load dengan data

### Test 2: Lihat Peminjaman Hari Ini
1. Buat beberapa transaksi sebagai customer
2. Login sebagai petugas
3. Buka monitoring
4. ✓ Scroll ke "Peminjaman Hari Ini"
5. ✓ Harus ada list transaksi hari ini

### Test 3: Lihat Detail Peminjaman
1. Di dashboard, klik detail peminjaman aktif
2. ✓ Halaman detail harus muncul dengan timeline
3. ✓ Status approval harus ditampilkan
4. ✓ Klik "Detail Approval" harus ke halaman approval

### Test 4: Chart Interaktif
1. Di dashboard, hover di chart
2. ✓ Tooltip harus muncul
3. ✓ Click di legend untuk hide/show data
4. ✓ Chart should be responsive

### Test 5: Top Customers
1. Buat multiple transaksi dengan customer berbeda
2. ✓ Klik monitoring
3. ✓ Lihat "Top 5 Customer"
4. ✓ Harus sorted berdasarkan jumlah pinjam

## Future Enhancements

1. **Real-time Updates**
   - WebSocket untuk live update
   - Pusher integration untuk notifications

2. **Advanced Filtering**
   - Filter by date range
   - Filter by customer
   - Filter by alat

3. **Export Reports**
   - Export to PDF
   - Export to Excel
   - Export to CSV

4. **Predictive Analytics**
   - Forecast demand
   - Customer churn prediction
   - Anomaly detection

5. **Performance Metrics**
   - Average approval time
   - Customer satisfaction score
   - Staff efficiency metrics

6. **Custom Dashboard**
   - Drag and drop widgets
   - Personalized view
   - Widget library

7. **Alerts & Notifications**
   - Alert untuk pending approval
   - Alert untuk overdue loans
   - Alert untuk inactive customers

8. **Mobile Monitoring**
   - Mobile responsive
   - Mobile app
   - Notification push

## Setup Checklist

✅ Controller methods added (monitoring & monitoringDetail)
✅ Model relationships updated (User::transaksis)
✅ Routes added (2 new routes)
✅ Views created (2 blade templates)
✅ Sidebar menu updated with monitoring link
✅ Chart.js integrated
✅ JavaScript for chart initialization
✅ UI styling completed
✅ Permission middleware applied
✅ No database migration needed

## Security Notes

- Routes protected by `auth` & `petugas` middleware
- Query builder tidak exposed
- Semua data sudah dalam database
- No SQL injection risk
- Proper relationships untuk data integrity

## Performance Tips

1. Cache statistics untuk reduce DB queries
2. Limit data dalam charts (7 days)
3. Limit list items (top 10 active, top 5 customers)
4. Use pagination untuk large datasets
5. Optimize N+1 queries dengan eager loading

## Troubleshooting

### Chart tidak muncul
- Check Chart.js CDN loaded
- Check console for JS errors
- Check data format (array/object)

### Data palsu
- Ensure migrations executed
- Check if transaksi sudah ada di DB
- Check scope methods di model

### Permission denied
- Ensure user has "petugas" role
- Check auth middleware active
- Check session active
