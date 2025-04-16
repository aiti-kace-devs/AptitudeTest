<script setup>
import { ref, watch } from 'vue';
import EasyMDE from '@/Components/EasyMDE.vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const content = ref(props.modelValue);
const editorOptions = ref({
  placeholder: "Write something...",
  spellChecker: false,
  autoDownloadFontAwesome: true,
  forceSync: true,
  minHeight: '200px',
  renderingConfig: {
    singleLineBreaks: false,
    codeSyntaxHighlighting: true,
  },
  // Add other EasyMDE options here
});

watch(content, (newValue) => {
  emit('update:modelValue', newValue);
});

watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue;
  }
});
</script>

<template>
  <div>
    <EasyMDE
      v-model="content"
      :options="editorOptions"
      :error="error"
    />
  </div>
</template>
