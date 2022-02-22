<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="errors && errors.length ? false : null"
    :invalid-feedback="errors && errors.length ? errors.join('<br>') : null"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
    :label-size="size"
  >
    <slot />
    <b-form-file
      :id="id || 'item-' + name"
      v-model="form[name]"
      :state="errors && errors.length ? false : null"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus"
      :placeholder="placeholder ? placeholder : $t('choose_file')"
      :multiple="multiple"
      browse-text=" "
      :size="size"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
export default {
  name: 'VFileInput',
  mixins: [control],

  props: {
    placeholder: { type: String, default: null },
    multiple: { type: Boolean, default: false }
  },

  computed: {
    errors () {
      const errors = []
      if (this.form[this.name] === null || this.form[this.name] === undefined) {
        return errors
      }
      const files = Array.from(this.form[this.name])
      Object.keys(this.form.errors.all()).filter(field => field.indexOf(this.name) === 0).forEach((field, i) => {
        const errs = this.form.errors.getAll(field)
        errs.forEach(err => {
          errors.push(err ? ((files[i] ? (files[i].name + ': ') : '') + err) : '')
        })
      })
      return errors
    }
  }
}
</script>
