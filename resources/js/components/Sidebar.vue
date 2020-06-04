<template>
  <div class="sidebar-wrapper bg-dark border-right">
    <router-link
      :to="{ name: user ? 'dashboard' : 'welcome' }"
      class="sidebar-heading"
    >
      <fa
        icon="biohazard"
        fixed-width
      />
      <span class="sidebar-heading-title">{{ appName }}</span>
    </router-link>

    <b-list-group flush>
      <template v-for="(item, index) in items">
        <b-list-group-item
          :key="index"
          action
          :class="item.children && item.children.length && item.expanded ? 'collapsed' : null"
          :aria-expanded="item.children && item.children.length ? (item.expanded ? 'true' : 'false') : null"
          :aria-controls="item.children && item.children.length ? 'sidebar-item-children-' + index : null"
          :tag="item.children && item.children.length && 'button'"
          :to="item.to"
          variant="light"
          class="bg-dark"
          active-class="active"
          @click="expandItem(item)"
        >
          <fa
            :icon="item.icon ? item.icon : 'caret-right'"
            fixed-width
          />
          <span class="sidebar-item-title">{{ item.title }}</span>
        </b-list-group-item>

        <b-collapse
          v-if="item.children && item.children.length"
          :id="'sidebar-item-children-' + index"
          :key="index"
          v-model="item.expanded"
          class="sidebar-children"
        >
          <b-list-group-item
            v-for="(child, cindex) in item.children"
            :key="cindex"
            action
            :to="child.to ? child.to : null"
            variant="light"
            class="bg-dark"
            active-class="active"
          >
            <fa
              :icon="child.icon ? child.icon : 'caret-right'"
              fixed-width
            />
            <span class="sidebar-item-title">{{ child.title }}</span>
          </b-list-group-item>
        </b-collapse>
      </template>
    </b-list-group>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {

  data: () => ({
    appName: window.config.appName,
    items: []
  }),

  computed: {
    ...mapGetters({
      user: 'auth/user'
    })
  },

  created () {
    this.initItems()
  },

  methods: {
    expandItem (item) {
      this.items.forEach(item => {
        item.expanded = false
      })
      if (!this.isCurrentRoute(item.to) || !item.expanded) {
        item.expanded = !item.expanded
      }
    },
    initItems () {
      this.items.push({
        title: this.$t('dashboard'),
        to: { name: 'dashboard' },
        icon: 'tachometer-alt'
      })
      // media
      if (this.user && (this.user.can['manage media'])) {
        this.items.push({
          title: this.$t('media_manager'),
          to: { name: 'media-manager' },
          icon: 'photo-video'
        })
      }
      // pages
      if (this.user && (this.user.can['manage pages'])) {
        this.items.push({
          title: this.$t('pages'),
          to: { name: 'pages' },
          icon: 'copy'
        })
      }
      // directories
      if (this.user && this.user.can['manage directories']) {
        const item = {
          title: this.$t('directories'),
          to: { name: 'directories' },
          icon: 'book',
          children: [],
          expanded: false
        }
        item.expanded = this.isCurrentRoute(item.to)
        // statuses
        if (this.user.can['manage statuses']) {
          item.children.push({
            title: this.$t('statuses'),
            to: { name: 'directories.statuses' },
            icon: 'lightbulb'
          })
        }
        this.items.push(item)
      }
      // access
      if (this.user && (this.user.can['manage access'] || this.user.can['manage users'])) {
        const item = {
          title: this.$t('access'),
          to: { name: 'access' },
          icon: 'lock',
          children: [],
          expanded: false
        }
        item.expanded = this.isCurrentRoute(item.to)
        // users
        if (this.user.can['manage users']) {
          item.children.push({
            title: this.$t('users'),
            to: { name: 'access.users' },
            icon: 'users'
          })
        }
        if (this.user.can['manage access']) {
          // roles
          item.children.push({
            title: this.$t('roles'),
            to: { name: 'access.roles' },
            icon: 'angle-double-up'
          })
          // permissions
          item.children.push({
            title: this.$t('permissions'),
            to: { name: 'access.permissions' },
            icon: 'key'
          })
        }
        this.items.push(item)
      }
    },
    isCurrentRoute (route) {
      const activeRoute = this.$route.matched.length ? this.$route.matched[0].name : this.$route.name
      return activeRoute === this.$router.resolve(route).resolved.name
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
