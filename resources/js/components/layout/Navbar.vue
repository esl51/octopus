<template>
  <b-navbar
    togglable="lg"
    type="light"
    class="bg-white"
  >
    <b-button
      variant="link"
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
      <b-navbar-nav class="ml-auto mr-2">
        <locale-dropdown />
      </b-navbar-nav>
      <b-navbar-nav>
        <!-- Authenticated -->
        <b-nav-item-dropdown
          v-if="user"
          right
          no-caret
          toggle-class="p-0"
        >
          <template slot="button-content">
            <b-avatar
              :src="user.photo_url"
              :text="user.name"
              size="40"
            />
          </template>
          <b-dropdown-item
            :to="{ name: 'settings.profile' }"
            link-class="d-flex align-items-center"
          >
            {{ $t('settings') }}
            <v-icon
              type="settings"
              class="ml-auto"
            />
          </b-dropdown-item>
          <b-dropdown-divider />
          <b-dropdown-item
            href="#"
            link-class="d-flex align-items-center"
            @click.prevent="logout"
          >
            {{ $t('logout') }}
            <v-icon
              type="logout"
              class="ml-auto"
            />
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
import LocaleDropdown from '~/components/layout/LocaleDropdown'

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
