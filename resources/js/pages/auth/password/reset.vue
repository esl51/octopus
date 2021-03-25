<template>
  <form-result
    v-if="status"
    :title="$t('reset_password')"
    :text="status"
  >
    <b-button
      variant="primary"
      :to="{ name: 'login' }"
    >
      {{ $t('login') }}
    </b-button>
  </form-result>
  <div
    v-else
    class="sign-form"
  >
    <b-form
      @submit.prevent="reset"
      @keydown="form.onKeydown($event)"
    >
      <h1 class="mb-2">
        {{ appName }}
      </h1>
      <p class="text-muted mb-4">
        {{ $t('reset_password') }}
      </p>
      <v-input
        :placeholder="$t('email')"
        :form="form"
        name="email"
        type="email"
        readonly
      />
      <v-input
        :placeholder="$t('password')"
        :form="form"
        name="password"
        type="password"
        autofocus
      />
      <v-input
        :placeholder="$t('confirm_password')"
        :form="form"
        name="password_confirmation"
        type="password"
      />
      <div class="form-footer">
        <v-submit :form="form">
          {{ $t('reset_password') }}
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
      token: '',
      email: '',
      password: '',
      password_confirmation: ''
    })
  }),

  created () {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.params.token
  },

  methods: {
    async reset () {
      const { data } = await this.form.post('/api/password/reset')
      this.status = data.status
      this.form.reset()
    }
  }
}
</script>
