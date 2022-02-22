<template>
  <div class="page-header">
    <component
      :is="titleTag"
      v-if="title"
      class="page-header-title"
    >
      {{ title }}
    </component>
    <slot />
  </div>
</template>

<script>
export default {
  name: 'PageHeader',
  props: {
    title: { type: String, default: null },
    titleTag: { type: String, default: 'h2' }
  }
}
</script>
