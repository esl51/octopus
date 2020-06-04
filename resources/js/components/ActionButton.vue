<template>
  <b-button
    :disabled="disabled"
    :v-b-tooltip.hover="title && title.length ? true : false"
    :title="title"
    :href="href"
    :to="to"
    :target="target"
    variant="link"
    size="sm"
    class="p-0">
    <fa v-if="icon && icon.length" :icon="icon" fixed-width />
    <slot/>
  </b-button>
</template>

<script>
export default {
  name: "ActionButton",
  props: {
    title: { type: String, default: null },
    icon: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    href: { type: String, default: null },
    target: { type: String, default: null },
    to: { type: [Object, String], default: null },
  }
}
</script>
