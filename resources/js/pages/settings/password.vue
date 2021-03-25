<template>
  <div class="pt-3">
    <b-row>
      <b-col md="7">
        <b-form
          @submit.prevent="update"
          @keydown="form.onKeydown($event)"
        >
          <!-- Password -->
          <v-input
            :label="$t('password')"
            :form="form"
            name="password"
            type="password"
            label-cols-md="4"
            autofocus
          />

          <!-- Password Confirmation -->
          <v-input
            :label="$t('confirm_password')"
            :form="form"
            name="password_confirmation"
            type="password"
            label-cols-md="4"
          />

          <div class="form-footer">
            <!-- Submit -->
            <v-submit :form="form">
              {{ $t('update') }}
            </v-submit>
          </div>
        </b-form>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')
      this.form.reset()
      this.$bvToast.toast(this.$t('password_updated'), {
        variant: 'success'
      })
    }
  }
}
</script>
