<template>
    <div v-if="totalPages > 1 && !isLoading" class="pagination">
      <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1">Anterior</button>
      <span>Página {{ currentPage }} de {{ totalPages }}</span>
      <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= totalPages">Próxima</button>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent, computed, PropType } from 'vue';
  
  export default defineComponent({
    name: 'Pagination',
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      totalPages: {
        type: Number,
        required: true,
      },
      isLoading: {
        type: Boolean,
        required: true,
      },
    },
    methods: {
      goToPage(page: number) {
        if (page >= 1 && page <= this.totalPages) {
          this.$emit('page-changed', page);
        }
      },
    },
  });
  </script>
  
  <style>
  .pagination {
    margin-top: 20px;
    text-align: center;
  }
  
  .pagination button {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .pagination button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  
  .pagination span {
    margin: 0 10px;
  }
  </style>
  