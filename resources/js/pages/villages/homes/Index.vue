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
import { Pencil, Trash2, Home as HomeIcon, Download, Upload, Languages, Search } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { store as storeHome, update as updateHome, destroy as destroyHome, index as indexHome, exportMethod as exportHome, importMethod as importHome, template as templateHome } from '@/routes/homes';
import Pagination from '@/components/Pagination.vue';
import ImportModal from '@/components/ImportModal.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import debounce from 'lodash/debounce';
import { toast } from 'vue-sonner';

type Props = {
    homes: {
        data: Array<{
            id: number;
            property_no: string;
            house_no: string | null;
            owner: string;
            occupant: string | null;
            address: string | null;
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
        indexHome().url,
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
const editingHome = ref<Props['homes']['data'][0] | null>(null);
const deleteHomeId = ref<number | null>(null);
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
    property_no: '',
    house_no: '',
    owner: '',
    occupant: '',
    address: '',
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

const editHome = (h: Props['homes']['data'][0]) => {
    editingHome.value = h;
    form.defaults({
        property_no: h.property_no,
        house_no: h.house_no || '',
        owner: h.owner,
        occupant: h.occupant || '',
        address: h.address || '',
        total: h.total,
    });
    form.reset();
    scrollFormIntoView();
};

const cancelEdit = () => {
    editingHome.value = null;
    form.reset();
};

const submit = () => {
    if (editingHome.value) {
        form.put(updateHome({ home: editingHome.value.id }).url, {
            onSuccess: () => {
                cancelEdit();
                toast.success('Property updated successfully');
            },
            onError: () => toast.error('Failed to update property. Please check for errors.'),
        });
    } else {
        form.post(storeHome().url, {
            onSuccess: () => {
                form.reset();
                toast.success('Property added successfully');
            },
            onError: () => toast.error('Failed to add property. Please check for errors.'),
        });
    }
};

const deleteHome = (id: number) => {
    deleteHomeId.value = id;
    isDeleteOpen.value = true;
};

const doDelete = () => {
    if (!deleteHomeId.value) return;

    router.delete(destroyHome({ home: deleteHomeId.value }).url, {
        onFinish: () => {
            deleteHomeId.value = null;
        },
    });
};

const showImportModal = ref(false);

const triggerImport = () => {
    showImportModal.value = true;
};

const exportData = () => {
    window.location.href = exportHome().url;
};


// Transliteration Logic
const gujaratiTyping = ref(true); // Toggle Switch
const isTransliterating = ref(false);

const handleTransliteration = async (e: KeyboardEvent, field: 'property_no' | 'house_no' | 'owner' | 'occupant' | 'address') => {
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
            { title: 'Homes (Personal)' },
        ],
    }),
});
</script>

<template>
    <Head title="Personal Tax (Homes)" />

    <div class="flex flex-col gap-6">
        <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                variant="small"
                title="Personal Tax Management (Homes)"
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
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between gap-4">
                            <CardTitle>Property List</CardTitle>
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
                                        <th class="px-4 py-3">Property No</th>
                                        <th class="px-4 py-3">House No</th>
                                        <th class="px-4 py-3">Owner</th>
                                        <th class="px-4 py-3">Address</th>
                                        <th class="px-4 py-3">Personal Tax (₹)</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="h in homes.data"
                                        :key="h.id"
                                        class="border-b transition-colors hover:bg-muted/30"
                                        :class="editingHome?.id === h.id ? 'bg-primary/5 ring-1 ring-inset ring-primary/25' : ''"
                                    >
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">{{ h.property_no }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ h.house_no }}</td>
                                        <td class="px-4 py-3 font-medium truncate max-w-[150px]" :title="h.owner">{{ h.owner }}</td>
                                        <td class="px-4 py-3 text-xs text-muted-foreground truncate max-w-[200px]" :title="h.address">{{ h.address }}</td>
                                        <td class="px-4 py-3 font-semibold text-foreground whitespace-nowrap">₹ {{ formatTax(h.total) }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button variant="ghost" size="icon" @click="editHome(h)">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                                <Button variant="ghost" size="icon" class="text-destructive" @click="deleteHome(h.id)">
                                                    <Trash2 class="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="homes.data.length === 0">
                                        <td colspan="6" class="px-4 py-8 text-center text-muted-foreground">
                                            No records found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="flex items-center justify-between border-t pt-4">
                            <div class="text-xs text-muted-foreground">
                                Total: {{ homes.total }} records
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
                                        from: homes.from,
                                        to: homes.to,
                                        total: homes.total,
                                        prev_page_url: homes.prev_page_url,
                                        next_page_url: homes.next_page_url,
                                        links: homes.links,
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
                :class="editingHome ? 'ring-2 ring-primary/30' : ''"
            >
                <CardHeader class="flex flex-row items-center justify-between border-b bg-muted/20">
                    <CardTitle class="text-lg">{{ editingHome ? 'Edit Property' : 'Add New Property' }}</CardTitle>
                    <span v-if="isTransliterating" class="text-[10px] text-blue-500 animate-pulse flex items-center gap-1">
                        <Languages class="h-3 w-3" /> Converting...
                    </span>
                </CardHeader>
                <CardContent class="pt-6">
                    <form @submit.prevent="submit" class="grid gap-5" novalidate>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="property_no">Property No <span class="text-destructive">*</span></Label>
                                <Input 
                                    id="property_no" 
                                    v-model="form.property_no" 
                                    required 
                                    :error="form.errors.property_no"
                                    @keydown="handleTransliteration($event, 'property_no')"
                                    placeholder="Property No"
                                />
                                <InputError :message="form.errors.property_no" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="house_no">House No <span class="text-destructive">*</span></Label>
                                <Input 
                                    id="house_no" 
                                    v-model="form.house_no" 
                                    required
                                    :error="form.errors.house_no"
                                    @keydown="handleTransliteration($event, 'house_no')"
                                    placeholder="House No"
                                />
                                <InputError :message="form.errors.house_no" />
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="owner">Owner Name <span class="text-destructive">*</span></Label>
                            <Input 
                                id="owner" 
                                v-model="form.owner" 
                                required 
                                :error="form.errors.owner"
                                @keydown="handleTransliteration($event, 'owner')"
                                placeholder="Owner name"
                            />
                            <InputError :message="form.errors.owner" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="occupant">Occupant Name <span class="text-destructive">*</span></Label>
                            <Input 
                                id="occupant" 
                                v-model="form.occupant" 
                                required
                                :error="form.errors.occupant"
                                @keydown="handleTransliteration($event, 'occupant')"
                                placeholder="Occupant name"
                            />
                            <InputError :message="form.errors.occupant" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="address">Address <span class="text-destructive">*</span></Label>
                            <Input 
                                id="address" 
                                v-model="form.address" 
                                required
                                :error="form.errors.address"
                                @keydown="handleTransliteration($event, 'address')"
                                placeholder="Property address"
                            />
                            <InputError :message="form.errors.address" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="total">Personal Tax Amount (₹) <span class="text-destructive">*</span></Label>
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
                                {{ editingHome ? 'Update Property' : 'Save Property' }}
                            </Button>
                            <Button v-if="editingHome" type="button" variant="ghost" @click="cancelEdit">
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
        title="Import Properties"
        description="Upload via Excel/CSV"
        :import-url="importHome().url"
        :template-url="templateHome().url"
        :notes="[
            'Upload CSV or Excel (.xlsx) — Gujarati headers like your TGP file are supported.',
            'AI mapping auto-detects columns when format differs from the template.',
            'Duplicate Property Numbers will be automatically updated with new data.',
            'Ensure the file size does not exceed 10MB.'
        ]"
    />

    <ConfirmDialog
        :open="isDeleteOpen"
        title="Delete property?"
        description="This will permanently delete the property."
        confirm-text="Delete"
        confirm-variant="destructive"
        @update:open="isDeleteOpen = $event"
        @confirm="doDelete"
    />
</template>
