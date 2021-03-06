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
      />
    </b-modal>

    <!-- Form -->
    <b-modal
      id="itemModal"
      centered
      :title="(item && item.name) || $t('role')"
    >
      <b-form
        ref="itemForm"
        @submit.prevent="submitItem()"
        @keydown="form.onKeydown($event)"
      >
        <!-- Name -->
        <v-input
          autofocus
          :label="$t('name')"
          :form="form"
          name="name"
        />

        <!-- Guard name -->
        <v-input
          :label="$t('guard_name')"
          :form="form"
          name="guard_name"
        />

        <!-- Permissions -->
        <v-checkboxes
          :label="$t('permissions')"
          :options="permissions"
          :form="form"
          name="permissions"
          label-attribute="name"
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
import { mapGetters } from 'vuex'

export default {
  mixins: [crud],
  middleware: ['auth', 'acl'],

  metaInfo () {
    return { title: this.$t('roles') }
  },

  data: () => ({
    apiUrl: '/api/access/roles/',
    attributes: { name: '', guard_name: 'api', permissions: [] }
  }),

  computed: {
    ...mapGetters({
      permissions: 'api/permissions'
    }),
    fields () {
      return [
        { key: 'id', label: this.$t('id'), sortable: true },
        { key: 'name', label: this.$t('name'), sortable: true },
        { key: 'guard_name', label: this.$t('guard_name'), sortable: true },
        { key: 'permissions', label: this.$t('permissions'), sortable: false, formatter: value => value.map(permission => permission.name).join(', ') },
        { key: 'actions', label: '', tdClass: 'table-actions' }
      ]
    }
  },

  created () {
    this.$store.dispatch('api/fetchPermissions')
  }
}
</script>
