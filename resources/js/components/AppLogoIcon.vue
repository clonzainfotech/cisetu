<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const { className = '', white = false } = defineProps<{
    className?: HTMLAttributes['class'];
    white?: boolean;
}>();
</script>

<template>
    <img
        :src="white ? '/images/white-logo.svg' : '/images/logo.svg'"
        alt="CiSeTU Logo"
        :class="className"
        v-bind="$attrs"
        class="object-contain"
    />
</template>
