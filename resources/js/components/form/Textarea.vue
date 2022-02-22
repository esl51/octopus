<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="state"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
    :label-size="size"
  >
    <b-form-textarea
      :id="id || 'item-' + name"
      v-model="form[name]"
      :state="state"
      :rows="rows"
      :max-rows="maxRows"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus"
      :size="size"
      :placeholder="placeholder"
    />
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VTextarea',
  mixins: [control],
  props: {
    rows: { type: Number, default: 3 },
    maxRows: { type: Number, default: 10 },
    placeholder: { type: String, default: null }
  }
}
</script>
