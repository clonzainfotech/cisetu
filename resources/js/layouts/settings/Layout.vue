<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import { User, ShieldCheck } from 'lucide-vue-next';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
        icon: User,
    },
    {
        title: 'Security',
        href: editSecurity(),
        icon: ShieldCheck,
    },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="block w-full">
        <!-- Tab Navigation - Standard Bottom Border with Dark Mode Support -->
        <div class="mb-8 border-b border-zinc-200 bg-transparent">
            <nav class="flex -mb-px space-x-8 overflow-x-auto" aria-label="Settings navigation">
                <Link
                    v-for="item in sidebarNavItems"
                    :key="toUrl(item.href)"
                    :href="toUrl(item.href)"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 flex items-center gap-2"
                    :class="[
                        isCurrentOrParentUrl(item.href)
                            ? 'border-primary text-primary'
                            : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300'
                    ]"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.title }}
                </Link>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="block w-full max-w-4xl">
            <slot />
        </div>
    </div>
</template>
