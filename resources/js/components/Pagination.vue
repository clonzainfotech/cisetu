<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type QueryParams = Record<string, string | number | null | undefined>;

type Props = {
    meta: {
        from: number | null;
        to: number | null;
        total: number;
        prev_page_url?: string | null;
        next_page_url?: string | null;
        links?: PaginationLink[];
    };
    /** Merged into prev/next URLs so per-page limit and search survive pagination. */
    query?: QueryParams;
};

const props = defineProps<Props>();

const linkList = computed((): PaginationLink[] => {
    const { links } = props.meta;

    return Array.isArray(links) ? links : [];
});

const stripLabel = (label: string): string =>
    label.replace(/<[^>]*>/g, '').trim();

const mergeQuery = (url: string | null): string | null => {
    if (!url || !props.query) {
        return url;
    }

    try {
        const target = new URL(url, window.location.origin);

        for (const [key, value] of Object.entries(props.query)) {
            if (value === null || value === undefined || value === '') {
                target.searchParams.delete(key);
            } else {
                target.searchParams.set(key, String(value));
            }
        }

        return `${target.pathname}${target.search}`;
    } catch {
        return url;
    }
};

const prevLink = computed((): string | null => {
    const raw =
        props.meta.prev_page_url ??
        linkList.value.find((link) =>
            /previous|prev|«/i.test(stripLabel(link.label)),
        )?.url ??
        null;

    return mergeQuery(raw);
});

const nextLink = computed((): string | null => {
    const raw =
        props.meta.next_page_url ??
        linkList.value.find((link) =>
            /next|»/i.test(stripLabel(link.label)),
        )?.url ??
        null;

    return mergeQuery(raw);
});
</script>

<template>
    <div class="flex items-center gap-1">
        <div class="flex h-10 items-center overflow-hidden rounded-lg border border-border bg-white shadow-sm transition-colors">
            <div class="px-4 py-2 text-sm font-medium text-foreground">
                <span class="font-bold text-foreground">{{ meta.from || 0 }}-{{ meta.to || 0 }}</span>
                <span class="ml-1 text-muted-foreground">of {{ meta.total }}</span>
            </div>

            <div class="flex h-full items-center gap-1 border-l border-border bg-white px-2">
                <Link
                    v-if="prevLink"
                    :href="prevLink"
                    class="p-1.5 text-muted-foreground transition-colors hover:text-foreground"
                    preserve-scroll
                >
                    <ChevronLeft class="h-4 w-4" />
                </Link>
                <div v-else class="cursor-not-allowed p-1.5 text-muted-foreground/30">
                    <ChevronLeft class="h-4 w-4" />
                </div>

                <Link
                    v-if="nextLink"
                    :href="nextLink"
                    class="p-1.5 text-muted-foreground transition-colors hover:text-foreground"
                    preserve-scroll
                >
                    <ChevronRight class="h-4 w-4" />
                </Link>
                <div v-else class="cursor-not-allowed p-1.5 text-muted-foreground/30">
                    <ChevronRight class="h-4 w-4" />
                </div>
            </div>
        </div>
    </div>
</template>
