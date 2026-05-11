<script setup lang="ts">
const props = defineProps<{
    checked: boolean;
}>();

const emit = defineEmits(['update:checked']);

const toggle = () => {
    emit('update:checked', !props.checked);
};
</script>

<template>
    <button
        type="button"
        role="switch"
        :aria-checked="checked"
        @click="toggle"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-[2.5px] border-black transition-colors duration-200 ease-in-out focus:outline-none"
        :class="checked ? 'bg-black' : 'bg-white'"
    >
        <span
            class="pointer-events-none inline-block h-4 w-4 transform rounded-full transition duration-200 ease-in-out shadow-sm"
            :class="checked ? 'translate-x-5 bg-white' : 'translate-x-0.5 bg-black'"
        />
    </button>
</template>
