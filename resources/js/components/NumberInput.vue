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
    <b-input-group
      :size="size"
      class="number-input"
    >
      <b-input-group-prepend>
        <b-button
          variant="outline-light"
          @click="decrease"
        >
          -
        </b-button>
      </b-input-group-prepend>
      <b-form-input
        :id="id || 'item-' + name"
        v-model="form[name]"
        :state="state"
        :readonly="readonly"
        :disabled="disabled"
        :autofocus="autofocus"
        :placeholder="placeholder"
        :min="unsigned ? 0 : null"
        type="number"
      />
      <b-input-group-append>
        <b-button
          variant="outline-light"
          @click="increase"
        >
          +
        </b-button>
      </b-input-group-append>
    </b-input-group>
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VNumberInput',
  mixins: [control],
  props: {
    placeholder: { type: String, default: null },
    unsigned: { type: Boolean, default: true }
  },

  methods: {
    decrease () {
      let value = parseInt(this.form[this.name])
      if (isNaN(value)) {
        value = 0
      }
      this.$set(this.form, this.name, this.unsigned && value - 1 < 0 ? 0 : value - 1)
    },
    increase () {
      let value = parseInt(this.form[this.name])
      if (isNaN(value)) {
        value = 0
      }
      this.$set(this.form, this.name, value + 1)
    }
  }
}
</script>
