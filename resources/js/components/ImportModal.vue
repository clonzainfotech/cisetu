<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import {
    UploadCloud,
    FileText,
    Download,
    CheckCircle2,
    AlertCircle,
    Loader2,
} from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    open: boolean;
    title: string;
    description: string;
    importUrl: string;
    templateUrl: string;
    notes: string[];
}>();

const emit = defineEmits(['update:open']);

const isDragging = ref(false);
const selectedFile = ref<File | null>(null);
const isProcessing = ref(false);
const useAi = ref(true);

const handleFileSelect = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files?.length) {
        selectedFile.value = target.files[0];
    }
};

const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    if (e.dataTransfer?.files.length) {
        selectedFile.value = e.dataTransfer.files[0];
    }
};

const processImport = () => {
    if (!selectedFile.value) {
        return;
    }

    isProcessing.value = true;
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    formData.append('use_ai', useAi.value ? '1' : '0');

    router.post(props.importUrl, formData, {
        onSuccess: () => {
            selectedFile.value = null;
            emit('update:open', false);
        },
        onFinish: () => {
            isProcessing.value = false;
        },
        forceFormData: true,
    });
};

const close = () => {
    if (!isProcessing.value) {
        emit('update:open', false);
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="close">
        <DialogContent class="gap-0 overflow-hidden p-0 sm:max-w-[480px]">
            <DialogHeader class="border-b border-border bg-muted/30 p-6 pb-4">
                <div class="space-y-1">
                    <DialogTitle class="text-lg font-bold tracking-tight text-foreground uppercase">
                        {{ title }}
                    </DialogTitle>
                    <p class="text-xs font-medium tracking-wide text-muted-foreground uppercase">
                        {{ description }}
                    </p>
                </div>
            </DialogHeader>

            <div class="space-y-5 bg-card p-6">
                <!-- Step 1: Download Template -->
                <div class="rounded-xl border border-border bg-muted/40 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <div
                                class="flex size-9 shrink-0 items-center justify-center rounded-lg border border-border bg-background shadow-sm"
                            >
                                <FileText class="size-4 text-primary" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs leading-none font-bold text-foreground uppercase">
                                    1. Download Template
                                </h4>
                                <p class="mt-1 text-[11px] text-muted-foreground">
                                    Use this format for import
                                </p>
                            </div>
                        </div>
                        <Button as-child variant="outline" size="sm" class="shrink-0 font-semibold">
                            <a :href="templateUrl">
                                <Download class="mr-1.5 size-3.5" />
                                Download
                            </a>
                        </Button>
                    </div>
                </div>

                <!-- Step 2: Upload File -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-foreground uppercase tracking-tight">
                        2. Upload Filled File
                    </h4>

                    <div
                        class="relative"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop"
                    >
                        <input
                            id="file-upload"
                            type="file"
                            class="absolute inset-0 z-10 size-full cursor-pointer opacity-0"
                            accept=".csv,.xlsx,.xls"
                            @change="handleFileSelect"
                        />
                        <div
                            :class="[
                                'flex flex-col items-center justify-center rounded-xl border-2 border-dashed p-8 text-center transition-colors',
                                isDragging
                                    ? 'border-primary bg-primary/10'
                                    : 'border-border bg-muted/30',
                                selectedFile ? 'border-emerald-500/50 bg-emerald-500/10' : '',
                            ]"
                        >
                            <div v-if="!selectedFile" class="space-y-3">
                                <div
                                    class="mx-auto flex size-12 items-center justify-center rounded-full border border-border bg-background shadow-sm"
                                >
                                    <UploadCloud
                                        :class="[
                                            'size-6 transition-colors',
                                            isDragging ? 'text-primary' : 'text-muted-foreground',
                                        ]"
                                    />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-foreground">
                                        <span class="text-primary">Click to upload</span>
                                        or drag and drop
                                    </p>
                                    <p class="mt-1 text-[11px] font-medium text-muted-foreground uppercase">
                                        CSV / Excel .xlsx (max. 10MB)
                                    </p>
                                </div>
                            </div>
                            <div v-else class="space-y-3">
                                <div
                                    class="mx-auto flex size-12 items-center justify-center rounded-full bg-emerald-500/15"
                                >
                                    <CheckCircle2 class="size-6 text-emerald-600 dark:text-emerald-400" />
                                </div>
                                <div
                                    class="inline-block max-w-full rounded-lg border border-border bg-background px-4 py-2 shadow-sm"
                                >
                                    <p class="truncate text-sm font-semibold text-foreground">
                                        {{ selectedFile.name }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="mx-auto block text-[11px] font-semibold text-destructive uppercase tracking-wide hover:underline"
                                    @click.stop="selectedFile = null"
                                >
                                    Remove and change
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Smart AI mapping -->
                <div
                    class="flex items-center justify-between gap-4 rounded-xl border border-border bg-muted/40 px-4 py-3"
                >
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-foreground uppercase tracking-wide">
                            Smart AI mapping
                        </p>
                        <p class="mt-0.5 text-[11px] text-muted-foreground">
                            Auto-fix Gujarati / messy Excel columns
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <Checkbox id="import-use-ai" v-model:checked="useAi" />
                        <Label for="import-use-ai" class="cursor-pointer text-xs font-semibold text-foreground">
                            On
                        </Label>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 dark:bg-amber-500/15">
                    <div class="mb-3 flex items-center gap-2">
                        <AlertCircle class="size-4 shrink-0 text-amber-600 dark:text-amber-400" />
                        <h5 class="text-xs font-bold text-foreground uppercase tracking-wide">
                            Important Notes
                        </h5>
                    </div>
                    <ul class="space-y-2">
                        <li
                            v-for="(note, i) in notes"
                            :key="i"
                            class="flex items-start gap-2.5 text-xs leading-relaxed text-muted-foreground"
                        >
                            <span
                                class="mt-1.5 size-1.5 shrink-0 rounded-full bg-amber-500 dark:bg-amber-400"
                            />
                            {{ note }}
                        </li>
                    </ul>
                </div>

                <!-- Action Button -->
                <Button
                    variant="default"
                    class="h-12 w-full rounded-xl bg-emerald-600 text-sm font-bold text-white uppercase tracking-wide hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500"
                    :disabled="!selectedFile || isProcessing"
                    @click="processImport"
                >
                    <template v-if="isProcessing">
                        <Loader2 class="mr-2 size-4 animate-spin" />
                        Processing...
                    </template>
                    <template v-else>
                        <UploadCloud class="mr-2 size-4" />
                        Process Import
                    </template>
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
