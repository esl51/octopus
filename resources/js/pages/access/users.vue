<template>
  <div>
    <h2 class="my-3">
      {{ $t('users') }}
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
      <!-- Email -->
      <template v-slot:cell(email)="data">
        <a :href="'mailto:' + data.item.email">
          {{ data.item.email }}
        </a>
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
        class="table-view"
        small
        stacked
        :items="[item]"
        :fields="fields.filter(field => field.key != 'actions')"
      >
        <a
          slot="[email]"
          slot-scope="row"
          :href="'mailto:' + row.item.email"
        >
          {{ row.item.email }}
        </a>
      </b-table>
    </b-modal>

    <!-- Form -->
    <b-modal
      id="itemModal"
      centered
      :title="(item && item.name) || $t('user')"
    >
      <b-form
        ref="itemForm"
        @submit.prevent="item && item.id ? updateItem() : createItem()"
        @keydown="form.onKeydown($event)"
      >
        <!-- Name -->
        <v-input
          autofocus
          :label="$t('name')"
          :form="form"
          name="name"
        />

        <!-- Email -->
        <v-input
          :label="$t('email')"
          :form="form"
          name="email"
          type="email"
        />

        <!-- Password -->
        <v-input
          :label="$t('password')"
          :form="form"
          name="password"
          type="password"
        />

        <!-- Confirm password -->
        <v-input
          :label="$t('confirm_password')"
          :form="form"
          name="password_confirmation"
          type="password"
        />

        <!-- Roles -->
        <v-checkboxes
          :label="$t('roles')"
          :options="roles"
          :form="form"
          name="roles"
          label-attribute="name"
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
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'acl'],
  mixins: [crud],

  metaInfo () {
    return { title: this.$t('users') }
  },

  data: () => ({
    apiUrl: '/api/access/users/',
    perPage: 5,
    attributes: { name: '', email: '', password: '', password_confirmation: '', roles: [] }
  }),

  computed: {
    ...mapGetters({
      roles: 'api/roles'
    }),
    fields () {
      return [
        { key: 'id', label: this.$t('id'), sortable: true },
        { key: 'name', label: this.$t('name'), sortable: true },
        { key: 'email', label: this.$t('email'), sortable: true },
        { key: 'roles', label: this.$t('roles'), sortable: false, formatter: value => value.map(role => role.name).join(', ') },
        { key: 'actions', label: '', tdClass: 'table-actions' }
      ]
    }
  },

  created () {
    this.$store.dispatch('api/fetchRoles')
  }
}
</script>
