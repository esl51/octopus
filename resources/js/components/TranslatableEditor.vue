<template>
  <b-form-group
    :description="hint"
    :state="hasErrors() ? false : null"
  >
    <div class="d-flex align-items-baseline mb-2">
      <label class="mb-0">{{ label }}</label>

      <v-translatable-switch
        :states="getStates()"
        class="ml-2"
        @change="changeLocale"
      />
    </div>

    <div
      v-for="locale in Object.keys(locales)"
      v-show="currentLocale == locale"
      :key="locale"
    >
      <jodit-editor
        :id="id ? id + '-' + locale : 'item-' + name + '-' + locale"
        :ref="'input-' + locale"
        v-model="form.translations[locale][name]"
        :config="editorConfig"
        :buttons="editorButtons"
      />
    </div>

    <div
      v-for="(error, index) in getErrors()"
      slot="invalid-feedback"
      :key="index"
    >
      {{ error }}
    </div>
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import { translatable } from '~/mixins/translatable'
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'

export default {
  name: 'VTranslatableEditor',

  components: {
    JoditEditor
  },
  mixins: [control, translatable],

  data: () => ({
    editorButtons: ['undo', 'redo', 'paragraph', '|', 'bold', 'italic', 'underline', 'strikethrough', 'align', '|', 'table', 'link', 'hr', '|', 'ul', 'ol', '|', 'superscript', 'subscript', '|', 'image', 'file', 'video', '|', 'eraser', 'fullsize', 'source'],
    editorConfig: {
      controls: {
        paragraph: {
          list: {
            p: 'Normal'
          }
        }
      },
      defaultActionOnPaste: 'insert_clear_html',
      filebrowser: {
        ajax: {
          url: '/api/jodit/'
        }
      },
      uploader: {
        url: '/api/jodit/?action=fileUpload'
      }
    }
  }),

  created () {
    this.editorConfig.autofocus = this.autofocus
    this.editorConfig.disabled = this.disabled
    this.editorConfig.readonly = this.readonly
  },

  methods: {
    changeLocale (locale) {
      this.currentLocale = locale
    }
  }
}
</script>
