<template>
  <b-card :title="$t('media_manager')">
    <file-manager
      :settings="settings"
      class="mt-3"
    />
  </b-card>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'acl'],

  data: () => ({
    settings: {}
  }),

  computed: mapGetters({
    locale: 'lang/locale'
  }),

  metaInfo () {
    return { title: this.$t('media_manager') }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', {
      breadcrumbs: [
        { text: this.$t('home'), to: { name: 'dashboard' } },
        { text: this.$t('media_manager'), to: { name: 'media-manager' } }
      ]
    })

    this.settings.lang = this.locale
  }
}
</script>

<style lang="scss">
@import "https://use.fontawesome.com/releases/v5.12.0/css/all.css";
</style>
