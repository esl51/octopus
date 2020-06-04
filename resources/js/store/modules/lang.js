import Cookies from 'js-cookie'
import * as types from '../mutation-types'

const { locale, locales, fallbackLocale } = window.config

// state
export const state = {
  locale: Cookies.get('locale') || locale,
  locales: locales,
  fallbackLocale: fallbackLocale
}

// getters
export const getters = {
  locale: state => state.locale,
  locales: state => state.locales,
  fallbackLocale: state => state.fallbackLocale
}

// mutations
export const mutations = {
  [types.SET_LOCALE] (state, { locale }) {
    state.locale = locale
  }
}

// actions
export const actions = {
  setLocale ({ commit }, { locale }) {
    commit(types.SET_LOCALE, { locale })

    Cookies.set('locale', locale, { expires: 365 })
  }
}
