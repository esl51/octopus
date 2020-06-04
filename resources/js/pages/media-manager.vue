<template>
  <div>
    <h1 class="mb-4">{{ $t('media_manager') }}</h1>
    <file-manager :settings="settings" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'acl'],

  computed: mapGetters({
    locale: 'lang/locale',
  }),

  data: () => ({
    settings: {},
  }),

  metaInfo () {
    return { title: this.$t('media_manager') }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', { breadcrumbs: [
      { text: this.$t('home'), to: { name: 'dashboard' } },
      { text: this.$t('media_manager'), to: { name: 'media-manager' } },
    ] })

    this.settings.lang = this.locale
  },
}
</script>

<style>
@import "https://use.fontawesome.com/releases/v5.12.0/css/all.css";

.fm {
  height: 800px;
  padding: 0;
}
.fm .fm-body {
  border-bottom-color: #dee2e6;
  border-top: none;
}
.fm-info-block {
  border-bottom: none;
  padding-top: 0.5rem;
}
.fm-tree {
  border-right-color: #dee2e6;
}
.fm-modal {
  z-index: 1040;
}
</style>
