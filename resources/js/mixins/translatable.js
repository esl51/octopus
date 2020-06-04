/* global config */
import { mapGetters } from 'vuex'

export const translatable = {

  computed: mapGetters({
    locales: 'lang/locales'
  }),

  data: () => ({
    fallbackLocale: config.fallbackLocale,
    currentLocale: config.fallbackLocale
  }),

  methods: {

    hasErrors (locale) {
      if (locale === undefined) {
        let hasErrors = false
        Object.keys(this.locales).forEach(locale => {
          if (this.form.errors.has(this.getName(locale))) {
            hasErrors = true
          }
        })
        return hasErrors
      }
      return this.form.errors.has(this.getName(locale))
    },

    getErrors (locale) {
      if (locale === undefined) {
        const errors = []
        Object.keys(this.locales).forEach(locale => {
          if (this.form.errors.has(this.getName(locale))) {
            errors.push(...this.form.errors.getAll(this.getName(locale)).map(item => '(' + locale + ') ' + item))
          }
        })
        return errors
      }
      return this.form.errors.getAll(this.getName(locale))
    },

    getStates () {
      const states = {}
      Object.keys(this.locales).forEach(locale => {
        states[locale] = !this.form.errors.has(this.getName(locale))
      })
      return states
    },

    getName (locale) {
      return 'translations.' + locale + '.' + this.name
    },

    changeLocale (locale) {
      this.currentLocale = locale
      this.$nextTick(() => {
        this.$refs['input-' + locale][0].focus()
      })
    }

  }
}
