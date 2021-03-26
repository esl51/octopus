<template>
  <form-result
    v-if="status"
    :title="$t('reset_password')"
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
      <h2 class="mb-2">
        {{ appName }}
      </h2>
      <p class="text-muted mb-4">
        {{ $t('reset_password') }}.
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
      </p>
      <v-input
        :placeholder="$t('email')"
        :form="form"
        name="email"
        type="email"
        size="lg"
        autofocus
      />
      <div class="form-footer">
        <v-submit :form="form">
          {{ $t('send_password_reset_link') }}
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
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    appName: window.config.appName,
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')
      this.status = data.status
      this.form.reset()
    }
  }
}
</script>
