<template>
  <b-button
    :type="type"
    :disabled="(form && form.busy) || disabled"
    :variant="variant"
    size="md"
    class="submit-button"
  >
    <b-spinner
      v-if="form && form.busy"
      class="mr-1"
      small
    />
    <slot />
  </b-button>
</template>

<script>
export default {
  name: 'VSubmit',
  props: {
    disabled: { type: Boolean, default: false },
    type: { type: String, default: 'submit' },
    form: { type: Object, default: null },
    variant: { type: String, default: 'primary' }
  }
}
</script>
