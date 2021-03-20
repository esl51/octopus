<template>
  <div
    class="wrapper"
    :class="{ 'toggled': !sidebarActive }"
  >
    <sidebar />
    <div class="page-content-wrapper">
      <navbar />
      <b-breadcrumb
        v-if="breadcrumbs && breadcrumbs.length"
        class="rounded-0"
        :items="breadcrumbs"
      />
      <b-container class="mt-4">
        <child />
      </b-container>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Sidebar from '~/components/Sidebar'
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Sidebar,
    Navbar
  },

  computed: mapGetters({
    breadcrumbs: 'common/breadcrumbs',
    sidebarActive: 'common/sidebarActive'
  })
}
</script>
