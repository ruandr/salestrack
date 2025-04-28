<template>
    <div class="layout">
      <aside class="sidebar">
        <div class="logo">SalesTrack</div>
        <nav>
          <ul>
            <li><router-link to="/sales" class="nav-link">Vendas</router-link></li>
            <li><router-link to="/sellers" class="nav-link">Vendedores</router-link></li>
          </ul>
        </nav>
        <button @click="logout" class="logout-button">Sair</button>
      </aside>
  
      <div class="main">
        <header class="topbar">
          <h1>{{ title }}</h1>
        </header>
  
        <main class="content">
          <slot />
        </main>
      </div>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  import { useAuthStore } from '@/stores/auth';
  
  export default defineComponent({
    name: 'LayoutDefault',
    props: {
      title: {
        type: String,
        default: 'Vendas',
      },
    },
    setup() {
      const authStore = useAuthStore();
  
      const logout = () => {
        authStore.logout();
      };
  
      return { logout };
    },
  });
  </script>
  
  <style scoped src="@/assets/css/default   -layout.css"></style>