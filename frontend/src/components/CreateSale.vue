<template>
    <div v-if="isOpen" class="modal-overlay">
      <div class="modal-content">
        <h2>Criar Nova Venda</h2>
  
        <SelectSeller v-model:sellerId="sellerId" />
  
        <input
          type="number"
          v-model="amount"
          placeholder="Valor da venda"
          class="input"
        />
  
        <input
          type="date"
          v-model="saleDate"
          placeholder="Data da venda"
          class="input"
        />
  
        <div class="button-group">
          <button @click="createSale" class="create-button">Salvar</button>
          <button @click="closeModal" class="cancel-button">Cancelar</button>
        </div>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent, ref } from 'vue';
  import { useSalesStore } from '@/stores/sales';
  import { useToast } from 'vue-toastification';
  import SelectSeller from '@/components/SelectSeller.vue';
  
  export default defineComponent({
    name: 'CreateSale',
    components: {
      SelectSeller,
    },
    props: {
      isOpen: {
        type: Boolean,
        required: true,
      },
    },
    emits: ['close', 'created'],
    setup(_, { emit }) {
      const salesStore = useSalesStore();
      const toast = useToast();
  
      const sellerId = ref<number | null>(null);
      const amount = ref<number | null>(null);
      const saleDate = ref<string>('');
  
      const createSale = async () => {
        if (!sellerId.value || !amount.value || !saleDate.value) {
          toast.error('Preencha todos os campos!');
          return;
        }
  
        try {
          await salesStore.createSale({
            seller_id: sellerId.value,
            amount: amount.value,
            sale_date: saleDate.value,
          });
  
          toast.success('Venda criada com sucesso!');
          emit('created');
          closeModal();
        } catch (error) {
          toast.error('Erro ao criar a venda.');
        }
      };
  
      const closeModal = () => {
        emit('close');
        sellerId.value = null;
        amount.value = null;
        saleDate.value = '';
      };
  
      return {
        sellerId,
        amount,
        saleDate,
        createSale,
        closeModal,
      };
    },
  });
  </script>
  
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background: white;
    padding: 24px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
  }
  
  .input {
    width: 100%;
    padding: 8px;
    margin: 8px 0 16px 0;
    border-radius: 4px;
    border: 1px solid #ccc;
  }
  
  .button-group {
    display: flex;
    gap: 10px;
  }
  
  .create-button {
    flex: 1;
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
  }
  
  .cancel-button {
    flex: 1;
    padding: 10px;
    background-color: #f44336;
    color: white;
    border: none;
    border-radius: 4px;
  }
  </style>
  