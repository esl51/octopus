<template>
  <div class="basic-layout">
    <b-sidebar
      sidebar-class="basic-drawer bg-white"
      right
      backdrop
      no-header
      no-close-on-backdrop
      no-close-on-esc
      no-close-on-route-change
      :visible="drawerVisible"
    >
      <child class="basic-inner" />
    </b-sidebar>
  </div>
</template>

<script>
export default {
  name: 'BasicLayout',
  data: () => ({
    drawerVisible: true
  })
}
</script>
