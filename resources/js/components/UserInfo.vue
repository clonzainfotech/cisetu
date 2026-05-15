<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Crown } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { Team, User } from '@/types';

type Props = {
    user: User;
    showEmail?: boolean;
    team?: Team | null;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
    team: null,
});

const { getInitials } = useInitials();

const showAvatar = computed(
    () => props.user.avatar && props.user.avatar !== '',
);

const page = usePage();
const isPremium = computed(() => (page.props.village as any)?.plan?.code === 'pragati');

</script>

<template>
    <div class="relative flex-shrink-0">
        <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
            <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
            <AvatarFallback class="rounded-lg bg-zinc-200 font-bold text-zinc-900">
                {{ getInitials(user.name) }}
            </AvatarFallback>
        </Avatar>
        <!-- Premium Crown Badge -->
        <div v-if="isPremium" class="absolute -right-1.5 -top-1.5 flex size-4 items-center justify-center rounded-full bg-amber-500 border-2 border-white shadow-sm ring-1 ring-amber-600/10">
            <Crown class="size-2 text-white fill-current" />
        </div>
    </div>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium text-foreground">{{ user.name }}</span>
        <span v-if="team" class="truncate text-xs text-muted-foreground">{{
            team.name
        }}</span>
        <span
            v-else-if="showEmail"
            class="truncate text-xs text-muted-foreground"
            >{{ user.email }}</span
        >
    </div>
</template>
