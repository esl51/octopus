<template>
  <form-result
    v-if="mustVerifyEmail"
    :title="$t('need_verification')"
    :text="$t('verify_email_address')"
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
      @submit.prevent="register"
      @keydown="form.onKeydown($event)"
    >
      <h2 class="mb-2">
        {{ appName }}
      </h2>
      <p class="text-muted mb-4">
        {{ $t('already_registered') }}
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
      </p>
      <v-input
        :placeholder="$t('name')"
        :form="form"
        name="name"
        size="lg"
        autofocus
      />
      <v-input
        :placeholder="$t('email')"
        :form="form"
        name="email"
        type="email"
        size="lg"
      />
      <v-input
        :placeholder="$t('password')"
        :form="form"
        name="password"
        type="password"
        size="lg"
      />
      <v-input
        :placeholder="$t('confirm_password')"
        :form="form"
        name="password_confirmation"
        type="password"
        size="lg"
      />
      <div class="form-footer">
        <v-submit :form="form">
          {{ $t('register') }}
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
    return { title: this.$t('register') }
  },

  data: () => ({
    appName: window.config.appName,
    form: new Form({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  methods: {
    async register () {
      const { data } = await this.form.post('/api/register')
      if (data.status) {
        this.mustVerifyEmail = true
      } else {
        const { data: { token } } = await this.form.post('/api/login')
        this.$store.dispatch('auth/saveToken', { token })
        await this.$store.dispatch('auth/updateUser', { user: data })
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
