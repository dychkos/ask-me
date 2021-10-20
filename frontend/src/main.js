import { createApp } from 'vue'
import App from './App.vue'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import router from "./router/router";
import components from "./components/UI"
import store from "./store"


const app = createApp(App);

components.forEach(component => {
    app.component(component.name, component)
})

app.use(router).use(store).mount('#app')
