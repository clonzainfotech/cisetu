<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Crown } from 'lucide-vue-next';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
const village = computed(() => page.props.village as any);
</script>

<template>
    <div
        class="flex h-8 w-auto items-center justify-center overflow-hidden rounded-md border border-border bg-white text-foreground dark:bg-zinc-950 px-1.5"
    >
        <img
            v-if="village?.logo"
            :src="'/storage/' + village.logo"
            :alt="village.name_en"
            class="h-full w-auto object-contain p-0.5"
        />
        <AppLogoIcon v-else class="h-5 w-auto" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-bold font-gujarati"
            >{{ village?.name_local || village?.name_en || 'Village Portal' }}</span
        >
        <span class="truncate text-[10px] text-muted-foreground font-medium uppercase tracking-wider flex items-center gap-1">
            <Crown v-if="village?.plan?.code === 'pragati'" class="size-2.5 text-amber-500 fill-current" />
            <span class="whitespace-nowrap">{{ village?.plan?.name || 'Basic Plan' }}</span>
        </span>
    </div>
</template>
