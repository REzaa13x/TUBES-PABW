<template>
  <div class="space-y-3">
    <!-- Rekomendasi Tab Header -->
    <div class="px-4 py-3 border-b border-gray-100">
      <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
        <span class="text-lg">⭐</span>
        Rekomendasi Untuk Anda
      </h3>
      <p class="text-xs text-gray-500 mt-1">
        Kampanye baru sesuai kategori yang Anda ikuti
      </p>
    </div>

    <!-- Loading -->
    <div v-if="isLoading" class="px-4 py-3 space-y-2">
      <div class="bg-gray-200 h-4 rounded animate-pulse"></div>
      <div class="bg-gray-200 h-4 rounded animate-pulse w-3/4"></div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="notifications.length === 0"
      class="px-4 py-6 text-center text-gray-500"
    >
      <div class="text-2xl mb-2">✨</div>
      <p class="text-sm">Belum ada rekomendasi kampanye</p>
    </div>

    <!-- Notifications List -->
    <div v-else class="divide-y divide-gray-100">
      <div
        v-for="notif in notifications.slice(0, 3)"
        :key="notif.id"
        class="px-4 py-3 hover:bg-blue-50 transition cursor-pointer group relative"
        @click="viewCampaign(notif.campaign.slug)"
      >
        <!-- Close Button -->
        <button
          @click.stop="dismissNotification(notif.id)"
          class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition"
          title="Tutup"
        >
          ✕
        </button>

        <!-- Notification Content -->
        <div class="pr-6">
          <!-- Campaign Title -->
          <h4 class="font-semibold text-gray-900 text-sm line-clamp-1">
            {{ notif.campaign.judul }}
          </h4>

          <!-- Category -->
          <span
            class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mt-1"
          >
            {{ notif.campaign.kategori }}
          </span>

          <!-- Location & Time -->
          <div class="text-xs text-gray-600 mt-1">
            <span>📍 {{ notif.campaign.lokasi }}</span>
            <span class="block text-gray-400">{{ notif.time_ago }}</span>
          </div>

          <!-- Quick Action -->
          <div class="mt-2 flex gap-2">
            <button
              @click.stop="viewCampaign(notif.campaign.slug)"
              class="flex-1 text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition"
            >
              Lihat
            </button>
            <button
              @click.stop="applyCampaign(notif.campaign.id)"
              class="flex-1 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200 transition"
            >
              Daftar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- See All Link -->
    <div v-if="notifications.length > 0" class="px-4 py-3 text-center border-t border-gray-100">
      <a
        href="/notifications?filter=recommendations"
        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
      >
        Lihat Semua Rekomendasi →
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const notifications = ref([]);
const isLoading = ref(false);

const loadRecommendations = async () => {
  isLoading.value = true;

  try {
    const response = await axios.get(
      "/api/v1/recommendations/notifications",
      {
        params: { limit: 3 },
      }
    );

    if (response.data.success) {
      notifications.value = response.data.data;
    }
  } catch (err) {
    console.error("Error loading recommendations:", err);
  } finally {
    isLoading.value = false;
  }
};

const dismissNotification = async (notificationId) => {
  try {
    await axios.put(
      `/api/v1/recommendations/notifications/${notificationId}/dismiss`
    );

    // Remove dari list
    notifications.value = notifications.value.filter(
      (n) => n.id !== notificationId
    );
  } catch (err) {
    console.error("Error dismissing notification:", err);
  }
};

const viewCampaign = (slug) => {
  window.location.href = `/campaigns/${slug}`;
};

const applyCampaign = (campaignId) => {
  // Redirect ke form aplikasi
  alert("Apply campaign: " + campaignId);
};

onMounted(() => {
  loadRecommendations();

  // Refresh setiap 30 detik
  setInterval(loadRecommendations, 30000);
});
</script>

<style scoped>
.line-clamp-1 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
}
</style>
