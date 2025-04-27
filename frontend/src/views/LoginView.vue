<template>
    <div class="login-container">
      <h1>Login</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" v-model="email" id="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" v-model="password" id="password" required />
        </div>
        <button type="submit" :disabled="loading">Login</button>
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
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    text-align: center;
    margin-bottom: 20px;
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
    padding: 12px;
    margin: 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
    box-sizing: border-box; /* Ensures padding doesn't increase input width */
  }
  
  button {
    width: 100%;
    padding: 12px;
    background-color: #1e293b;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    box-sizing: border-box; /* Ensures padding doesn't increase button width */
    transition: background-color 0.3s ease;
  }
  
  button:hover {
    background-color: #334155;
  }
  
  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  </style>
  