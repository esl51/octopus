<template>
  <b-form-group
    :description="hint"
    :state="hasErrors() ? false : null"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
    :label-size="size"
  >
    <div :class="'d-flex align-items-baseline mb-2 col-form-label-' + size">
      <label class="mb-0">{{ label }}</label>

      <v-translatable-switch
        :states="getStates()"
        class="ml-2"
        @change="changeLocale"
      />
    </div>

    <div
      v-for="locale in Object.keys(locales)"
      v-show="currentLocale == locale"
      :key="locale"
    >
      <jodit-editor
        :id="id ? id + '-' + locale : 'item-' + name + '-' + locale"
        :ref="'input-' + locale"
        v-model="form.translations[locale][name]"
        :config="editorConfig"
        :buttons="editorButtons"
      />
    </div>

    <div
      v-for="(error, index) in getErrors()"
      slot="invalid-feedback"
      :key="index"
    >
      {{ error }}
    </div>
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import { translatable } from '~/mixins/translatable'
import { joditEditor } from '~/mixins/joditEditor'
import { JoditEditor } from 'jodit-vue'

export default {
  name: 'VTranslatableEditor',

  components: {
    JoditEditor
  },
  mixins: [control, translatable, joditEditor],

  methods: {
    changeLocale (locale) {
      this.currentLocale = locale
    }
  }
}
</script>
