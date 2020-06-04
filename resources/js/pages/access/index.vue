<template>
  <div>
    <h1 class="mb-4">
      {{ $t('access') }}
    </h1>

    <tabs :items="tabs" />

    <transition
      name="fade"
      mode="out-in"
    >
      <div class="nav-tabs-content">
        <router-view />
      </div>
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'acl'],

  metaInfo () {
    return { title: this.$t('access') }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    tabs () {
      const tabs = []
      if (this.user.can['manage users']) {
        tabs.push({
          icon: 'users',
          name: this.$t('users'),
          route: { name: 'access.users' },
          permission: 'manage users'
        })
      }
      if (this.user.can['manage access']) {
        tabs.push({
          icon: 'angle-double-up',
          name: this.$t('roles'),
          route: { name: 'access.roles' },
          permission: 'manage access'
        })
        tabs.push({
          icon: 'key',
          name: this.$t('permissions'),
          route: { name: 'access.permissions' },
          permission: 'manage access'
        })
      }
      return tabs
    }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', {
      breadcrumbs: [
        { text: this.$t('home'), to: { name: 'dashboard' } },
        { text: this.$t('access'), to: { name: 'access' } }
      ]
    })
  },

  mounted () {
    // open first tab
    if (this.tabs.length && this.$route.name === 'access') {
      this.$router.replace(this.tabs[0].route)
    }
  }
}
</script>
