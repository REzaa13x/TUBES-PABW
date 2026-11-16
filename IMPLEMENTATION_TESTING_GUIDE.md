# Smart Volunteer Recommendation - Implementation Checklist & Testing Guide

## ✅ Pre-Implementation Checklist

### Database Setup
- [x] Migration files dibuat (`volunteer_category_preferences` & `recommendation_notifications`)
- [x] Models dibuat (`VolunteerCategoryPreference` & `RecommendationNotification`)
- [ ] Run migration: `php artisan migrate`
- [ ] Verify tables di database

### Backend Setup
- [x] Notification class dibuat (`RecommendedCampaignNotification`)
- [x] Controller dibuat (`VolunteerRecommendationController`)
- [x] Observer dibuat dan registered (`VolunteerCampaignObserver`)
- [x] Routes ditambahkan di `routes/api.php`
- [x] User model relasi diupdate
- [ ] Test API endpoints

### Frontend Components
- [x] `RecommendationCard.vue` - Grid rekomendasi kampanye
- [x] `RecommendationDropdown.vue` - Dropdown di navbar
- [x] `CategoryPreferences.vue` - Menu preferensi di profil
- [ ] Integrasikan ke halaman notifikasi
- [ ] Integrasikan ke profil relawan
- [ ] Integrasikan ke navbar

### Documentation
- [x] `SMART_RECOMMENDATION_GUIDE.md` - Dokumentasi sistem
- [x] `NOTIFICATION_INTEGRATION_GUIDE.md` - Integrasi halaman notifikasi
- [x] Inline code comments
- [ ] API documentation (Postman collection)

## 🧪 Testing Guide

### 1. Setup Database
```bash
# Run migrations
php artisan migrate

# Run seeder untuk auto-discover kategori relawan yang sudah ada
php artisan db:seed --class=VolunteerCategoryPreferenceSeeder
```

### 2. Test API Endpoints

#### Get Available Categories
```bash
curl -X GET http://localhost:8000/api/v1/recommendations/categories
```
Expected: 200 OK dengan array kategori

#### Get User Preferences (AUTH REQUIRED)
```bash
curl -X GET http://localhost:8000/api/v1/recommendations/preferences \
  -H "Authorization: Bearer TOKEN"
```

#### Update Preferences (AUTH REQUIRED)
```bash
curl -X POST http://localhost:8000/api/v1/recommendations/preferences \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "categories": ["Pendidikan", "Kesehatan", "Sosial"]
  }'
```

#### Get Recommendations (AUTH REQUIRED)
```bash
curl -X GET http://localhost:8000/api/v1/recommendations/campaigns?limit=10 \
  -H "Authorization: Bearer TOKEN"
```

#### Get Recommendation Notifications (AUTH REQUIRED)
```bash
curl -X GET http://localhost:8000/api/v1/recommendations/notifications?limit=10 \
  -H "Authorization: Bearer TOKEN"
```

#### Mark as Read
```bash
curl -X PUT http://localhost:8000/api/v1/recommendations/notifications/1/read \
  -H "Authorization: Bearer TOKEN"
```

#### Dismiss Notification
```bash
curl -X PUT http://localhost:8000/api/v1/recommendations/notifications/1/dismiss \
  -H "Authorization: Bearer TOKEN"
```

### 3. Test Recommendation Generation

#### Manual Test
```php
// Di tinker
php artisan tinker

// Trigger observer manual
$campaign = VolunteerCampaign::create([
  'judul' => 'Test Campaign',
  'slug' => 'test-campaign',
  'deskripsi' => 'Test Description',
  'lokasi' => 'Jakarta',
  'tanggal_mulai' => now()->addDays(1),
  'tanggal_selesai' => now()->addDays(10),
  'kategori' => 'Pendidikan',
  'kuota_total' => 50,
  'kuota_terisi' => 0,
  'status' => 'Aktif'
]);

// Check recommendation notifications created
RecommendationNotification::where('campaign_id', $campaign->id)->count()
```

Expected: Notifikasi dibuat untuk semua relawan dengan preferensi kategori "Pendidikan"

### 4. Test Auto-Discovery

```php
// Di tinker
$user = User::find(1);

// Dapatkan kategori dari riwayat relawan
$categories = VolunteerApplication::where('user_id', $user->id)
  ->with('campaign')
  ->get()
  ->map(fn($app) => $app->campaign->kategori)
  ->unique();

// Verify preferences dibuat
$preferences = $user->categoryPreferences()->pluck('category');
```

### 5. Frontend Testing

#### Component: RecommendationCard.vue
1. Render komponen di halaman admin/test
2. Verify API call untuk recommendations
3. Test responsive layout (mobile, tablet, desktop)
4. Verify image loading
5. Test button actions (redirect, apply)

#### Component: RecommendationDropdown.vue
1. Render di navbar
2. Test auto-refresh (30 detik)
3. Test dismiss action
4. Verify truncation untuk long titles

#### Component: CategoryPreferences.vue
1. Render di halaman profil
2. Test multi-select checkbox
3. Test save functionality
4. Verify error handling
5. Test reset button

### 6. Database Integrity Tests

```php
// Check unique constraints
VolunteerCategoryPreference::where('user_id', 1)
  ->where('category', 'Pendidikan')
  ->count() // Should be 1

// Check foreign key constraints
RecommendationNotification::where('campaign_id', 999)->count() // Invalid ID
```

### 7. Performance Tests

```php
// Generate many recommendations
$campaigns = VolunteerCampaign::limit(100)->get();
foreach ($campaigns as $campaign) {
  $controller = new VolunteerRecommendationController();
  $controller->generateRecommendations($campaign);
}

// Measure time
```

## 🚀 Deployment Checklist

### Before Going Live
- [ ] All migrations run successfully
- [ ] No SQL errors in database
- [ ] API endpoints tested with Postman
- [ ] Frontend components tested locally
- [ ] Error handling in place
- [ ] Logging configured
- [ ] Rate limiting configured (untuk API)

### Environment Variables
```env
# .env
APP_DEBUG=false (production)
APP_ENV=production

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dongiv
DB_USERNAME=root
DB_PASSWORD=...

# Queue (untuk async notification)
QUEUE_CONNECTION=database
```

### Queue Setup (Optional - untuk async)
```bash
# Setup queue table
php artisan queue:table
php artisan migrate

# Run queue worker
php artisan queue:work
```

## 📊 Monitoring

### Logs Location
```
storage/logs/laravel.log
```

### Key Events to Monitor
1. `"Rekomendasi kampanye X berhasil dibuat untuk Y relawan"`
2. `"Gagal generate rekomendasi untuk kampanye X"`
3. API errors di recommendation endpoints

### Database Monitoring
```sql
-- Jumlah preferences per relawan
SELECT user_id, COUNT(*) as preference_count 
FROM volunteer_category_preferences 
GROUP BY user_id;

-- Notifikasi yang belum dibaca
SELECT COUNT(*) as unread_count 
FROM recommendation_notifications 
WHERE is_read = false;

-- Notifikasi yang di-dismiss
SELECT COUNT(*) as dismissed_count 
FROM recommendation_notifications 
WHERE is_dismissed = true;
```

## 🐛 Troubleshooting

### Issue: Migrasi gagal
**Solution**: 
```bash
php artisan migrate:rollback
php artisan migrate
```

### Issue: Observer tidak trigger
**Solution**: 
- Verify `AppServiceProvider` sudah register observer
- Check log untuk error messages
- Test manual di tinker

### Issue: API 404
**Solution**: 
- Verify routes di `routes/api.php`
- Check controller namespace
- Run `php artisan route:cache` dan `php artisan route:clear`

### Issue: Notification tidak muncul
**Solution**: 
- Check `recommendation_notifications` table
- Verify relawan punya preferences
- Check campaign kategori cocok
- Check relawan belum apply kampanye

### Issue: Auto-discovery tidak jalan
**Solution**: 
- Manual run seeder: `php artisan db:seed --class=VolunteerCategoryPreferenceSeeder`
- Check relawan punya history volunteer applications
- Verify campaign kategori tidak null

## 📝 SQL Queries untuk Testing

### Get relawan dengan preference Pendidikan
```sql
SELECT DISTINCT u.* 
FROM users u
JOIN volunteer_category_preferences vcp ON u.id = vcp.user_id
WHERE vcp.category = 'Pendidikan' 
AND vcp.is_preferred = 1;
```

### Get kampanye Pendidikan yang belum ada notifikasi untuk relawan X
```sql
SELECT vc.* 
FROM volunteer_campaigns vc
WHERE vc.kategori = 'Pendidikan'
AND vc.status = 'Aktif'
AND NOT EXISTS (
  SELECT 1 FROM recommendation_notifications rn
  WHERE rn.campaign_id = vc.id
  AND rn.user_id = 1
);
```

### Get statistik rekomendasi
```sql
SELECT 
  (SELECT COUNT(DISTINCT user_id) FROM recommendation_notifications) as total_users_with_recommendations,
  (SELECT COUNT(*) FROM recommendation_notifications WHERE is_read = 0) as unread_notifications,
  (SELECT COUNT(*) FROM recommendation_notifications WHERE is_dismissed = 1) as dismissed_notifications,
  (SELECT COUNT(DISTINCT campaign_id) FROM recommendation_notifications) as campaigns_with_recommendations;
```

## 🎯 Success Criteria

✅ Sistem dianggap berhasil jika:
1. Database migrations berjalan tanpa error
2. API endpoints semua return 200 OK
3. Observer trigger saat kampanye dibuat
4. Notifikasi dibuat untuk relawan yang cocok
5. Frontend components render tanpa error
6. Category preferences tersimpan dengan benar
7. Auto-discovery berjalan untuk existing volunteers
8. UI responsive di semua device
9. Error handling proper (no blank pages)
10. Performance acceptable (< 2s untuk API calls)

---

**Version**: 1.0
**Last Updated**: June 2, 2026
**Status**: Ready for Testing
