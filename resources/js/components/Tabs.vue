<template>
  <b-nav tabs>
    <b-nav-item
      v-for="item in items"
      :key="item.route.name"
      :to="item.route"
      exact
      exact-active-class="active"
    >
      <v-icon
        v-if="item.icon"
        class="mr-1"
        :type="item.icon"
      />
      {{ item.name }}
      <b-badge
        v-if="item.badge"
        :variant="item.badge.variant ? item.badge.variant : 'secondary'"
      >
        {{ item.badge.value }}
      </b-badge>
    </b-nav-item>
  </b-nav>
</template>

<script>
export default {
  name: 'Tabs',

  props: {
    items: { type: Array, default: null }
  }
}
</script>
