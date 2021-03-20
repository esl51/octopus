<template>
  <div class="container-tight py-6">
    <div class="text-center mb-4">
      <h1>octopus</h1>
    </div>
    <b-card class="card-md">
      <form
        @submit.prevent="login"
        @keydown="form.onKeydown($event)"
      >
        <h2 class="mb-5 text-center">
          {{ $t('login') }}
        </h2>
        <v-input
          :label="$t('email')"
          :form="form"
          class="mb-3"
          name="email"
          type="email"
        />
        <v-input
          :label="$t('password')"
          :form="form"
          class="mb-2"
          name="password"
          type="password"
        >
          <template #label>
            {{ $t('password') }}
            <span class="form-label-description">
              <router-link :to="{ name: 'password.request' }">
                {{ $t('forgot_password') }}
              </router-link>
            </span>
          </template>
        </v-input>
        <div class="mb-2">
          <label class="form-check">
            <input
              v-model="remember"
              type="checkbox"
              class="form-check-input"
              name="remember"
            >
            <span class="form-check-label">{{ $t('remember_me') }}</span>
          </label>
        </div>
        <div class="form-footer">
          <b-button
            block
            type="submit"
            variant="primary"
          >
            <span
              v-if="form.busy"
              class="spinner-border spinner-border-sm mr-2"
              role="status"
            />
            {{ $t('login') }}
          </b-button>
        </div>
      </form>
    </b-card>
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
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'dashboard' })
    }
  }
}
</script>
