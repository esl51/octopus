<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :state="state"
    :description="hint"
    :class="type === 'password' ? 'password-field' : null"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
    :label-size="size"
  >
    <b-input-group v-if="type === 'password'">
      <b-form-input
        :id="id || 'item-' + name"
        v-model="form[name]"
        :state="state"
        :type="passwordInputType"
        :readonly="readonly"
        :disabled="disabled"
        :autofocus="autofocus"
        :placeholder="placeholder"
        :size="size"
        class="rounded"
      />
      <b-input-group-append>
        <b-button
          variant="link"
          class="text-secondary border-0 shadow-none"
          tabindex="-1"
          @click="togglePasswordVisible"
        >
          <v-icon :type="passwordVisible ? 'eye' : 'eye-off'" />
        </b-button>
      </b-input-group-append>
    </b-input-group>
    <b-form-input
      v-else
      :id="id || 'item-' + name"
      v-model="form[name]"
      :state="state"
      :type="type"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus"
      :placeholder="placeholder"
      :size="size"
    />
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'

export default {
  name: 'VInput',
  mixins: [control],
  props: {
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: null }
  },
  data: () => ({
    passwordVisible: false
  }),
  computed: {
    passwordInputType () {
      return this.passwordVisible ? 'text' : 'password'
    }
  },
  methods: {
    togglePasswordVisible () {
      this.passwordVisible = !this.passwordVisible
    }
  }
}
</script>
