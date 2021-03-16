/* global config */
import Form from 'vform'
import { serialize } from 'object-to-formdata'
import { mapGetters } from 'vuex'

export const crud = {

  data: () => ({
    item: {},
    apiUrl: '',
    totalRows: 1,
    currentPage: 1,
    perPage: 15,
    search: '',
    sortBy: '',
    sortDesc: false,
    sortDirection: 'asc',
    isBusy: false,
    filters: {},
    form: {},
    attributes: {},
    translatedAttributes: {}
  }),

  computed: mapGetters({
    user: 'auth/user',
    locale: 'lang/locale',
    fallbackLocale: 'lang/fallbackLocale'
  }),

  methods: {

    async fetchItems (ctx) {
      const params = {
        page: ctx.currentPage,
        per_page: ctx.perPage,
        sort_by: ctx.sortBy,
        sort_desc: ctx.sortDesc ? 1 : 0,
        search: ctx.filter
      }
      if (Object.keys(this.filters).length) {
        for (const filter in this.filters) {
          params[filter] = this.filters[filter]
        }
      }
      this.isBusy = true
      const { data } = await this.$axios.get(ctx.apiUrl, { params })
      this.isBusy = false
      this.totalRows = data.meta.total
      return data.data
    },

    async refreshItems () {
      this.$refs.items.refresh()
    },

    initForm (formName, attributes, translatedAttributes) {
      if (formName === undefined) {
        formName = 'form'
      }
      if (attributes === undefined) {
        attributes = this.attributes
      }
      if (translatedAttributes === undefined) {
        translatedAttributes = this.translatedAttributes
      }
      var prepared = attributes
      if (Object.keys(translatedAttributes).length) {
        prepared.translations = {}
        Object.keys(config.locales).forEach((locale) => {
          if (!prepared.translations[locale]) {
            prepared.translations[locale] = {}
          }
          for (const attr in translatedAttributes) {
            if (Object.prototype.hasOwnProperty.call(translatedAttributes, attr)) {
              prepared.translations[locale][attr] = translatedAttributes[attr]
            }
          }
        })
      }
      this[formName] = new Form(prepared)
    },

    fillForm (item, formName, attributes, translatedAttributes) {
      if (formName === undefined) {
        formName = 'form'
      }
      if (attributes === undefined) {
        attributes = this.attributes
      }
      if (translatedAttributes === undefined) {
        translatedAttributes = this.translatedAttributes
      }
      this.initForm(formName, attributes, translatedAttributes)
      let data = item
      if (item.pivot) {
        data = item.pivot
      }
      if (Object.keys(data).length < 1) {
        return
      }
      this[formName].keys().forEach(key => {
        // translatable
        if (key === 'translations') {
          data.translations.forEach((translation) => {
            for (const attr in translatedAttributes) {
              if (!this[formName][key][translation.locale]) {
                this[formName][key][translation.locale] = {}
              }
              this[formName][key][translation.locale][attr] = translation[attr] === null ? '' : translation[attr]
            }
          })
        // property values
        } else if (Array.isArray(data[key]) && data[key].length && typeof data[key][0] === 'object' && data[key][0].id && !data[key][0].downloadUrl) {
          this[formName][key] = data[key].map(v => v.id)
        // property value
        } else if (typeof data[key] === 'object' && data[key] && data[key].id && !data[key].downloadUrl) {
          this[formName][key] = data[key].id
        // other value(s)
        } else if (data[key] !== undefined) {
          this[formName][key] = data[key]
        }
      })
    },

    viewItem (item) {
      this.item = item
      this.$bvModal.show('itemView')
    },

    addItem () {
      this.item = {}
      this.$bvModal.show('itemModal')
      this.initForm()
    },

    async createItem () {
      const { data } = await this.form.submit('post', this.apiUrl, {
        transformRequest: [(data, headers) => {
          return serialize(data, { indices: true })
        }]
      })
      this.item = data.data
      this.refreshItems()
      this.$bvModal.hide('itemModal')
      this.initForm()
    },

    editItem (item) {
      this.item = item
      this.fillForm(item)
      this.$bvModal.show('itemModal')
    },

    async updateItem () {
      const { data } = await this.form.submit('post', this.apiUrl + this.item.id, {
        transformRequest: [(data, headers) => {
          data._method = 'PUT'
          return serialize(data, { indices: true })
        }]
      })
      this.item = data.data
      this.refreshItems()
      this.$bvModal.hide('itemModal')
      this.initForm()
    },

    async trashItem (item) {
      const value = await this.$bvModal.msgBoxConfirm(this.$t('delete_confirm', { name: item.title || item.name }), {
        title: this.$t('confirm_title'),
        okVariant: 'danger',
        okTitle: this.$t('yes'),
        cancelTitle: this.$t('no'),
        hideHeaderClose: false,
        centered: true
      })
      if (value) {
        await this.$axios.delete(this.apiUrl + item.id)
        this.refreshItems()
      }
    },

    moveItem ({ relatedContext, draggedContext }) {
      this.item = draggedContext.element
      this.moveTo = relatedContext.element
    },

    async itemMoved (event) {
      if (event.oldIndex < event.newIndex) {
        const { data } = await this.$axios.post(this.apiUrl + this.item.id + '/move-after/' + this.moveTo.id)
        this.item = data.data
      } else if (event.oldIndex > event.newIndex) {
        const { data } = await this.$axios.post(this.apiUrl + this.item.id + '/move-before/' + this.moveTo.id)
        this.item = data.data
      }
    }

  },

  created () {
    this.initForm()
  }
}
