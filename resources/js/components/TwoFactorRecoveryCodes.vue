<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Eye, EyeOff, LockKeyhole, RefreshCw, Copy, Check } from 'lucide-vue-next';
import { nextTick, onMounted, ref, useTemplateRef } from 'vue';
import AlertError from '@/components/AlertError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { regenerateRecoveryCodes } from '@/routes/two-factor';
import { useClipboard } from '@vueuse/core';
import { toast } from 'vue-sonner';

const { recoveryCodesList, fetchRecoveryCodes, errors } = useTwoFactorAuth();
const isRecoveryCodesVisible = ref<boolean>(false);
const recoveryCodeSectionRef = useTemplateRef('recoveryCodeSectionRef');

const form = useForm({});

const { copy } = useClipboard({ legacy: true });

const copyToClipboard = async (text: string | null) => {
    if (!text) {
        toast.error('No text available to copy');
        return;
    }
    
    try {
        await copy(text);
        toast.success('Copied to clipboard');
    } catch (err) {
        toast.error('Failed to copy');
    }
};

const regenerate = () => {
    form.post(regenerateRecoveryCodes.url(), {
        preserveScroll: true,
        onSuccess: () => {
            fetchRecoveryCodes();
            toast.success('Recovery codes regenerated');
        }
    });
};

const copyAllCodes = () => {
    const codes = recoveryCodesList.value.join('\n');
    copyToClipboard(codes);
};

const toggleRecoveryCodesVisibility = async () => {
    if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }

    isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value;

    if (isRecoveryCodesVisible.value) {
        await nextTick();
        recoveryCodeSectionRef.value?.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(async () => {
    if (!recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }
});
</script>

<template>
    <Card class="w-full border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <CardHeader class="bg-muted/30 pb-4">
            <CardTitle class="flex items-center gap-3 text-lg font-bold">
                <div class="p-2 rounded-lg bg-background border shadow-sm">
                    <LockKeyhole class="size-4 text-primary" />
                </div>
                2FA recovery codes
            </CardTitle>
            <CardDescription>
                Recovery codes let you regain access if you lose your 2FA
                device. Store them in a secure password manager.
            </CardDescription>
        </CardHeader>
        <CardContent class="pt-6">
            <div
                class="flex flex-col gap-3 select-none sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex items-center gap-2">
                    <Button @click="toggleRecoveryCodesVisibility" variant="outline" class="h-9 gap-2">
                        <component
                            :is="isRecoveryCodesVisible ? EyeOff : Eye"
                            class="size-4"
                        />
                        {{ isRecoveryCodesVisible ? 'Hide' : 'View' }} codes
                    </Button>

                    <Button 
                        v-if="isRecoveryCodesVisible && recoveryCodesList.length" 
                        variant="outline" 
                        size="icon" 
                        class="h-9 w-9" 
                        @click="copyAllCodes"
                        title="Copy all codes"
                    >
                        <Copy class="size-4" />
                    </Button>
                </div>

                <form
                    v-if="isRecoveryCodesVisible && recoveryCodesList.length"
                    @submit.prevent="regenerate"
                >
                    <Button
                        variant="secondary"
                        type="submit"
                        :disabled="form.processing"
                        class="h-9 gap-2"
                    >
                        <RefreshCw :class="['size-4', form.processing ? 'animate-spin' : '']" />
                        Regenerate
                    </Button>
                </form>
            </div>
            <div
                :class="[
                    'relative overflow-hidden transition-all duration-300',
                    isRecoveryCodesVisible
                        ? 'max-h-[500px] opacity-100 mt-6'
                        : 'max-h-0 opacity-0',
                ]"
            >
                <div v-if="errors?.length">
                    <AlertError :errors="errors" />
                </div>
                <div v-else class="space-y-4">
                    <div
                        ref="recoveryCodeSectionRef"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 rounded-xl border bg-muted/50 p-6 font-mono text-sm shadow-inner"
                    >
                        <div v-if="!recoveryCodesList.length" class="contents">
                            <div
                                v-for="n in 8"
                                :key="n"
                                class="h-10 animate-pulse rounded-lg bg-muted-foreground/10"
                            ></div>
                        </div>
                        <div
                            v-else
                            v-for="(code, index) in recoveryCodesList"
                            :key="index"
                            class="flex items-center justify-between p-2 rounded-md bg-background border border-zinc-200 dark:border-zinc-800 shadow-sm group hover:border-primary/30 transition-colors"
                        >
                            <span class="tracking-widest">{{ code }}</span>
                            <button @click="copyToClipboard(code)" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:text-primary">
                                <Copy class="size-3.5" />
                            </button>
                        </div>
                    </div>
                    <p class="text-[11px] text-muted-foreground select-none flex items-center gap-2 px-2">
                        <span class="h-1 w-1 rounded-full bg-primary/50"></span>
                        Each code can be used once. If you need more, click <span class="font-bold text-foreground">Regenerate</span> above.
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
