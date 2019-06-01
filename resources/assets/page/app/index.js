import Vue from 'vue'
import plugin from './core/plugin';
import App from './App.vue';
import router from './router/router';
import components from './core/components';
import directives from './core/directives';

Vue.config.productionTip = false;

new Vue({
    el: '#core',
    plugin ,
    components , 
    router , 
    render: h => h(App)
})
