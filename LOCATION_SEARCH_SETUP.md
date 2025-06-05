# Setup Fitur Pencarian Lokasi

## Instalasi dan Konfigurasi

### 1. Verifikasi File yang Dibuat

Pastikan semua file berikut sudah ada:

```
app/
├── Livewire/
│   └── LocationSearch.php
├── Filament/
│   ├── Pages/
│   │   └── LocationSearchPage.php
│   ├── Widgets/
│   │   └── LocationSearchWidget.php
│   └── Components/
│       └── LocationSearchField.php

resources/views/
├── livewire/
│   └── location-search.blade.php
├── filament/
│   ├── pages/
│   │   └── location-search.blade.php
│   ├── widgets/
│   │   └── location-search-widget.blade.php
│   └── components/
│       └── location-search-field.blade.php
```

### 2. Clear Cache

```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

### 3. Verifikasi Dependencies

Pastikan Leaflet.js ter-load dengan benar. File sudah menggunakan CDN:

```html
<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

## Testing Fitur

### 1. Test Widget di Dashboard

1. **Login sebagai Super Admin, HRD, atau CFO**
2. **Buka Dashboard**: `/backoffice`
3. **Verifikasi Widget**: Pastikan widget "Pencarian Lokasi Cepat" muncul
4. **Test Pencarian**: Coba ketik nama kota (misal: "Jakarta")
5. **Test Lokasi Saat Ini**: Klik tombol "Lokasi Saya"
6. **Test Klik Peta**: Klik pada peta untuk menandai lokasi

### 2. Test Halaman Pencarian Lengkap

1. **Akses Halaman**: `/backoffice/location-search-page`
2. **Test Pencarian**: Coba berbagai jenis pencarian:
   - Nama kota: "Bandung"
   - Alamat: "Jl. Sudirman Jakarta"
   - Landmark: "Monas"
3. **Test Lokasi Populer**: Klik tombol lokasi populer
4. **Test Geolocation**: Klik "Lokasi Saya"

### 3. Test Integrasi dengan Peta Kehadiran

1. **Buka Peta Kehadiran**: `/backoffice/map`
2. **Verifikasi**: Pastikan ada section "Pencarian Lokasi" di samping filter
3. **Test Pencarian**: Coba cari lokasi dan lihat peta berubah

## Konfigurasi Lanjutan

### 1. Mengubah Default Location

Edit file `app/Livewire/LocationSearch.php`:

```php
// Ubah koordinat default (contoh: Bandung)
public $defaultLat = -6.9175;
public $defaultLng = 107.6191;
public $zoom = 13;
```

### 2. Menambah Lokasi Populer

Edit file `resources/views/filament/pages/location-search.blade.php`:

```php
$popularLocations = [
    ['name' => 'Jakarta', 'lat' => -6.2088, 'lng' => 106.8456],
    ['name' => 'Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
    // Tambah lokasi baru di sini
    ['name' => 'Bali', 'lat' => -8.3405, 'lng' => 115.0920],
];
```

### 3. Mengubah Permission Access

Edit file `app/Filament/Pages/LocationSearchPage.php`:

```php
public static function canAccess(): bool
{
    // Contoh: Hanya Super Admin
    return Auth::user()->hasRole('super_admin');
    
    // Atau semua role
    return true;
    
    // Atau role tertentu
    return Auth::user()->hasAnyRole(['super_admin', 'hrd']);
}
```

### 4. Kustomisasi Widget

Edit file `app/Filament/Widgets/LocationSearchWidget.php`:

```php
// Ubah posisi widget
protected static ?int $sort = 5; // Angka lebih kecil = posisi lebih atas

// Ubah lebar widget
protected int | string | array $columnSpan = 2; // Atau 'full', [2, 4]
```

## Integrasi dengan Form

### 1. Menggunakan di Form Office

Edit `app/Filament/Resources/OfficeResource.php`:

```php
use App\Filament\Components\LocationSearchField;

// Dalam form schema
LocationSearchField::make('location_search')
    ->label('Cari Lokasi')
    ->mapId('office-location-search')
    ->defaultLocation(-6.2088, 106.8456)
    ->zoom(13)
    ->afterStateUpdated(function ($state, Forms\Set $set) {
        if ($state) {
            $location = json_decode($state, true);
            $set('latitude', $location['lat']);
            $set('longitude', $location['lon']);
        }
    }),
```

### 2. Menggunakan di Form Karyawan

```php
LocationSearchField::make('address_search')
    ->label('Cari Alamat')
    ->mapId('employee-address')
    ->afterStateUpdated(function ($state, Forms\Set $set) {
        if ($state) {
            $location = json_decode($state, true);
            $set('alamat', $location['display_name']);
        }
    }),
```

## Troubleshooting

### 1. Widget Tidak Muncul

**Penyebab**: Permission atau role tidak sesuai

**Solusi**:
```php
// Check di LocationSearchWidget.php
public static function canView(): bool
{
    return true; // Sementara untuk testing
}
```

### 2. Peta Tidak Load

**Penyebab**: Leaflet CSS/JS tidak ter-load

**Solusi**:
1. Check network tab di browser
2. Pastikan CDN accessible
3. Coba gunakan CDN alternatif

### 3. Pencarian Tidak Berfungsi

**Penyebab**: API Nominatim blocked atau rate limited

**Solusi**:
1. Check network tab untuk error
2. Coba dari browser lain
3. Tunggu beberapa menit jika rate limited

### 4. Geolocation Tidak Berfungsi

**Penyebab**: Browser permission atau HTTPS required

**Solusi**:
1. Pastikan menggunakan HTTPS
2. Check browser permission
3. Test di browser lain

## Monitoring dan Maintenance

### 1. Performance Monitoring

Monitor penggunaan API:
- **Rate Limit**: 1 request/second untuk Nominatim
- **Response Time**: Biasanya < 1 detik
- **Error Rate**: Monitor 4xx/5xx responses

### 2. User Feedback

Collect feedback tentang:
- **Akurasi Hasil**: Apakah hasil pencarian relevan
- **Performance**: Apakah pencarian cukup cepat
- **Usability**: Apakah interface mudah digunakan

### 3. Updates

Regular updates untuk:
- **Leaflet Version**: Update ke versi terbaru
- **API Changes**: Monitor perubahan Nominatim API
- **Browser Compatibility**: Test di browser terbaru

## Best Practices

### 1. User Experience

- **Loading States**: Selalu tampilkan loading indicator
- **Error Messages**: Berikan pesan error yang jelas
- **Fallback Options**: Sediakan alternatif jika fitur tidak berfungsi

### 2. Performance

- **Debouncing**: Gunakan debounce untuk input
- **Caching**: Cache hasil pencarian yang sering digunakan
- **Lazy Loading**: Load komponen hanya saat dibutuhkan

### 3. Security

- **Input Validation**: Validasi semua input user
- **Rate Limiting**: Implement client-side rate limiting
- **Error Handling**: Handle error dengan graceful degradation

## Support dan Resources

### 1. Documentation

- **Leaflet**: https://leafletjs.com/reference.html
- **Nominatim**: https://nominatim.org/release-docs/develop/api/
- **Livewire**: https://livewire.laravel.com/docs

### 2. Community

- **Laravel Community**: https://laravel.com/community
- **Filament Community**: https://filamentphp.com/community
- **OpenStreetMap**: https://www.openstreetmap.org/

### 3. Alternatives

Jika Nominatim tidak sesuai, alternatif lain:
- **Google Maps API**: Berbayar, lebih akurat
- **Mapbox API**: Freemium, fitur lengkap
- **HERE API**: Enterprise grade
