<template>
  <div
    v-if="error"
    class="text-red-400 text-xs mt-1"
  >
    <span
      v-if="safe"
      v-html="error"
    />
    <span v-else>{{ error }}</span>
  </div>
</template>

<script>
export default {
    name: 'XFormErrors',
    props: {
        error: {
            type: [String, Array],
            default: '',
            required: false
        },
        safe: {
            type: Boolean,
            default: true,
            required: false
        },
    }
}
</script>
