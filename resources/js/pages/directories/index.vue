<template>
  <b-card :title="$t('directories')">
    <tabs :items="tabs" />

    <transition
      name="fade"
      mode="out-in"
    >
      <div class="nav-tabs-content">
        <router-view />
      </div>
    </transition>
  </b-card>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'acl'],

  metaInfo () {
    return { title: this.$t('directories') }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    tabs () {
      const tabs = []
      if (this.user.can['manage statuses']) {
        tabs.push({
          icon: 'bulb',
          name: this.$t('statuses'),
          route: { name: 'directories.statuses' },
          permission: 'manage statuses'
        })
      }
      return tabs
    }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', {
      breadcrumbs: [
        { text: this.$t('home'), to: { name: 'dashboard' } },
        { text: this.$t('directories'), to: { name: 'directories' } }
      ]
    })
  },

  mounted () {
    // open first tab
    if (this.tabs.length && this.$route.name === 'directories') {
      this.$router.replace(this.tabs[0].route)
    }
  }
}
</script>
