function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/admin/login', name: 'login', component: page('auth/login.vue') },
  { path: '/admin/register', name: 'register', component: page('auth/register.vue') },
  { path: '/admin/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/admin/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/admin/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/admin/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/admin/dashboard', name: 'dashboard', component: page('dashboard.vue') },

  { path: '/admin/media-manager', name: 'media-manager', component: page('media-manager.vue'), meta: { permissions: ['manage media'] } },

  { path: '/admin/pages', name: 'pages', component: page('pages.vue'), meta: { permissions: ['manage pages'] } },

  {
    path: '/admin/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', name: 'settings', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  {
    path: '/admin/directories',
    name: 'directories',
    component: page('directories/index.vue'),
    meta: { permissions: ['manage directories'] },
    children: [
      { path: 'statuses', name: 'directories.statuses', component: page('directories/statuses.vue'), meta: { permissions: ['manage statuses'] } }
    ]
  },

  {
    path: '/admin/access',
    name: 'access',
    component: page('access/index.vue'),
    meta: { permissions: ['manage users', 'manage access'] },
    children: [
      { path: 'users', name: 'access.users', component: page('access/users.vue'), meta: { permissions: ['manage users'] } },
      { path: 'roles', name: 'access.roles', component: page('access/roles.vue'), meta: { permissions: ['manage access'] } },
      { path: 'permissions', name: 'access.permissions', component: page('access/permissions.vue'), meta: { permissions: ['manage access'] } }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
