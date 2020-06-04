<template>
  <div>
    <h1 class="mb-4">{{ $t('dashboard') }}</h1>
    <p>{{ $t('you_are_logged_in') }}</p>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('dashboard') }
  },

  created () {
    this.$store.dispatch('common/setBreadcrumbs', { breadcrumbs: [] })
  },
}
</script>
