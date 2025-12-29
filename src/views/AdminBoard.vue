<script setup>
import { ref, onMounted, computed } from 'vue';
import AssetList from '../components/AssetList.vue';
import AssetForm from '../components/AssetForm.vue';
import ImagePreviewModal from '../components/ImagePreviewModal.vue';
import { useAssets } from '../composables/useAssets';

const { assets, fetchAssets, addAsset, updateAsset, deleteAsset, isLoading } = useAssets();

const isModalOpen = ref(false);
const isImageModalOpen = ref(false);
const currentAssetToEdit = ref(null);
const selectedAssetImages = ref({ image1: null, image2: null });
const currentType = ref(1);

const filteredAssets = computed(() => {
  return assets.value.filter(asset => {
    // Ensure both are treated as numbers for comparison
    const assetType = asset.type ? Number(asset.type) : 1;
    return assetType === currentType.value;
  });
});

onMounted(() => {
  fetchAssets();
});

const openAddModal = () => {
  currentAssetToEdit.value = null;
  isModalOpen.value = true;
};

const openEditModal = (asset) => {
  currentAssetToEdit.value = { ...asset };
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  currentAssetToEdit.value = null;
};

const handleSave = async (assetData) => {
  try {
    if (currentAssetToEdit.value) {
      await updateAsset(assetData);
    } else {
      if (!assetData.name || !assetData.model) {
        alert('請填寫必填欄位');
        return;
      }
      await addAsset(assetData);
    }
    closeModal();
  } catch (e) {
    alert('儲存失敗: ' + e.message);
  }
};

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

const handleDelete = async (id) => {
  if (confirm('確定要刪除此資產嗎?')) {
    await deleteAsset(id);
  }
};
</script>

<template>
  <div class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
      <h1 class="h2 mb-0 fw-bold text-primary-gradient">資產管理</h1>
      <div class="user-profile bg-white shadow-sm px-3 py-2 rounded-pill">
        <span class="fw-semibold text-secondary">管理員</span>
      </div>
    </header>

    <main>
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div v-else>
        <ul class="nav nav-tabs mb-4">
          <li class="nav-item">
            <a class="nav-link" :class="{ active: currentType === 1 }" href="#" @click.prevent="currentType = 1">菜鳥資產清點</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" :class="{ active: currentType === 2 }" href="#" @click.prevent="currentType = 2">其特資產清點</a>
          </li>
        </ul>
        <AssetList 
          :assets="filteredAssets" 
          @add="openAddModal"
          @edit="openEditModal"
          @delete="handleDelete"
          @viewImages="openImageModal"
        />
      </div>
    </main>

    <AssetForm 
      v-if="isModalOpen"
      :assetToEdit="currentAssetToEdit"
      :initialType="currentType"
      @save="handleSave"
      @cancel="closeModal"
    />

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
