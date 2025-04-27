import { createRouter, createWebHistory } from 'vue-router'
import SaleView from '@/views/SaleView.vue'
import LoginView from '@/views/LoginView.vue'
import SellersView from '@/views/SellersView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/sales',
      name: 'sales',
      component: SaleView,
    },
    {
      path: '/sellers',
      name: 'Sellers',
      component: SellersView,
    },
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/logout',
      name: 'logout',
      beforeEnter: (to, from, next) => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        next({ name: 'login' });
      }
    }
  ],
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  if (to.name !== 'login' && to.name !== 'logout' && !token) {
    next({ name: 'login' });
  } else {
    next(); 
  }
});

export default router
