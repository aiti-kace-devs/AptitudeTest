<script setup>
import { onMounted, ref } from "vue";

defineProps(["modelValue", "cols", "rows"]);

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <textarea
    class="border-gray-300 focus:border-gray-700 focus:ring-gray-700 rounded-sm shadow-sm text-sm read-only:cursor-not-allowed read-only:bg-gray-200"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
    :cols="cols"
    :rows="rows"
  ></textarea>
</template>
