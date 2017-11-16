import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';

// loads the Icon plugin
UIkit.use(Icons);

import Vue from 'vue';

import App from './app.vue';
import router from './router';

const app = new Vue({
  el: '#root',
  template: `<app></app>`,
  components: {App},
  router
});