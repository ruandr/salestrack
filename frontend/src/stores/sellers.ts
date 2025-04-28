import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import api from '@/utils/axios';

interface Seller {
  id: number;
  name: string;
  email: string;
}

export const useSellersStore = defineStore('sellers', () => {
  const sellers = ref<Seller[]>([]);
  const totalSellers = computed(() => sellers.value.length);
  const totalPages = ref(1);
  const currentPage = ref(1);
  const perPage = ref(15);

  async function fetchSellers(page: number = 1, per_page: number = 15) {
    try {
      const params: any = { page, per_page };
      const response = await api.get('/sellers', { params });
      if (response.data.success) {
        sellers.value = response.data.data.data;
        totalPages.value = response.data.data.last_page;
        currentPage.value = page;
      }
    } catch (error) {
      console.error('Error fetching sellers:', error);
    }
  }

  async function fetchSellerById(sellerId: number) {
    try {
      const response = await api.get(`/sellers/${sellerId}`);
      if (response.data.success) {
        return response.data.data;
      }
    } catch (error) {
      console.error(`Error fetching seller with id ${sellerId}:`, error);
    }
  }

  async function createSeller(sellerData: { name: string; email: string; }) {
    try {
      const response = await api.post('/sellers', sellerData);
      sellers.value.push(response.data.data);
    } catch (error) {
      console.error('Error creating seller:', error);
    }
  }

  async function updateSeller(sellerId: number, sellerData: { name: string; email: string }) {
    try {
      const response = await api.put(`/sellers/${sellerId}`, sellerData);
      const index = sellers.value.findIndex(seller => seller.id === sellerId);
      if (index !== -1) {
        sellers.value[index] = response.data.data;
      }
    } catch (error) {
      console.error(`Error updating seller with id ${sellerId}:`, error);
    }
  }

  async function deleteSeller(sellerId: number) {
    try {
      await api.delete(`/sellers/${sellerId}`);
      sellers.value = sellers.value.filter(seller => seller.id !== sellerId);
    } catch (error) {
      console.error(`Error deleting seller with id ${sellerId}:`, error);
    }
  }

  function setPage(page: number) {
    if (page >= 1 && page <= totalPages.value) {
      fetchSellers(page, perPage.value);
    }
  }

  return {
    sellers,
    totalSellers,
    totalPages,
    currentPage,
    perPage,
    fetchSellers,
    fetchSellerById,
    createSeller,
    updateSeller,
    deleteSeller,
    setPage, 
  };
});
