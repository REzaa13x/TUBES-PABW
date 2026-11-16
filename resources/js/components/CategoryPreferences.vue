<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <span class="text-2xl">❤️</span>
        Kategori Minat Relawan
      </h2>
      <p class="text-gray-600 text-sm mt-2">
        Pilih kategori kegiatan yang Anda minati. Kami akan merekomendasikan
        kampanye baru yang sesuai dengan kategori pilihan Anda.
      </p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="grid grid-cols-2 md:grid-cols-3 gap-3">
      <div class="bg-gray-200 h-24 rounded-lg animate-pulse"></div>
      <div class="bg-gray-200 h-24 rounded-lg animate-pulse"></div>
      <div class="bg-gray-200 h-24 rounded-lg animate-pulse"></div>
    </div>

    <!-- Categories Grid -->
    <div v-else class="grid grid-cols-2 md:grid-cols-3 gap-3">
      <div
        v-for="category in allCategories"
        :key="category"
        class="relative"
      >
        <input
          :id="`category-${category}`"
          type="checkbox"
          v-model="selectedCategories"
          :value="category"
          class="sr-only"
        />
        <label
          :for="`category-${category}`"
          class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition"
          :class="{
            'border-blue-600 bg-blue-50': selectedCategories.includes(category),
          }"
        >
          <!-- Category Icon -->
          <span class="text-3xl mb-2">
            {{ getCategoryIcon(category) }}
          </span>

          <!-- Category Name -->
          <span class="text-sm font-semibold text-gray-900 text-center">
            {{ category }}
          </span>

          <!-- Engagement Score (if exists) -->
          <span
            v-if="getEngagementScore(category) > 0"
            class="text-xs text-gray-500 mt-1"
          >
            {{ getEngagementScore(category) }} kegiatan
          </span>

          <!-- Checkbox Indicator -->
          <div
            v-if="selectedCategories.includes(category)"
            class="absolute top-2 right-2 bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center"
          >
            ✓
          </div>
        </label>
      </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
      <p class="text-sm text-blue-900">
        <span class="font-semibold">💡 Tips:</span> Kategori yang Anda pilih akan
        membantu kami merekomendasikan kampanye yang lebih relevan dengan minat
        dan pengalaman Anda.
      </p>
    </div>

    <!-- Current Preferences (Auto-discovered) -->
    <div v-if="autoDiscoveredCategories.length > 0">
      <h3 class="text-sm font-semibold text-gray-900 mb-3">
        📊 Kategori dari Riwayat Kegiatan Anda
      </h3>
      <div class="flex flex-wrap gap-2">
        <span
          v-for="category in autoDiscoveredCategories"
          :key="category"
          class="bg-gray-100 text-gray-800 text-xs font-medium px-3 py-1 rounded-full"
        >
          {{ category }} (auto-detected)
        </span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
      <p class="text-sm text-red-800">{{ error }}</p>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-3">
      <p class="text-sm text-green-800">{{ successMessage }}</p>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
      <button
        @click="savePreferences"
        :disabled="isSaving"
        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:shadow-lg transition disabled:opacity-50"
      >
        {{ isSaving ? "Menyimpan..." : "Simpan Preferensi" }}
      </button>
      <button
        @click="resetPreferences"
        class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition"
      >
        Reset
      </button>
    </div>

    <!-- Additional Info -->
    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
      <h3 class="font-semibold text-gray-900 text-sm">📋 Penjelasan Kategori</h3>
      <div class="text-sm text-gray-600 space-y-2">
        <p>
          <span class="font-semibold">📚 Pendidikan:</span> Program edukasi,
          pelatihan, dan pemberdayaan masyarakat
        </p>
        <p>
          <span class="font-semibold">🏥 Kesehatan:</span> Program kesehatan,
          vaksinasi, dan pemeriksaan kesehatan gratis
        </p>
        <p>
          <span class="font-semibold">🤝 Sosial:</span> Program sosial,
          pemberdayaan UMKM, dan bantuan kemanusiaan
        </p>
        <p>
          <span class="font-semibold">🌿 Lingkungan:</span> Program lingkungan,
          penanaman pohon, dan kebersihan
        </p>
        <p>
          <span class="font-semibold">🚨 Bencana Alam:</span> Program tanggap
          darurat bencana dan pemulihan
        </p>
        <p>
          <span class="font-semibold">🕌 Keagamaan:</span> Program keagamaan dan
          kegiatan spiritual
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const selectedCategories = ref([]);
const autoDiscoveredCategories = ref([]);
const allCategories = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const error = ref(null);
const successMessage = ref(null);

const allCategoriesData = [
  "Pendidikan",
  "Kesehatan",
  "Sosial",
  "Lingkungan",
  "Bencana",
  "Keagamaan",
];

const categoryIcons = {
  Pendidikan: "📚",
  Kesehatan: "🏥",
  Sosial: "🤝",
  Lingkungan: "🌿",
  Bencana: "🚨",
  Keagamaan: "🕌",
};

const engagementScores = {};

const getCategoryIcon = (category) => {
  return categoryIcons[category] || "📌";
};

const getEngagementScore = (category) => {
  return engagementScores[category] || 0;
};

const loadPreferences = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    // Load available categories
    const categoriesRes = await axios.get(
      "/api/v1/recommendations/categories"
    );
    if (categoriesRes.data.success) {
      allCategories.value = categoriesRes.data.data;
    }

    // Load user preferences
    const prefsRes = await axios.get("/api/v1/recommendations/preferences");
    if (prefsRes.data.success) {
      const preferences = prefsRes.data.data;

      // Separate selected and auto-discovered
      selectedCategories.value = preferences
        .filter((p) => p.is_preferred)
        .map((p) => p.category);

      autoDiscoveredCategories.value = preferences
        .filter((p) => p.auto_discovered && !p.is_preferred)
        .map((p) => p.category);

      // Store engagement scores
      preferences.forEach((p) => {
        engagementScores[p.category] = p.engagement_score;
      });
    }
  } catch (err) {
    console.error("Error loading preferences:", err);
    error.value = "Gagal memuat preferensi. Silakan coba lagi.";
  } finally {
    isLoading.value = false;
  }
};

const savePreferences = async () => {
  isSaving.value = true;
  error.value = null;
  successMessage.value = null;

  try {
    const response = await axios.post(
      "/api/v1/recommendations/preferences",
      {
        categories: selectedCategories.value,
      }
    );

    if (response.data.success) {
      successMessage.value = "✅ Preferensi kategori berhasil disimpan!";
      setTimeout(() => {
        successMessage.value = null;
      }, 3000);

      // Reload preferences
      await loadPreferences();
    }
  } catch (err) {
    console.error("Error saving preferences:", err);
    error.value =
      err.response?.data?.message ||
      "Gagal menyimpan preferensi. Silakan coba lagi.";
  } finally {
    isSaving.value = false;
  }
};

const resetPreferences = () => {
  selectedCategories.value = [];
};

onMounted(() => {
  loadPreferences();
});
</script>

<style scoped></style>
