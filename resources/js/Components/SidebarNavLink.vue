<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  href: {
    type: String,
    required: true,
  },
  active: {
    type: Boolean,
  },
});

const classes = computed(() =>
  props.active
    ? "w-full capitalize inline-flex items-center px-4 py-3 rounded-sm font-bold leading-5 bg-white text-gray-700 focus:outline-none focus:border-gray-700 transition duration-150 ease-in-out"
    : "w-full capitalize inline-flex items-center px-4 py-3 rounded-sm font-medium leading-5 text-gray-400 hover:text-gray-300 focus:outline-none focus:text-gray-400 transition duration-150 ease-in-out"
);
</script>

<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>
