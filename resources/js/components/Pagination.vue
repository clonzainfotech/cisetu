<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

type Props = {
    meta: {
        from: number | null;
        to: number | null;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
};

const props = defineProps<Props>();

// Find prev and next links
const prevLink = props.meta.links[0]?.url;
const nextLink = props.meta.links[props.meta.links.length - 1]?.url;
</script>

<template>
    <div class="flex items-center gap-1">
        <div class="flex items-center h-10 border border-border rounded-lg bg-background shadow-sm overflow-hidden transition-colors">
            <div class="px-4 py-2 text-sm font-medium text-foreground">
                <span class="font-bold text-foreground">{{ meta.from || 0 }}-{{ meta.to || 0 }}</span>
                <span class="text-muted-foreground ml-1">of {{ meta.total }}</span>
            </div>
            
            <div class="flex items-center border-l border-border h-full px-2 gap-1 bg-background">
                <Link
                    v-if="prevLink"
                    :href="prevLink"
                    class="p-1.5 text-muted-foreground hover:text-foreground transition-colors"
                >
                    <ChevronLeft class="h-4 w-4" />
                </Link>
                <div v-else class="p-1.5 text-muted-foreground/30 cursor-not-allowed">
                    <ChevronLeft class="h-4 w-4" />
                </div>

                <Link
                    v-if="nextLink"
                    :href="nextLink"
                    class="p-1.5 text-muted-foreground hover:text-foreground transition-colors"
                >
                    <ChevronRight class="h-4 w-4" />
                </Link>
                <div v-else class="p-1.5 text-muted-foreground/30 cursor-not-allowed">
                    <ChevronRight class="h-4 w-4" />
                </div>
            </div>
        </div>
    </div>
</template>
