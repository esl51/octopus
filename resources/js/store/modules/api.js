import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  permissions: null,
  roles: null,
  statuses: null
}

// getters
export const getters = {
  permissions: state => state.permissions,
  roles: state => state.roles,
  statuses: state => state.statuses
}

// mutations
export const mutations = {

  [types.FETCH_PERMISSIONS] (state, { permissions }) {
    state.permissions = permissions
  },
  [types.FETCH_ROLES] (state, { roles }) {
    state.roles = roles
  },
  [types.FETCH_STATUSES] (state, { statuses }) {
    state.statuses = statuses
  }
}

// actions
export const actions = {

  async fetchPermissions ({ commit }) {
    const { data } = await axios.get('/api/access/permissions', { params: { per_page: 9999 } })
    commit(types.FETCH_PERMISSIONS, { permissions: data.data })
  },
  async fetchRoles ({ commit }) {
    const { data } = await axios.get('/api/access/roles', { params: { per_page: 9999 } })
    commit(types.FETCH_ROLES, { roles: data.data })
  },
  async fetchStatuses ({ commit }) {
    const { data } = await axios.get('/api/statuses', { params: { per_page: 9999 } })
    commit(types.FETCH_STATUSES, { statuses: data.data })
  }
}
