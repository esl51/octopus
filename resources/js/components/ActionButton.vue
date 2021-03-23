<template>
  <b-button
    v-b-tooltip.hover
    :disabled="disabled"
    :title="title"
    :href="href"
    :to="to"
    :target="target"
    variant="link"
    size="sm"
    class="p-0"
  >
    <v-icon :type="icon" />
    <slot />
  </b-button>
</template>

<script>
export default {
  name: 'ActionButton',
  props: {
    title: { type: String, default: null },
    icon: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    href: { type: String, default: null },
    target: { type: String, default: null },
    to: { type: [Object, String], default: null }
  }
}
</script>
