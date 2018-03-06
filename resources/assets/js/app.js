
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 
window.Vue = require('vue');

let authorizations = require('./authorizations');

Vue.prototype.authorize = function(...params) {
	if (! window.App.signedIn) return false;

	if (typeof params[0] === 'string') {
		return authorizations[params[0]](params[1]);
	}

	return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));
Vue.component('avatar-form', require('./components/AvatarForm.vue'));
Vue.component('user-notification', require('./components/UserNotification.vue'));

const app = new Vue({
    el: '#app'
});
