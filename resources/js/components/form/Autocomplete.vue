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
    <vue-bootstrap-typeahead
      ref="search"
      v-model="dataSearch"
      :data="dataItems"
      :serializer="s => s[labelAttribute]"
      :input-class="form.errors.has(name) ? 'is-invalid' : ''"
      :size="size"
      @hit="dataSelected = $event"
    />
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
    <input
      ref="input"
      v-model="dataKey"
      type="hidden"
      :name="name"
    >
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'

export default {
  name: 'VAutocomplete',

  components: {
    VueBootstrapTypeahead
  },
  mixins: [control],

  props: {
    items: { type: Array, default: null },
    nameTitle: { type: String, default: null },
    keyAttribute: { type: String, default: 'id' },
    labelAttribute: { type: String, default: 'name' }
  },

  data: () => ({
    dataItems: [],
    dataSearch: null,
    dataKey: null,
    dataSelected: null
  }),

  watch: {
    dataSelected (item) {
      this.dataKey = item[this.keyAttribute]
      this.$emit('selected', item)
    },
    dataSearch (search) {
      this.form[this.name] = null
      this.$emit('cleared')
      this.form[this.nameTitle] = search
      this.$emit('search', search)
    },
    dataKey (key) {
      this.form[this.name] = key
    },
    items (items) {
      this.dataItems = items
    },
    form: {
      handler (form) {
        this.$refs.search.inputValue = form[this.nameTitle] ? form[this.nameTitle] : ''
        this.dataKey = form[this.name] ? form[this.name] : ''
      },
      deep: true
    }
  },

  mounted () {
    this.$refs.search.inputValue = this.form[this.nameTitle] ? this.form[this.nameTitle] : ''
    this.dataKey = this.form[this.name] ? this.form[this.name] : ''
  }
}
</script>
