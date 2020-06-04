<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="form.errors && form.errors.has(name) ? false : null"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint"
  >
    <vue-select
      :id="id || 'item-' + name"
      v-model="form[name]"
      :label="labelAttribute"
      :reduce="item => item[keyAttribute]"
      :options="options"
      :multiple="multiple"
      :components="{ OpenIndicator }"
    />
  </b-form-group>
</template>

<script>
import VueSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
  name: 'VSelect',

  components: {
    VueSelect
  },

  props: {
    label: { type: String, default: null },
    hint: { type: String, default: null },
    form: { type: Object, default: null },
    id: { type: String, default: null },
    name: { type: String, default: null },
    keyAttribute: { type: String, default: 'id' },
    labelAttribute: { type: String, default: 'name' },
    options: { type: Array, default: null },
    multiple: { type: Boolean, default: false }
  },

  data: () => ({
    OpenIndicator: {
      render: createElement => createElement('span')
    }
  })
}
</script>
