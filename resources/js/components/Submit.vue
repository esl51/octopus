<template>
  <b-button
    :type="type"
    :disabled="form.busy"
    :variant="variant"
    class="submit-button"
  >
    <b-spinner
      v-if="form.busy"
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
    type: { type: String, default: 'submit' },
    form: { type: Object, required: true },
    variant: { type: String, default: 'primary' }
  }
}
</script>
