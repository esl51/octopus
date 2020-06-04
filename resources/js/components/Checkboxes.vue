<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint">

    <b-form-checkbox-group
      v-model="form[name]"
      :options="options.map(item => ({ text: item[labelAttribute], value: item[keyAttribute] }))"
      :state="form.errors && form.errors.has(name) ? false : null"
      :id="id || 'item-' + name" />

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
    options: { type: Array, default: null },
  }
}
</script>
