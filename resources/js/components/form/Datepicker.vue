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
    class="datepicker-container"
  >
    <b-form-datepicker
      :id="id || 'item-' + name"
      v-model="form[name]"
      :state="state"
      :readonly="readonly"
      :disabled="disabled"
      :autofocus="autofocus"
      :placeholder="placeholder"
      :label-no-date-selected="placeholder"
      :size="size"
      label-help=""
      :locale="locale"
      :start-weekday="locale === 'en' ? 0 : 1"
      :date-format-options="{
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      }"
      selected-variant="primary-outline"
      reset-button
      :label-reset-button="$t('reset')"
      :date-disabled-fn="dateDisabled"
      :date-info-fn="dateClass"
      :min="min"
      :max="max"
      @input="$emit('input', $event)"
    >
      <template slot="nav-prev-year">
        <v-icon
          type="chevrons-left"
        />
      </template>
      <template slot="nav-prev-month">
        <v-icon
          type="chevron-left"
        />
      </template>
      <template slot="nav-next-month">
        <v-icon
          type="chevron-right"
        />
      </template>
      <template slot="nav-next-year">
        <v-icon
          type="chevrons-right"
        />
      </template>
      <template slot="nav-this-month">
        <v-icon
          type="point"
        />
      </template>
    </b-form-datepicker>
    <b-form-invalid-feedback
      :state="state"
      v-html="errors"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import { mapGetters } from 'vuex'

export default {
  name: 'VDatepicker',
  mixins: [control],
  props: {
    min: { type: [Date, String], default: null },
    max: { type: [Date, String], default: null },
    disabledDates: { type: Array, default: null },
    accentDates: { type: Array, default: null },
    placeholder: { type: String, default: null }
  },

  computed: {
    ...mapGetters({
      locale: 'lang/locale'
    })
  },

  methods: {
    dateDisabled (ymd, date) {
      if (!this.disabledDates) {
        return false
      }
      let result = false
      this.disabledDates.forEach(disabledDate => {
        if (disabledDate.getTime() === date.getTime()) {
          result = true
        }
      })
      return result
    },
    dateClass (ymd, date) {
      if (!this.accentDates) {
        return null
      }
      let result = null
      this.accentDates.forEach(accentDate => {
        if (accentDate.getTime() === date.getTime()) {
          result = 'col-accent'
        }
      })
      return result
    }
  }
}
</script>

<style>
.datepicker-container {
  min-width: 150px;
}
</style>
