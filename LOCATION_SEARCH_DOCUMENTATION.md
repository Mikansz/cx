# Dokumentasi Fitur Pencarian Lokasi

## Overview
Fitur pencarian lokasi yang telah diimplementasikan memungkinkan pengguna untuk mencari, menemukan, dan memilih lokasi dengan mudah menggunakan berbagai metode pencarian dan peta interaktif.

## Komponen yang Dibuat

### 1. Livewire Component: LocationSearch
**File**: `app/Livewire/LocationSearch.php`

**Fitur**:
- Pencarian lokasi menggunakan Nominatim API (OpenStreetMap)
- Autocomplete dengan debounce untuk performa optimal
- Geolocation untuk mendapatkan lokasi saat ini
- Reverse geocoding untuk mendapatkan alamat dari koordinat
- Integrasi dengan peta Leaflet

**Methods**:
- `searchLocation()`: Mencari lokasi berdasarkan query
- `selectLocation()`: Memilih lokasi dari hasil pencarian
- `getCurrentLocation()`: Mendapatkan lokasi saat ini
- `setCurrentLocation()`: Set lokasi berdasarkan koordinat

### 2. Halaman Pencarian Lokasi
**File**: `app/Filament/Pages/LocationSearchPage.php`

**Fitur**:
- Halaman khusus untuk pencarian lokasi
- Instruksi penggunaan yang jelas
- Lokasi populer untuk akses cepat
- Tips dan informasi pencarian

**Akses**: Super Admin, HRD, CFO

### 3. Widget Pencarian Lokasi
**File**: `app/Filament/Widgets/LocationSearchWidget.php`

**Fitur**:
- Widget kompak untuk dashboard
- Quick actions untuk berbagai fungsi
- Integrasi dengan halaman lengkap
- Lokasi populer

### 4. Custom Form Field
**File**: `app/Filament/Components/LocationSearchField.php`

**Fitur**:
- Field khusus untuk form Filament
- Integrasi dengan peta
- Validasi koordinat
- Support untuk default location

## Cara Penggunaan

### 1. Menggunakan Livewire Component

```blade
@livewire('location-search', ['mapId' => 'unique-map-id'])
```

**Parameters**:
- `mapId`: ID unik untuk peta (opsional)

### 2. Menggunakan Widget di Dashboard

Widget sudah otomatis ditambahkan ke dashboard untuk role:
- Super Admin
- HRD  
- CFO

### 3. Menggunakan Custom Form Field

```php
use App\Filament\Components\LocationSearchField;

LocationSearchField::make('location')
    ->label('Lokasi')
    ->mapId('office-location')
    ->defaultLocation(-6.2088, 106.8456)
    ->zoom(13)
```

### 4. Akses Halaman Pencarian

**URL**: `/backoffice/location-search-page`

**Atau melalui navigasi**: Manajemen Lokasi > Pencarian Lokasi

## Fitur Pencarian

### 1. Pencarian Teks
- **Input**: Nama tempat, alamat, atau landmark
- **Contoh**: "Jakarta", "Jl. Sudirman", "Monas"
- **API**: Nominatim OpenStreetMap
- **Debounce**: 500ms untuk performa optimal

### 2. Lokasi Saat Ini
- **Metode**: HTML5 Geolocation API
- **Akurasi**: High accuracy mode
- **Timeout**: 10 detik
- **Fallback**: Manual input koordinat

### 3. Klik Peta
- **Metode**: Click event pada peta Leaflet
- **Reverse Geocoding**: Otomatis mendapatkan alamat
- **Marker**: Otomatis ditempatkan di lokasi

### 4. Lokasi Populer
- Jakarta (-6.2088, 106.8456)
- Bandung (-6.9175, 107.6191)
- Surabaya (-7.2575, 112.7521)
- Yogyakarta (-7.7956, 110.3695)
- Medan (3.5952, 98.6722)
- Semarang (-6.9667, 110.4167)

## Integrasi dengan Sistem

### 1. Dashboard Integration
Widget pencarian lokasi ditambahkan ke dashboard untuk:
- **Super Admin**: Monitoring dan manajemen
- **HRD**: Manajemen lokasi kantor dan karyawan
- **CFO**: Analisis lokasi untuk keperluan finansial

### 2. Map Integration
Fitur pencarian terintegrasi dengan:
- **Peta Kehadiran**: `/backoffice/map`
- **Form Office**: Untuk menambah/edit lokasi kantor
- **Widget Dashboard**: Quick access

### 3. Navigation Integration
- **Menu Group**: Manajemen Lokasi
- **Sort Order**: 10 (setelah menu utama)
- **Icon**: Map Pin (heroicon-o-map-pin)

## API dan Dependencies

### 1. Nominatim API
- **Provider**: OpenStreetMap
- **Endpoint**: `https://nominatim.openstreetmap.org/`
- **Rate Limit**: 1 request per second
- **Coverage**: Global dengan fokus Indonesia

### 2. Leaflet.js
- **Version**: 1.9.4
- **CDN**: unpkg.com
- **Features**: Interactive maps, markers, popups

### 3. Browser APIs
- **Geolocation API**: Untuk lokasi saat ini
- **Fetch API**: Untuk HTTP requests
- **Local Storage**: Untuk caching (future enhancement)

## Konfigurasi

### 1. Default Settings
```php
// Default coordinates (Jakarta)
$defaultLat = -6.2088;
$defaultLng = 106.8456;
$defaultZoom = 13;

// Search settings
$minQueryLength = 3;
$searchDebounce = 500; // ms
$maxResults = 8;
```

### 2. Map Settings
```javascript
// Tile layer
tileLayer: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

// Attribution
attribution: '&copy; OpenStreetMap contributors'

// Default view
defaultView: [-6.2088, 106.8456]
defaultZoom: 13
```

## Error Handling

### 1. Network Errors
- **Timeout**: 10 detik untuk API calls
- **Retry**: Tidak otomatis (user manual retry)
- **Fallback**: Manual input koordinat

### 2. Geolocation Errors
- **Permission Denied**: Alert dengan pesan error
- **Position Unavailable**: Fallback ke pencarian manual
- **Timeout**: Alert dengan instruksi

### 3. API Errors
- **Rate Limit**: Pesan "Terlalu banyak permintaan"
- **No Results**: Pesan "Tidak ada lokasi ditemukan"
- **Invalid Response**: Fallback ke input manual

## Performance Optimization

### 1. Debouncing
- **Search Input**: 500ms debounce
- **Prevents**: Spam API calls
- **UX**: Smooth typing experience

### 2. Result Limiting
- **Max Results**: 8 items
- **Sorting**: By importance score
- **Filtering**: Indonesia focus (countrycodes=id)

### 3. Caching
- **Browser Cache**: HTTP cache headers
- **Component State**: Livewire state management
- **Map Tiles**: Browser cache

## Security Considerations

### 1. API Security
- **Public API**: Nominatim adalah public API
- **Rate Limiting**: Built-in rate limiting
- **No API Key**: Tidak memerlukan API key

### 2. Input Validation
- **XSS Protection**: Livewire built-in protection
- **SQL Injection**: Tidak ada database query langsung
- **CSRF**: Livewire CSRF protection

### 3. Permission Control
- **Role-based Access**: Hanya role tertentu yang bisa akses
- **Component Level**: Permission check di widget
- **Page Level**: Permission check di halaman

## Troubleshooting

### 1. Peta Tidak Muncul
- **Check**: Leaflet CSS dan JS ter-load
- **Check**: Container memiliki height yang valid
- **Check**: Network connectivity

### 2. Pencarian Tidak Berfungsi
- **Check**: Network connectivity
- **Check**: API endpoint accessible
- **Check**: Query length minimal 3 karakter

### 3. Geolocation Tidak Berfungsi
- **Check**: HTTPS connection (required for geolocation)
- **Check**: Browser permission
- **Check**: Device GPS enabled

## Future Enhancements

### 1. Offline Support
- **Service Worker**: Cache map tiles
- **Local Database**: Store popular locations
- **Sync**: Background sync when online

### 2. Advanced Search
- **Filters**: By type (restaurant, office, etc.)
- **Radius Search**: Within X km from point
- **Category Search**: Business categories

### 3. Integration Enhancements
- **Save Favorites**: User favorite locations
- **Recent Searches**: Search history
- **Team Locations**: Shared team locations

### 4. Performance Improvements
- **Lazy Loading**: Load components on demand
- **Virtual Scrolling**: For large result sets
- **Progressive Loading**: Load map tiles progressively
