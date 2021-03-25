<template>
  <b-form-group
    :label="label"
    :label-for="id || 'item-' + name"
    :invalid-feedback="form.errors && form.errors.get(name)"
    :description="hint"
    :label-cols="labelCols"
    :label-cols-sm="labelColsSm"
    :label-cols-md="labelColsMd"
    :label-cols-lg="labelColsLg"
  >
    <jodit-editor
      :id="id || name"
      v-model="form.translations[locale][name]"
      :config="editorConfig"
      :buttons="editorButtons"
    />
  </b-form-group>
</template>

<script>
import { control } from '~/mixins/control'
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'

export default {
  name: 'VEditor',

  components: {
    JoditEditor
  },
  mixins: [control],

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
  }
}
</script>
