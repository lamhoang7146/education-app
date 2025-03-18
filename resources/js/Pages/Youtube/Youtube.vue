<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const videos = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await axios.get('/api/youtube-videos', {
      params: {
        channel_id: 'UCNSCWwgW-rwmoE3Yc4WmJhw',
        max_results: 6,
      },
    });
    videos.value = response.data.items;
  } catch (error) {
    console.error('Error fetching videos:', error);
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4 text-gray-800">YouTube Videos</h1>
    <div v-if="loading" class="text-center">
      <p>Loading...</p>
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="video in videos" :key="video.id.videoId" class="bg-white rounded-lg shadow-md overflow-hidden">
        <iframe
          :src="'https://www.youtube.com/embed/' + video.id.videoId"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          class="w-full h-48"
        ></iframe>
        <div class="p-4">
          <h2 class="text-lg font-semibold text-gray-800">{{ video.snippet.title }}</h2>
          <p class="text-gray-600 text-sm mt-2">{{ video.snippet.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles if needed */
</style>
