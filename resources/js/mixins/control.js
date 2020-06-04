export const control = {

  props: {
    id: { type: String, default: null },
    form: { type: Object, default: null },
    name: { type: String, default: null },
    label: { type: String, default: null },
    hint: { type: String, default: null },
    readonly: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    autofocus: { type: Boolean, default: false }
  }
}
