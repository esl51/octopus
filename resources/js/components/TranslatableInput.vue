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

    <b-form-input
      v-for="locale in Object.keys(locales)"
      v-show="currentLocale == locale"
      :id="id ? id + '-' + locale : 'item-' + name + '-' + locale"
      :key="locale"
      :ref="'input-' + locale"
      v-model="form.translations[locale][name]"
      :state="hasErrors(locale) ? false : null"
      :type="type"
      :name="getName(locale)"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus && currentLocale == locale"
      :size="size"
    />

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

export default {
  name: 'VTranslatableInput',
  mixins: [control, translatable],

  props: {
    type: { type: String, default: 'text' }
  }
}
</script>
