import { crud } from '~/mixins/crud'
export const crudModule = {
  mixins: [crud],
  props: {
    loadItem: { type: Object, default: null },
    moduleApiUrl: { type: String, default: null }
  },

  data: () => ({
    itemListId: 'itemList'
  }),

  watch: {
    moduleApiUrl: {
      immediate: true,
      handler (url) {
        this.apiUrl = url
        if (this.apiUrl) {
          this.refreshItems()
        }
      }
    },
    loadItem: {
      immediate: true,
      handler (item) {
        this.item = item
        if (this.item) {
          this.fillForm()
        } else {
          this.initForm()
        }
      }
    }
  },

  methods: {
    open () {
      this.$bvModal.show(this.itemListId)
      this.refreshItems()
      this.$emit('open')
    },
    close () {
      this.$bvModal.hide(this.itemListId)
      this.$emit('close')
    },
    openModal () {
      this.$bvModal.show(this.modalId)
      this.$emit('open-modal')
    },
    closeModal () {
      this.$bvModal.hide(this.modalId)
      this.$emit('close-modal')
    }
  }
}
