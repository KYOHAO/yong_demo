<script setup>
import { ref, reactive, watch, computed } from 'vue';

const props = defineProps({
  assetToEdit: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['save', 'cancel']);

const form = reactive({
  id: '',
  name: '',
  image1: null, // URL or base64 (display)
  image1_file: null, // Raw File Object (upload)
  image2: null,
  image2_file: null,
  model: '',
  cost: 0,
  quantity: 1,
  assetId: '',
  invoiceNumber: '',
  inventoryDate: new Date().toISOString().split('T')[0],
  inventoryTaker: '',
  createdAt: '',
  updatedAt: '',
});

const imagePreview1 = ref(null);
const imagePreview2 = ref(null);

const isEditMode = computed(() => !!props.assetToEdit);

// Initialize form
watch(
  () => props.assetToEdit,
  (newVal) => {
    if (newVal) {
      Object.assign(form, newVal);
      // Ensure file objects are cleared when editing a new item, unless we want to keep them?
      // Usually, when editing, we start with no *new* file selected.
      form.image1_file = null;
      form.image2_file = null;
      
      imagePreview1.value = newVal.image1;
      imagePreview2.value = newVal.image2;
    } else {
      resetForm();
    }
  },
  { immediate: true }
);

function resetForm() {
  Object.assign(form, {
    id: '',
    name: '',
    image1: null,
    image1_file: null,
    image2: null,
    image2_file: null,
    model: '',
    cost: 0,
    quantity: 1,
    asset_id: '',
    invoice_number: '',
    inventory_date: new Date().toISOString().split('T')[0],
    inventory_taker: '',
    created_at: '',
    updated_at: '',
  });
  imagePreview1.value = null;
  imagePreview2.value = null;
}

const handleFileChange = (event, imageKey) => {
  const file = event.target.files[0];
  if (file) {
    // Store raw file for upload
    if (imageKey === 'image1') form.image1_file = file;
    if (imageKey === 'image2') form.image2_file = file;

    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
      const result = e.target.result;
      // We don't necessarily update form.image1 (the URL) here, 
      // just the preview. But for consistency with current logic:
      if (imageKey === 'image1') imagePreview1.value = result;
      if (imageKey === 'image2') imagePreview2.value = result;
    };
    reader.readAsDataURL(file);
  }
};

const handleSubmit = () => {
  emit('save', { ...form });
};
</script>

<template>
  <div class="modal-overlay" @click.self="$emit('cancel')">
    <div class="modal-content modal-lg">
      <div class="modal-header border-bottom-0 pb-0">
        <h3 class="modal-title h5 fw-bold">{{ isEditMode ? '編輯資產' : '新增資產' }}</h3>
        <button type="button" class="btn-close" @click="$emit('cancel')"></button>
      </div>
      
      <form @submit.prevent="handleSubmit" class="p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">資產編號</label>
            <input v-model="form.asset_id" type="text" class="form-control" required placeholder="請輸入資產編號" />
          </div>

          <div class="col-md-6">
            <label class="form-label">品名</label>
            <input v-model="form.name" type="text" class="form-control" required placeholder="請輸入品名" />
          </div>

          <div class="col-12">
             <label class="form-label">型號</label>
             <textarea v-model="form.model" class="form-control" rows="3" placeholder="請輸入型號"></textarea>
          </div>

          <div class="col-md-6">
            <label class="form-label">成本(含稅)</label>
            <div class="input-group">
              <span class="input-group-text">NT$</span>
              <input v-model.number="form.cost" type="number" class="form-control" min="0" required />
            </div>
          </div>

          <div class="col-md-6">
             <label class="form-label">數量</label>
             <input v-model.number="form.quantity" type="number" class="form-control" min="1" required />
          </div>

          <div class="col-md-6">
            <label class="form-label">發票號碼</label>
             <input v-model="form.invoice_number" type="text" class="form-control" placeholder="請輸入發票號碼" />
          </div>

          <div class="col-md-6">
             <label class="form-label">清點日期</label>
             <input v-model="form.inventory_date" type="date" class="form-control" required />
          </div>
          
           <div class="col-md-12">
             <label class="form-label">盤點人</label>
             <input v-model="form.inventory_taker" type="text" class="form-control" />
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3 text-center bg-light">
              <label class="form-label d-block mb-2">圖片1</label>
              <input type="file" class="form-control form-control-sm mb-2" accept="image/*" @change="(e) => handleFileChange(e, 'image1')" />
              <div v-if="imagePreview1" class="preview-box mt-2 border bg-white">
                <img :src="imagePreview1" alt="Preview 1" class="img-fluid" style="max-height: 100px; object-fit: contain;" />
              </div>
            </div>
          </div>

          <div class="col-md-6">
             <div class="border rounded p-3 text-center bg-light">
              <label class="form-label d-block mb-2">圖片2</label>
              <input type="file" class="form-control form-control-sm mb-2" accept="image/*" @change="(e) => handleFileChange(e, 'image2')" />
              <div v-if="imagePreview2" class="preview-box mt-2 border bg-white">
                 <img :src="imagePreview2" alt="Preview 2" class="img-fluid" style="max-height: 100px; object-fit: contain;" />
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
          <button type="button" class="btn btn-secondary" @click="$emit('cancel')">取消</button>
          <button type="submit" class="btn btn-primary px-4">{{ isEditMode ? '更新' : '新增' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5); /* Bootstrap-like backdrop */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1055; /* Higher than sticky navs */
  padding: 1rem;
}

.modal-content {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  max-height: 90vh;
  overflow-y: auto;
  animation: slideDown 0.3s ease-out;
}

.modal-lg {
  width: 100%;
  max-width: 800px;
}

/* Custom Animation matching Bootstrap feel */
@keyframes slideDown {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
