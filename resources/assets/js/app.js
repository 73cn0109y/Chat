require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

Vue.use(VueRouter);

require('./filters');
require('./Directives');

const router = new VueRouter({
	mode: 'history',
	routes,
});

// Components
Vue.component('app', require('./Layouts/app.vue'));
Vue.component('v-dropdown', require('./Components/VDropdown.vue'));

const app = new Vue({
	router,
	computed: {
		app() {
			return this.$refs.app;
		},
	},
}).$mount('#appRoot');

// Close open Dropdowns
$(document).click((e) => {
	$('.v-dropdown').each(function () {
		if ($(e.target).parents('.v-dropdown').get(0) === this) return;
		this.__vue__.close();
	});
});
