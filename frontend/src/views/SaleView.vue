<template>
    <LayoutDefault>
        <div class="actions-container">
            <div class="filter-group">
                <SelectSeller v-model:sellerId="searchSellerId" />
                <button @click="applyFilter" class="button filter-button">Filtrar</button>
            </div>

            <div class="action-group">
                <button @click="openModal" class="button new-sale-button">Nova Venda</button>
                <button v-if="searchSellerId" @click="sendReport" class="button send-report-button">
                    Enviar Relatório
                </button>
            </div>
        </div>

        <div v-if="sales.length > 0" class="sales-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendedor</th>
                        <th>Valor</th>
                        <th>Comissão</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sale in sales" :key="sale.id">
                        <td>{{ sale.id }}</td>
                        <td>{{ sale.name }}</td>
                        <td>{{ formatCurrency(sale.amount) }}</td>
                        <td>{{ formatCurrency(sale.commission) }}</td>
                        <td>{{ formatDate(sale.sale_date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else v-if="isLoading">Carregando vendas...</p>

        <Pagination :currentPage="currentPage" :totalPages="totalPages" :isLoading="isLoading"
            @page-changed="goToPage" />

        <CreateSale :isOpen="isModalOpen" @close="closeModal" @created="onSaleCreated" />
    </LayoutDefault>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, computed } from 'vue';
import { useSalesStore } from '@/stores/sales';
import LayoutDefault from '@/layouts/LayoutDefault.vue';
import SelectSeller from '@/components/SelectSeller.vue';
import CreateSale from '@/components/CreateSale.vue';
import Pagination from '@/components/Pagination.vue';

export default defineComponent({
    name: 'SaleView',
    components: {
        LayoutDefault,
        SelectSeller,
        CreateSale,
        Pagination,
    },
    setup() {
        const salesStore = useSalesStore();
        const currentPage = ref(1);
        const totalPages = ref(1);
        const searchSellerId = ref<number | null>(null);
        const isLoading = computed(() => salesStore.isLoading);
        const sales = computed(() => salesStore.sales);
        const isModalOpen = ref(false);

        const openModal = () => {
            isModalOpen.value = true;
        };

        const closeModal = () => {
            isModalOpen.value = false;
        };

        const applyFilter = () => {
            currentPage.value = 1;
            fetchSales();
        };

        const onSaleCreated = () => {
            fetchSales();
        };

        const fetchSales = async () => {
            try {
                await salesStore.fetchSales(currentPage.value, searchSellerId.value);
                totalPages.value = salesStore.totalPages;
            } catch (error) {
                console.error('Erro ao buscar vendas:', error);
                if (error.response && error.response.status === 401) {
                    alert('Sessão expirada. Por favor, faça login novamente.');
                }
            }
        };

        const sendReport = async () => {
            if (searchSellerId.value) {
                await salesStore.sendSummaryReport(searchSellerId.value);
            }
        };

        const formatCurrency = (value: string) => {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(parseFloat(value));
        };

        const formatDate = (date: string) => {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('pt-BR', options);
        };

        const goToPage = (page: number) => {
            if (page >= 1 && page <= totalPages.value) {
                currentPage.value = page;
                fetchSales();
            }
        };

        onMounted(() => {
            fetchSales();
        });

        return {
            sales,
            isLoading,
            currentPage,
            totalPages,
            searchSellerId,
            applyFilter,
            sendReport,
            goToPage,
            formatCurrency,
            formatDate,
            onSaleCreated,
            isModalOpen,
            openModal,
            closeModal,
        };
    },
});
</script>

<style>
.sales-table table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.sales-table th,
.sales-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.sales-table th {
    background-color: #f1f5f9;
    color: #1e293b;
}

.sales-table td {
    background-color: #ffffff;
}

.sales-table td,
.sales-table th {
    font-size: 14px;
}

.filter-container {
    margin-bottom: 20px;
}

.filter-button,
.send-report-button {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.send-report-button {
    background-color: #2196f3;
}

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

.actions-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
    justify-content: space-between;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.action-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.button {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter-button {
    background-color: #6c63ff;
    color: white;
}

.filter-button:hover {
    background-color: #5750e5;
}

.new-sale-button {
    background-color: #4caf50;
    color: white;
}

.new-sale-button:hover {
    background-color: #3e8e41;
}

.send-report-button {
    background-color: #f44336;
    color: white;
}

.send-report-button:hover {
    background-color: #d32f2f;
}

select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
</style>
