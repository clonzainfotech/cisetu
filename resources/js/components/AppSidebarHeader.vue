<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Separator } from '@/components/ui/separator';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-2 border-b bg-background/95 backdrop-blur transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
    >
        <div class="flex items-center gap-2 px-4 h-full">
            <SidebarTrigger class="-ml-1" />
            <Separator orientation="vertical" class="mx-2 h-4" />
            <div class="flex items-center h-full">
                <Breadcrumbs :breadcrumbs="breadcrumbs" v-if="breadcrumbs && breadcrumbs.length > 0" />
            </div>
        </div>
    </header>
</template>
