import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import ElementPlus from 'element-plus'
import ruRu from 'element-plus/dist/locale/ru.min.mjs'
import './assets/styles/_colors.scss' // цветовая схема
import './assets/styles.scss' // общие стили
import 'element-plus/dist/index.css' // element-plus
import './assets/styles/element-plus.scss' // custom element-plus
import store from "@/store"
import components from "@/components/UI";

const app = createApp(App)
components.forEach(component => {
    app.component(component.name, component)
})

app.use(ElementPlus, {
    locale: ruRu
})
app.use(router).use(store).mount('#app')
