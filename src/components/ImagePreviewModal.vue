<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  images: {
    type: Object, // { image1: string|null, image2: string|null }
    required: true,
  },
});

const emit = defineEmits(['close']);
</script>

<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">資產圖片預覽</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <!-- Image 1 -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-header text-center fw-bold text-secondary">圖片 1</div>
                <div class="card-body d-flex align-items-center justify-content-center bg-light" style="min-height: 250px;">
                  <img v-if="images.image1" :src="images.image1" class="img-fluid rounded" alt="Image 1" style="max-height: 300px;" />
                  <div v-else class="text-muted">
                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                    <p class="mt-2">未上傳圖片</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Image 2 -->
            <div class="col-md-6">
              <div class="card h-100">
                <div class="card-header text-center fw-bold text-secondary">圖片 2</div>
                <div class="card-body d-flex align-items-center justify-content-center bg-light" style="min-height: 250px;">
                  <img v-if="images.image2" :src="images.image2" class="img-fluid rounded" alt="Image 2" style="max-height: 300px;" />
                  <div v-else class="text-muted">
                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                    <p class="mt-2">未上傳圖片</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">關閉</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  z-index: 1060;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}
</style>
