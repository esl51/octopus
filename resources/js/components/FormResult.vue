<template>
  <div class="form-result">
    <div
      v-if="icon"
      class="form-result-icon"
    >
      <v-icon
        :type="icon"
        size="32"
      />
    </div>
    <p
      v-if="title"
      class="form-result-title"
    >
      {{ title }}
    </p>
    <div
      v-if="text"
      class="form-result-text"
      v-html="text"
    />
    <slot />
  </div>
</template>

<script>
export default {
  name: 'FormResult',
  props: {
    icon: { type: String, default: 'check' },
    title: { type: String, default: null },
    text: { type: String, default: null }
  }
}
</script>
