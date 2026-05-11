<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

type Props = {
    breadcrumbs: BreadcrumbItemType[];
};

defineProps<Props>();
</script>

<template>
    <Breadcrumb class="hidden md:block">
        <BreadcrumbList class="flex-nowrap whitespace-nowrap overflow-hidden">
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem class="flex items-center">
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage class="font-bold text-foreground truncate max-w-[200px]">{{ item.title }}</BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child>
                            <Link :href="item.href" class="transition-colors hover:text-foreground">{{ item.title }}</Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" class="mx-1 opacity-50" />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
    
    <!-- Mobile Breadcrumb (Simplified) -->
    <div class="flex md:hidden text-xs font-black uppercase tracking-widest text-foreground/70">
        {{ breadcrumbs[breadcrumbs.length - 1]?.title }}
    </div>
</template>
