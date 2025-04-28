<template>
    <div class="login-container">
      <h1>Login</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" v-model="email" id="email" required />
        </div>
        <div class="form-group">
          <label for="password">Senha:</label>
          <input type="password" v-model="password" id="password" required />
        </div>
        <button type="submit" :disabled="loading">Entrar</button>
      </form>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent, ref } from 'vue'
  import { useAuthStore } from '@/stores/auth'
  
  export default defineComponent({
    name: 'Login',
    setup() {
      const email = ref('')
      const password = ref('')
      const loading = ref(false)
      const authStore = useAuthStore()
  
      const handleLogin = async () => {
        loading.value = true
        try {
          await authStore.login(email.value, password.value)
        } catch (error) {
          console.error('Erro no login:', error)
        } finally {
          loading.value = false
        }
      }
  
      return { email, password, loading, handleLogin }
    },
  })
  </script>
  
  <style scoped>
  .login-container {
    max-width: 400px;
    margin: 10vh auto;
    padding: 2rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  h1 {
    color: #1e293b;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: bold;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 8px;
    font-size: 1rem;
    color: #333;
  }
  
  input {
    width: 100%;
    padding: 14px; 
    margin: 0;
    border-radius: 8px; 
    border: 1px solid #d1d5db;
    font-size: 1rem;
    color: #1e293b;
    background-color: #f9fafb;
    transition: border-color 0.3s ease-in-out;
    box-sizing: border-box;
  }
  
  input:focus {
    outline: none;
    border-color: #4c8bf5;
  }
  
  button {
    width: 100%;
    padding: 14px;
    background-color: #4c8bf5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: background-color 0.3s ease;
    box-sizing: border-box;
  }
  
  button:hover {
    background-color: #3b7ce0;
  }
  
  button:disabled {
    background-color: #d1d5db;
    cursor: not-allowed;
  }
  </style>
  