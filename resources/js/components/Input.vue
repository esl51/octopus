<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint">

    <b-form-input
      v-model="form[name]"
      :state="form.errors && form.errors.has(name) ? false : null"
      :id="id || 'item-' + name"
      :type="type"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus" />

  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VInput',
  mixins: [control],
  props: {
    type: { type: String, default: 'text' },
  }
}
</script>
