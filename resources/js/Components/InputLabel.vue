<script setup>
defineProps(['value', 'required']);
</script>

<template>
    <label class="block font-medium text-sm capitalize text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span class="text-red-600" v-if="required">*</span>
    </label>
</template>
