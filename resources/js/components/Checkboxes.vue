<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
  >
    <b-form-checkbox-group
      :id="id || 'item-' + name"
      v-model="form[name]"
      :options="options.map(item => ({ text: item[labelAttribute], value: item[keyAttribute] }))"
      :state="form.errors && form.errors.has(name) ? false : null"
    />
  </b-form-group>
</template>

<script>
export default {
  name: 'VCheckboxes',
  props: {
    label: { type: String, default: null },
    hint: { type: String, default: null },
    form: { type: Object, default: null },
    id: { type: String, default: null },
    name: { type: String, default: null },
    keyAttribute: { type: String, default: 'id' },
    labelAttribute: { type: String, default: 'name' },
    options: { type: Array, default: null }
  }
}
</script>
