<template>
  <div class="pt-3">
    <b-row>
      <b-col md="7">
        <b-form
          @submit.prevent="update"
          @keydown="form.onKeydown($event)"
        >
          <!-- Name -->
          <v-input
            :label="$t('name')"
            :form="form"
            name="name"
            label-cols-md="4"
            autofocus
          />

          <!-- Email -->
          <v-input
            :label="$t('email')"
            :form="form"
            name="email"
            type="email"
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
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
      this.$bvToast.toast(this.$t('info_updated'), {
        variant: 'success'
      })
    }
  }
}
</script>
