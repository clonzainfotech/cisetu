<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectItemText,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import Pagination from '@/components/Pagination.vue';
import debounce from 'lodash/debounce';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { dashboard } from '@/routes';
import { plans } from '@/routes/subscriptions';
import { index as indexUsers, store as storeUser } from '@/routes/managed-users';
import { Shield, AlertCircle, Search } from 'lucide-vue-next';

type Props = {
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
            village_id: number | null;
            permissions: string[] | null;
        }>;
        links: Array<any>;
        total: number;
        from: number;
        to: number;
    };
    filters: {
        search: string | null;
        limit: number;
    };
    villages: Array<{ id: number; name_en: string }>;
    currentVillage: { id: number; name_en: string; subscription_plan_id: number } | null;
    limits: { maxUserAccounts: number | null; currentUserAccounts: number | null };
    actor: { isSuperMasterAdmin: boolean; isVillageAdmin: boolean };
};

const props = defineProps<Props>();

// Search & Pagination Logic
const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 10));

const updateList = debounce(() => {
    router.get(indexUsers().url, { search: search.value, limit: limit.value }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, limit], () => updateList());

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: props.actor.isVillageAdmin ? 'user' : 'user',
    village_id: '',
    permissions: [] as string[],
});

const submit = () => {
    form.post(storeUser().url, {
        onSuccess: () => {
            form.reset();
            toast.success('User created successfully');
        },
        onError: () => toast.error('Failed to create user. Please check for errors.'),
    });
};

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Team Management' },
        ],
    }),
});

const page = usePage();
</script>

<template>
    <Head title="Users" />

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                variant="small"
                title="Users"
                description="Manage your village staff and their access rights."
            />

            <div
                v-if="currentVillage"
                class="rounded-lg border bg-muted/30 px-4 py-3 text-sm"
            >
                <div class="text-muted-foreground">
                    Village:
                    <span class="font-medium text-foreground">
                        {{ currentVillage.name_en }}
                    </span>
                </div>
                <div v-if="limits.currentUserAccounts !== null" class="text-muted-foreground">
                    Users:
                    <span class="font-medium text-foreground">
                        {{ limits.currentUserAccounts }}
                    </span>
                    <span v-if="limits.maxUserAccounts !== null" class="text-muted-foreground">
                        / {{ limits.maxUserAccounts }}
                    </span>
                    <span v-else class="text-muted-foreground">/ Unlimited</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:items-start">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between gap-4">
                        <CardTitle>Users list</CardTitle>
                        <div class="relative w-64">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search..." class="h-9 pl-9" />
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="flex flex-col gap-3">
                    <div
                        v-if="users.data.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        No users found.
                    </div>

                    <div v-else class="min-h-0 flex-1 overflow-y-auto space-y-3">
                        <div
                            v-for="u in users.data"
                            :key="u.id"
                            class="flex items-start justify-between gap-4 rounded-lg border p-4"
                        >
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ u.name }}</div>
                                <div class="truncate text-sm text-muted-foreground">
                                    {{ u.email }}
                                </div>
                                <!-- Permissions indicators -->
                                <div v-if="u.permissions?.length" class="mt-2 flex gap-1.5">
                                    <div 
                                        v-for="p in u.permissions" 
                                        :key="p" 
                                        class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-600 border border-emerald-100"
                                    >
                                        {{ p.replaceAll('_', ' ') }}
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0 text-right text-sm">
                                <div class="font-medium text-muted-foreground">
                                    {{ u.role.replaceAll('_', ' ') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="users.data.length > 0"
                        class="sticky bottom-0 -mx-6 mt-1 flex items-center justify-between gap-3 border-t bg-background px-6 py-3"
                    >
                        <div class="text-xs text-muted-foreground">
                            Total: {{ users.total }} records
                        </div>
                        <div class="flex items-center gap-2">
                            <Select v-model="limit">
                                <SelectTrigger class="h-10 w-auto gap-1 px-3 shadow-sm">
                                    <span class="text-sm font-bold">{{ limit }}</span>
                                    <span class="text-sm text-muted-foreground">per page</span>
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="10">10</SelectItem>
                                    <SelectItem value="25">25</SelectItem>
                                    <SelectItem value="50">50</SelectItem>
                                    <SelectItem value="100">100</SelectItem>
                                </SelectContent>
                            </Select>
                            <Pagination :meta="{ from: users.from, to: users.to, total: users.total, links: users.links }" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="lg:sticky lg:top-6 lg:self-start">
                <CardHeader>
                    <CardTitle>Create user</CardTitle>
                </CardHeader>
                <CardContent>
                    <!-- Upgrade Notice for Vikas Plan (Enhanced Design) -->
                    <div 
                        v-if="limits.maxUserAccounts && limits.currentUserAccounts >= limits.maxUserAccounts && actor.isVillageAdmin"
                        class="rounded-lg bg-primary/[0.03] dark:bg-zinc-900 border border-primary/10 dark:border-zinc-800 p-6 text-center space-y-4 animate-in fade-in zoom-in duration-300"
                    >
                        <div class="flex justify-center">
                            <AlertCircle class="size-8 text-primary" />
                        </div>
                        <div class="space-y-1 text-center">
                            <h3 class="text-sm font-bold text-primary dark:text-zinc-100 uppercase tracking-widest">Subscription Limit Reached</h3>
                            <p class="text-xs font-medium text-primary/70 dark:text-zinc-400 leading-relaxed">
                                Your current plan only supports 1 admin user. <br />
                                Upgrade to <span class="font-bold text-primary underline decoration-primary/30">CI Pragati</span> to add team members.
                            </p>
                        </div>
                        <Link :href="plans().url" class="w-full">
                            <Button variant="outline" class="w-full h-9 border-primary/20 dark:border-zinc-800 bg-primary/5 dark:bg-zinc-800/40 hover:bg-primary/10 dark:hover:bg-zinc-700 text-primary dark:text-zinc-100 text-[10px] font-bold uppercase tracking-widest transition-all">
                                View Pricing Plans
                            </Button>
                        </Link>
                    </div>

                    <div v-else>
                        <p class="mb-4 text-sm text-muted-foreground">
                            {{
                                actor.isSuperMasterAdmin
                                    ? 'Create village admins and village users.'
                                    : 'Create users for your village (subscription limits apply).'
                            }}
                        </p>

                        <form @submit.prevent="submit" class="grid gap-5" novalidate>
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Full name"
                                    required
                                    :error="form.errors.name"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="email@example.com"
                                    required
                                    :error="form.errors.email"
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">Password</Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Password"
                                    required
                                    :error="form.errors.password"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label>Role</Label>
                                <Select
                                    v-model="form.role"
                                >
                                    <SelectTrigger :error="form.errors.role">
                                        <SelectValue placeholder="Select role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="user">User</SelectItem>
                                            <SelectItem
                                                v-if="actor.isSuperMasterAdmin"
                                                value="village_admin"
                                            >
                                                Village Admin
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role" />
                            </div>

                             <div v-if="actor.isSuperMasterAdmin" class="grid gap-2">
                                <Label>Village</Label>
                                <Select v-model="form.village_id">
                                    <SelectTrigger :error="form.errors.village_id">
                                        <SelectValue placeholder="Select village" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="v in villages"
                                                :key="v.id"
                                                :value="String(v.id)"
                                            >
                                                {{ v.name_en }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.village_id" />
                            </div>

                            <!-- Permissions Section (Matches Global Theme) -->
                            <div class="mt-2 rounded-xl bg-muted/30 p-4 border border-dashed">
                                <div class="flex items-center gap-2 mb-4 text-blue-600 font-bold">
                                    <Shield class="h-5 w-5" />
                                    <h3>Module Access Rights</h3>
                                </div>
                                <div class="grid gap-3">
                                    <div class="flex items-center space-x-3">
                                        <Checkbox 
                                            id="p_personal" 
                                            :checked="form.permissions.includes('personal_tax')"
                                            @update:checked="(checked) => {
                                                if (checked) form.permissions.push('personal_tax');
                                                else form.permissions = form.permissions.filter(p => p !== 'personal_tax');
                                            }"
                                        />
                                        <Label for="p_personal" class="text-sm font-medium leading-none cursor-pointer">
                                            Personal Tax (Homes)
                                        </Label>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Checkbox 
                                            id="p_professional" 
                                            :checked="form.permissions.includes('professional_tax')"
                                            @update:checked="(checked) => {
                                                if (checked) form.permissions.push('professional_tax');
                                                else form.permissions = form.permissions.filter(p => p !== 'professional_tax');
                                            }"
                                        />
                                        <Label for="p_professional" class="text-sm font-medium leading-none cursor-pointer">
                                            Professional Tax (Shops)
                                        </Label>
                                    </div>
                                </div>
                                <InputError :message="form.errors.permissions" />
                            </div>

                            <div class="flex items-center gap-3 pt-1">
                                <Button type="submit" :disabled="form.processing">
                                    Create user
                                </Button>
                            </div>
                        </form>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

