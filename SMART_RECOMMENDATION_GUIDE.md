# Smart Volunteer Recommendation Notification - Implementation Guide

## 📋 Overview

Smart Volunteer Recommendation Notification adalah sistem pintar yang secara otomatis merekomendasikan kampanye baru kepada relawan berdasarkan kategori kampanye yang pernah mereka ikuti sebelumnya.

## 🎯 Fitur Utama

1. **Auto-Discovery Kategori**: Sistem secara otomatis mendeteksi kategori kampanye dari riwayat relawan
2. **Preferensi Kategori**: Relawan dapat memilih kategori yang diminati pada profil mereka
3. **Smart Matching**: Saat kampanye baru dibuat, sistem otomatis mencari relawan yang cocok
4. **Notifikasi Real-time**: Notifikasi dikirim ke database dan dapat dikembangkan ke email/SMS
5. **Recommendation Tracking**: Sistem melacak notifikasi yang sudah dikirim untuk menghindari duplikat

## 📁 Struktur Basis Data

### Tabel: `volunteer_category_preferences`
Menyimpan preferensi kategori relawan:
```
id (Primary Key)
user_id (Foreign Key -> users.id)
category (String: Pendidikan, Kesehatan, Sosial, Lingkungan, Bencana, Keagamaan)
is_preferred (Boolean) - User sengaja memilih kategori ini
auto_discovered (Boolean) - Kategori otomatis ditemukan dari riwayat
engagement_score (Integer) - Semakin tinggi semakin sering user terlibat
created_at
updated_at
Unique Constraint: (user_id, category)
```

### Tabel: `recommendation_notifications`
Melacak notifikasi rekomendasi yang sudah dikirim:
```
id (Primary Key)
user_id (Foreign Key -> users.id)
campaign_id (Foreign Key -> volunteer_campaigns.id)
matching_category (String) - Kategori yang cocok
reason (Text) - Alasan rekomendasi
is_read (Boolean)
is_dismissed (Boolean)
created_at
updated_at
Unique Constraint: (user_id, campaign_id)
```

## 🔧 Model & Relationships

### Model: VolunteerCategoryPreference
```php
belongsTo(User)
scopeActive() - Preferensi yang aktif
scopeOrderByEngagement() - Urutan berdasarkan engagement score
```

### Model: RecommendationNotification
```php
belongsTo(User)
belongsTo(VolunteerCampaign)
scopeUnread() - Notifikasi yang belum dibaca
scopeActive() - Notifikasi yang tidak di-dismiss
scopeLatest() - Urutan terbaru
markAsRead() - Tandai sebagai dibaca
markAsDismissed() - Tandai sebagai ditutup
```

### Relasi User
```php
categoryPreferences() - hasMany(VolunteerCategoryPreference)
recommendationNotifications() - hasMany(RecommendationNotification)
```

## 🔌 API Endpoints

### Mendapatkan Kategori yang Tersedia
**GET** `/api/v1/recommendations/categories`
```json
{
  "success": true,
  "data": ["Pendidikan", "Kesehatan", "Sosial", "Lingkungan", "Bencana", "Keagamaan"]
}
```

### Mendapatkan Preferensi Kategori Relawan
**GET** `/api/v1/recommendations/preferences`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "category": "Pendidikan",
      "is_preferred": true,
      "auto_discovered": true,
      "engagement_score": 3
    }
  ]
}
```

### Update Preferensi Kategori
**POST** `/api/v1/recommendations/preferences`
```json
{
  "categories": ["Pendidikan", "Kesehatan", "Sosial"]
}
```

### Mendapatkan Kampanye yang Direkomendasikan
**GET** `/api/v1/recommendations/campaigns?limit=10`
```json
{
  "success": true,
  "total": 5,
  "data": [
    {
      "id": 1,
      "judul": "Kelas Inspirasi Anak Desa",
      "slug": "kelas-inspirasi-anak-desa",
      "image": "url-ke-gambar",
      "deskripsi": "Deskripsi kampanye...",
      "kategori": "Pendidikan",
      "lokasi": "Bandung",
      "tanggal_mulai": "2026-06-15",
      "tanggal_selesai": "2026-06-20",
      "kuota_total": 50,
      "kuota_terisi": 35,
      "status": "Aktif",
      "matching_reason": "Sesuai dengan kategori Pendidikan yang pernah Anda ikuti"
    }
  ]
}
```

### Mendapatkan Notifikasi Rekomendasi
**GET** `/api/v1/recommendations/notifications?limit=10`
```json
{
  "success": true,
  "total": 3,
  "data": [
    {
      "id": 1,
      "campaign": {
        "id": 1,
        "judul": "Kelas Inspirasi Anak Desa",
        "slug": "kelas-inspirasi-anak-desa",
        "image": "url",
        "kategori": "Pendidikan",
        "lokasi": "Bandung",
        "status": "Aktif"
      },
      "matching_category": "Pendidikan",
      "reason": "Kampanye ini sesuai dengan kategori Pendidikan yang pernah Anda ikuti",
      "is_read": false,
      "created_at": "2026-06-02T10:30:00",
      "time_ago": "2 jam yang lalu"
    }
  ]
}
```

### Tandai Notifikasi sebagai Dibaca
**PUT** `/api/v1/recommendations/notifications/{notificationId}/read`

### Tutup/Dismiss Notifikasi
**PUT** `/api/v1/recommendations/notifications/{notificationId}/dismiss`

## 📍 Alur Sistem

### 1. Saat Relawan Pertama Kali Login
- Sistem secara otomatis mendeteksi kategori dari riwayat kampanye yang pernah diikuti relawan
- Kategori disimpan di `volunteer_category_preferences` dengan `auto_discovered = true`

### 2. Saat Relawan Update Preferensi Kategori
- Preferensi yang dipilih disimpan dengan `is_preferred = true`
- Auto-discover juga tetap berjalan untuk melengkapi preferensi

### 3. Saat Kampanye Baru Dibuat
- Observer `VolunteerCampaignObserver` mendeteksi event `created`
- Sistem mencari semua relawan yang punya preferensi untuk kategori kampanye
- Untuk setiap relawan yang cocok:
  - Buat record di `recommendation_notifications`
  - Kirim notifikasi ke database (via Notification)
  - Notifikasi dapat ditampilkan di UI

### 4. Relawan Melihat Rekomendasi
- Di dropdown notifikasi navbar: max 3 rekomendasi terbaru
- Di halaman notifikasi lengkap: tampilkan semua rekomendasi dengan filter "Rekomendasi"
- Relawan bisa klik "Lihat Detail Kampanye" atau "Daftar Sebagai Relawan"

## 🎨 Frontend Components

### 1. Dropdown Notifikasi (Navbar)
- Tambahkan tab "Rekomendasi Untuk Anda" di dropdown notifikasi
- Tampilkan max 3 rekomendasi terbaru dengan icon ⭐
- Format: "[Kategori] Nama Kampanye - Lokasi"

### 2. Halaman Notifikasi Lengkap
- Tambahkan filter: "Semua | Donasi | Kampanye | Relawan | Sistem | Rekomendasi"
- Saat filter "Rekomendasi" dipilih, tampilkan:
  - Card kampanye dengan badge ⭐
  - Informasi kategori, lokasi, status
  - Tombol "Lihat Detail Kampanye" dan "Daftar Sebagai Relawan"
  - Tombol dismiss (x) untuk menutup notifikasi

### 3. Menu Preferensi Kategori di Profil
- Buat tab/section baru: "Kategori Minat"
- Multi-select checkbox untuk pilih kategori
- Simpan perubahan dengan POST ke `/api/v1/recommendations/preferences`
- Tampilkan engagement score untuk setiap kategori

### 4. Empty State
Tampilkan ketika tidak ada rekomendasi:
```
🎯 Belum Ada Rekomendasi

Saat ini belum ada kampanye baru yang sesuai dengan kategori 
kegiatan relawan yang pernah Anda ikuti.

Tetap semangat menyebarkan kebaikan bersama Dongiv 💙

[Tombol: Jelajahi Semua Kampanye]
```

## 🚀 Migration & Database Setup

Jalankan migrasi:
```bash
php artisan migrate
```

Seeder kategori (opsional):
```php
// Jalankan untuk relawan yang sudah ada
php artisan tinker
$users = User::where('role', 'relawan')->get();
foreach ($users as $user) {
    app(VolunteerRecommendationController::class)->autoDiscoverCategories($user);
}
```

## 📊 Monitoring & Logging

Sistem akan log:
- Kapan rekomendasi dibuat: `"Rekomendasi kampanye {campaign_id} berhasil dibuat untuk {count} relawan"`
- Error saat generate rekomendasi: `"Gagal generate rekomendasi untuk kampanye {campaign_id}"`

## ✅ Checklist Implementasi Frontend

- [ ] Buat Vue component untuk dropdown rekomendasi di navbar
- [ ] Tambahkan filter "Rekomendasi" di halaman notifikasi
- [ ] Buat card component untuk menampilkan rekomendasi
- [ ] Buat section preferensi kategori di halaman profil relawan
- [ ] Implementasi API calls untuk fetch rekomendasi
- [ ] Implementasi mark as read dan dismiss action
- [ ] Styling dengan Tailwind CSS (sesuai desain Dongiv)
- [ ] Testing di berbagai device (desktop, tablet, mobile)
- [ ] Integration testing dengan backend API

## 🎨 Styling Tips

Gunakan palet warna Dongiv:
- Primary: Biru gradient (#2563EB ke #1D4ED8)
- Secondary: Kuning (#FBBF24) untuk badge rekomendasi
- Background: Putih (#FFFFFF) dengan shadow lembut
- Text: Abu-abu gelap (#374151)
- Border: Abu-abu muda (#E5E7EB)

Typography:
- Font: Poppins
- Heading: Poppins Bold
- Body: Poppins Regular

## 📝 Contoh Implementasi Vue Component

Lihat file-file di folder `resources/js/components/`:
- `RecommendationDropdown.vue` - Dropdown di navbar
- `RecommendationNotifications.vue` - Halaman lengkap
- `CategoryPreferences.vue` - Menu preferensi di profil

## 🔗 Referensi

- [Laravel Observers Documentation](https://laravel.com/docs/11.x/eloquent#observers)
- [Laravel Notifications Documentation](https://laravel.com/docs/11.x/notifications)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

**Last Updated**: June 2, 2026
**Version**: 1.0
**Status**: Ready for Implementation
