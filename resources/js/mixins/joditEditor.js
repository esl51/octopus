import 'jodit/build/jodit.min.css'
export const joditEditor = {
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
          url: '/api/jodit/browse',
          prepareData: data => {
            data.source = 'default'
            return data
          },
          process: resp => {
            if (resp.data && resp.data.sources) {
              const sources = []
              Object.keys(resp.data.sources).forEach(key => {
                const source = resp.data.sources[key]
                source.name = key
                sources.push(source)
              })
              resp.data.sources = sources
            }
            return resp
          }
        }
      },
      uploader: {
        url: '/api/jodit/upload'
      }
    }
  }),

  created () {
    this.editorConfig.autofocus = this.autofocus
    this.editorConfig.disabled = this.disabled
    this.editorConfig.readonly = this.readonly
  }
}
