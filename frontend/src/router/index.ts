import {
  createRouter,
  createWebHistory,
  RouteRecordRaw
} from 'vue-router'
import Api from '@/api'
import Http404 from '@/views/Http404.vue'
import Home from '@/views/Home.vue'
import Auth from '@/views/auth/Auth.vue'
import NewsList from '@/views/news/NewsList.vue'
import Users from '@/views/users/Users.vue'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: Home,
    redirect: {
      name: 'news'
    },
    children: [
      {
        name: 'users',
        path: 'users',
        component: Users,
      }, {
        name: 'news',
        path: '/news',
        component: NewsList,
      }
    ],
  }, {
    path: '/login',
    name: 'login',
    component: Auth,
  }, {
    path: '/:catchAll(.*)',
    component: Http404,
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async to => {
  let me
  try {
    me = await Api.site.me()
  } catch (error) {
    console.error(error)
  }
  if (me) {
    if (to.name === 'login') {
      return { name: 'home' }
    }
  } else {
    if (to.name !== 'login') {
      return { name: 'login' }
    }
  }
  return true
})

export default router
