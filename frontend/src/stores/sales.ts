import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import api from '@/utils/axios';
import { useToast } from 'vue-toastification';

interface Sale {
  id: number;
  seller_id: number;
  amount: number;
  sale_date: string;
}

export const useSalesStore = defineStore('sales', () => {
  const toast = useToast();

  const sales = ref<Sale[]>([]);
  const totalSales = computed(() => sales.value.length);
  const totalAmount = computed(() => sales.value.reduce((sum, sale) => sum + sale.amount, 0));
  const totalPages = ref(1);

  async function fetchSales(page: number = 1, sellerId: number | null = null) {
    try {
      const params: any = { page };
      if (sellerId) {
        params.seller_id = sellerId;
      }
      const response = await api.get('/sales', { params });
      if (response.data.success) {
        sales.value = response.data.data.data;
        totalPages.value = response.data.data.last_page;
      }
    } catch (error) {
      console.error('Error fetching sales:', error);
    }
  }

  async function createSale(saleData: { seller_id: number; amount: number; sale_date: string }) {
    try {
      const response = await api.post('/sales', saleData);
      sales.value.push(response.data.data);
    } catch (error) {
      console.error('Error creating sale:', error);
    }
  }

  async function sendSummaryReport(sellerId: number) {
    try {
      await api.post(`/sales/summary/${sellerId}`);
      toast.success('Relatório enviado com sucesso para o vendedor!');
    } catch (error) {
      toast.error('Erro ao enviar o relatório. Tente novamente.');
      throw error;
    }
  }

  return {
    sales,
    totalSales,
    totalAmount,
    totalPages,
    fetchSales,
    createSale,
    sendSummaryReport,
  };
});
