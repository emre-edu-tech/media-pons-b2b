/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// # Moment.js
import moment from 'moment';
require('moment/locale/de');    // Workaround for importing moment locales
// #########

// # Sweet alert
import swal from 'sweetalert2';
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;
// ######

// # Vue form 
import {Form, HasError, AlertError} from 'vform';
window.Form = Form;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
// ####

// # Vue Progress Bar
import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '10px',
});
// ########

import objectToFormData from "./objectToFormData";
window.objectToFormData = objectToFormData;

// # Custom Event Handling Mechanism
// Custom event handling mechanism registered
window.FireEvent = new Vue();
// ########

// # Vue router related code
import VueRouter from 'vue-router';
Vue.use(VueRouter);
let routes = [
    {
        path: '/home',
        component: require('./components/Home.vue').default
    },
    {
        path: '/dealers',
        component: require('./components/Dealers.vue').default
    },
    {
        path: '/products',
        component: require('./components/Products.vue').default
    },
    {
        path: '/products/:id',
        component: require('./components/SingleProduct.vue').default
    },
    {
        path: '/cart',
        component: require('./components/Cart.vue').default
    },
    {
        path: '/checkout',
        component: require('./components/Checkout.vue').default
    },
    {
        path: '/cart',
        component: require('./components/Cart.vue').default
    },
    {
        path: '/users',
        component: require('./components/Users.vue').default
    },
    {
        path: '/dealer-chat',
        component: require('./components/ChatApp.vue').default
    },
    {
        path: '/profile',
        component: require('./components/Profile.vue').default
    },
    {
        path: '*',
        component: require('./components/NotFound.vue').default
    }
];
// register your router
const router = new VueRouter({
    linkActiveClass: 'active',
    mode: 'history',
    routes
});
// #######

// # Global Filter functions
Vue.filter('allUpperCase', function(text) {
    return text.toUpperCase();
});

Vue.filter('firstUpperCase', function(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('titleCase', function(text) {
    
    return text.toLowerCase().split(' ').map(function(chunk){
        return chunk.charAt(0).toUpperCase() + chunk.substring(1);
    }).join(' ');
});

Vue.filter('customDate', function(dateTime) {
    return moment(dateTime).format('D MMM YYYY, H:mm:ss');
});

Vue.filter('truncatedText', function(text) {
    
    if (text.length > 70) {
        return text.substring(0, 70) + '...';
    }
    return text;

})

// Format money for the decimal separator -> , and thousands separator -> .
Vue.filter('formatMoney', function (number) {

    number = parseInt(number);  // we store numbers as integers so get the integer part
    number = number/100;    // change money from cents to euro
    number = number.toFixed(2);   // add fixed decimal points
    number = number.replace('.', ',');  // change decimal point from . to ,
    number = number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");  // finally thousands separate with .
    return number;
});
// ##############################

// # For Global Frontend Authentication
import Gate from './Gate';
// We want to access gate class everywhere from application
Vue.prototype.$gate = new Gate(window.user);
// ########################

// # vue loading overlay
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
// Init plugin
Vue.use(Loading);
// ########################

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('apply-form', require('./components/ApplyForm.vue').default);
Vue.component('not-found', require('./components/NotFound.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});