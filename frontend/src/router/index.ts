import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ContactsView from '../views/ContactsView.vue'
import ContactFormView from '../views/ContactFormView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/',
      name: 'home',
      component: ContactsView,
      meta: { requiresAuth: true }
    },
    { 
      path: '/contacts/new', 
      name: 'new-contact', 
      component: ContactFormView, 
      meta: { requiresAuth: true } 
    },
    { 
      path: '/contacts/:id/edit', 
      name: 'edit-contact', 
      component: ContactFormView, 
      meta: { requiresAuth: true } 
    }
  ]
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('user_id');

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router