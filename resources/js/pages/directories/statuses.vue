<template>
  <div>
    <h2 class="my-3">
      {{ $t('statuses') }}
    </h2>

    <!-- Filters -->
    <b-input-group
      size="sm"
      class="my-3"
    >
      <b-input-group-prepend>
        <!-- Add -->
        <b-button
          v-b-tooltip.hover
          :title="$t('add')"
          variant="success"
          @click="addItem()"
        >
          <fa
            icon="plus"
            fixed-width
          />
          <span class="d-none d-md-inline">{{ $t('add') }}</span>
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
          <fa
            icon="sync"
            fixed-width
          />
        </b-button>
      </b-input-group-append>
    </b-input-group>

    <!-- Items -->
    <b-table
      ref="items"
      small
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
      <template v-slot:cell(name)="data">
        <div class="d-flex align-items-center">
          <div
            v-if="data.item.variant"
            class="p-2 rounded mr-2"
            :class="'bg-' + data.item.variant"
          />
          {{ data.item.name }}
        </div>
      </template>

      <template v-slot:cell(is_published)="data">
        <fa
          v-if="data.item.is_published"
          fixed-width
          :title="$t('is_published')"
          icon="check"
        />
        <span v-else />
      </template>

      <template v-slot:cell(is_default)="data">
        <fa
          v-if="data.item.is_default"
          fixed-width
          :title="$t('is_default')"
          icon="check"
        />
        <span v-else />
      </template>

      <template v-slot:cell(actions)="data">
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
          icon="trash-alt"
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
      />
    </b-modal>

    <!-- Form -->
    <b-modal
      id="itemModal"
      centered
      :title="(item && item.name) || $t('status')"
    >
      <b-form
        ref="itemForm"
        @submit.prevent="item && item.id ? updateItem() : createItem()"
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

        <b-button
          v-show="false"
          ref="itemSubmit"
          type="submit"
        />
      </b-form>

      <template slot="modal-footer">
        <!-- Submit -->
        <b-button
          :disabled="form.busy"
          :variant="item && item.id ? 'primary' : 'success'"
          :class="{ 'btn-loading': form.busy }"
          @click="$refs.itemSubmit.click()"
        >
          {{ item && item.id ? $t('update') : $t('create') }}
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { crud } from '~/mixins/crud'

export default {
  middleware: ['auth', 'acl'],
  mixins: [crud],

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
