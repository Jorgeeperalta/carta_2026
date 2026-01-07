import { createRouter, createWebHistory } from 'vue-router'
import MenuView from '../views/MenuView.vue'
import LoginView from '../views/LoginView.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: LoginView
  },
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    children: [
      {
        path: 'negocios',
        name: 'BusinessList',
        component: () => import('../views/BusinessList.vue')
      },
      {
        path: 'negocio/:id',
        name: 'BusinessSettings',
        component: () => import('../views/BusinessSettings.vue'),
        props: true
      },
      {
        path: 'negocio/nuevo',
        name: 'CreateBusiness',
        component: () => import('../views/BusinessSettings.vue'),
        props: { id: 'nuevo' }
      },
      {
        path: 'negocio/:id/productos',
        name: 'ProductManagement',
        component: () => import('../views/ProductManagement.vue'),
        props: true
      }
    ]
  },
  {
    path: '/:id',
    name: 'Menu',
    component: MenuView,
    props: true
  },
  {
    path: '/',
    redirect: '/1'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guard de navegación
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('admin_token')
  
  if (to.path.startsWith('/admin') && !isAuthenticated) {
    next({ name: 'Login' })
  } else if (to.name === 'Login' && isAuthenticated) {
    next({ name: 'BusinessList' })
  } else {
    next()
  }
})

export default router
