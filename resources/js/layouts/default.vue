<template>
  <div
    class="default-layout wrapper"
    :class="{ 'toggled': !sidebarActive }"
  >
    <sidebar />
    <div class="content">
      <navbar />
      <b-breadcrumb
        v-if="breadcrumbs && breadcrumbs.length"
        :items="breadcrumbs"
      />
      <b-container :class="breadcrumbs && breadcrumbs.length ? null : 'mt-3'">
        <child />
      </b-container>
      <b-container class="my-3 text-muted">
        <small>{{ (new Date).getFullYear() }} &copy; {{ appName }}</small>
      </b-container>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Sidebar from '~/components/layout/Sidebar'
import Navbar from '~/components/layout/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Sidebar,
    Navbar
  },

  data: () => ({
    appName: window.config.appName
  }),

  computed: mapGetters({
    breadcrumbs: 'common/breadcrumbs',
    sidebarActive: 'common/sidebarActive'
  })
}
</script>
