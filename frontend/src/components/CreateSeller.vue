<template>
    <div v-if="isOpen" class="modal-overlay">
        <div class="modal-content">
            <h2>Criar Novo Vendedor</h2>

            <!-- Input para Nome -->
            <input v-model="name" type="text" placeholder="Nome do vendedor" class="input" />

            <!-- Input para Email -->
            <input v-model="email" type="email" placeholder="Email do vendedor" class="input" />

            <div class="button-group">
                <button @click="createSeller" class="create-button">Salvar</button>
                <button @click="closeModal" class="cancel-button">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import { useSellersStore } from '@/stores/sellers';

export default defineComponent({
    name: 'CreateSeller',
    props: {
        isOpen: {
            type: Boolean,
            required: true,
        },
    },
    emits: ['close', 'created'],
    setup(_, { emit }) {
        const sellersStore = useSellersStore();
        const name = ref<string>('');
        const email = ref<string>('');
        const createSeller = async () => {
            if (!name.value.trim()) {
                alert('Nome do vendedor é obrigatório!');
                return;
            }
            if (!email.value.trim() || !validateEmail(email.value)) {
                alert('Email do vendedor é obrigatório e deve ser válido!');
                return;
            }

            try {
                const sellerData = { name: name.value, email: email.value };
                await sellersStore.createSeller(sellerData);
                emit('created');
                closeModal();
            } catch (error) {
                alert('Erro ao criar vendedor');
            }
        };


        const closeModal = () => {
            emit('close');
            name.value = '';
            email.value = '';
        };

        // Função simples para validar o formato de email
        const validateEmail = (email: string) => {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return regex.test(email);
        };

        return {
            name,
            email,
            createSeller,
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