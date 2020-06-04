<template>
  <v-select
    :label="label"
    :hint="hint"
    :form="form"
    :id="id"
    :name="name"
    key-attribute="name"
    label-attribute="name"
    :options="options"
    name="variant" />
</template>

<script>
export default {
  name: 'VVariantSelect',

  props: {
    label: { type: String, default: null },
    hint: { type: String, default: null },
    form: { type: Object, default: null },
    id: { type: String, default: null },
    name: { type: String, default: null },
    multiple: { type: Boolean, default: false },
  },

  data: () => ({
    options: [
      { name: 'primary' },
      { name: 'secondary' },
      { name: 'success' },
      { name: 'info' },
      { name: 'warning' },
      { name: 'danger' },
      { name: 'dark' },
    ],
  }),
}
</script>
