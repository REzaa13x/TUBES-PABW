# Smart Volunteer Recommendation - Setup & Deployment Guide

## 🚀 Quick Start Setup

### Step 1: Run Database Migrations
```bash
# Terminal di project root
php artisan migrate

# Verify tables created
php artisan tinker
>>> Schema::hasTable('volunteer_category_preferences')
>>> Schema::hasTable('recommendation_notifications')
```

### Step 2: Run Seeder untuk Auto-Discovery
```bash
# Auto-discover kategori dari riwayat relawan yang sudah ada
php artisan db:seed --class=VolunteerCategoryPreferenceSeeder

# Verify di database
php artisan tinker
>>> VolunteerCategoryPreference::count()
```

### Step 3: Verify Routes
```bash
# Cek routes
php artisan route:list | grep recommendations

# Output should show:
# GET|HEAD   /api/v1/recommendations/categories
# GET|HEAD   /api/v1/recommendations/preferences
# POST       /api/v1/recommendations/preferences
# GET|HEAD   /api/v1/recommendations/campaigns
# GET|HEAD   /api/v1/recommendations/notifications
# PUT        /api/v1/recommendations/notifications/{notificationId}/read
# PUT        /api/v1/recommendations/notifications/{notificationId}/dismiss
```

### Step 4: Test API Endpoints
```bash
# Get auth token terlebih dahulu
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email": "volunteer@example.com", "password": "password"}'

# Copy token dari response
TOKEN="your_token_here"

# Test get categories
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/categories

# Test get preferences
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/preferences

# Test get recommendations
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/campaigns
```

### Step 5: Integrate Components (Vue)

#### 5a. Import Components di halaman yang diperlukan

**Dropdown di Navbar:**
```vue
<template>
  <div class="navbar">
    <!-- ... other navbar items ... -->
    
    <!-- Notification Bell with Dropdown -->
    <div class="relative group">
      <button class="notification-bell">
        🔔
      </button>
      
      <!-- Dropdown -->
      <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
        <RecommendationDropdown />
      </div>
    </div>
  </div>
</template>

<script setup>
import RecommendationDropdown from '@/components/RecommendationDropdown.vue';
</script>
```

**Di Halaman Profile Relawan:**
```vue
<template>
  <div class="profile-page">
    <tabs>
      <tab name="Data Pribadi">
        <!-- existing content -->
      </tab>
      <tab name="Kategori Minat">
        <CategoryPreferences />
      </tab>
    </tabs>
  </div>
</template>

<script setup>
import CategoryPreferences from '@/components/CategoryPreferences.vue';
</script>
```

**Di Halaman Rekomendasi/Dashboard Relawan:**
```vue
<template>
  <div class="recommendations-page">
    <RecommendationCard />
  </div>
</template>

<script setup>
import RecommendationCard from '@/components/RecommendationCard.vue';
</script>
```

**Di Halaman Notifikasi:**
```vue
<!-- Add this to notification page component -->
<div class="filter-tabs">
  <button 
    @click="filterType = 'recommendation'"
    :class="{'active': filterType === 'recommendation'}"
    class="filter-btn"
  >
    ⭐ Rekomendasi
  </button>
</div>

<!-- Show recommendations when filter is selected -->
<div v-if="filterType === 'recommendation'" class="notifications-list">
  <div v-for="notif in filteredRecommendations" :key="notif.id">
    <!-- Recommendation notification card -->
  </div>
</div>
```

## 📁 File Structure Summary

```
app/
├── Http/Controllers/Api/
│   └── VolunteerRecommendationController.php ✨ NEW
├── Models/
│   ├── VolunteerCategoryPreference.php ✨ NEW
│   ├── RecommendationNotification.php ✨ NEW
│   ├── User.php (updated with relations)
│   └── ...
├── Notifications/
│   ├── RecommendedCampaignNotification.php ✨ NEW
│   └── ...
├── Observers/
│   └── VolunteerCampaignObserver.php ✨ NEW
└── Providers/
    └── AppServiceProvider.php (updated)

database/
├── migrations/
│   ├── 2026_06_02_000001_create_volunteer_category_preferences_table.php ✨ NEW
│   ├── 2026_06_02_000002_create_recommendation_notifications_table.php ✨ NEW
│   └── ...
└── seeders/
    └── VolunteerCategoryPreferenceSeeder.php ✨ NEW

resources/js/components/
├── RecommendationCard.vue ✨ NEW
├── RecommendationDropdown.vue ✨ NEW
├── CategoryPreferences.vue ✨ NEW
└── ...

routes/
└── api.php (updated with recommendation routes)

Documentation/
├── SMART_RECOMMENDATION_GUIDE.md ✨ NEW
├── NOTIFICATION_INTEGRATION_GUIDE.md ✨ NEW
├── IMPLEMENTATION_TESTING_GUIDE.md ✨ NEW
└── SETUP_DEPLOYMENT_GUIDE.md ✨ NEW (this file)
```

## 🔄 Workflow Overview

### Saat Relawan Mendaftar
```
User Register
    ↓
User mendapat role 'relawan'
    ↓
(Opsional) Admin/User setup kategori minat di profil
    ↓
Sistem ready untuk generate recommendations
```

### Saat Relawan Ikut Kampanye
```
User Apply Campaign (Pendidikan)
    ↓
VolunteerApplication created
    ↓
System auto-discover kategori Pendidikan
    ↓
Simpan di volunteer_category_preferences (auto_discovered=true)
```

### Saat Kampanye Baru Dibuat
```
Admin/User create VolunteerCampaign (kategori=Pendidikan)
    ↓
VolunteerCampaignObserver::created() trigger
    ↓
VolunteerRecommendationController::generateRecommendations()
    ↓
Query relawan dengan kategori preference = Pendidikan
    ↓
Untuk setiap relawan yang cocok:
    - Buat RecommendationNotification record
    - Send Notification via queue
    - Notify relawan
    ↓
Relawan lihat di dashboard/dropdown notifikasi
```

### Saat Relawan Lihat Rekomendasi
```
User buka halaman notifikasi
    ↓
Frontend call GET /api/v1/recommendations/notifications
    ↓
API return active recommendations (not dismissed)
    ↓
Tampilkan di UI dengan badge ⭐
    ↓
User bisa klik Lihat Detail atau Daftar
    ↓
User bisa dismiss atau mark as read
```

## 🛠️ Maintenance Commands

```bash
# Clear cache
php artisan cache:clear
php artisan route:cache
php artisan route:clear

# Check logs
tail -f storage/logs/laravel.log

# Database stats
php artisan tinker
>>> VolunteerCategoryPreference::count()
>>> RecommendationNotification::count()
>>> RecommendationNotification::where('is_read', false)->count()

# Check queue (jika menggunakan async)
php artisan queue:work

# Reset database (development only)
php artisan migrate:refresh --seed
```

## 🔐 Security Considerations

1. **Authentication**: Semua recommendation endpoints require `auth:sanctum`
2. **Authorization**: Relawan hanya bisa akses preferensi & notifikasi mereka sendiri
3. **Rate Limiting**: Add rate limiting untuk API endpoints
4. **Validation**: Semua input validate sebelum save

Contoh menambah rate limiting:
```php
// routes/api.php
Route::prefix('recommendations')->middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    // routes here
});
```

## 📊 Monitoring & Analytics

### Query untuk analytics:
```sql
-- Total recommendations sent
SELECT COUNT(*) FROM recommendation_notifications;

-- Recommendation read rate
SELECT 
  COUNT(*) as total,
  SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read,
  ROUND(SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as read_percentage
FROM recommendation_notifications;

-- Most recommended categories
SELECT 
  matching_category,
  COUNT(*) as count,
  COUNT(DISTINCT campaign_id) as unique_campaigns
FROM recommendation_notifications
GROUP BY matching_category
ORDER BY count DESC;

-- Most engaged volunteers
SELECT 
  user_id,
  COUNT(*) as recommendations_received,
  SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as recommendations_read
FROM recommendation_notifications
GROUP BY user_id
ORDER BY recommendations_read DESC
LIMIT 10;
```

## 🎯 Performance Optimization

### Database Indexes
```php
// Tambah di migration jika diperlukan
Schema::table('volunteer_category_preferences', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('category');
});

Schema::table('recommendation_notifications', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('campaign_id');
    $table->index(['user_id', 'is_dismissed']);
});
```

### Query Optimization
- Gunakan `with()` untuk eager loading relations
- Gunakan `limit()` untuk pagination
- Cache kategori list di Redis (optional)

Contoh:
```php
// Good - dengan eager loading
$recommendations = VolunteerCampaign::whereIn('kategori', $categories)
    ->with('volunteers') // eager load relations
    ->limit(10)
    ->get();

// Bad - N+1 query problem
foreach ($recommendations as $rec) {
    $rec->volunteers; // queries per item
}
```

## 📞 Support & Troubleshooting

### Common Issues

**Q: Rekomendasi tidak muncul untuk relawan baru**
A: Relawan baru belum punya kategori preference. Run seeder atau biarkan auto-discover dari history.

**Q: Observer tidak trigger**
A: Check AppServiceProvider sudah register observer. Restart server.

**Q: API returns 401 Unauthorized**
A: Verify token valid dan not expired. Get new token via login endpoint.

**Q: Performance lambat**
A: Check database indexes created. Use query debugger untuk identify slow queries.

## 🎓 Learning Resources

- [Laravel Observers](https://laravel.com/docs/11.x/eloquent#observers)
- [Laravel Notifications](https://laravel.com/docs/11.x/notifications)
- [Eloquent Relationships](https://laravel.com/docs/11.x/eloquent-relationships)
- [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [Tailwind CSS](https://tailwindcss.com/docs)

## ✅ Final Checklist

- [ ] Semua files sudah ada di tempat yang benar
- [ ] Migrations berhasil dijalankan
- [ ] Seeder berhasil dijalankan
- [ ] API endpoints tested dan working
- [ ] Components integrated ke views
- [ ] Frontend styling sesuai desain
- [ ] Error handling implemented
- [ ] Logging configured
- [ ] Testing documentation completed
- [ ] Deployment tested di staging

---

**Version**: 1.0
**Last Updated**: June 2, 2026
**Ready for Production**: ✅ Yes
