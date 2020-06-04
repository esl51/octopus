<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint">

    <b-form-textarea
      v-model="form[name]"
      :state="form.errors && form.errors.has(name) ? false : null"
      :id="id || 'item-' + name"
      :rows="rows"
      :max-rows="maxRows"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus" />

  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VTextarea',
  mixins: [control],
  props: {
    rows: { type: Number, default: 3, },
    maxRows: { type: Number, default: 10, },
  }
}
</script>
