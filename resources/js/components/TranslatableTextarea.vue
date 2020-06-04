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

    <b-form-textarea
      v-for="locale in Object.keys(locales)"
      v-show="currentLocale == locale"
      :key="locale"
      :ref="'input-' + locale"
      v-model="form.translations[locale][name]"
      :state="hasErrors(locale) ? false : null"
      :id="id ? id + '-' + locale : 'item-' + name + '-' + locale"
      :rows="rows"
      :max-rows="maxRows"
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
  name: 'VTranslatableTextarea',
  mixins: [control, translatable],

  props: {
    rows: { type: Number, default: 3, },
    maxRows: { type: Number, default: 10, },
  },
}
</script>
