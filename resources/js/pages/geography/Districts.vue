<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
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
import Pagination from '@/components/Pagination.vue';
import debounce from 'lodash/debounce';
import { Search } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { index as indexDistricts, store as storeDistrict } from '@/routes/districts';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';

type Props = {
    districts: {
        data: Array<{ id: number; name_en: string; state: { id: number; name_en: string } }>;
        links: Array<any>;
        total: number;
        from: number;
        to: number;
    };
    states: Array<{ id: number; name_en: string; code: string }>;
    filters: {
        search: string | null;
        limit: number;
    };
};

const props = defineProps<Props>();

const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 25));

const updateList = debounce(() => {
    router.get(indexDistricts().url, { search: search.value, limit: limit.value }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, limit], () => updateList());

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Districts' },
        ],
    }),
});
</script>

<template>
    <Head title="Districts" />

    <div class="flex flex-col gap-8">
        <Heading
            variant="small"
            title="Districts"
            description="Add districts under a state (Super Master Admin)"
        />

        <div class="grid grid-cols-1 gap-6 lg:h-[calc(100dvh-14rem)] lg:grid-cols-2 lg:items-start lg:overflow-hidden">
            <Card class="lg:h-full lg:overflow-hidden">
                <CardHeader>
                    <div class="flex items-center justify-between gap-4">
                        <CardTitle>Districts list</CardTitle>
                        <div class="relative w-64">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search..." class="h-9 pl-9" />
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="flex flex-col gap-3 lg:h-[calc(100%-5rem)] lg:overflow-hidden">
                    <div
                        v-if="districts.data.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        No districts yet.
                    </div>

                    <div v-else class="min-h-0 flex-1 overflow-y-auto pr-1 flex flex-col gap-3">
                        <div
                            v-for="d in districts.data"
                            :key="d.id"
                            class="flex items-start justify-between gap-4 rounded-lg border p-4"
                        >
                            <div class="min-w-0">
                                <div class="truncate font-medium">{{ d.name_en }}</div>
                                <div class="truncate text-sm text-muted-foreground">
                                    State: {{ d.state.name_en }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="districts.data.length > 0"
                        class="sticky bottom-0 -mx-6 mt-1 flex items-center justify-between gap-3 border-t bg-background px-6 py-3"
                    >
                        <div class="text-xs text-muted-foreground">
                            Total: {{ districts.total }} records
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
                            <Pagination :meta="{ from: districts.from, to: districts.to, total: districts.total, links: districts.links }" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="lg:sticky lg:top-6 lg:self-start">
                <CardHeader>
                    <CardTitle>Add district</CardTitle>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="states.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        Add at least 1 state first.
                    </div>

                    <Form
                        v-else
                        :action="storeDistrict().url"
                        method="post"
                        class="grid gap-5"
                        novalidate
                        @success="toast.success('District added successfully')"
                        @error="toast.error('Failed to add district. Please check for errors.')"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label>State</Label>
                            <Select name="state_id">
                                <SelectTrigger :error="errors.state_id">
                                    <SelectValue placeholder="Select state" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="s in states"
                                            :key="s.id"
                                            :value="String(s.id)"
                                        >
                                            <SelectItemText>
                                                {{ s.name_en }} ({{ s.code }})
                                            </SelectItemText>
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.state_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="name_en">District name</Label>
                            <Input
                                id="name_en"
                                name="name_en"
                                placeholder="e.g. Valsad"
                                required
                                :error="errors.name_en"
                            />
                            <InputError :message="errors.name_en" />
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <Button type="submit" :disabled="processing">
                                Add district
                            </Button>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

