<script setup lang="ts">
import { usePage, Link, router } from '@inertiajs/vue3';
import { LogOut, Settings, Crown, ChevronRight, Sparkles, Calendar } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { logout } from '@/routes';
import { plans } from '@/routes/subscriptions';
import { edit } from '@/routes/profile';
import type { User } from '@/types';

type Props = {
    user: User;
};

const handleLogout = () => {
    router.post(logout().url);
};

defineProps<Props>();

const page = usePage();
const village = computed(() => page.props.village as any);
const planCode = computed(() => village.value?.plan?.code);

const expiryDate = computed(() => {
    if (!village.value?.subscription_expires_at) return null;
    return new Date(village.value.subscription_expires_at).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
});

</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />

    <!-- Subscription Status Card -->
    <div v-if="village" class="px-2 py-2">
        <Link :href="plans().url" class="block group">
            <div 
                class="rounded-lg border p-3 transition-all duration-300 shadow-sm overflow-hidden relative"
                :class="[
                    planCode === 'pragati' 
                        ? 'bg-primary/[0.03] border-primary/20 hover:bg-primary/[0.08]' 
                        : 'bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 hover:border-primary/30'
                ]"
            >
                <!-- Subtle Background Pattern (Matches Brand) -->
                <div 
                    class="absolute inset-0 pointer-events-none z-0 opacity-[0.03] dark:opacity-[0.05] dark:invert"
                    style="background-image: url('/images/bg-pattern.png'); background-repeat: repeat; background-size: 200px; mix-blend-mode: soft-light;"
                ></div>

                <div class="relative z-10 flex items-center gap-3">
                    <div 
                        class="flex size-10 flex-shrink-0 items-center justify-center rounded-full transition-transform group-hover:scale-105"
                        :class="planCode === 'pragati' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400'"
                    >
                        <Crown v-if="planCode === 'pragati'" class="size-5 fill-current" />
                        <Sparkles v-else class="size-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black uppercase tracking-widest leading-none" :class="planCode === 'pragati' ? 'text-primary' : 'text-zinc-600 dark:text-zinc-400'">
                                {{ village?.plan?.name || 'CI Vikas' }}
                            </span>
                            <span class="rounded-full px-1.5 py-0.5 text-[7px] font-black uppercase ring-1 ring-inset" :class="planCode === 'pragati' ? 'bg-primary text-white ring-white/10' : 'bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 ring-zinc-300 dark:ring-zinc-700'">
                                Active
                            </span>
                        </div>
                        <div class="mt-1.5 flex flex-col gap-1">
                            <div v-if="expiryDate" class="flex items-center gap-1 text-[9px] text-muted-foreground font-medium uppercase tracking-tight">
                                <Calendar class="size-2.5 opacity-70" />
                                <span>Valid until {{ expiryDate }}</span>
                            </div>
                            <div class="mt-3 pt-2 border-t border-primary/10 flex items-center justify-between group-hover:border-primary/20 transition-colors">
                                <span class="text-[9px] font-bold text-primary/80 uppercase tracking-wider">Upgrade for more features</span>
                                <ChevronRight class="size-3 text-primary/60 group-hover:translate-x-0.5 transition-transform" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Link>
    </div>
    <DropdownMenuSeparator v-if="village" />

    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true" class="focus:bg-primary/5 focus:text-primary cursor-pointer transition-colors">
            <Link class="flex w-full items-center" :href="edit()" prefetch>
                <Settings class="mr-2 h-4 w-4 opacity-70" />
                <span>Settings</span>
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true" class="focus:bg-destructive/5 focus:text-destructive cursor-pointer transition-colors">
        <Link
            class="flex w-full items-center"
            as="button"
            @click.prevent="handleLogout"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4 opacity-70" />
            <span>Log out</span>
        </Link>
    </DropdownMenuItem>
</template>
