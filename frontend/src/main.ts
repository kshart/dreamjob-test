import { createApp } from 'vue'
import router from './router'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import App from './App.vue'

const vuetify = createVuetify()

createApp(App)
  .use(vuetify)
  .use(router)
  .mount('#app')
