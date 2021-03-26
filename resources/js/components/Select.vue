<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="form.errors && form.errors.has(name) ? false : null"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
  >
    <vue-select
      :id="id || 'item-' + name"
      v-model="form[name]"
      :label="labelAttribute"
      :reduce="item => item[keyAttribute]"
      :options="options"
      :multiple="multiple"
      :components="{ OpenIndicator, Deselect }"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import VueSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
  name: 'VSelect',

  components: {
    VueSelect
  },

  mixins: [control],

  props: {
    keyAttribute: { type: String, default: 'id' },
    labelAttribute: { type: String, default: 'name' },
    options: { type: Array, default: null },
    multiple: { type: Boolean, default: false }
  },

  data: () => ({
    Deselect: {
      render: createElement => createElement('span')
    },
    OpenIndicator: {
      render: createElement => createElement('span')
    }
  })
}
</script>
