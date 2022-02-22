import Vue from 'vue'
import Checkbox from './form/Checkbox'
import Checkboxes from './form/Checkboxes'
import Select from './form/Select'
import VariantSelect from './form/VariantSelect'
import Autocomplete from './form/Autocomplete'
import Datepicker from './form/Datepicker'
import Input from './form/Input'
import NumberInput from './form/NumberInput'
import FileInput from './form/FileInput'
import Textarea from './form/Textarea'
import Editor from './form/Editor'
import TranslatableSwitch from './form/TranslatableSwitch'
import TranslatableInput from './form/TranslatableInput'
import TranslatableTextarea from './form/TranslatableTextarea'
import TranslatableEditor from './form/TranslatableEditor'
import Submit from './form/Submit'
import Tabs from './controls/Tabs'
import Icon from './controls/Icon'
import FileList from './controls/FileList'
import ActionButton from './controls/ActionButton'
import Child from './layout/Child'
import PageHeader from './layout/PageHeader'

// Components that are registered globally.
[
  Checkbox,
  Checkboxes,
  Select,
  VariantSelect,
  Autocomplete,
  Datepicker,
  Input,
  NumberInput,
  FileInput,
  Textarea,
  Editor,
  TranslatableSwitch,
  TranslatableInput,
  TranslatableTextarea,
  TranslatableEditor,
  Submit,
  Tabs,
  Icon,
  FileList,
  ActionButton,
  Child,
  PageHeader
].forEach(Component => {
  Vue.component(Component.name, Component)
})
