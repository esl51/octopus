<template>
  <div>
    <h2 class="my-3">{{ $t('roles') }}</h2>

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

      <template v-slot:cell(actions)="data">

        <!-- View -->
        <action-button
          @click.native="viewItem(data.item)"
          :title="$t('view')"
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
    <b-modal centered
      id="itemModal"
      :title="(item && item.name) || $t('role')">

      <b-form
        ref="itemForm"
        @submit.prevent="item && item.id ? updateItem() : createItem()"
        @keydown="form.onKeydown($event)">

        <!-- Name -->
        <v-input autofocus
          :label="$t('name')"
          :form="form"
          name="name" />

        <!-- Guard name -->
        <v-input
          :label="$t('guard_name')"
          :form="form"
          name="guard_name" />

        <!-- Permissions -->
        <v-checkboxes
          :label="$t('permissions')"
          :options="permissions"
          :form="form"
          name="permissions"
          label-attribute="name" />

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

export default {
  middleware: ['auth', 'acl'],
  mixins: [crud],

  metaInfo () {
    return { title: this.$t('roles') }
  },

  data: () => ({
    apiUrl: '/api/access/roles/',
    attributes: { name: '', guard_name: 'api', permissions: [] },
  }),

  computed: {
    ...mapGetters({
      permissions: 'api/permissions'
    }),
    fields () {
      return [
        { key: 'id', label: this.$t('id'), sortable: true, },
        { key: 'name', label: this.$t('name'), sortable: true, },
        { key: 'guard_name', label: this.$t('guard_name'), sortable: true, },
        { key: 'permissions', label: this.$t('permissions'), sortable: false, formatter: value => value.map(permission => permission.name).join(", ") },
        { key: 'actions', label: '', tdClass: 'table-actions', },
      ]
    }
  },

  created () {
    this.$store.dispatch('api/fetchPermissions');
  },
}
</script>
