<template>
  <b-form-group
    :description="hint"
    :state="hasErrors() ? false : null"
  >
    <div class="d-flex align-items-baseline mb-2">
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
