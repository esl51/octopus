<template>
  <b-navbar togglable="lg" type="light" class="border-bottom bg-light">
    <b-button variant="light" @click="toggleSidebar()" class="mr-2 text-muted sidebar-toggle">
      <!--<span class="navbar-toggler-icon"/>-->
      <fa icon="angle-double-left" fixed-width />
    </b-button>
    <b-navbar-toggle target="nav-collapse"/>
    <b-collapse class="ml-2" id="nav-collapse" is-nav>
      <b-navbar-nav>
        <locale-dropdown />
        <!--<b-nav-item-dropdown :text="locales[locale]">
          <b-dropdown-item v-for="(value, key) in locales" :key="key" :href="getLocalePath(key)">{{ value }}</b-dropdown-item>
        </b-nav-item-dropdown>-->
      </b-navbar-nav>
      <b-navbar-nav class="ml-auto">
        <!-- Authenticated -->
        <b-nav-item-dropdown v-if="user" right>
          <template slot="button-content">
            <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
            {{ user.name }}
          </template>
          <b-dropdown-item :to="{ name: 'settings.profile' }">
            <fa icon="cog" fixed-width />
            {{ $t('settings') }}
          </b-dropdown-item>
          <b-dropdown-divider/>
          <b-dropdown-item href="#" @click.prevent="logout">
            <fa icon="sign-out-alt" fixed-width />
            {{ $t('logout') }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
        <!-- Guest -->
        <template v-else>
          <b-nav-item :to="{ name: 'login' }" active-class="active">{{ $t('login') }}</b-nav-item>
          <b-nav-item :to="{ name: 'register' }" active-class="active">{{ $t('register') }}</b-nav-item>
        </template>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from '~/components/LocaleDropdown'

export default {

  data: () => ({
    appName: window.config.appName
  }),

  components: {
    LocaleDropdown,
  },

  computed: mapGetters({
    user: 'auth/user',
    sidebarActive: 'common/sidebarActive',
    locales: 'lang/locales',
    locale: 'lang/locale',
    fallbackLocale: 'lang/fallbackLocale',
  }),

  methods: {
    getLocalePath (locale, path) {
      let base = '/admin'
      if (locale !== this.fallbackLocale) {
        base = '/' + locale + base
      }
      return base + (this.$route.path)
    },
    toggleSidebar () {
      this.$store.dispatch('common/toggleSidebar')
    },
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
