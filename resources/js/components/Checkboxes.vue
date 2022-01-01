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
    <b-form-checkbox-group
      :id="id || 'item-' + name"
      v-model="form[name]"
      :options="options.map(item => ({ text: item[labelAttribute], value: item[keyAttribute] }))"
      :state="state"
      :size="size"
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
  name: 'VCheckboxes',
  mixins: [control],
  props: {
    keyAttribute: { type: String, default: 'id' },
    labelAttribute: { type: String, default: 'name' },
    options: { type: Array, default: null }
  }
}
</script>
