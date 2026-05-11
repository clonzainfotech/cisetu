<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { 
    X, 
    UploadCloud, 
    FileText, 
    Download, 
    CheckCircle2, 
    AlertCircle, 
    Loader2 
} from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

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
    if (!selectedFile.value) return;

    isProcessing.value = true;
    const formData = new FormData();
    formData.append('file', selectedFile.value);

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
        <DialogContent class="sm:max-w-[480px] p-0 overflow-hidden border-none shadow-2xl bg-card dark:bg-zinc-950">
            <DialogHeader class="p-6 pb-2">
                <div class="space-y-0.5">
                    <DialogTitle class="text-xl font-black uppercase tracking-tight text-zinc-900 dark:text-white">
                        {{ title }}
                    </DialogTitle>
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
                        {{ description }}
                    </p>
                </div>
            </DialogHeader>

            <div class="px-6 pb-6 space-y-5">
                <!-- Step 1: Download Template -->
                <div class="relative rounded-xl border border-blue-100/50 bg-blue-50/30 p-3.5 dark:border-blue-900/10 dark:bg-blue-900/5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex size-9 items-center justify-center rounded-lg bg-background shadow-sm dark:bg-zinc-800 border border-blue-50/50 dark:border-blue-900/10">
                                <FileText class="size-4 text-blue-500" />
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight leading-none">1. Download Template</h4>
                                <p class="text-[9px] text-zinc-500 font-bold mt-1">Use this format for import</p>
                            </div>
                        </div>
                        <Button as-child variant="outline" class="h-8 px-4 font-black text-[9px] uppercase tracking-widest border-2 border-zinc-900 dark:border-white hover:bg-zinc-900 hover:text-white dark:hover:bg-white dark:hover:text-black transition-all">
                            <a :href="templateUrl">
                                <Download class="mr-1.5 size-2.5" /> Download
                            </a>
                        </Button>
                    </div>
                </div>

                <!-- Step 2: Upload File -->
                <div class="space-y-3">
                    <h4 class="text-[11px] font-black text-zinc-900 dark:text-white uppercase tracking-tight">2. Upload Filled File</h4>
                    
                    <div 
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop"
                        class="relative"
                    >
                        <input 
                            type="file" 
                            id="file-upload" 
                            class="absolute inset-0 z-10 size-full cursor-pointer opacity-0"
                            @change="handleFileSelect"
                            accept=".csv"
                        />
                        <div 
                            :class="[
                                'flex flex-col items-center justify-center rounded-2xl border-2 border-dashed p-8 transition-all text-center',
                                isDragging ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-500/10' : 'border-zinc-100 bg-zinc-50/30 dark:border-zinc-800 dark:bg-zinc-900/20',
                                selectedFile ? 'border-emerald-500/30 bg-emerald-50/10 dark:bg-emerald-500/5' : ''
                            ]"
                        >
                            <div v-if="!selectedFile" class="space-y-3">
                                <div class="flex size-12 items-center justify-center rounded-full bg-background shadow-sm mx-auto dark:bg-zinc-800">
                                    <UploadCloud :class="['size-6 transition-colors', isDragging ? 'text-blue-500' : 'text-zinc-400']" />
                                </div>
                                <div>
                                    <p class="text-sm font-black text-zinc-900 dark:text-white">
                                        <span class="text-blue-500">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-[9px] font-black text-zinc-400 uppercase tracking-widest mt-1">CSV (MAX. 5MB)</p>
                                </div>
                            </div>
                            <div v-else class="space-y-3">
                                <div class="flex size-12 items-center justify-center rounded-full bg-emerald-100 mx-auto dark:bg-emerald-500/20">
                                    <CheckCircle2 class="size-6 text-emerald-600 dark:text-emerald-400" />
                                </div>
                                <div class="px-4 py-2 rounded-lg bg-background shadow-sm dark:bg-zinc-800 inline-block border border-zinc-100 dark:border-zinc-700">
                                    <p class="text-[11px] font-black text-zinc-900 dark:text-white truncate max-w-[220px]">
                                        {{ selectedFile.name }}
                                    </p>
                                </div>
                                <button @click.stop="selectedFile = null" class="text-[9px] font-black text-destructive uppercase tracking-widest hover:underline block mx-auto">
                                    Remove and change
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="rounded-xl border border-amber-100/50 bg-amber-50/10 p-4 dark:border-amber-900/10 dark:bg-amber-900/5">
                    <div class="flex items-center gap-2 mb-3">
                        <AlertCircle class="size-3 text-amber-600" />
                        <h5 class="text-[10px] font-black text-amber-800 dark:text-amber-500 uppercase tracking-widest">Important Notes</h5>
                    </div>
                    <ul class="space-y-2">
                        <li v-for="(note, i) in notes" :key="i" class="flex items-start gap-2.5 text-[10px] text-amber-900/50 dark:text-amber-400/50 font-bold leading-normal">
                            <span class="size-1.5 rounded-full bg-amber-400/70 mt-1 flex-shrink-0"></span>
                            {{ note }}
                        </li>
                    </ul>
                </div>

                <!-- Action Button -->
                <Button 
                    @click="processImport"
                    :disabled="!selectedFile || isProcessing"
                    class="group w-full h-12 rounded-xl bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-widest transition-all active:scale-[0.98] disabled:opacity-40 disabled:bg-zinc-300 dark:disabled:bg-zinc-800 shadow-lg shadow-emerald-500/20"
                >
                    <template v-if="isProcessing">
                        <Loader2 class="mr-2 size-4 animate-spin" /> Processing...
                    </template>
                    <template v-else>
                        <UploadCloud class="mr-2 size-4 group-hover:-translate-y-0.5 transition-transform" /> Process Import
                    </template>
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
