import '../css/app.css';
import './bootstrap';
import { createApp} from 'vue';
import App from './App.vue'
import Vue3Toastify from 'vue3-toastify'
import { createPinia } from 'pinia'
import router from './router'
import Aura from '@primevue/themes/aura'
import { PrimeVue } from '@primevue/core'

const app = createApp(App)
app.use(PrimeVue, {
    theme: {
      preset: Aura,
      options: {
        darkModeSelector: false || 'none'
      }
    }
  })

  app.use(Vue3Toastify, {
    autoClose: 3000,
    multiple: true,
  })
  app.use(createPinia())
  app.use(router)
  
app.mount('#app')