<template>
  <span class="icon-wrap">
    <component
      :is="type + '-icon'"
      :size="iconSize"
    />
  </span>
</template>

<script>
export default {
  name: 'VIcon',
  props: {
    type: { type: String, required: true },
    size: { type: String, default: 'md' }
  },
  computed: {
    iconSize () {
      if (parseInt(this.size)) {
        return this.size
      }
      let iconSize = '20'
      switch (this.size) {
        case 'xs':
          iconSize = '14'
          break
        case 'sm':
          iconSize = '16'
          break
        case 'md':
          iconSize = '20'
          break
        case 'lg':
          iconSize = '24'
          break
        default:
          iconSize = '20'
          break
      }
      return iconSize
    }
  }
}
</script>
