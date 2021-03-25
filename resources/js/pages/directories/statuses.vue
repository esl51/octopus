<template>
  <div>
    <!-- Filters -->
    <b-input-group class="my-3">
      <b-input-group-prepend>
        <!-- Add -->
        <b-button
          v-b-tooltip.hover
          :title="$t('add')"
          variant="success"
          @click="addItem()"
        >
          <v-icon type="plus" />
          <span class="d-none d-md-inline-block ml-1">{{ $t('add') }}</span>
        </b-button>
      </b-input-group-prepend>

      <!-- Search -->
      <b-form-input
        id="filterInput"
        v-model="search"
        type="search"
        :debounce="200"
        :placeholder="$t('search')"
      />

      <b-input-group-append>
        <!-- Refresh -->
        <b-button
          v-b-tooltip.hover
          :title="$t('refresh')"
          @click="refreshItems()"
        >
          <v-icon type="refresh" />
        </b-button>
      </b-input-group-append>
    </b-input-group>

    <!-- Items -->
    <b-table
      ref="items"
      stacked="sm"
      :api-url="apiUrl"
      :items="fetchItems"
      :fields="fields"
      :busy.sync="isBusy"
      :current-page="currentPage"
      :per-page="perPage"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      :filter="search"
    >
      <template #cell(name)="data">
        <div class="d-flex align-items-center">
          <div
            v-if="data.item.variant"
            class="p-2 rounded mr-2"
            :class="'bg-' + data.item.variant"
          />
          {{ data.item.name }}
        </div>
      </template>

      <template #cell(is_published)="data">
        <v-icon
          v-if="data.item.is_published"
          :title="$t('is_published')"
          type="check"
        />
        <span v-else />
      </template>

      <template #cell(is_default)="data">
        <v-icon
          v-if="data.item.is_default"
          :title="$t('is_default')"
          type="check"
        />
        <span v-else />
      </template>

      <template #cell(actions)="data">
        <!-- View -->
        <action-button
          :title="$t('view')"
          class="mr-2"
          icon="eye"
          @click.native="viewItem(data.item)"
        />

        <!-- Edit -->
        <action-button
          :disabled="!data.item.is_editable"
          :title="$t('edit')"
          class="mr-2"
          icon="edit"
          @click.native="editItem(data.item)"
        />

        <!-- Delete -->
        <action-button
          :disabled="!data.item.is_deletable"
          :title="$t('delete')"
          class="text-danger"
          icon="trash"
          @click.native="deleteItem(data.item)"
        />
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
      class="my-0"
    />

    <!-- View -->
    <b-modal
      id="itemView"
      centered
      ok-only
      :title="item.name"
    >
      <b-table
        small
        stacked
        :items="[item]"
        :fields="fields.filter(field => field.key != 'actions')"
        class="table-view"
      >
        <template #cell(is_published)="data">
          <v-icon
            v-if="data.item.is_published"
            :title="$t('is_published')"
            type="check"
          />
          <span v-else />
        </template>

        <template #cell(is_default)="data">
          <v-icon
            v-if="data.item.is_default"
            :title="$t('is_default')"
            type="check"
          />
          <span v-else />
        </template>
      </b-table>
    </b-modal>

    <!-- Form -->
    <b-modal
      id="itemModal"
      centered
      :title="(item && item.name) || $t('status')"
    >
      <b-form
        ref="itemForm"
        @submit.prevent="submitItem()"
        @keydown="form.onKeydown($event)"
      >
        <!-- Title -->
        <v-translatable-input
          autofocus
          :label="$t('name')"
          :form="form"
          name="name"
        />

        <!-- Variant -->
        <v-variant-select
          :label="$t('variant')"
          :form="form"
          name="variant"
        />

        <!-- Is published -->
        <v-checkbox
          :label="$t('is_published')"
          :form="form"
          name="is_published"
        />

        <!-- Is default -->
        <v-checkbox
          :label="$t('is_default')"
          :form="form"
          name="is_default"
        />
      </b-form>

      <template slot="modal-footer">
        <!-- Submit -->
        <v-submit
          :form="form"
          @click.native="submitItem()"
        >
          {{ item && item.id ? $t('update') : $t('create') }}
        </v-submit>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { crud } from '~/mixins/crud'

export default {
  mixins: [crud],
  middleware: ['auth', 'acl'],

  metaInfo () {
    return { title: this.$t('statuses') }
  },

  data: () => ({
    apiUrl: '/api/statuses/',
    attributes: {
      variant: '',
      is_published: 0,
      is_default: 0
    },
    translatedAttributes: { name: '' }
  }),

  computed: {
    fields () {
      return [
        { key: 'id', label: this.$t('id'), sortable: true },
        { key: 'name', label: this.$t('name'), sortable: true },
        { key: 'is_published', label: this.$t('is_published'), sortable: true },
        { key: 'is_default', label: this.$t('is_default'), sortable: true },
        { key: 'actions', label: '', tdClass: 'table-actions' }
      ]
    }
  }
}
</script>
