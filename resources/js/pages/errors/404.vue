<template>
  <form-result
    title="404"
    :text="$t('page_not_found')"
  >
    <b-button
      variant="primary"
      :to="{ name: 'dashboard' }"
    >
      {{ $t('go_home') }}
    </b-button>
  </form-result>
</template>

<script>
import FormResult from '~/components/layout/FormResult'

export default {
  name: 'NotFound',

  components: {
    FormResult
  },

  layout: 'basic'
}
</script>
