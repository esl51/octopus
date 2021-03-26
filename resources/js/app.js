import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import axios from 'axios'
import App from '~/components/App'
import BootstrapVue from 'bootstrap-vue'
import FileManager from 'laravel-file-manager'

import '~/plugins'
import '~/components'

Vue.use(BootstrapVue, {
  BCard: {
    titleTag: 'h5'
  },
  BTable: {
    outlined: true
  },
  BToast: {
    toaster: 'b-toaster-top-center'
  }
})
Vue.use(FileManager, { store })

Vue.prototype.$axios = axios

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
