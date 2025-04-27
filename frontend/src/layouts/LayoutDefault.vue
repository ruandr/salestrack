<template>
  <div class="layout">
    <aside class="sidebar">
      <div class="logo">SalesTrack</div>
      <nav>
        <ul>
          <li><router-link to="/sales">Vendas</router-link></li>
          <li><router-link to="/sellers">Vendedores</router-link></li>

          <li><a @click.prevent="logout">Sair</a></li>
        </ul>
      </nav>
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

<style scoped>
/* Layout container */
.layout {
  display: flex;
  min-height: 100vh;
  background: #f9fafb;
  margin: 0;
  padding: 0;
}

/* Sidebar styling */
.sidebar {
  width: 250px;
  background: #1e293b;
  color: white;
  display: flex;
  flex-direction: column;
  padding: 20px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh; /* Garantir que a sidebar ocupe toda a altura da tela */
  z-index: 10;
}

/* Logo styling */
.sidebar .logo {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 2rem;
}

.sidebar nav ul {
  list-style: none;
  padding: 0;
}

.sidebar nav ul li {
  margin-bottom: 1rem;
}

.sidebar nav ul li a {
  color: white;
  text-decoration: none;
}

.sidebar nav ul li a:hover {
  text-decoration: underline;
}

/* Main content area */
.main {
  margin-left: 280px;
  display: flex;
  flex-direction: column;
  flex: 1;
  transition: margin-left 0.3s ease-in-out;
}

/* Topbar styling */
.topbar {
  background: #ffffff;
  padding: 20px;
  border-bottom: 1px solid #e2e8f0;
}

.topbar h1 {
  margin: 0;
  color: #1e293b;
}

/* Content styling */
.content {
  padding: 20px;
  flex: 1;
  overflow-y: auto;
}

/* Mobile-first: Adjustments for smaller screens */
@media (max-width: 768px) {
  .layout {
    flex-direction: column;
    margin: 0;
  }

  /* Sidebar adjustments */
  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
    display: flex;
    justify-content: space-between;
    padding: 10px;
  }

  .sidebar .logo {
    font-size: 1.2rem;
  }

  /* Main content area */
  .main {
    margin-left: 0;
    margin-top: 60px; /* Espaço para o header */
  }

  /* Topbar adjustments */
  .topbar {
    padding: 10px;
  }

  /* Content adjustments */
  .content {
    padding: 15px;
  }
}

@media (min-width: 768px) {
  .main {
    margin-left: 280px; /* Garantir que o conteúdo tenha margem adequada em telas grandes */
  }
}
</style>
