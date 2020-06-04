<template>
  <b-nav tabs>
    <b-nav-item
      v-for="item in items"
      :key="item.route.name"
      :to="item.route"
      exact
      exact-active-class="active"
    >
      <fa
        v-if="item.icon"
        :icon="item.icon"
        fixed-width
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
