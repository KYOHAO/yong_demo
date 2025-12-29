<script setup>
import { ref, onMounted } from 'vue';
import AssetList from '../components/AssetList.vue';
import ImagePreviewModal from '../components/ImagePreviewModal.vue';
import { useAssets } from '../composables/useAssets';

const { assets, fetchAssets, isLoading } = useAssets();

const isImageModalOpen = ref(false);
const selectedAssetImages = ref({ image1: null, image2: null });

onMounted(() => {
  fetchAssets();
});

const openImageModal = (asset) => {
  selectedAssetImages.value = { 
    image1: asset.image1, 
    image2: asset.image2 
  };
  isImageModalOpen.value = true;
};

const closeImageModal = () => {
  isImageModalOpen.value = false;
  selectedAssetImages.value = { image1: null, image2: null };
};
</script>

<template>
  <div class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
      <h1 class="h2 mb-0 fw-bold text-primary-gradient">資產檢視</h1>
    </header>

    <main>
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <AssetList 
        v-else
        :assets="assets" 
        :readonly="true"
        @viewImages="openImageModal"
      />
    </main>

    <ImagePreviewModal
      v-if="isImageModalOpen"
      :images="selectedAssetImages"
      @close="closeImageModal"
    />
  </div>
</template>

<style scoped>
.text-primary-gradient {
  background: linear-gradient(to right, var(--primary-color), var(--primary-light));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
