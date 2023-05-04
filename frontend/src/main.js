import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.css';

Vue.config.productionTip = false

Vue.use(VueIziToast);

new Vue({
  router,
  store,
  vuetify,
  render: function (h) { return h(App) }
}).$mount('#app')
