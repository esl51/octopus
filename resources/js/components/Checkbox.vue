<template>
  <b-form-checkbox
    :id="id || 'item-' + name"
    v-model="form[name]"
    :value="1"
    :unchecked-value="0"
    :state="state"
    :invalid-feedback="errors"
    :description="hint"
    :size="size"
  >
    {{ label }}
  </b-form-checkbox>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VCheckbox',

  mixins: [control]
}
</script>
