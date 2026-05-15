<script setup lang="ts">
import { ChevronDown, Loader2, Search } from 'lucide-vue-next';
import { onClickOutside } from '@vueuse/core';
import { computed, onMounted, ref, watch } from 'vue';
import { cn } from '@/lib/utils';
import debounce from 'lodash/debounce';

type DistrictOption = {
    id: number;
    name_en: string;
    state_name_en: string;
};

type DistrictSearchResponse = {
    data: DistrictOption[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
};

const props = defineProps<{
    modelValue: string;
    error?: string | boolean | null;
    initialSearch?: string | null;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
    'update:district': [district: DistrictOption | null];
}>();

const isOpen = ref(false);
const search = ref('');
const options = ref<DistrictOption[]>([]);
const selected = ref<DistrictOption | null>(null);
const isLoading = ref(false);
const currentPage = ref(1);
const lastPage = ref(1);
const listRef = ref<HTMLElement | null>(null);
const rootRef = ref<HTMLElement | null>(null);

onClickOutside(rootRef, () => closeDropdown());

const displayLabel = computed(() => {
    if (! selected.value) {
        return 'Select district';
    }

    return `${selected.value.name_en} (${selected.value.state_name_en})`;
});

const hasMore = computed(() => currentPage.value < lastPage.value);

const isInvalid = computed(() => !! props.error);

const fetchDistricts = async (page = 1, append = false) => {
    isLoading.value = true;

    try {
        const params = new URLSearchParams({
            page: String(page),
        });

        if (search.value.trim()) {
            params.set('search', search.value.trim());
        }

        const response = await fetch(`/api/districts/search?${params.toString()}`, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (! response.ok) {
            throw new Error('Failed to load districts');
        }

        const payload = (await response.json()) as DistrictSearchResponse;

        options.value = append ? [...options.value, ...payload.data] : payload.data;
        currentPage.value = payload.meta.current_page;
        lastPage.value = payload.meta.last_page;
    } finally {
        isLoading.value = false;
    }
};

const fetchDistrictById = async (id: string) => {
    if (! id) {
        selected.value = null;
        emit('update:district', null);

        return;
    }

    const response = await fetch(`/api/districts/search?id=${encodeURIComponent(id)}`, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
    });

    if (! response.ok) {
        return;
    }

    const payload = (await response.json()) as DistrictSearchResponse;
    const district = payload.data[0] ?? null;

    selected.value = district;
    emit('update:district', district);

    if (district && ! options.value.some((option) => option.id === district.id)) {
        options.value = [district, ...options.value];
    }
};

const debouncedSearch = debounce(() => {
    if (! isOpen.value) {
        return;
    }

    fetchDistricts(1, false);
}, 300);

const openDropdown = async () => {
    isOpen.value = true;

    if (options.value.length === 0) {
        await fetchDistricts(1, false);
    }
};

const closeDropdown = () => {
    isOpen.value = false;
};

const selectDistrict = (district: DistrictOption) => {
    selected.value = district;
    emit('update:modelValue', String(district.id));
    emit('update:district', district);
    closeDropdown();
};

const loadMore = async () => {
    if (isLoading.value || ! hasMore.value) {
        return;
    }

    await fetchDistricts(currentPage.value + 1, true);
};

const onListScroll = (event: Event) => {
    const target = event.target as HTMLElement;
    const remaining = target.scrollHeight - target.scrollTop - target.clientHeight;

    if (remaining < 48) {
        loadMore();
    }
};

watch(search, () => debouncedSearch());

watch(
    () => props.modelValue,
    (value) => {
        if (value && (! selected.value || String(selected.value.id) !== value)) {
            fetchDistrictById(value);
        }

        if (! value) {
            selected.value = null;
            emit('update:district', null);
        }
    },
    { immediate: true },
);

onMounted(async () => {
    if (props.initialSearch && ! props.modelValue) {
        search.value = props.initialSearch;
        await fetchDistricts(1, false);

        const match = options.value.find((option) =>
            option.name_en.toLowerCase().includes(props.initialSearch!.toLowerCase()),
        );

        if (match) {
            selectDistrict(match);
        }
    }
});
</script>

<template>
    <div ref="rootRef" class="relative">
        <button
            type="button"
            :aria-invalid="isInvalid || undefined"
            :class="cn(
                'border-input flex h-9 w-full items-center justify-between gap-2 rounded-md border bg-white px-3 py-2 text-sm shadow-xs transition-[color,box-shadow]',
                'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] outline-none',
                isInvalid && 'border-destructive ring-destructive/20',
                !selected && 'text-muted-foreground',
            )"
            @click="isOpen ? closeDropdown() : openDropdown()"
        >
            <span class="truncate text-left">{{ displayLabel }}</span>
            <ChevronDown class="size-4 shrink-0 opacity-50" />
        </button>

        <div
            v-if="isOpen"
            class="absolute z-50 mt-1 w-full overflow-hidden rounded-md border border-border bg-white shadow-lg"
        >
            <div class="border-b border-border p-2">
                <div class="relative">
                    <Search class="absolute left-2.5 top-2.5 size-4 text-muted-foreground" />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search district or state..."
                        class="h-9 w-full rounded-md border border-input bg-white py-2 pr-3 pl-9 text-sm outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        @click.stop
                    />
                </div>
            </div>

            <div
                ref="listRef"
                class="max-h-60 overflow-y-auto p-1"
                @scroll="onListScroll"
            >
                <button
                    v-for="district in options"
                    :key="district.id"
                    type="button"
                    class="flex w-full rounded-sm px-2 py-2 text-left text-sm hover:bg-accent hover:text-accent-foreground"
                    :class="String(district.id) === modelValue ? 'bg-accent text-accent-foreground' : ''"
                    @click="selectDistrict(district)"
                >
                    {{ district.name_en }} ({{ district.state_name_en }})
                </button>

                <p v-if="!isLoading && options.length === 0" class="px-2 py-4 text-center text-sm text-muted-foreground">
                    No districts found.
                </p>

                <div v-if="isLoading" class="flex items-center justify-center gap-2 py-3 text-sm text-muted-foreground">
                    <Loader2 class="size-4 animate-spin" />
                    Loading...
                </div>

                <p v-else-if="hasMore && options.length > 0" class="py-2 text-center text-xs text-muted-foreground">
                    Scroll for more...
                </p>
            </div>
        </div>
    </div>
</template>
