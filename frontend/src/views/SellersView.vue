<template>
    <LayoutDefault>
      <div class="actions-container">
        <button @click="openModal" class="button new-seller-button">Novo Vendedor</button>
      </div>
  
      <div v-if="isLoading" class="loading">Carregando vendedores...</div>
  
      <div v-if="!isLoading && sellers.length > 0" class="sellers-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>E-mail</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="seller in sellers" :key="seller.id">
              <td>{{ seller.id }}</td>
              <td>{{ seller.name }}</td>
              <td>{{ seller.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <Pagination
        :currentPage="currentPage || 1"
        :totalPages="totalPages || 1"
        :isLoading="isLoading || false"
        @page-changed="changePage"
      />
  
      <CreateSeller :isOpen="isModalOpen" @close="closeModal" @created="onSellerCreated" />
    </LayoutDefault>
  </template>
  
  <script lang="ts">
  import { defineComponent, onMounted, computed, ref } from 'vue';
  import { useSellersStore } from '@/stores/sellers';
  import LayoutDefault from '@/layouts/LayoutDefault.vue';
  import CreateSeller from '@/components/CreateSeller.vue';
  import Pagination from '@/components/Pagination.vue'; // Importando o componente Pagination
  
  export default defineComponent({
    name: 'SellersView',
    components: {
      LayoutDefault,
      CreateSeller,
      Pagination,
    },
    setup() {
      const sellersStore = useSellersStore();
      const isLoading = computed(() => sellersStore.isLoading);
      const sellers = computed(() => sellersStore.sellers);
      
      // Usar currentPage e totalPages diretamente do store
      const currentPage = computed(() => sellersStore.currentPage);
      const totalPages = computed(() => sellersStore.totalPages);
  
      const isModalOpen = ref(false);
  
      const openModal = () => {
        isModalOpen.value = true;
      };
  
      const closeModal = () => {
        isModalOpen.value = false;
      };
  
      const onSellerCreated = () => {
        fetchSellers();
      };
  
      const fetchSellers = async () => {
        await sellersStore.fetchSellers(currentPage.value);
      };
  
      const changePage = (newPage: number) => {
        if (newPage >= 1 && newPage <= totalPages.value) {
          sellersStore.setPage(newPage);
        }
      };
  
      onMounted(() => {
        fetchSellers();
      });
  
      return {
        isLoading,
        sellers,
        currentPage,
        totalPages,
        isModalOpen,
        openModal,
        closeModal,
        onSellerCreated,
        changePage,
      };
    },
  });
  </script>
  
  <style>
  .sellers-table table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.sellers-table th,
.sellers-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}

.sellers-table th {
  background-color: #f1f5f9;
  color: #1e293b;
}

.sellers-table td {
  background-color: #ffffff;
}

.actions-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 20px;
}

.new-seller-button {
  padding: 10px 20px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 6px;
}

.new-seller-button:hover {
  background-color: #3e8e41;
}

.pagination {
  margin-top: 20px;
  text-align: center;
}

.pagination button {
  padding: 5px 10px;
  margin: 0 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
}

.pagination button:disabled {
  background-color: #ccc;
}

  </style>
  