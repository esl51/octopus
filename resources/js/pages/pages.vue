<template>
  <div>
    <h1 class="mb-4">{{ $t('pages') }}</h1>

    <!-- Filters -->
    <b-input-group size="sm" class="my-3">

      <b-input-group-prepend>

        <!-- Add -->
        <b-button v-b-tooltip.hover
          @click="addItem()"
          :title="$t('add')"
          variant="success">
          <fa icon="plus" fixed-width />
          <span class="d-none d-md-inline">{{ $t('add') }}</span>
        </b-button>

      </b-input-group-prepend>

      <!-- Search -->
      <b-form-input
        v-model="search"
        type="search"
        id="filterInput"
        :debounce="200"
        :placeholder="$t('search')" />

      <b-input-group-append>

        <!-- Refresh -->
        <b-button v-b-tooltip.hover
          @click="refreshItems()"
          :title="$t('refresh')">
          <fa icon="sync" fixed-width />
        </b-button>

      </b-input-group-append>

    </b-input-group>

    <!-- Items -->
    <b-table small stacked="sm"
      ref="items"
      :api-url="apiUrl"
      :items="fetchItems"
      :fields="fields"
      :busy.sync="isBusy"
      :current-page="currentPage"
      :per-page="perPage"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      :filter="search">

      <template v-slot:cell(status)="data">
        <div class="d-flex align-items-center">
          <div v-if="data.item.status.variant" class="p-2 rounded mr-2" :class="'bg-' + data.item.status.variant" />
          {{ data.item.status.name }}
        </div>
      </template>

      <template v-slot:cell(actions)="data">

        <!-- View -->
        <action-button
          :title="$t('view')"
          :href="data.item.url"
          target="_blank"
          class="mr-2"
          icon="eye" />

        <!-- Edit -->
        <action-button
          @click.native="editItem(data.item)"
          :disabled="!data.item.is_editable"
          :title="$t('edit')"
          class="mr-2"
          icon="edit" />

        <!-- Trash -->
        <action-button
          @click.native="trashItem(data.item)"
          :disabled="!data.item.is_deletable"
          :title="$t('edit')"
          class="text-danger"
          icon="trash-alt" />

      </template>

    </b-table>

    <!-- Pagination -->
    <b-pagination
      v-if="totalRows > perPage"
      v-model="currentPage"
      :total-rows="totalRows"
      :per-page="perPage"
      align="right"
      size="sm"
      class="my-0" />

    <!-- View -->
    <b-modal centered ok-only
      id="itemView"
      :title="item.name">

      <b-table small stacked
        :items="[item]"
        :fields="fields.filter(field => field.key != 'actions')"
        class="table-view" />

    </b-modal>

    <!-- Form -->
    <b-modal centered no-enforce-focus no-close-on-backdrop
      id="itemModal"
      size="lg"
      :title="(item && item.name) || $t('page')">

      <b-form
        ref="itemForm"
        @submit.prevent="item && item.id ? updateItem() : createItem()"
        @keydown="form.onKeydown($event)">

        <b-tabs content-class="pt-2">
          <b-tab active
            :title="$t('common_data')">

            <!-- Title -->
            <v-translatable-input autofocus
              :label="$t('title')"
              :hint="$t('title_hint')"
              :form="form"
              name="title" />

            <b-form-row>

              <b-col sm="12" md="7">
                <!-- Slug -->
                <div class="position-relative">
                  <v-translatable-input
                    :label="$t('slug')"
                    :form="form"
                    :disabled="slugBusy"
                    name="slug" />
                  <b-button v-b-tooltip.hover
                    @click="refreshSlugs()"
                    :title="$t('refresh_slugs')"
                    :class="{ 'busy': slugBusy }"
                    variant="link"
                    class="refresh-slug text-dark">
                    <fa size="xs" icon="sync" />
                  </b-button>
                </div>
              </b-col>

              <b-col sm="12" md="5">
                <!-- Status -->
                <v-select
                  :label="$t('status')"
                  :form="form"
                  :options="statuses"
                  name="status_id" />
              </b-col>

            </b-form-row>

            <!-- Headline -->
            <v-translatable-input
              :label="$t('headline')"
              :hint="$t('headline_hint')"
              :form="form"
              name="headline" />

            <!-- Body -->
            <v-translatable-editor
              :label="$t('body')"
              :form="form"
              name="body" />

          </b-tab>

          <b-tab
            :title="$t('seo')">

            <!-- Meta title -->
            <v-translatable-input
              :label="$t('meta_title')"
              :form="form"
              name="meta_title" />

            <!-- Meta description -->
            <v-translatable-input
              :label="$t('meta_description')"
              :form="form"
              name="meta_description" />

            <!-- Meta keywords -->
            <v-translatable-input
              :label="$t('meta_keywords')"
              :form="form"
              name="meta_keywords" />

          </b-tab>

        </b-tabs>

        <b-button v-show="false" ref="itemSubmit" type="submit" />

      </b-form>

       <template slot="modal-footer">

        <!-- Submit -->
        <b-button
          @click="$refs.itemSubmit.click()"
          :disabled="form.busy"
          :variant="item && item.id ? 'primary' : 'success'"
          :class="{ 'btn-loading': form.busy }">
          {{ item && item.id ? $t('update') : $t('create') }}
        </b-button>

       </template>

    </b-modal>

  </div>
</template>

<script>
import { crud } from '~/mixins/crud'
import { mapGetters } from 'vuex'
import _ from 'lodash'

export default {
  middleware: ['auth', 'acl'],
  mixins: [crud],

  metaInfo () {
    return { title: this.$t('pages') }
  },

  data: () => ({
    apiUrl: '/api/pages/',
    attributes: {
      status_id: '',
    },
    translatedAttributes: {
      slug: '',
      title: '',
      headline: '',
      body: '',
      meta_title: '',
      meta_description: '',
      meta_keywords: '',
    },
    oldTranslations: {},
    slugBusy: false,
  }),

  computed: {
    ...mapGetters({
      statuses: 'api/statuses',
    }),
    defaultStatus () {
      return null
    },
    fields () {
      return [
        { key: 'id', label: this.$t('id'), sortable: true, },
        { key: 'title', label: this.$t('title'), sortable: true, },
        { key: 'status', label: this.$t('status'), sortable: true },
        { key: 'actions', label: '', tdClass: 'table-actions', },
      ]
    }
  },

  methods: {
    async fetchSlug (title) {
      const { data } = await this.$axios.get(this.apiUrl + 'slug', { params: { title } })
      return data.slug
    },
    async refreshSlugs () {
      this.slugBusy = true
      let fallback = await this.fetchSlug(this.form.translations[this.fallbackLocale].title)
      for (const locale of Object.keys(this.form.translations)) {
        if (this.form.translations[locale].title != '') {
          let val = await this.fetchSlug(this.form.translations[locale].title)
          this.form.translations[locale].slug = val
        } else if (this.form.translations[locale].title == '') {
          this.form.translations[locale].slug = fallback
        }
      }
      this.slugBusy = false
    },
    async generateSlug (value) {
      if (this.item && !this.item.id) {
        for (const locale of Object.keys(this.form.translations)) {
          if (this.form.translations[locale].title === value || this.form.translations[locale].title == '') {
            let val = await this.fetchSlug(value)
            this.form.translations[locale].slug = val
          }
        }
      }
    },
  },

  async created () {
    this.$store.dispatch('common/setBreadcrumbs', { breadcrumbs: [
      { text: this.$t('home'), to: { name: 'dashboard' } },
      { text: this.$t('pages'), to: { name: 'pages' } },
    ] })
    await this.$store.dispatch('api/fetchStatuses')

    let defaultStatuses = this.statuses.filter(item => item.is_default)
    if (defaultStatuses.length) {
      this.attributes.status_id = defaultStatuses[0].id
    }
    this.debouncedGenerateSlug = _.debounce(this.generateSlug, 200)
    Object.keys(this.form.translations).forEach(locale => {
      this.$watch(() => this.form.translations[locale].title, this.debouncedGenerateSlug)
    })
  },
}
</script>

<style>
.refresh-slug {
  transition: all .25s linear;
  position: absolute;
  display: flex;
  align-items: center;
  height: 24px;
  width: auto;
  padding: 0 .25rem;
  top: 0;
  right: 0;
}
.refresh-slug.busy {
  animation: spinAround .5s infinite linear;
}

</style>
