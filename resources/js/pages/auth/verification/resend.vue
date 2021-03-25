<template>
  <form-result
    v-if="status"
    :title="$t('verify_email')"
    :text="status"
  />
  <div
    v-else
    class="sign-form"
  >
    <b-form
      @submit.prevent="send"
      @keydown="form.onKeydown($event)"
    >
      <h1 class="mb-2">
        {{ appName }}
      </h1>
      <p class="text-muted mb-4">
        {{ $t('verify_email') }}
      </p>
      <v-input
        :placeholder="$t('email')"
        :form="form"
        name="email"
        type="email"
        autofocus
      />
      <div class="form-footer">
        <v-submit :form="form">
          {{ $t('send_verification_link') }}
        </v-submit>
      </div>
    </b-form>
  </div>
</template>

<script>
import Form from 'vform'
import FormResult from '~/components/FormResult'

export default {
  components: {
    FormResult
  },

  layout: 'basic',
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('verify_email') }
  },

  data: () => ({
    appName: window.config.appName,
    status: '',
    form: new Form({
      email: ''
    })
  }),

  created () {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email
    }
  },

  methods: {
    async send () {
      const { data } = await this.form.post('/api/email/resend')
      this.status = data.status
      this.form.reset()
    }
  }
}
</script>
