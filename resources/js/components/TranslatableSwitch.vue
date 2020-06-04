<template>
  <b-nav
    pills
    small
  >
    <b-nav-item
      v-for="locale in Object.keys(locales)"
      :key="locale"
      :active="currentLocale == locale"
      :link-classes="{
        'py-0 px-2 rounded': true,
        'text-danger': currentLocale !== locale && states[locale] == false,
        'bg-danger text-white': currentLocale == locale && states[locale] == false,
      }"
      class="ml-1"
      @click.prevent="change(locale)"
    >
      {{ locale }}
    </b-nav-item>
  </b-nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'VTranslatableSwitch',

  props: {
    states: { type: Object, default: null }
  },

  data: () => ({
    currentLocale: null
  }),

  computed: mapGetters({
    fallbackLocale: 'lang/fallbackLocale',
    locales: 'lang/locales'
  }),

  mounted () {
    this.currentLocale = this.fallbackLocale
  },

  methods: {
    change (locale) {
      this.currentLocale = locale
      this.$emit('change', locale)
    }
  }
}
</script>
