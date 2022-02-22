<template>
  <form-result
    :icon="success ? 'check' : 'x'"
    :title="$t('verify_email')"
    :text="success ? success : error || $t('failed_to_verify_email')"
  >
    <b-button
      v-if="success"
      variant="primary"
      :to="{ name: 'login' }"
    >
      {{ $t('login') }}
    </b-button>
    <b-button
      v-else
      variant="primary"
      :to="{ name: 'verification.resend' }"
    >
      {{ $t('resend_verification_link') }}
    </b-button>
  </form-result>
</template>

<script>
import FormResult from '~/components/layout/FormResult'
import axios from 'axios'
const qs = (params) => Object.keys(params).map(key => `${key}=${params[key]}`).join('&')

export default {
  components: {
    FormResult
  },

  async beforeRouteEnter (to, from, next) {
    try {
      const { data } = await axios.post(`/api/email/verify/${to.params.id}?${qs(to.query)}`)
      next(vm => { vm.success = data.status })
    } catch (e) {
      next(vm => { vm.error = e.response.data.status })
    }
  },

  layout: 'basic',
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('verify_email') }
  },

  data: () => ({
    error: '',
    success: ''
  })
}
</script>
