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
    <h2
      v-if="title"
      class="form-result-title"
    >
      {{ title }}
    </h2>
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
    icon: { type: String, default: null },
    title: { type: String, default: null },
    text: { type: String, default: null }
  }
}
</script>
