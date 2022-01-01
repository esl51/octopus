<template>
  <div
    v-if="files && files.length > 0"
    class="file-list"
  >
    <div
      v-for="file in files"
      :key="file.id"
      class="file-list-item"
    >
      <a
        v-if="download"
        :href="file.url"
        target="_blank"
        class="file-list-title"
        @click.prevent="download ? downloadFile(file) : false"
      >
        {{ file.name }}
      </a>
      <a
        v-else
        :href="file.url"
        target="_blank"
        class="file-list-title"
      >
        {{ file.name }}
      </a>
      <a
        v-if="!readonly"
        class="file-list-delete"
        href="#"
        :title="$t('delete')"
        @click.prevent="deleteFile(file)"
      >
        <v-icon type="x" />
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileList',

  props: {
    files: { type: Array, default: null },
    readonly: { type: Boolean, default: false },
    download: { type: Boolean, default: false }
  },

  methods: {
    async downloadFile (file) {
      const { data } = await this.$axios({ url: file.url, method: 'get', responseType: 'blob' })
      const blob = new Blob([data], { type: file.type })
      const link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = file.name
      document.body.appendChild(link)
      link.click()
      setTimeout(() => {
        document.body.removeChild(link)
      }, 100)
    },
    async deleteFile (file) {
      const i = this.files.map(c => c.id).indexOf(file.id)
      await this.$axios.delete(file.url)
      this.files.splice(i, 1)
      this.$noty.success(this.$t('deleted'))
    }
  }
}
</script>
