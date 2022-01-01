<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="form.errors && form.errors.has(name) ? false : null"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
    :label-size="size"
    class="select-container"
  >
    <vue-select
      :id="id || 'item-' + name"
      v-model="form[name]"
      :get-option-label="getLabel"
      :reduce="item => item[keyAttribute]"
      :options="options"
      :multiple="multiple"
      :components="{ OpenIndicator, Deselect }"
      :disabled="disabled"
      :placeholder="placeholder"
      :class="'form-select form-select-' + size"
      @input="$emit('input', $event)"
    />
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
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
    placeholder: { type: String, default: null },
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
  }),

  methods: {
    getLabel (option) {
      if (typeof option === 'object') {
        return option[this.labelAttribute]
      }
      const optionObject = this.options.find(o => o[this.keyAttribute].toString() === option.toString())
      if (optionObject) {
        return optionObject[this.labelAttribute]
      }
      return option
    }
  }
}
</script>

<style>
.select-container {
  min-width: 150px;
}
</style>
