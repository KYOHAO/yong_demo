import { ref } from 'vue';

const API_BASE_url = 'https://test.stockinfo888.com/api/assets';

export function useAssets() {
  const assets = ref([]);
  const error = ref(null);
  const isLoading = ref(false);

  const fetchAssets = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await fetch(API_BASE_url);
      if (!response.ok) throw new Error('Failed to fetch assets');
      assets.value = await response.json();
    } catch (err) {
      error.value = err.message;
      console.error(err);
    } finally {
      isLoading.value = false;
    }
  };

  const addAsset = async (assetData) => {
    isLoading.value = true;
    try {
      const formData = new FormData();
      // Append all text fields
      for (const key in assetData) {
        if (key !== 'image1_file' && key !== 'image2_file' && key !== 'image1' && key !== 'image2') {
          formData.append(key, assetData[key] ?? '');
        }
      }
      // Append files if they exist
      if (assetData.image1_file) formData.append('image1_file', assetData.image1_file);
      if (assetData.image2_file) formData.append('image2_file', assetData.image2_file);

      const response = await fetch(API_BASE_url, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        const errRes = await response.json();
        throw new Error(JSON.stringify(errRes.messages) || 'Failed to add asset');
      }

      const newAsset = await response.json();
      assets.value.push(newAsset);
      return newAsset;
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const updateAsset = async (assetData) => {
    isLoading.value = true;
    try {
      const formData = new FormData();
      // Append all text fields
      for (const key in assetData) {
        if (key !== 'image1_file' && key !== 'image2_file' && key !== 'image1' && key !== 'image2') {
          formData.append(key, assetData[key] ?? '');
        }
      }
      // Append files if they exist (new uploads)
      if (assetData.image1_file) formData.append('image1_file', assetData.image1_file);
      if (assetData.image2_file) formData.append('image2_file', assetData.image2_file);

      // Using POST for update because of multipart/form-data limitations with PUT in some environments
      // The backend route 'assets/(:num)' handles this POST
      const response = await fetch(`${API_BASE_url}/${assetData.id}`, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        const errRes = await response.json();
        throw new Error(JSON.stringify(errRes.messages) || 'Failed to update asset');
      }

      // Refresh list or update local item
      // Simple strategy: fetch all again to ensure consistency or update local
      await fetchAssets();
    } catch (err) {
      error.value = err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const deleteAsset = async (id) => {
    try {
      const response = await fetch(`${API_BASE_url}/${id}`, {
        method: 'DELETE',
      });

      if (!response.ok) throw new Error('Failed to delete asset');

      assets.value = assets.value.filter((a) => a.id !== id);
    } catch (err) {
      error.value = err.message;
      alert('Delete failed: ' + err.message);
    }
  };

  return {
    assets,
    error,
    isLoading,
    fetchAssets,
    addAsset,
    updateAsset,
    deleteAsset,
  };
}
