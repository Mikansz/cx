# Setup Dashboard Multi-Role Filament

## Instalasi dan Konfigurasi

### 1. Persiapan Database
Pastikan database sudah dikonfigurasi dan migration sudah dijalankan:

```bash
php artisan migrate
```

### 2. Setup Roles dan Permissions
Jalankan seeder untuk membuat roles dan permissions:

```bash
php artisan db:seed --class=KaryawanRoleSeeder
php artisan db:seed --class=HRDRoleSeeder
php artisan db:seed --class=CFORoleSeeder
```

### 3. Generate Data Test (Opsional)
Untuk testing dashboard dengan data sample:

```bash
php artisan db:seed --class=DashboardTestDataSeeder
```

Seeder ini akan membuat:
- User untuk setiap role (admin, hrd, cfo, karyawan)
- Data karyawan sample
- Data kehadiran 7 hari terakhir
- Data cuti dan izin
- Data penggajian

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Akun Test yang Dibuat

Setelah menjalankan `DashboardTestDataSeeder`, Anda dapat login dengan:

| Role | Email | Password | Dashboard Features |
|------|-------|----------|-------------------|
| Super Admin | admin@example.com | password | Semua widget, monitoring sistem |
| HRD | hrd@example.com | password | Widget karyawan, kehadiran, cuti |
| CFO | cfo@example.com | password | Widget keuangan, penggajian |
| Karyawan | john@example.com | password | Widget personal, self-service |
| Karyawan | jane@example.com | password | Widget personal, self-service |

## Struktur Dashboard per Role

### Super Admin Dashboard
- **System Overview**: Total users, karyawan, kehadiran hari ini, cuti pending
- **User Stats**: Chart distribusi user berdasarkan role
- **Activity Log**: Log aktivitas terbaru sistem
- **Employee Stats**: Statistik karyawan
- **Attendance Overview**: Chart kehadiran
- **Financial Overview**: Overview keuangan

### HRD Dashboard
- **Employee Stats**: Total karyawan, aktif, jabatan, karyawan baru
- **Attendance Overview**: Chart kehadiran 7 hari terakhir
- **Leave Requests**: Tabel cuti pending dengan aksi approve/reject
- **Quick Actions**: Kelola karyawan, kehadiran, cuti, jabatan

### CFO Dashboard
- **Financial Overview**: Total penggajian, karyawan terbayar, rata-rata gaji
- **Payroll Stats**: Chart distribusi gaji per jabatan
- **Monthly Revenue**: Chart pengeluaran 6 bulan terakhir
- **Quick Actions**: Kelola penggajian, data karyawan, laporan

### Karyawan Dashboard
- **Personal Attendance**: Kehadiran personal (bulan ini, hari ini, persentase)
- **Personal Leave**: Riwayat cuti dan izin personal
- **Personal Payroll**: Info gaji (bulan ini, bulan lalu, total tahun)
- **Quick Actions**: Presensi, ajukan cuti, lihat slip gaji

## Customization

### Menambah Widget Baru

1. **Buat Widget Class**:
```bash
php artisan make:filament-widget NewWidget --stats-overview
```

2. **Tempatkan di direktori role yang sesuai**:
```
app/Filament/Widgets/[RoleName]/NewWidget.php
```

3. **Implement permission check**:
```php
public static function canView(): bool
{
    return Auth::user()->hasRole('role_name');
}
```

4. **Tambahkan ke Dashboard.php**:
```php
// Dalam method getWidgets()
if ($user->hasRole('role_name')) {
    $widgets = [
        // ... widget lain
        NewWidget::class,
    ];
}
```

### Mengubah Quick Actions

Edit file `resources/views/filament/pages/dashboard.blade.php`:

```blade
@if(auth()->user()->hasRole('role_name'))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="/path" class="bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow border border-gray-200">
            <!-- Quick action content -->
        </a>
    </div>
@endif
```

### Menambah Role Baru

1. **Buat Role Seeder**:
```php
// database/seeders/NewRoleSeeder.php
$role = Role::firstOrCreate(['name' => 'new_role']);
$permissions = ['permission1', 'permission2'];
$role->syncPermissions($permissions);
```

2. **Buat direktori widget**:
```bash
mkdir app/Filament/Widgets/NewRole
```

3. **Update Dashboard.php**:
```php
elseif ($user->hasRole('new_role')) {
    $widgets = [
        NewRoleWidget::class,
    ];
}
```

## Troubleshooting

### Widget Tidak Muncul
1. Pastikan user memiliki role yang benar
2. Check method `canView()` di widget
3. Clear cache: `php artisan config:clear`

### Data Tidak Tampil
1. Pastikan relasi model sudah benar
2. Check query di widget
3. Pastikan data ada di database

### Permission Error
1. Pastikan role sudah di-assign ke user
2. Check permission di policy
3. Jalankan `php artisan permission:cache-reset`

### Chart Tidak Muncul
1. Pastikan data untuk chart ada
2. Check format data yang dikembalikan
3. Pastikan JavaScript chart library ter-load

## Performance Tips

1. **Limit Query**: Gunakan `limit()` pada query widget
2. **Cache Data**: Implement caching untuk data yang jarang berubah
3. **Lazy Loading**: Gunakan lazy loading untuk widget yang berat
4. **Index Database**: Pastikan field yang sering di-query memiliki index

## Security Notes

1. **Permission Check**: Setiap widget harus implement `canView()`
2. **Data Filtering**: Filter data berdasarkan user permission
3. **Input Validation**: Validasi semua input dari user
4. **SQL Injection**: Gunakan Eloquent ORM, hindari raw query

## Maintenance

### Update Widget Data
Widget akan otomatis update setiap kali dashboard di-refresh. Untuk real-time update, pertimbangkan menggunakan:
- Livewire polling
- WebSocket
- Server-sent events

### Backup Data
Pastikan backup database secara berkala, terutama data:
- Users dan roles
- Attendance records
- Payroll data
- Leave requests

### Monitoring
Monitor performa dashboard dengan:
- Laravel Telescope (development)
- Application monitoring tools
- Database query monitoring
