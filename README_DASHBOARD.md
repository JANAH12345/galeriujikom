# Dashboard Galeri SMKN 4 Bogor

Dashboard user untuk aplikasi galeri SMKN 4 Bogor dengan desain modern menggunakan nuansa biru lembut dan abu-abu muda.

## Fitur Dashboard

### 1. Navbar
- **Beranda**: Halaman utama dashboard
- **Informasi**: Menampilkan informasi terbaru sekolah
- **Galeri**: Menampilkan foto-foto kegiatan sekolah
- **Agenda**: Menampilkan jadwal kegiatan mendatang

### 2. Statistik Dashboard
- Total Foto Galeri
- Total Informasi
- Total Agenda
- Total Siswa

### 3. Konten Utama
- **Welcome Section**: Sambutan selamat datang
- **Informasi Terbaru**: Daftar informasi penting sekolah
- **Galeri Terbaru**: Preview foto-foto kegiatan
- **Agenda Mendatang**: Jadwal kegiatan sekolah

## File yang Dibuat

### 1. Views
- `resources/views/dashboard.blade.php` - Halaman dashboard utama
- `resources/views/layouts/app.blade.php` - Layout master

### 2. Controller
- `app/Http/Controllers/DashboardController.php` - Controller untuk dashboard

### 3. Routes
- `routes/web.php` - Route untuk dashboard

### 4. Assets
- `public/css/dashboard.css` - Styling khusus dashboard

## Warna Tema

### Primary Colors
- **Primary Blue**: #7A9CC6 (Blue Gray)
- **Soft Blue**: #9BB5D1 (Light Blue Gray)
- **Light Gray**: #F5F5F5 (White Smoke)
- **Medium Gray**: #D3D3D3 (Light Gray)
- **Dark Gray**: #696969 (Dim Gray)
- **White**: #FFFFFF

## Responsive Design

Dashboard dirancang responsif untuk berbagai ukuran layar:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## Teknologi yang Digunakan

- **Laravel 12** - Framework PHP
- **Bootstrap 5** - CSS Framework
- **Font Awesome 6** - Icon Library
- **Blade Template** - Template Engine Laravel
- **CSS Grid & Flexbox** - Layout System

## Cara Menjalankan

1. Pastikan Laravel sudah terinstall
2. Jalankan `php artisan serve`
3. Buka browser dan akses `http://localhost:8000`

## Struktur Data

### Stats
```php
$stats = [
    'total_gallery' => 150,
    'total_information' => 25,
    'total_agenda' => 12,
    'total_students' => 1200
];
```

### Informations
```php
$informations = [
    [
        'title' => 'Judul Informasi',
        'description' => 'Deskripsi informasi',
        'date' => '2023-12-01'
    ]
];
```

### Galleries
```php
$galleries = [
    [
        'title' => 'Judul Galeri',
        'image' => 'URL gambar',
        'date' => '2023-12-01'
    ]
];
```

### Agendas
```php
$agendas = [
    [
        'title' => 'Judul Agenda',
        'description' => 'Deskripsi agenda',
        'date' => '2023-12-15',
        'time' => '08:00 - 12:00'
    ]
];
```

## Customization

Untuk mengubah warna tema, edit file `public/css/dashboard.css` dan ubah nilai CSS variables di bagian `:root`.

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+