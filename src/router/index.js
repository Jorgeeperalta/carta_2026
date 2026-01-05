import { createRouter, createWebHistory } from 'vue-router'
import MenuView from '../views/MenuView.vue'

const routes = [
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

export default router
