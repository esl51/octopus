<template>
  <b-form-group
    :description="hint"
    :state="hasErrors() ? false : null">

    <div class="d-flex align-items-baseline mb-2">

      <label class="mb-0">{{ label }}</label>

      <v-translatable-switch
        :states="getStates()"
        @change="changeLocale"
        class="ml-2" />

    </div>

    <b-form-input
      v-for="locale in Object.keys(locales)"
      v-show="currentLocale == locale"
      :key="locale"
      :ref="'input-' + locale"
      v-model="form.translations[locale][name]"
      :state="hasErrors(locale) ? false : null"
      :id="id ? id + '-' + locale : 'item-' + name + '-' + locale"
      :type="type"
      :name="getName(locale)"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus && currentLocale == locale" />

    <div
      slot="invalid-feedback"
      v-for="(error, index) in getErrors()"
      :key="index">
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
    type: { type: String, default: 'text' },
  },
}
</script>
