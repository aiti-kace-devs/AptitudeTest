<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  options: {
    type: Object,
    default: () => ({})
  },
  error: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const editor = ref(null);
const easymde = ref(null);

const toolbar = [
  {
    name: "bold",
    action: EasyMDE.toggleBold,
    className: "fa fa-bold",
    title: "Bold",
  },
  {
    name: "italic",
    action: EasyMDE.toggleItalic,
    className: "fa fa-italic",
    title: "Italic",
  },            
  {
    name: "heading-1",
    action: EasyMDE.toggleHeading1,
    className: "fa fa-header header-1",
    title: "Heading 1",
  },
  {
    name: "heading-2",
    action: EasyMDE.toggleHeading2,
    className: "fa fa-header header-2",
    title: "Heading 2",
  },
  {
    name: "heading-3",
    action: EasyMDE.toggleHeading3,
    className: "fa fa-header header-3",
    title: "Heading 3",
  },
  "|",
  {
    name: "unordered-list",
    action: EasyMDE.toggleUnorderedList,
    className: "fa fa-list-ul",
    title: "Bullet List",
  },
  {
    name: "ordered-list",
    action: EasyMDE.toggleOrderedList,
    className: "fa fa-list-ol",
    title: "Numbered List",
  },
  "|",
  {
    name: "preview",
    action: EasyMDE.togglePreview,
    className: "fa fa-eye no-disable",
    title: "Toggle Preview",
  },
];

const defaultOptions = {  
  toolbar
};

const initializeEditor = () => {
  easymde.value = new EasyMDE({
    element: editor.value,
    initialValue: props.modelValue,
    ...defaultOptions,
    ...props.options
  });

  easymde.value.codemirror.on("change", () => {
    emit('update:modelValue', easymde.value.value());
  });

  easymde.value.codemirror.on("keydown", (cm, event) => {
     if (event.key === "Enter" && event.shiftKey) {
      // Shift+Enter for line break
      cm.replaceSelection("\n\n"); // Two spaces + newline
      event.preventDefault();
    }
  });
};

onMounted(() => {
  initializeEditor();
});

onBeforeUnmount(() => {
  if (easymde.value) {
    easymde.value.toTextArea();
    easymde.value = null;
  }
});

watch(() => props.modelValue, (newValue) => {
  if (easymde.value && newValue !== easymde.value.value()) {
    easymde.value.value(newValue);
  }
});
</script>

<template>
  <div :class="{ 'easy-mde-wrapper': true, 'error': error }">
    <textarea ref="editor"></textarea>
  </div>
</template>

<style scoped>
.easy-mde-wrapper {
  --border-color: rgb(209 213 219);
  --border-color-error: rgb(220 38 38);
  --focus-color: rgb(59 130 246);
  --shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

:deep(.EasyMDEContainer) {
  font-family: inherit;
}

:deep(.CodeMirror) {
  min-height: 150px;
  border-radius: 0.125rem;
  border: 1px solid var(--border-color);
  padding: 0.75rem;
  box-shadow: var(--shadow);
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.error :deep(.CodeMirror) {
  border-color: var(--border-color-error) !important;
}

.error :deep(.CodeMirror-focused) {
  box-shadow: 0 0 0 1px var(--border-color-error);
}
</style>