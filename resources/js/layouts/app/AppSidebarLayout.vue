<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import { usePage, Link } from '@inertiajs/vue3';
import { AlertCircle } from 'lucide-vue-next';
import { computed } from 'vue';
import { contactUs } from '@/routes';
import { plans } from '@/routes/subscriptions';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const subscription = computed(() => (page.props.subscription as any) || {});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="flex-1 overflow-x-hidden min-h-screen">
            <!-- Subscription Status Banners -->
            <div 
                v-if="subscription.is_suspended" 
                class="bg-destructive/10 border-b border-destructive/20 px-6 py-2.5 flex items-center justify-between text-destructive text-xs font-semibold animate-in fade-in slide-in-from-top duration-500"
            >
                <div class="flex items-center gap-2">
                    <AlertCircle class="h-4 w-4" />
                    <span>Your subscription has been <span class="font-bold underline decoration-destructive/50 underline-offset-2">suspended</span>. Some features may be restricted.</span>
                </div>
                <Link :href="contactUs().url" class="bg-destructive text-destructive-foreground px-3 py-1 rounded-full text-[10px] uppercase tracking-wider hover:bg-destructive/90 transition-colors">
                    Contact Support
                </Link>
            </div>

            <div 
                v-else-if="subscription.expired" 
                class="bg-destructive/10 border-b border-destructive/20 px-6 py-2.5 flex items-center justify-between text-destructive text-xs font-semibold animate-in fade-in slide-in-from-top duration-500"
            >
                <div class="flex items-center gap-2">
                    <AlertCircle class="h-4 w-4" />
                    <span>Your subscription has <span class="font-bold underline decoration-destructive/50 underline-offset-2">expired</span>. Please renew to restore full access.</span>
                </div>
                <Link :href="contactUs().url" class="bg-destructive text-destructive-foreground px-3 py-1 rounded-full text-[10px] uppercase tracking-wider hover:bg-destructive/90 transition-colors">
                    Renew Now
                </Link>
            </div>

            <div 
                v-else-if="subscription.in_grace" 
                class="bg-amber-500/10 border-b border-amber-500/20 px-6 py-2.5 flex items-center justify-between text-amber-600 dark:text-amber-400 text-xs font-semibold animate-in fade-in slide-in-from-top duration-500"
            >
                <div class="flex items-center gap-2">
                    <AlertCircle class="h-4 w-4" />
                    <span>Your subscription is in a <span class="font-bold underline decoration-amber-500/50 underline-offset-2">grace period</span>. Please renew soon to avoid service interruption.</span>
                </div>
                <Link :href="plans().url" class="bg-amber-600 text-white px-3 py-1 rounded-full text-[10px] uppercase tracking-wider hover:bg-amber-700 transition-colors">
                    Renew Now
                </Link>
            </div>

            <div 
                v-else-if="subscription.due_soon" 
                class="animate-in fade-in slide-in-from-top flex items-center justify-between border-b border-primary/10 bg-primary/[0.04] px-6 py-2.5 text-xs font-semibold text-foreground duration-500"
            >
                <div class="flex items-center gap-2">
                    <AlertCircle class="h-4 w-4 text-primary" />
                    <span v-if="subscription.days_left === 0">Your subscription expires <span class="underline decoration-primary/50 decoration-2 underline-offset-2 font-bold">today</span>. Please renew to avoid service interruption.</span>
                    <span v-else-if="subscription.days_left === 1">Your subscription expires in <span class="font-bold">1 day</span>. Please renew to avoid service interruption.</span>
                    <span v-else>Your subscription expires in <span class="font-bold">{{ subscription.days_left }} days</span>. Please renew to avoid service interruption.</span>
                </div>
                <Link :href="plans().url" class="rounded-full bg-primary/10 px-3 py-1 text-[10px] tracking-wider text-primary uppercase transition-colors hover:bg-primary/20">
                    Renew Now
                </Link>
            </div>

            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="mx-auto w-full max-w-6xl px-4 py-6 md:px-6">
                <slot />
            </div>
        </AppContent>
        <Toaster />
    </AppShell>
</template>
