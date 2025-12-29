<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  assets: {
    type: Array,
    required: true,
  },
  readonly: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['edit', 'delete', 'add']);

const formatCurrency = (value) => {
  // Ensure we use NT$ explicitly as requested
  if (value == null) return 'NT$0';
  return 'NT$' + Number(value).toLocaleString('zh-TW');
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('zh-TW');
};
</script>

<template>
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark">資產清點</h2>
        <button v-if="!readonly" class="btn btn-primary" @click="$emit('add')">
          <span class="me-1">+</span> 新增資產
        </button>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col" class="text-nowrap">資產編號</th>
              <th scope="col" style="min-width: 150px;">品名</th>
              <th scope="col" style="min-width: 150px;">型號</th>
              <th scope="col" class="text-nowrap">成本(含稅)</th>
              <th scope="col" class="text-nowrap">數量</th>
              <th scope="col" class="text-nowrap">發票號碼</th>
              <th scope="col" class="text-nowrap">清點日期</th>
              <th scope="col" class="text-nowrap">清點人</th>
              <th scope="col" class="text-nowrap" v-if="!readonly || assets.some(a => a.image1 || a.image2)">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="assets.length === 0">
              <td colspan="9" class="text-center py-4 text-muted">未找到資產</td>
            </tr>
            <tr v-for="asset in assets" :key="asset.id">
              <td><span class="badge bg-light text-dark border">{{ asset.asset_id }}</span></td>
              <td>
                <div class="fw-medium">{{ asset.name }}</div>
              </td>
              <td class="small text-secondary" style="white-space: pre-wrap;">{{ asset.model }}</td>
              <td class="text-end pe-4">{{ formatCurrency(asset.cost) }}</td>
              <td class="text-center">{{ asset.quantity }}</td>
              <td>{{ asset.invoice_number }}</td>
              <td>{{ formatDate(asset.inventory_date) }}</td>
              <td>{{ asset.inventory_taker }}</td>
              <td>
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-outline-info" @click="$emit('viewImages', asset)" title="圖片">
                    <i class="bi bi-image"></i> 圖片
                  </button>
                  <template v-if="!readonly">
                    <button class="btn btn-outline-primary" @click="$emit('edit', asset)" title="編輯">
                      <i class="bi bi-pencil"></i> 編輯
                    </button>
                    <button class="btn btn-outline-danger" @click="$emit('delete', asset.id)" title="刪除">
                      <i class="bi bi-trash"></i> 刪除
                    </button>
                  </template>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table th {
  font-weight: 600;
  font-size: 0.875rem;
}
.table td {
  font-size: 0.95rem;
}
</style>
