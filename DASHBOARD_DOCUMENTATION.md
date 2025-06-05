# Dokumentasi Dashboard Multi-Role Filament

## Overview
Sistem dashboard yang telah dibuat menyediakan tampilan yang berbeda untuk setiap role pengguna dalam aplikasi Filament. Setiap role memiliki widget dan quick actions yang sesuai dengan kebutuhan dan permission mereka.

## Struktur Role dan Dashboard

### 1. Super Admin Dashboard
**Path**: `/backoffice` (untuk super_admin)

**Widget yang ditampilkan**:
- `SystemOverviewWidget`: Statistik umum sistem (total pengguna, karyawan, kehadiran hari ini, cuti pending)
- `UserStatsWidget`: Chart distribusi pengguna berdasarkan role
- `ActivityLogWidget`: Log aktivitas terbaru dari seluruh sistem
- `EmployeeStatsWidget`: Statistik karyawan (untuk monitoring)
- `AttendanceOverviewWidget`: Overview kehadiran
- `FinancialOverviewWidget`: Overview keuangan

**Fitur**:
- Akses ke semua data sistem
- Monitor aktivitas seluruh pengguna
- Statistik lengkap semua modul

### 2. HRD Dashboard
**Path**: `/backoffice` (untuk role hrd)

**Widget yang ditampilkan**:
- `EmployeeStatsWidget`: Statistik karyawan (total, aktif, jabatan, karyawan baru)
- `AttendanceOverviewWidget`: Chart kehadiran 7 hari terakhir
- `LeaveRequestsWidget`: Tabel permintaan cuti yang pending dengan aksi approve/reject

**Quick Actions**:
- Kelola Karyawan
- Monitor Kehadiran
- Kelola Cuti Karyawan
- Kelola Jabatan

**Fitur**:
- Fokus pada manajemen SDM
- Approval cuti langsung dari dashboard
- Monitoring kehadiran karyawan

### 3. CFO Dashboard
**Path**: `/backoffice` (untuk role cfo)

**Widget yang ditampilkan**:
- `FinancialOverviewWidget`: Overview keuangan (total penggajian, karyawan terbayar, rata-rata gaji)
- `PayrollStatsWidget`: Chart distribusi gaji berdasarkan jabatan
- `MonthlyRevenueChart`: Chart pengeluaran gaji 6 bulan terakhir

**Quick Actions**:
- Kelola Penggajian
- Lihat Data Karyawan
- Laporan Keuangan

**Fitur**:
- Fokus pada aspek finansial
- Analisis pengeluaran gaji
- Monitoring budget SDM

### 4. Karyawan Dashboard
**Path**: `/backoffice` (untuk role karyawan)

**Widget yang ditampilkan**:
- `PersonalAttendanceWidget`: Statistik kehadiran personal (bulan ini, hari ini, minggu ini, persentase)
- `PersonalLeaveWidget`: Tabel riwayat cuti dan izin personal
- `PersonalPayrollWidget`: Informasi gaji personal (bulan ini, bulan lalu, total tahun ini, gaji pokok)

**Quick Actions**:
- Presensi (Absen masuk/keluar)
- Ajukan Cuti
- Lihat Slip Gaji

**Fitur**:
- Data personal saja
- Self-service untuk absensi dan cuti
- Transparansi informasi gaji

## File Structure

```
app/Filament/
├── Pages/
│   └── Dashboard.php                    # Main dashboard controller
├── Widgets/
│   ├── SuperAdmin/
│   │   ├── SystemOverviewWidget.php     # Stats overview untuk admin
│   │   ├── UserStatsWidget.php          # Chart distribusi user
│   │   └── ActivityLogWidget.php        # Log aktivitas sistem
│   ├── HRD/
│   │   ├── EmployeeStatsWidget.php      # Stats karyawan
│   │   ├── AttendanceOverviewWidget.php # Chart kehadiran
│   │   └── LeaveRequestsWidget.php      # Tabel cuti pending
│   ├── CFO/
│   │   ├── FinancialOverviewWidget.php  # Overview keuangan
│   │   ├── PayrollStatsWidget.php       # Chart gaji per jabatan
│   │   └── MonthlyRevenueChart.php      # Chart pengeluaran bulanan
│   └── Karyawan/
│       ├── PersonalAttendanceWidget.php # Stats kehadiran personal
│       ├── PersonalLeaveWidget.php      # Riwayat cuti personal
│       └── PersonalPayrollWidget.php    # Info gaji personal

resources/views/filament/pages/
└── dashboard.blade.php                  # Custom dashboard view
```

## Fitur Keamanan

### Permission-based Access
- Setiap widget memiliki method `canView()` yang mengecek role pengguna
- Data yang ditampilkan disesuaikan dengan permission masing-masing role
- Karyawan hanya bisa melihat data mereka sendiri

### Data Filtering
- **Super Admin**: Akses semua data
- **HRD**: Data karyawan dan kehadiran
- **CFO**: Data keuangan dan penggajian
- **Karyawan**: Hanya data personal (user_id = Auth::id())

## Customization

### Menambah Widget Baru
1. Buat widget baru di direktori role yang sesuai
2. Implement method `canView()` untuk permission
3. Tambahkan widget ke array di `Dashboard.php`

### Mengubah Layout
- Edit `resources/views/filament/pages/dashboard.blade.php`
- Sesuaikan quick actions untuk setiap role
- Ubah styling sesuai kebutuhan

### Menambah Role Baru
1. Buat direktori widget baru di `app/Filament/Widgets/[RoleName]/`
2. Tambahkan kondisi role di `Dashboard.php`
3. Buat widget sesuai kebutuhan role tersebut

## Dependencies

Widget ini menggunakan model-model berikut:
- `User`: Data pengguna dan role
- `Karyawan`: Data karyawan
- `Attendance`: Data kehadiran
- `Leave`: Data cuti dan izin
- `Penggajian`: Data penggajian
- `Jabatan`: Data jabatan

## Notes

1. **Performance**: Widget menggunakan query yang dioptimasi dengan limit dan filtering
2. **Real-time**: Data diupdate setiap kali dashboard direfresh
3. **Responsive**: Layout menggunakan grid system yang responsive
4. **Localization**: Semua text menggunakan bahasa Indonesia
5. **Icons**: Menggunakan Heroicons untuk konsistensi dengan Filament

## Testing

Untuk testing dashboard:
1. Login dengan user yang memiliki role berbeda
2. Verifikasi widget yang muncul sesuai dengan role
3. Pastikan data yang ditampilkan sesuai dengan permission
4. Test quick actions untuk setiap role
