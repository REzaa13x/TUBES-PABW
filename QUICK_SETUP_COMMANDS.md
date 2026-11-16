# Smart Volunteer Recommendation - Quick Setup Commands

## 🚀 One-Command Setup

```bash
# Run semua setup sekaligus
php artisan migrate && php artisan db:seed --class=VolunteerCategoryPreferenceSeeder && php artisan route:clear && php artisan cache:clear
```

## 📋 Step-by-Step Setup

### Step 1: Database Migration
```bash
# Run migrations
php artisan migrate

# Verify tables
php artisan tinker
> Schema::hasTable('volunteer_category_preferences')
> Schema::hasTable('recommendation_notifications')
> exit
```

### Step 2: Seed Data (Auto-Discovery)
```bash
# Run seeder untuk existing relawan
php artisan db:seed --class=VolunteerCategoryPreferenceSeeder

# Verify
php artisan tinker
> VolunteerCategoryPreference::count()
> exit
```

### Step 3: Clear Cache & Routes
```bash
php artisan route:clear
php artisan cache:clear
php artisan view:cache
```

### Step 4: Verify API Routes
```bash
# List semua recommendation routes
php artisan route:list | grep recommendations

# Output:
# GET|HEAD   /api/v1/recommendations/categories
# GET|HEAD   /api/v1/recommendations/preferences
# POST       /api/v1/recommendations/preferences
# GET|HEAD   /api/v1/recommendations/campaigns
# GET|HEAD   /api/v1/recommendations/notifications
# PUT        /api/v1/recommendations/notifications/{notificationId}/read
# PUT        /api/v1/recommendations/notifications/{notificationId}/dismiss
```

## 🧪 Testing Commands

### Test API with Authentication
```bash
# Get token
TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email": "volunteer@example.com", "password": "password"}' \
  | jq -r '.token')

# Test get categories (no auth needed)
curl http://localhost:8000/api/v1/recommendations/categories

# Test get preferences (auth required)
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/preferences

# Test get recommendations
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/campaigns

# Test get notifications
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/recommendations/notifications
```

### Test with Postman
1. Set base URL: `http://localhost:8000/api/v1`
2. Get auth token from `/login` endpoint
3. Add `Authorization: Bearer {token}` to headers
4. Test each recommendation endpoint

## 🧩 Tinker Commands (Interactive Testing)

```bash
php artisan tinker

# Check preference count
VolunteerCategoryPreference::count()

# Get preferences for user 1
User::find(1)->categoryPreferences()->get()

# Get recommendations for user 1
RecommendationNotification::where('user_id', 1)->get()

# Check unread notifications
RecommendationNotification::where('user_id', 1)->where('is_read', false)->count()

# Check dismissed notifications
RecommendationNotification::where('user_id', 1)->where('is_dismissed', true)->count()

# Get notification engagement rate
RecommendationNotification::selectRaw('count(*) as total, sum(case when is_read = 1 then 1 else 0 end) as read')
  ->first()

# Create test campaign
$campaign = VolunteerCampaign::create([
  'judul' => 'Test Campaign',
  'slug' => 'test-campaign-' . now()->timestamp,
  'deskripsi' => 'Test Description',
  'lokasi' => 'Jakarta',
  'tanggal_mulai' => now()->addDay(),
  'tanggal_selesai' => now()->addDays(10),
  'kategori' => 'Pendidikan',
  'kuota_total' => 50,
  'kuota_terisi' => 0,
  'status' => 'Aktif'
]);

# Check recommendations created for this campaign
RecommendationNotification::where('campaign_id', $campaign->id)->count()

# Exit tinker
exit
```

## 📊 Database Queries

### Get Statistics
```sql
-- Total recommendations sent
SELECT COUNT(*) as total_recommendations FROM recommendation_notifications;

-- Unread recommendations
SELECT COUNT(*) as unread FROM recommendation_notifications WHERE is_read = 0;

-- Read rate
SELECT 
  COUNT(*) as total,
  SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count,
  ROUND(SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as read_percentage
FROM recommendation_notifications;

-- Recommendations by category
SELECT 
  matching_category,
  COUNT(*) as count
FROM recommendation_notifications
GROUP BY matching_category
ORDER BY count DESC;

-- User engagement
SELECT 
  user_id,
  COUNT(*) as recommendations_received,
  SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read,
  SUM(CASE WHEN is_dismissed = 1 THEN 1 ELSE 0 END) as dismissed
FROM recommendation_notifications
GROUP BY user_id
ORDER BY recommendations_received DESC
LIMIT 10;
```

## 🔧 Maintenance Commands

```bash
# Check logs
tail -f storage/logs/laravel.log

# Search for recommendation logs
grep -i "rekomendasi\|recommendation" storage/logs/laravel.log

# Queue worker (jika menggunakan async)
php artisan queue:work

# Reset database (development only!)
php artisan migrate:refresh --seed

# Monitor database size
du -sh storage/database/

# Backup database
php artisan db:backup

# View table structure
php artisan tinker
> Schema::getColumnListing('volunteer_category_preferences')
> Schema::getColumnListing('recommendation_notifications')
```

## 🚀 Production Deployment

```bash
# Pre-deployment checks
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Warm up caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker (background service)
# Use Supervisor or similar tool for production
php artisan queue:work --daemon

# Monitor error logs
tail -f storage/logs/laravel.log

# Restart application (if needed)
php artisan down
php artisan migrate
php artisan up
```

## 📱 Frontend Setup (Optional - if using Vite)

```bash
# Install dependencies
npm install

# Build frontend assets
npm run build

# Watch for development
npm run dev
```

## ✅ Verification Checklist

```bash
# 1. Database tables exist
php artisan tinker -c "
  dd([
    'pref' => Schema::hasTable('volunteer_category_preferences'),
    'notif' => Schema::hasTable('recommendation_notifications')
  ])
"

# 2. Models can be instantiated
php artisan tinker -c "
  dd([
    'pref' => VolunteerCategoryPreference::count(),
    'notif' => RecommendationNotification::count()
  ])
"

# 3. Routes registered
php artisan route:list --name=recommendations

# 4. Observer registered
php artisan tinker -c "
  dd(VolunteerCampaign::getObservers())
"

# 5. API responds
curl http://localhost:8000/api/v1/recommendations/categories

# 6. Components exist
ls -la resources/js/components/Recommendation*.vue
```

## 🐛 Troubleshooting Commands

```bash
# Check if migrations ran
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Fresh start (danger!)
php artisan migrate:fresh --seed

# Check routes cache issue
php artisan route:clear

# Check application cache issue
php artisan cache:forget-all

# View error logs
php artisan log:tail

# Test database connection
php artisan tinker -c "
  dd(DB::connection()->getPdo())
"

# Check file permissions
ls -la storage/ bootstrap/cache/
chmod -R 775 storage bootstrap/cache
```

## 📈 Performance Monitoring

```bash
# Check slow queries
php artisan tinker
> DB::enableQueryLog()
> VolunteerCategoryPreference::with('user')->get()
> dd(DB::getQueryLog())

# Check memory usage
php artisan tinker
> memory_get_peak_usage(true)
```

## 📚 Quick Reference

| Command | Purpose |
|---------|---------|
| `php artisan migrate` | Run migrations |
| `php artisan db:seed` | Run seeders |
| `php artisan tinker` | Interactive shell |
| `php artisan route:list` | List all routes |
| `php artisan route:clear` | Clear route cache |
| `php artisan cache:clear` | Clear all caches |
| `php artisan queue:work` | Start queue worker |
| `php artisan log:tail` | Watch logs real-time |

---

**Last Updated**: June 2, 2026
**Version**: 1.0
