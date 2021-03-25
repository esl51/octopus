<template>
  <b-card :title="$t('dashboard')">
    {{ $t('you_are_logged_in') }}
  </b-card>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('dashboard') }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', { breadcrumbs: [] })
  }
}
</script>
