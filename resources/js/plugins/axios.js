import Vue from 'vue'
import axios from 'axios'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'

// Request interceptor
axios.interceptors.request.use(request => {
  const token = store.getters['auth/token']
  if (token) {
    request.headers.common.Authorization = `Bearer ${token}`
  }

  const locale = store.getters['lang/locale']
  if (locale) {
    request.headers.common['Accept-Language'] = locale
  }

  // request.headers['X-Socket-Id'] = Echo.socketId()

  return request
})

// Response interceptor
axios.interceptors.response.use(response => response, error => {
  const { status } = error.response
  const vm = new Vue()

  if (status >= 500) {
    vm.$bvToast.toast(i18n.t('error_alert_text'), {
      title: i18n.t('error_alert_title'),
      variant: 'danger'
    })
  }

  if (status === 401 && store.getters['auth/check']) {
    vm.$bvToast.toast(i18n.t('token_expired_alert_text'), {
      title: i18n.t('token_expired_alert_title'),
      variant: 'warning'
    })
    store.commit('auth/LOGOUT')
    router.push({ name: 'login' })
  }

  return Promise.reject(error)
})
