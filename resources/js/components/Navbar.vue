<template>
  <b-navbar
    togglable="lg"
    type="light"
    class="border-bottom bg-light"
  >
    <b-button
      variant="light"
      class="mr-2 text-muted sidebar-toggle"
      @click="toggleSidebar()"
    >
      <!--<span class="navbar-toggler-icon"/>-->
      <v-icon type="chevrons-left" />
    </b-button>
    <b-navbar-toggle target="nav-collapse" />
    <b-collapse
      id="nav-collapse"
      class="ml-2"
      is-nav
    >
      <b-navbar-nav>
        <locale-dropdown />
        <!--<b-nav-item-dropdown :text="locales[locale]">
          <b-dropdown-item v-for="(value, key) in locales" :key="key" :href="getLocalePath(key)">{{ value }}</b-dropdown-item>
        </b-nav-item-dropdown>-->
      </b-navbar-nav>
      <b-navbar-nav class="ml-auto">
        <!-- Authenticated -->
        <b-nav-item-dropdown
          v-if="user"
          right
        >
          <template slot="button-content">
            <img
              :src="user.photo_url"
              class="rounded-circle profile-photo mr-1"
            >
            {{ user.name }}
          </template>
          <b-dropdown-item :to="{ name: 'settings.profile' }">
            <v-icon type="settings" />
            {{ $t('settings') }}
          </b-dropdown-item>
          <b-dropdown-divider />
          <b-dropdown-item
            href="#"
            @click.prevent="logout"
          >
            <v-icon type="logout" />
            {{ $t('logout') }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
        <!-- Guest -->
        <template v-else>
          <b-nav-item
            :to="{ name: 'login' }"
            active-class="active"
          >
            {{ $t('login') }}
          </b-nav-item>
          <b-nav-item
            :to="{ name: 'register' }"
            active-class="active"
          >
            {{ $t('register') }}
          </b-nav-item>
        </template>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from '~/components/LocaleDropdown'

export default {

  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: window.config.appName
  }),

  computed: mapGetters({
    user: 'auth/user',
    sidebarActive: 'common/sidebarActive',
    locales: 'lang/locales',
    locale: 'lang/locale',
    fallbackLocale: 'lang/fallbackLocale'
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
  margin: -0.375rem 0;
}
</style>
