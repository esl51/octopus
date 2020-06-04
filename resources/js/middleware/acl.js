import store from '~/store'

export default (to, from, next) => {
  const permissions = [].concat(...to.matched.map((r) => {
    return r.meta.permissions
  }))
  /*
  ### CHECK ALL ###
  let userAuthorized = true
  permissions.forEach((permission) => {
    if (userAuthorized && permission !== undefined && !store.getters['auth/user'].can[permission]) {
      userAuthorized = false
    }
  })
  */
  let userAuthorized = false
  permissions.forEach((permission) => {
    if (permission !== undefined && store.getters['auth/user'].can[permission]) {
      userAuthorized = true
    }
  })
  if (!userAuthorized) {
    next({ name: 'not-found' })
  } else {
    next()
  }
}
