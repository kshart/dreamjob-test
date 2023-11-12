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
    path: '/:pathMatch(.*)',
    component: Http404,
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async to => {
  try {
    await Api.site.me()
  } catch (error) {
    console.error(error)
  }
  return true
})

export default router
