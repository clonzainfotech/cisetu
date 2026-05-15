<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import Pagination from '@/components/Pagination.vue';
import debounce from 'lodash/debounce';
import { computed, nextTick, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { dashboard } from '@/routes';
import { plans } from '@/routes/subscriptions';
import {
    index as indexUsers,
    store as storeUser,
    update as updateUser,
    destroy as destroyUser,
} from '@/routes/managed-users';
import { Shield, AlertCircle, Search, Pencil, Trash2 } from 'lucide-vue-next';
import { useAdminContext } from '@/composables/useAdminContext';

type UserRow = {
    id: number;
    name: string;
    email: string;
    role: string;
    village_id: number | null;
    permissions: string[] | null;
};

type Props = {
    users: {
        data: UserRow[];
        links: Array<any>;
        total: number;
        from: number;
        to: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search: string | null;
        limit: number;
    };
    villages: Array<{ id: number; name_en: string }>;
    currentVillage: { id: number; name_en: string; subscription_plan_id: number } | null;
    requiresVillageContext: boolean;
    limits: { maxUserAccounts: number | null; currentUserAccounts: number | null };
    actor: { id: number; isSuperMasterAdmin: boolean; isVillageAdmin: boolean };
};

const props = defineProps<Props>();
const { actsAsVillageAdmin, currentVillage } = useAdminContext();

const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 10));

const paginationQuery = computed(() => ({
    search: search.value || undefined,
    limit: limit.value,
}));

const updateList = debounce(() => {
    router.get(
        indexUsers().url,
        {
            search: search.value || undefined,
            limit: limit.value,
            page: 1,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        },
    );
}, 300);

watch([search, limit], () => updateList());

const editingUser = ref<UserRow | null>(null);
const deleteUserId = ref<number | null>(null);
const isDeleteOpen = ref(false);
const formPanelRef = ref<HTMLElement | null>(null);

const scrollFormIntoView = (): void => {
    nextTick(() => {
        const panel = formPanelRef.value;

        if (!panel) {
            return;
        }

        const isDesktop = window.matchMedia('(min-width: 1024px)').matches;
        const headerOffset = 88;
        const rect = panel.getBoundingClientRect();
        const isFullyVisible =
            rect.top >= headerOffset && rect.bottom <= window.innerHeight;

        if (!isDesktop || !isFullyVisible) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        panel.querySelector<HTMLElement>('input:not([type="hidden"])')?.focus();
    });
};

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'user',
    village_id: '',
    permissions: [] as string[],
});

const canManageUser = (user: UserRow): boolean => {
    if (user.id === props.actor.id) {
        return false;
    }

    if (props.actor.isSuperMasterAdmin) {
        if (props.currentVillage) {
            return user.village_id === props.currentVillage.id;
        }

        return true;
    }

    return props.actor.isVillageAdmin && user.role === 'user';
};

const editUser = (user: UserRow): void => {
    editingUser.value = user;
    form.defaults({
        name: user.name,
        email: user.email,
        password: '',
        role: user.role,
        village_id: user.village_id ? String(user.village_id) : '',
        permissions: user.permissions ?? [],
    });
    form.reset();
    scrollFormIntoView();
};

const cancelEdit = (): void => {
    editingUser.value = null;
    form.reset();
};

const submit = (): void => {
    if (props.currentVillage) {
        form.village_id = String(props.currentVillage.id);
    }

    if (editingUser.value) {
        form.put(updateUser({ user: editingUser.value.id }).url, {
            onSuccess: () => {
                cancelEdit();
                toast.success('User updated successfully');
            },
            onError: () => toast.error('Failed to update user. Please check for errors.'),
        });

        return;
    }

    form.post(storeUser().url, {
        onSuccess: () => {
            form.reset();
            toast.success('User created successfully');
        },
        onError: () => toast.error('Failed to create user. Please check for errors.'),
    });
};

const deleteUser = (id: number): void => {
    deleteUserId.value = id;
    isDeleteOpen.value = true;
};

const doDelete = (): void => {
    if (!deleteUserId.value) {
        return;
    }

    router.delete(destroyUser({ user: deleteUserId.value }).url, {
        onFinish: () => {
            deleteUserId.value = null;
            isDeleteOpen.value = false;
        },
        onSuccess: () => {
            if (editingUser.value?.id === deleteUserId.value) {
                cancelEdit();
            }
            toast.success('User deleted successfully');
        },
        onError: () => toast.error('Failed to delete user.'),
    });
};

const canCreateUser = computed(
    () =>
        !props.requiresVillageContext &&
        (!props.limits.maxUserAccounts ||
            props.limits.currentUserAccounts === null ||
            props.limits.currentUserAccounts < props.limits.maxUserAccounts ||
            props.actor.isSuperMasterAdmin),
);

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Team Management' },
        ],
    }),
});
</script>

<template>
    <Head title="Users" />

    <div class="flex flex-col gap-6">
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

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
            <Card class="min-w-0">
                <CardHeader>
                    <div class="flex items-center justify-between gap-4">
                        <CardTitle>Users list</CardTitle>
                        <div class="relative w-64">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search..." class="h-9 pl-9" />
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div
                        v-if="requiresVillageContext"
                        class="rounded-lg border border-dashed border-primary/30 bg-primary/5 p-6 text-sm text-muted-foreground"
                    >
                        Select a village from the sidebar (e.g. Tithal) to view and manage that
                        village&apos;s users. The main admin portal does not list users from all
                        villages together.
                    </div>

                    <div
                        v-else-if="users.data.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        No users found.
                    </div>

                    <div
                        v-else
                        class="-mx-6 flex max-h-[min(70vh,calc(100dvh-14rem))] flex-col gap-3 overflow-y-auto px-6"
                    >
                        <div
                            v-for="u in users.data"
                            :key="u.id"
                            class="flex items-start justify-between gap-4 rounded-lg border p-4 transition-colors"
                            :class="editingUser?.id === u.id ? 'bg-primary/5 ring-1 ring-inset ring-primary/25' : ''"
                        >
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ u.name }}</div>
                                <div class="truncate text-sm text-muted-foreground">
                                    {{ u.email }}
                                </div>
                                <div v-if="u.permissions?.length" class="mt-2 flex flex-wrap gap-1.5">
                                    <div
                                        v-for="p in u.permissions"
                                        :key="p"
                                        class="rounded border border-emerald-100 bg-emerald-50 px-1.5 py-0.5 text-[9px] font-bold tracking-wider text-emerald-600 uppercase"
                                    >
                                        {{ p.replaceAll('_', ' ') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <div class="text-right text-sm font-medium text-muted-foreground">
                                    {{ u.role.replaceAll('_', ' ') }}
                                </div>
                                <Button
                                    v-if="canManageUser(u)"
                                    variant="ghost"
                                    size="icon"
                                    @click="editUser(u)"
                                >
                                    <Pencil class="h-4 w-4" />
                                </Button>
                                <Button
                                    v-if="canManageUser(u)"
                                    variant="ghost"
                                    size="icon"
                                    class="text-destructive"
                                    @click="deleteUser(u.id)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="users.data.length > 0"
                        class="flex items-center justify-between gap-3 border-t pt-4"
                    >
                        <div class="text-xs text-muted-foreground">
                            Total: {{ users.total }} records
                        </div>
                        <div class="flex items-center gap-2">
                            <Select v-model="limit">
                                <SelectTrigger class="h-10 w-auto gap-1 border-border bg-white px-3 shadow-sm">
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
                            <Pagination
                                :meta="{
                                    from: users.from,
                                    to: users.to,
                                    total: users.total,
                                    prev_page_url: users.prev_page_url,
                                    next_page_url: users.next_page_url,
                                    links: users.links,
                                }"
                                :query="paginationQuery"
                            />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <aside
                ref="formPanelRef"
                class="scroll-mt-24 lg:sticky lg:top-20 lg:z-10 lg:max-h-[calc(100dvh-6rem)] lg:overflow-y-auto lg:self-start"
            >
                <Card
                    class="border-primary/10 shadow-lg transition-shadow"
                    :class="editingUser ? 'ring-2 ring-primary/30' : ''"
                >
                    <CardHeader>
                        <CardTitle>{{ editingUser ? 'Edit user' : 'Create user' }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="!canCreateUser && !editingUser && actor.isVillageAdmin"
                            class="animate-in fade-in zoom-in space-y-4 rounded-lg border border-primary/20 bg-primary/[0.04] p-6 text-center duration-300"
                        >
                            <div class="flex justify-center">
                                <AlertCircle class="size-8 text-primary" />
                            </div>
                            <div class="space-y-1 text-center">
                                <h3 class="text-sm font-bold tracking-widest text-primary uppercase">
                                    Subscription Limit Reached
                                </h3>
                                <p class="text-xs leading-relaxed font-medium text-zinc-600">
                                    Your current plan only supports 1 admin user.
                                    <br />
                                    Upgrade to
                                    <span class="font-bold text-primary underline decoration-primary/30">
                                        CI Pragati
                                    </span>
                                    to add team members.
                                </p>
                            </div>
                            <Link :href="plans().url" class="w-full">
                                <Button
                                    variant="outline"
                                    class="h-9 w-full border-primary/20 bg-white text-[10px] font-bold tracking-widest text-primary uppercase transition-all hover:bg-primary/5"
                                >
                                    View Pricing Plans
                                </Button>
                            </Link>
                        </div>

                        <div v-else>
                            <p class="mb-4 text-sm text-muted-foreground">
                                {{
                                    editingUser
                                        ? 'Update user details or set a new password.'
                                        : actor.isSuperMasterAdmin && !currentVillage
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
                                    <Label for="password">
                                        {{ editingUser ? 'New password' : 'Password' }}
                                        <span v-if="editingUser" class="text-xs font-normal text-muted-foreground">
                                            (optional)
                                        </span>
                                    </Label>
                                    <Input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        :placeholder="editingUser ? 'Leave blank to keep current' : 'Password'"
                                        :required="!editingUser"
                                        :error="form.errors.password"
                                    />
                                    <InputError :message="form.errors.password" />
                                </div>

                                <div class="grid gap-2">
                                    <Label>Role</Label>
                                    <Select v-model="form.role" :disabled="actor.isVillageAdmin || actsAsVillageAdmin">
                                        <SelectTrigger :error="form.errors.role">
                                            <SelectValue placeholder="Select role" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem value="user">User</SelectItem>
                                                <SelectItem
                                                    v-if="actor.isSuperMasterAdmin && !currentVillage"
                                                    value="village_admin"
                                                >
                                                    Village Admin
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.role" />
                                </div>

                                <div
                                    v-if="actor.isSuperMasterAdmin && !currentVillage"
                                    class="grid gap-2"
                                >
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

                                <div class="mt-2 rounded-xl border border-dashed bg-muted/30 p-4">
                                    <div class="mb-4 flex items-center gap-2 font-bold text-blue-600">
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
                                            <Label for="p_personal" class="cursor-pointer text-sm leading-none font-medium">
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
                                            <Label for="p_professional" class="cursor-pointer text-sm leading-none font-medium">
                                                Professional Tax (Shops)
                                            </Label>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.permissions" />
                                </div>

                                <div class="flex gap-2 pt-1">
                                    <Button type="submit" :disabled="form.processing" class="flex-1">
                                        {{ editingUser ? 'Update user' : 'Create user' }}
                                    </Button>
                                    <Button v-if="editingUser" type="button" variant="ghost" @click="cancelEdit">
                                        Cancel
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </CardContent>
                </Card>
            </aside>
        </div>
    </div>

    <ConfirmDialog
        :open="isDeleteOpen"
        title="Delete user?"
        description="This will permanently remove the user account."
        confirm-text="Delete"
        confirm-variant="destructive"
        @update:open="isDeleteOpen = $event"
        @confirm="doDelete"
    />
</template>
