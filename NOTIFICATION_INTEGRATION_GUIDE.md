# Integrasi Smart Recommendation di Halaman Notifikasi

## 📝 Filter Notifikasi

Tambahkan filter baru di halaman notifikasi untuk menampilkan berbagai tipe notifikasi:

```html
<!-- Filter Tabs -->
<div class="flex gap-2 border-b border-gray-200 mb-6">
  <button
    @click="activeFilter = 'all'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'all'}"
    class="px-4 py-2 border-b-2 font-medium transition"
  >
    Semua
  </button>
  <button
    @click="activeFilter = 'donation'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'donation'}"
    class="px-4 py-2 border-b-2 font-medium transition"
  >
    Donasi
  </button>
  <button
    @click="activeFilter = 'campaign'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'campaign'}"
    class="px-4 py-2 border-b-2 font-medium transition"
  >
    Kampanye
  </button>
  <button
    @click="activeFilter = 'volunteer'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'volunteer'}"
    class="px-4 py-2 border-b-2 font-medium transition"
  >
    Relawan
  </button>
  <button
    @click="activeFilter = 'system'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'system'}"
    class="px-4 py-2 border-b-2 font-medium transition"
  >
    Sistem
  </button>
  <!-- NEW: Rekomendasi Filter -->
  <button
    @click="activeFilter = 'recommendation'"
    :class="{'border-blue-600 text-blue-600': activeFilter === 'recommendation'}"
    class="px-4 py-2 border-b-2 font-medium transition flex items-center gap-1"
  >
    <span class="text-lg">⭐</span>
    Rekomendasi
  </button>
</div>
```

## 🎯 Vue Script Logic

```javascript
import { ref, computed } from 'vue';
import axios from 'axios';

const activeFilter = ref('all');
const notifications = ref([]);
const isLoading = ref(false);

// Computed property untuk filter notifikasi
const filteredNotifications = computed(() => {
  if (activeFilter.value === 'all') {
    return notifications.value;
  } else if (activeFilter.value === 'recommendation') {
    return notifications.value.filter(n => n.type === 'recommendation');
  } else {
    return notifications.value.filter(n => n.category === activeFilter.value);
  }
});

// Load notifikasi dari API
const loadNotifications = async () => {
  isLoading.value = true;
  
  try {
    const response = await axios.get('/api/v1/notifications');
    
    if (response.data.success) {
      // Gabungkan regular notifications dengan recommendation notifications
      const regularNotifications = response.data.data || [];
      
      // Fetch recommendation notifications separately
      const recResponse = await axios.get('/api/v1/recommendations/notifications?limit=100');
      const recommendationNotifications = recResponse.data.data || [];
      
      // Format recommendation notifications
      const formattedRecommendations = recommendationNotifications.map(n => ({
        id: n.id,
        type: 'recommendation',
        category: 'recommendation',
        title: '⭐ Kampanye Baru Sesuai Minat Anda',
        message: n.reason,
        campaign: n.campaign,
        matching_category: n.matching_category,
        created_at: n.created_at,
        time_ago: n.time_ago,
        badge: '⭐ Rekomendasi'
      }));
      
      notifications.value = [...regularNotifications, ...formattedRecommendations];
    }
  } catch (err) {
    console.error('Error loading notifications:', err);
  } finally {
    isLoading.value = false;
  }
};

// Call on mounted
onMounted(() => {
  loadNotifications();
});
```

## 🎨 Kartu Notifikasi Rekomendasi

```html
<!-- Recommendation Notification Card -->
<div
  v-if="notification.type === 'recommendation'"
  class="bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-lg p-4 hover:shadow-lg transition"
>
  <!-- Badge & Close -->
  <div class="flex items-start justify-between mb-3">
    <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
      <span>⭐</span> Rekomendasi
    </span>
    <button
      @click="dismissNotification(notification.id)"
      class="text-gray-400 hover:text-red-500 transition"
    >
      ✕
    </button>
  </div>

  <!-- Title -->
  <h3 class="font-bold text-gray-900 mb-2">{{ notification.campaign.judul }}</h3>

  <!-- Campaign Info -->
  <div class="space-y-1 text-sm text-gray-700 mb-3">
    <p>
      <span class="font-semibold">Kategori:</span> {{ notification.campaign.kategori }}
    </p>
    <p>
      <span class="font-semibold">📍 Lokasi:</span> {{ notification.campaign.lokasi }}
    </p>
    <p>
      <span class="font-semibold">Status:</span> 
      <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
        Membutuhkan Relawan
      </span>
    </p>
    <p class="text-gray-500 italic mt-2">
      {{ notification.time_ago }}
    </p>
  </div>

  <!-- Reason -->
  <div class="bg-white bg-opacity-50 border-l-2 border-yellow-400 pl-3 py-2 mb-3 rounded text-sm text-gray-700">
    <p class="italic">{{ notification.message }}</p>
  </div>

  <!-- Buttons -->
  <div class="flex gap-2">
    <a
      :href="`/campaigns/${notification.campaign.slug}`"
      class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium text-center hover:bg-blue-700 transition"
    >
      Lihat Detail Kampanye
    </a>
    <button
      @click="applyAsVolunteer(notification.campaign.id)"
      class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition"
    >
      Daftar Sebagai Relawan
    </button>
  </div>
</div>
```

## 📋 Halaman Notifikasi - Struktur Lengkap

### Header dengan Informasi
```html
<div class="mb-6">
  <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifikasi Anda</h1>
  <p class="text-gray-600">
    Kelola dan lihat semua notifikasi dari Dongiv
  </p>
  
  <!-- Unread Count -->
  <div class="mt-4 flex gap-4">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
      <p class="text-sm text-gray-600">Total Notifikasi</p>
      <p class="text-2xl font-bold text-blue-600">{{ totalNotifications }}</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
      <p class="text-sm text-gray-600">⭐ Rekomendasi Baru</p>
      <p class="text-2xl font-bold text-yellow-600">{{ recommendationCount }}</p>
    </div>
  </div>
</div>
```

### Pagination (jika perlu)
```html
<!-- Pagination -->
<div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
  <button
    @click="previousPage"
    :disabled="currentPage === 1"
    class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 disabled:opacity-50"
  >
    ← Sebelumnya
  </button>
  
  <span class="text-gray-600">
    Halaman {{ currentPage }} dari {{ totalPages }}
  </span>
  
  <button
    @click="nextPage"
    :disabled="currentPage === totalPages"
    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
  >
    Selanjutnya →
  </button>
</div>
```

## 🔌 API Integration

### Load semua notifikasi dengan pagination
```javascript
const loadNotifications = async (page = 1) => {
  try {
    // Load regular notifications
    const notifResponse = await axios.get('/api/v1/notifications', {
      params: { page, limit: 10 }
    });
    
    // Load recommendation notifications
    const recResponse = await axios.get('/api/v1/recommendations/notifications', {
      params: { page, limit: 10 }
    });
    
    // Merge dan sort by created_at
    const allNotifications = [
      ...notifResponse.data.data,
      ...recResponse.data.data
    ].sort((a, b) => 
      new Date(b.created_at) - new Date(a.created_at)
    );
    
    notifications.value = allNotifications;
    totalNotifications.value = allNotifications.length;
    recommendationCount.value = recResponse.data.total;
  } catch (err) {
    console.error('Error loading notifications:', err);
  }
};
```

### Mark as read
```javascript
const markAsRead = async (notificationId) => {
  try {
    await axios.put(
      `/api/v1/recommendations/notifications/${notificationId}/read`
    );
  } catch (err) {
    console.error('Error marking as read:', err);
  }
};
```

### Dismiss notification
```javascript
const dismissNotification = async (notificationId) => {
  try {
    await axios.put(
      `/api/v1/recommendations/notifications/${notificationId}/dismiss`
    );
    
    // Remove dari list
    notifications.value = notifications.value.filter(
      n => n.id !== notificationId
    );
  } catch (err) {
    console.error('Error dismissing notification:', err);
  }
};
```

## 📱 Responsive Design

```html
<!-- Mobile-first responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4">
  <!-- Notification Cards go here -->
</div>

<!-- For tablet and desktop: show sidebar filter -->
<div class="hidden lg:block fixed left-0 top-0 w-64 h-screen bg-gray-50 border-r border-gray-200">
  <!-- Filter sidebar -->
</div>
```

## ✨ Empty State untuk Rekomendasi

```html
<div
  v-if="filteredNotifications.length === 0 && activeFilter === 'recommendation'"
  class="text-center py-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100"
>
  <div class="text-5xl mb-4">🎯</div>
  <h3 class="text-xl font-semibold text-gray-900 mb-2">
    Belum Ada Rekomendasi
  </h3>
  <p class="text-gray-600 mb-4">
    Saat ini belum ada kampanye baru yang sesuai dengan kategori kegiatan
    relawan yang pernah Anda ikuti.
  </p>
  <p class="text-gray-600 mb-6">
    Tetap semangat menyebarkan kebaikan bersama Dongiv 💙
  </p>
  <a
    href="/campaigns"
    class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-lg transition"
  >
    Jelajahi Semua Kampanye
  </a>
</div>
```

## 🎨 Styling Classes (Tailwind)

Rekomendasi menggunakan:
- Border: `border-yellow-200`
- Background: `from-yellow-50 to-amber-50`
- Text: `text-yellow-600`
- Badge: `bg-yellow-400 text-gray-900`
- Icon: `⭐`

Untuk konsistensi dengan desain Dongiv:
- Primary Blue: `#2563EB` (blue-600)
- Secondary Indigo: `#4F46E5` (indigo-600)
- Accent Yellow: `#FBBF24` (amber-400)
- Font: Poppins

---

**Updated**: June 2, 2026
