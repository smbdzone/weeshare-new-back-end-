import './bootstrap';
import { createApp } from 'vue';
import router from './router.js';
import App from './layouts/App.vue';


createApp(App)
    .use(router)
    .mount('#app')

// const app = createApp({});
// import example from './components/example.vue';
// app.component('example', example);
// app.mount("#app");


// import App from './layouts/App.vue'