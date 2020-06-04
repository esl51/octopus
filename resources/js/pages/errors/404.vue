<template>
  <b-card
    :title="$t('page_not_found')"
    class="text-center"
  >
    <b-card-text>
      <router-link :to="{ name: 'welcome' }">
        {{ $t('go_home') }}
      </router-link>
    </b-card-text>
  </b-card>
</template>

<script>
export default {
  name: 'NotFound'
}
</script>
