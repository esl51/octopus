<template>
  <div class="sign-form">
    <b-form
      @submit.prevent="login"
      @keydown="form.onKeydown($event)"
    >
      <h1 class="mb-2">
        {{ appName }}
      </h1>
      <p class="text-muted mb-4">
        {{ $t('new_user') }}
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </p>
      <v-input
        :placeholder="$t('email')"
        :form="form"
        name="email"
        type="email"
        autofocus
      />
      <v-input
        :placeholder="$t('password')"
        :form="form"
        name="password"
        type="password"
      />
      <p>
        <router-link :to="{ name: 'password.request' }">
          {{ $t('forgot_password') }}
        </router-link>
      </p>
      <div class="form-footer">
        <v-submit :form="form">
          {{ $t('login') }}
        </v-submit>
      </div>
    </b-form>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  layout: 'basic',
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    appName: window.config.appName,
    form: new Form({
      email: '',
      password: ''
    }),
    remember: true
  }),

  methods: {
    async login () {
      const { data } = await this.form.post('/api/login')
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })
      await this.$store.dispatch('auth/fetchUser')
      this.$router.push({ name: 'dashboard' })
    }
  }
}
</script>
