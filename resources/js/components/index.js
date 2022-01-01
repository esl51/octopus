import Vue from 'vue'
import Child from './Child'
import PageHeader from './PageHeader'
import Checkbox from './Checkbox'
import Checkboxes from './Checkboxes'
import Select from './Select'
import VariantSelect from './VariantSelect'
import ActionButton from './ActionButton'
import Autocomplete from './Autocomplete'
import Datepicker from './Datepicker'
import Input from './Input'
import NumberInput from './NumberInput'
import FileInput from './FileInput'
import FileList from './FileList'
import Textarea from './Textarea'
import Editor from './Editor'
import TranslatableSwitch from './TranslatableSwitch'
import TranslatableInput from './TranslatableInput'
import TranslatableTextarea from './TranslatableTextarea'
import TranslatableEditor from './TranslatableEditor'
import Submit from './Submit'
import Tabs from './Tabs'
import Icon from './Icon'

// Components that are registered globally.
[
  Child,
  PageHeader,
  Checkbox,
  Checkboxes,
  Select,
  VariantSelect,
  ActionButton,
  Autocomplete,
  Datepicker,
  Input,
  NumberInput,
  FileInput,
  FileList,
  Textarea,
  Editor,
  TranslatableSwitch,
  TranslatableInput,
  TranslatableTextarea,
  TranslatableEditor,
  Submit,
  Tabs,
  Icon
].forEach(Component => {
  Vue.component(Component.name, Component)
})
