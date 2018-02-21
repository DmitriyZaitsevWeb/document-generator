import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import { i18n } from '~/plugins'
import App from '~/components/App'
import '~/components'

import {ServerTable, ClientTable, Event} from 'vue-tables-2';
import BootstrapVue from 'bootstrap-vue'
import VueFormGenerator from 'vue-form-generator'

import Pikaday from 'pikaday';
window.Pikaday = Pikaday;

Vue.use(VueFormGenerator)
Vue.use(BootstrapVue);
Vue.use(ServerTable)
Vue.use(ClientTable)
Vue.use(Event)

Vue.config.productionTip = false

new Vue({
  i18n,
  store,
  router,
  ...App
})
