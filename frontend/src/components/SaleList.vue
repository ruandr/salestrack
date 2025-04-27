<template>
  <div>
    <h1>Vendas</h1>
    <div v-if="sales.length > 0">
      <div v-for="sale in sales" :key="sale.id" class="sale-item">
        <p><strong>ID da Venda:</strong> {{ sale.id }}</p>
        <p><strong>Vendedor:</strong> {{ sale.seller_id }}</p>
        <p><strong>Valor:</strong> {{ sale.amount }}</p>
        <p><strong>Data:</strong> {{ sale.sale_date }}</p>
        <p><strong>Comissão:</strong> {{ sale.commission }}</p>
      </div>
    </div>
    <p v-else>Carregando...</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from 'vue';
import { useSalesStore } from '@/stores/sales';

export default defineComponent({
  name: 'SaleView',
  setup() {
    const salesStore = useSalesStore();

    onMounted(() => {
      salesStore.fetchSales(); 
    });

    return {
      sales: salesStore.sales,
    };
  },
});
</script>
