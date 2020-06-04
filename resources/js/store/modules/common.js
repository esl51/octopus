import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  sidebarActive: Cookies.get('sidebarActive') === 'true' || false,
  breadcrumbs: []
}

// getters
export const getters = {
  sidebarActive: state => state.sidebarActive,
  breadcrumbs: state => state.breadcrumbs
}

// mutations
export const mutations = {
  [types.TOGGLE_SIDEBAR] (state) {
    state.sidebarActive = !state.sidebarActive
  },
  [types.SET_BREADCRUMBS] (state, { breadcrumbs }) {
    state.breadcrumbs = breadcrumbs
  }
}

// actions
export const actions = {
  toggleSidebar ({ commit }) {
    commit(types.TOGGLE_SIDEBAR)
    Cookies.set('sidebarActive', state.sidebarActive, { expires: 365 })
  },
  setBreadcrumbs ({ commit }, { breadcrumbs }) {
    commit(types.SET_BREADCRUMBS, { breadcrumbs })
  }
}
