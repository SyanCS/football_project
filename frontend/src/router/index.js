import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Home.vue'),
  },
  {
    path: '/teams',
    name: 'teams',
    component: () => import('@/views/TeamList.vue'),
  },
  {
    path: '/teams/add',
    name: 'AddTeam',
    component: () => import('@/views/AddTeam.vue'),
  },
  {
    path: '/transfers',
    name: 'Transfer',
    component: () => import('@/views/Transfer.vue'),
  },
  /*{
    path: '/teams/:id/edit',
    name: 'EditTeam',
    component: () => import('@/views/EditTeam.vue'),
  },
  {
    path: '/teams/:id/delete',
    name: 'DeleteTeam',
    component: () => import('@/views/DeleteTeam.vue'),
  },
  {
    path: '/players/add',
    name: 'AddPlayer',
    component: () => import('@/views/AddPlayer.vue'),
  },
  {
    path: '/players/:id/edit',
    name: 'EditPlayer',
    component: () => import('@/views/EditPlayer.vue'),
  },
  {
    path: '/players/:id/delete',
    name: 'DeletePlayer',
    component: () => import('@/views/DeletePlayer.vue'),
  },*/
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
