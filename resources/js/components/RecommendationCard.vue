<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <span class="text-2xl">⭐</span>
        Rekomendasi Untuk Anda
      </h2>
      <button
        @click="loadRecommendations"
        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
      >
        Refresh
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="space-y-3">
      <div class="bg-gray-200 h-32 rounded-lg animate-pulse"></div>
      <div class="bg-gray-200 h-32 rounded-lg animate-pulse"></div>
      <div class="bg-gray-200 h-32 rounded-lg animate-pulse"></div>
    </div>

    <!-- Empty State -->
    <div
      v-else-if="recommendations.length === 0"
      class="text-center py-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100"
    >
      <div class="text-4xl mb-3">🎯</div>
      <h3 class="text-lg font-semibold text-gray-900 mb-2">
        Belum Ada Rekomendasi
      </h3>
      <p class="text-gray-600 mb-6">
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

    <!-- Recommendations Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="rec in recommendations"
        :key="rec.id"
        class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden border border-gray-100"
      >
        <!-- Campaign Image -->
        <div class="relative h-48 bg-gray-200 overflow-hidden">
          <img
            v-if="rec.image"
            :src="rec.image"
            :alt="rec.judul"
            class="w-full h-full object-cover"
          />
          <!-- Badge -->
          <div
            class="absolute top-2 right-2 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1"
          >
            <span>⭐</span> Rekomendasi
          </div>
          <!-- Category Badge -->
          <div
            class="absolute bottom-2 left-2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold"
          >
            {{ rec.kategori }}
          </div>
        </div>

        <!-- Content -->
        <div class="p-4">
          <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">
            {{ rec.judul }}
          </h3>

          <!-- Meta Info -->
          <div class="space-y-2 text-sm text-gray-600 mb-4">
            <div class="flex items-center gap-2">
              <span>📍</span>
              {{ rec.lokasi }}
            </div>
            <div class="flex items-center gap-2">
              <span>📅</span>
              {{ formatDate(rec.tanggal_mulai) }}
            </div>
            <div class="flex items-center gap-2">
              <span>👥</span>
              {{ rec.kuota_terisi }}/{{ rec.kuota_total }} relawan
            </div>
          </div>

          <!-- Progress Bar -->
          <div class="mb-4">
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-blue-600 h-2 rounded-full transition-all"
                :style="{
                  width:
                    (rec.kuota_terisi / rec.kuota_total) * 100 + '%',
                }"
              ></div>
            </div>
          </div>

          <!-- Reason -->
          <p class="text-xs text-gray-500 mb-4 italic">
            {{ rec.matching_reason }}
          </p>

          <!-- Actions -->
          <div class="flex gap-2">
            <a
              :href="`/campaigns/${rec.slug}`"
              class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium text-center hover:bg-blue-700 transition"
            >
              Lihat Detail
            </a>
            <button
              @click="applyAsCampaign(rec.id)"
              class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition"
            >
              Daftar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- See All Button -->
    <div v-if="recommendations.length > 0" class="text-center pt-4">
      <a
        href="/notifications?filter=recommendations"
        class="inline-block text-blue-600 hover:text-blue-700 font-medium"
      >
        Lihat Semua Rekomendasi →
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const recommendations = ref([]);
const isLoading = ref(false);
const error = ref(null);

const loadRecommendations = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    const response = await axios.get("/api/v1/recommendations/campaigns", {
      params: {
        limit: 9,
      },
    });

    if (response.data.success) {
      recommendations.value = response.data.data;
    }
  } catch (err) {
    console.error("Error loading recommendations:", err);
    error.value = err.message;
  } finally {
    isLoading.value = false;
  }
};

const applyAsCampaign = (campaignId) => {
  // Implement campaign application
  alert("Redirect to apply campaign: " + campaignId);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

onMounted(() => {
  loadRecommendations();
});
</script>

<style scoped>
.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}
</style>
