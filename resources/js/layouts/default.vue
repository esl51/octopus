<template>
  <div class="wrapper" :class="{ 'toggled': !sidebarActive }">
    <sidebar />
    <div class="page-content-wrapper">
      <navbar />
      <b-breadcrumb class="rounded-0" v-if="breadcrumbs && breadcrumbs.length" :items="breadcrumbs" />
      <b-container fluid class="mt-4">
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

  computed: mapGetters({
    breadcrumbs: 'common/breadcrumbs',
    sidebarActive: 'common/sidebarActive',
  }),

  components: {
    Sidebar,
    Navbar,
  }
}
</script>
