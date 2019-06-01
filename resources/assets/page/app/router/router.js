import Vue from 'vue';
import vueRouter from 'vue-router';

Vue.use(vueRouter);

import profile from '../page/profile';
import serveur from '../page/serveur';
import installsite from '../page/installsite';
import manager from '../page/manager';
import History from '../page/History';

const router = new vueRouter({
	routes : [
		
		{ name: 'profile', path : '/', component : profile },
		{ name: 'seveur', path : '/seveur', component : serveur },
		{ name: 'installsite', path : '/site', component : installsite },
		{ name: 'manager', path : '/manager', component : manager },
		{ name: 'history', path : '/history', component : History },
		{ path : '*', redirect : '/'},

	]
})

export default router ; 