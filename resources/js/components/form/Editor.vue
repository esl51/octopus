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
    <jodit-editor
      :id="id || name"
      v-model="form[name]"
      :config="editorConfig"
      :buttons="editorButtons"
    />
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import { joditEditor } from '~/mixins/joditEditor'
import { JoditEditor } from 'jodit-vue'

export default {
  name: 'VEditor',

  components: {
    JoditEditor
  },
  mixins: [control, joditEditor]
}
</script>
