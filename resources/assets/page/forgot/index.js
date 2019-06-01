import Vue from 'vue'
import plugin from './core/plugin';
import App from './App.vue';
import components from './core/components';
import directives from './core/directives';

Vue.config.productionTip = false;

new Vue({
    el: '#core',
    plugin ,
    components , 
    render: h => h(App)
})
