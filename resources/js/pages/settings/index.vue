<template>
  <b-card :title="$t('settings')">
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
export default {
  middleware: 'auth',

  computed: {
    tabs () {
      return [
        {
          icon: 'user',
          name: this.$t('profile'),
          route: { name: 'settings.profile' }
        },
        {
          icon: 'lock',
          name: this.$t('password'),
          route: { name: 'settings.password' }
        }
      ]
    }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', {
      breadcrumbs: [
        { text: this.$t('home'), to: { name: 'dashboard' } },
        { text: this.$t('settings'), to: { name: 'settings' } }
      ]
    })
  }
}
</script>
