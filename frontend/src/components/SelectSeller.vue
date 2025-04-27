<template>
  <div class="select-seller">
    <label for="seller">Vendedor:</label>
    <select v-model="selectedSellerId" @change="onSellerChange">
      <option value="" disabled>Selecione um vendedor</option>
      <option v-for="seller in sellers" :key="seller.id" :value="seller.id">
        {{ seller.name }}
      </option>
    </select>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from 'vue';
import { useSellersStore } from '@/stores/sellers';

export default defineComponent({
  name: 'SelectSeller',
  emits: ['update:sellerId'],
  setup(_, { emit }) {
    const sellersStore = useSellersStore();
    const selectedSellerId = ref<number | null>(null);

    const sellers = computed(() => sellersStore.sellers);

    onMounted(() => {
      sellersStore.fetchSellers(1, 100);
    });

    const onSellerChange = () => {
      emit('update:sellerId', selectedSellerId.value);
    };

    return {
      selectedSellerId,
      sellers,
      onSellerChange,
    };
  },
});
</script>

<style scoped>
.select-seller {
  margin-bottom: 1rem;
}

select {
  padding: 8px;
  width: 100%;
  border-radius: 4px;
  border: 1px solid #ddd;
}
</style>
