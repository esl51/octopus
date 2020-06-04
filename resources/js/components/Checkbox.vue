<template>
  <b-form-checkbox
    :id="id || 'item-' + name"
    v-model="form[name]"
    :value="1"
    :unchecked-value="0"
    :state="form.errors && form.errors.has(name) ? false : null"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint"
  >
    {{ label }}
  </b-form-checkbox>
</template>

<script>
export default {
  name: 'VCheckbox',
  props: {
    label: { type: String, default: null },
    hint: { type: String, default: null },
    form: { type: Object, default: null },
    id: { type: String, default: null },
    name: { type: String, default: null }
  }
}
</script>
