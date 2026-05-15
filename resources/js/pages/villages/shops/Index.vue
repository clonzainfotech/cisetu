<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Pencil, Trash2, Store, Download, Upload, Languages, Search } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { store as storeShop, update as updateShop, destroy as destroyShop, index as indexShop, exportMethod as exportShop, importMethod as importShop, template as templateShop } from '@/routes/shops';
import Pagination from '@/components/Pagination.vue';
import ImportModal from '@/components/ImportModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import debounce from 'lodash/debounce';
import { toast } from 'vue-sonner';

type Props = {
    shops: {
        data: Array<{
            id: number;
            reg_no: string;
            name: string;
            total: number;
        }>;
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
    village: {
        id: number;
        name_en: string;
        plan: { code: string; import_export: boolean };
    };
};

const props = defineProps<Props>();

// Search & Pagination Logic
const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 10));

const paginationQuery = computed(() => ({
    search: search.value || undefined,
    limit: limit.value,
}));

const updateList = debounce(() => {
    router.get(
        indexShop().url,
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

// Form Logic
const editingShop = ref<Props['shops']['data'][0] | null>(null);
const deleteShopId = ref<number | null>(null);
const isDeleteOpen = ref(false);
const formPanelRef = ref<HTMLElement | null>(null);

const scrollFormIntoView = (): void => {
    nextTick(() => {
        const panel = formPanelRef.value;
        if (!panel) {
            return;
        }
        const isDesktop = window.matchMedia('(min-width: 1024px)').matches;
        const rect = panel.getBoundingClientRect();
        if (!isDesktop || rect.top < 88 || rect.bottom > window.innerHeight) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        panel.querySelector<HTMLElement>('input:not([type="hidden"])')?.focus();
    });
};

const form = useForm({
    reg_no: '',
    name: '',
    total: 0,
});

const isPragati = computed(() => props.village.plan?.import_export);

const formatTax = (value: number | string | null | undefined): string => {
    if (value === null || value === undefined || value === '') {
        return '—';
    }

    const amount = Number(value);

    if (Number.isNaN(amount)) {
        return '—';
    }

    return amount.toLocaleString('en-IN', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    });
};

const editShop = (s: Props['shops']['data'][0]) => {
    editingShop.value = s;
    form.defaults({
        reg_no: s.reg_no,
        name: s.name,
        total: s.total,
    });
    form.reset();
    scrollFormIntoView();
};

const cancelEdit = () => {
    editingShop.value = null;
    form.reset();
};

const submit = () => {
    if (editingShop.value) {
        form.put(updateShop({ shop: editingShop.value.id }).url, {
            onSuccess: () => {
                cancelEdit();
                toast.success('Shop updated successfully');
            },
            onError: () => toast.error('Failed to update shop. Please check for errors.'),
        });
    } else {
        form.post(storeShop().url, {
            onSuccess: () => {
                form.reset();
                toast.success('Shop added successfully');
            },
            onError: () => toast.error('Failed to add shop. Please check for errors.'),
        });
    }
};

const deleteShop = (id: number) => {
    deleteShopId.value = id;
    isDeleteOpen.value = true;
};

const doDelete = () => {
    if (!deleteShopId.value) return;

    router.delete(destroyShop({ shop: deleteShopId.value }).url, {
        onFinish: () => {
            deleteShopId.value = null;
        },
    });
};

const showImportModal = ref(false);

const triggerImport = () => {
    showImportModal.value = true;
};

const exportData = () => {
    window.location.href = exportShop().url;
};

// Transliteration Logic
const gujaratiTyping = ref(true); // Toggle Switch
const isTransliterating = ref(false);

const handleTransliteration = async (e: KeyboardEvent, field: 'reg_no' | 'name') => {
    if (!gujaratiTyping.value) return; // Only if switch is ON
    if (e.key !== ' ' && e.key !== 'Enter') return;

    const input = e.target as HTMLInputElement;
    const value = input.value;
    const selectionStart = input.selectionStart || 0;

    const textBeforeCursor = value.substring(0, selectionStart).trim();
    const words = textBeforeCursor.split(/\s+/);
    const lastWord = words[words.length - 1];

    if (lastWord && /^[a-zA-Z0-9]+$/.test(lastWord)) {
        isTransliterating.value = true;
        try {
            const response = await fetch(`https://inputtools.google.com/request?text=${lastWord}&itc=gu-t-i0-und&num=1&cp=0&cs=1&ie=utf-8&oe=utf-8&app=demopage`);
            const data = await response.json();
            if (data[0] === 'SUCCESS') {
                const transliterated = data[1][0][1][0];
                const newValue = value.substring(0, selectionStart - lastWord.length) + transliterated + value.substring(selectionStart);
                form[field] = newValue;
                
                setTimeout(() => {
                    input.setSelectionRange(selectionStart - lastWord.length + transliterated.length, selectionStart - lastWord.length + transliterated.length);
                }, 0);
            }
        } catch (error) {
            console.error('Transliteration failed:', error);
        } finally {
            isTransliterating.value = false;
        }
    }
};

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Shops (Prof.)' },
        ],
    }),
});
</script>

<template>
    <Head title="Professional Tax (Shops)" />

    <div class="flex flex-col gap-6">
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                variant="small"
                title="Professional Tax Management (Shops)"
                :description="`Village: ${village.name_en}`"
            />
            
            <div class="flex flex-wrap gap-2">
                <div v-if="isPragati" class="flex gap-2">
                     <Button variant="outline" size="sm" @click="triggerImport">
                         <Upload class="mr-2 h-4 w-4" /> Import
                     </Button>
                     <Button variant="outline" size="sm" @click="exportData">
                         <Download class="mr-2 h-4 w-4" /> Export
                     </Button>
                </div>

                <div class="flex items-center space-x-3 rounded-full border bg-white px-4 py-1.5 shadow-sm transition-all hover:bg-slate-50">
                    <Switch id="gu-typing" v-model:checked="gujaratiTyping" />
                    <Label for="gu-typing" class="text-xs font-bold cursor-pointer flex items-center gap-2 text-black">
                        <Languages class="h-4 w-4 text-black" /> Gujarati Typing
                    </Label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(300px,1fr)]">
            <Card class="min-w-0">
                    <CardHeader class="shrink-0 pb-3">
                        <div class="flex items-center justify-between gap-4">
                            <CardTitle>Shop List</CardTitle>
                            <div class="flex items-center gap-2">
                                <div class="relative w-64">
                                    <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                    <Input
                                        v-model="search"
                                        placeholder="Search..."
                                        class="pl-9 h-9"
                                    />
                                </div>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="-mx-6 max-h-[min(70vh,calc(100dvh-14rem))] overflow-x-auto overflow-y-auto px-6">
                            <table class="w-full text-left text-sm">
                                <thead class="sticky top-0 z-10 bg-muted/95 text-xs uppercase shadow-sm backdrop-blur-sm">
                                    <tr>
                                        <th class="px-4 py-3">Reg No</th>
                                        <th class="px-4 py-3">Shop Name</th>
                                        <th class="px-4 py-3">Professional Tax (₹)</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="s in shops.data"
                                        :key="s.id"
                                        class="border-b transition-colors hover:bg-muted/30"
                                        :class="editingShop?.id === s.id ? 'bg-primary/5 ring-1 ring-inset ring-primary/25' : ''"
                                    >
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">{{ s.reg_no }}</td>
                                        <td class="px-4 py-3 truncate max-w-[200px]" :title="s.name">{{ s.name }}</td>
                                        <td class="px-4 py-3 font-semibold text-foreground whitespace-nowrap">₹ {{ formatTax(s.total) }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button variant="ghost" size="icon" @click="editShop(s)">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                                <Button variant="ghost" size="icon" class="text-destructive" @click="deleteShop(s.id)">
                                                    <Trash2 class="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="shops.data.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 flex shrink-0 items-center justify-between border-t bg-white pt-4">
                            <div class="text-xs text-muted-foreground">
                                Total: {{ shops.total }} records
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <!-- Limit Dropdown -->
                                <div class="flex items-center">
                                    <Select v-model="limit">
                                        <SelectTrigger class="h-10 border-slate-200 bg-white px-3 w-auto gap-1 shadow-sm focus:ring-slate-100">
                                            <span class="text-sm font-bold text-slate-900">{{ limit }}</span>
                                            <span class="text-sm text-slate-500">per page</span>
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="10">10</SelectItem>
                                            <SelectItem value="25">25</SelectItem>
                                            <SelectItem value="50">50</SelectItem>
                                            <SelectItem value="100">100</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <Pagination
                                    :meta="{
                                        from: shops.from,
                                        to: shops.to,
                                        total: shops.total,
                                        prev_page_url: shops.prev_page_url,
                                        next_page_url: shops.next_page_url,
                                        links: shops.links,
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
                :class="editingShop ? 'ring-2 ring-primary/30' : ''"
            >
                <CardHeader class="flex flex-row items-center justify-between border-b bg-muted/20">
                    <CardTitle class="text-lg">{{ editingShop ? 'Edit Shop' : 'Add New Shop' }}</CardTitle>
                    <span v-if="isTransliterating" class="text-[10px] text-blue-500 animate-pulse flex items-center gap-1">
                        <Languages class="h-3 w-3" /> Converting...
                    </span>
                </CardHeader>
                <CardContent class="pt-6">
                    <form @submit.prevent="submit" class="grid gap-5" novalidate>
                        <div class="grid gap-2">
                            <Label for="reg_no">Registration No <span class="text-destructive">*</span></Label>
                            <Input 
                                id="reg_no" 
                                v-model="form.reg_no" 
                                required 
                                :error="form.errors.reg_no"
                                @keydown="handleTransliteration($event, 'reg_no')"
                                placeholder="Registration No"
                            />
                            <InputError :message="form.errors.reg_no" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="name">Shop Name <span class="text-destructive">*</span></Label>
                            <Input 
                                id="name" 
                                v-model="form.name" 
                                required 
                                :error="form.errors.name"
                                @keydown="handleTransliteration($event, 'name')"
                                placeholder="Shop name"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="total">Professional Tax Amount (₹) <span class="text-destructive">*</span></Label>
                            <Input 
                                id="total" 
                                v-model="form.total" 
                                type="number" 
                                step="0.01" 
                                required 
                                :error="form.errors.total"
                            />
                            <InputError :message="form.errors.total" />
                        </div>
                        <div class="flex gap-2 pt-2 border-t mt-2">
                            <Button type="submit" :disabled="form.processing" class="flex-1">
                                {{ editingShop ? 'Update Shop' : 'Save Shop' }}
                            </Button>
                            <Button v-if="editingShop" type="button" variant="ghost" @click="cancelEdit">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
            </aside>
        </div>
    </div>

    <ImportModal
        v-model:open="showImportModal"
        title="Import Shops"
        description="Upload via Excel/CSV"
        :import-url="importShop().url"
        :template-url="templateShop().url"
        :notes="[
            'Upload CSV or Excel (.xlsx) — Gujarati or English headers are supported.',
            'AI mapping auto-detects columns when format differs from the template.',
            'Duplicate Registration Numbers will be automatically updated with new data.',
            'Ensure the file size does not exceed 10MB.'
        ]"
    />

    <ConfirmDialog
        :open="isDeleteOpen"
        title="Delete shop?"
        description="This will permanently delete the shop."
        confirm-text="Delete"
        confirm-variant="destructive"
        @update:open="isDeleteOpen = $event"
        @confirm="doDelete"
    />
</template>
