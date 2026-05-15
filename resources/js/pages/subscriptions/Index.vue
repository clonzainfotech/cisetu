<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import Pagination from '@/components/Pagination.vue';
import { dashboard } from '@/routes';
import { index as subscriptionsIndex, edit as editSubscription } from '@/routes/subscriptions';

type Props = {
    villages: {
        data: Array<{
            id: number;
            token: string;
            name_en: string;
            subdomain: string;
            status: 'none' | 'active' | 'grace' | 'expired' | 'suspended' | 'inactive';
            subscription: null | {
                plan: { id: number; name: string; code: string };
                starts_at: string | null;
                ends_at: string | null;
                grace_ends_at: string | null;
            };
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
};

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 10));

const updateList = debounce(() => {
    router.get(subscriptionsIndex().url, { search: search.value, limit: limit.value }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, limit], () => updateList());

const badgeVariant = (status: string) => {
    if (status === 'active') {
        return 'default';
    }
    if (status === 'grace') {
        return 'secondary';
    }
    if (status === 'expired' || status === 'suspended' || status === 'inactive') {
        return 'destructive';
    }
    return 'outline';
};

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Subscriptions' },
        ],
    }),
});
</script>

<template>
    <Head title="Subscriptions" />

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <Heading
                variant="small"
                title="Subscriptions"
                description="Village wise subscription management"
            />
            
            <div class="flex items-center gap-3">
                <div class="relative w-64">
                    <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                    <Input v-model="search" placeholder="Search villages..." class="h-10 pl-9 shadow-sm" />
                </div>
            </div>
        </div>

        <Card>
            <CardHeader class="pb-3">
                <CardTitle>Villages</CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
                <div
                    v-if="villages.data.length === 0"
                    class="rounded-lg border border-dashed p-12 text-center text-sm text-muted-foreground"
                >
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted/50 mb-4">
                        <Search class="h-6 w-6" />
                    </div>
                    No villages found for "{{ search }}".
                </div>

                <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="v in villages.data"
                        :key="v.id"
                        :href="editSubscription(v.token).url"
                        class="group block rounded-xl border border-border p-5 shadow-sm transition-all hover:border-primary/50 hover:shadow-md bg-card dark:hover:border-primary"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="truncate text-lg font-black tracking-tight text-foreground uppercase leading-none mb-1">
                                    {{ v.name_en }}
                                </div>
                                <div class="truncate text-xs font-bold text-muted-foreground tracking-wide">
                                    {{ v.subdomain }}.{{ $page.props.app_url.replace(/^https?:\/\//, '') }}
                                </div>
                            </div>

                            <Badge :variant="badgeVariant(v.status)" class="uppercase text-[10px] font-black tracking-widest px-2 py-0.5">
                                {{ v.status }}
                            </Badge>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-border pt-4">
                            <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/80">
                                <template v-if="v.subscription">
                                    {{ v.subscription.plan.name }}
                                </template>
                                <template v-else> No Infrastructure Plan </template>
                            </div>
                        </div>

                        <div
                            v-if="v.subscription"
                            class="mt-3 grid grid-cols-2 gap-y-2 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"
                        >
                            <div>Start: <span class="text-foreground font-black">{{ v.subscription.starts_at ?? '-' }}</span></div>
                            <div>End: <span class="text-foreground font-black">{{ v.subscription.ends_at ?? '-' }}</span></div>
                            <div v-if="v.subscription.grace_ends_at" class="col-span-2 mt-1 text-amber-600 dark:text-amber-500">
                                Grace: {{ v.subscription.grace_ends_at }}
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination & Limit Footer -->
                <div
                    v-if="villages.data.length > 0"
                    class="sticky bottom-0 -mx-6 -mb-6 flex items-center justify-between gap-3 border-t border-border bg-white px-6 py-4"
                >
                    <div class="text-xs text-muted-foreground font-medium">
                        Showing {{ villages.from }} to {{ villages.to }} of {{ villages.total }} villages
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <Select v-model="limit">
                            <SelectTrigger class="h-10 w-auto gap-2 border-border bg-white px-3 shadow-sm font-bold text-xs">
                                <span>{{ limit }}</span>
                                <span class="text-muted-foreground font-medium">/ page</span>
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="10">10</SelectItem>
                                <SelectItem value="25">25</SelectItem>
                                <SelectItem value="50">50</SelectItem>
                                <SelectItem value="100">100</SelectItem>
                            </SelectContent>
                        </Select>
                        
                        <Pagination :meta="{ from: villages.from, to: villages.to, total: villages.total, links: villages.links }" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
