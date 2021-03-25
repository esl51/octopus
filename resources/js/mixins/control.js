export const control = {

  props: {
    id: { type: String, default: null },
    form: { type: Object, default: null },
    name: { type: String, default: null },
    label: { type: String, default: null },
    hint: { type: String, default: null },
    readonly: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    autofocus: { type: Boolean, default: false },
    labelCols: { type: [String, Number], default: null },
    labelColsSm: { type: [String, Number], default: null },
    labelColsMd: { type: [String, Number], default: null },
    labelColsLg: { type: [String, Number], default: null }
  }
}
