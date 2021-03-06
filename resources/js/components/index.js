import Vue from 'vue'
import Child from './Child'
import PageHeader from './PageHeader'
import Checkbox from './Checkbox'
import Checkboxes from './Checkboxes'
import Select from './Select'
import VariantSelect from './VariantSelect'
import ActionButton from './ActionButton'
import Input from './Input'
import Textarea from './Textarea'
import Editor from './Editor'
import TranslatableSwitch from './TranslatableSwitch'
import TranslatableInput from './TranslatableInput'
import TranslatableTextarea from './TranslatableTextarea'
import TranslatableEditor from './TranslatableEditor'
import Submit from './Submit'
import Tabs from './Tabs'
import Icon from './Icon'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globally.
[
  Child,
  PageHeader,
  Checkbox,
  Checkboxes,
  Select,
  VariantSelect,
  ActionButton,
  Input,
  Textarea,
  Editor,
  TranslatableSwitch,
  TranslatableInput,
  TranslatableTextarea,
  TranslatableEditor,
  Submit,
  Tabs,
  Icon,
  HasError,
  AlertError,
  AlertSuccess
].forEach(Component => {
  Vue.component(Component.name, Component)
})
