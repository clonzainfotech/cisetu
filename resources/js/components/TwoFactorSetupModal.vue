<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import { Check, Copy, ScanLine } from 'lucide-vue-next';
import { computed, nextTick, ref, useTemplateRef, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { confirm } from '@/routes/two-factor';
import type { TwoFactorConfigContent } from '@/types';
import { toast } from 'vue-sonner';

type Props = {
    requiresConfirmation: boolean;
    twoFactorEnabled: boolean;
};

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } =
    useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<string>('');

const pinInputContainerRef = useTemplateRef('pinInputContainerRef');

const form = useForm({
    code: '',
});

const { copy } = useClipboard({ legacy: true });

const copyToClipboard = async () => {
    if (!manualSetupKey.value) {
        toast.error('No key available to copy');
        return;
    }
    
    try {
        await copy(manualSetupKey.value);
        toast.success('Key copied to clipboard');
    } catch (err) {
        toast.error('Failed to copy key');
    }
};

const modalConfig = computed<TwoFactorConfigContent>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: 'Two-factor authentication enabled',
            description:
                'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
            buttonText: 'Close',
        };
    }

    if (showVerificationStep.value) {
        return {
            title: 'Verify authentication code',
            description: 'Enter the 6-digit code from your authenticator app',
            buttonText: 'Continue',
        };
    }

    return {
        title: 'Enable two-factor authentication',
        description:
            'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
        buttonText: 'Continue',
    };
});

const handleModalNextStep = () => {
    if (props.requiresConfirmation) {
        showVerificationStep.value = true;

        nextTick(() => {
            pinInputContainerRef.value?.querySelector('input')?.focus();
        });

        return;
    }

    clearSetupData();
    isOpen.value = false;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData();
    }

    showVerificationStep.value = false;
    code.value = '';
    form.reset();
};

const submitVerification = () => {
    form.code = code.value;
    form.post(confirm.url(), {
        preserveScroll: true,
        onSuccess: () => {
            isOpen.value = false;
        },
        onFinish: () => {
            code.value = '';
        },
    });
};

watch(
    () => isOpen.value,
    async (isOpen) => {
        if (!isOpen) {
            resetModalState();

            return;
        }

        if (!qrCodeSvg.value) {
            await fetchSetupData();
        }
    },
);
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="flex items-center justify-center">
                <div
                    class="mb-3 w-auto rounded-full border border-border bg-card p-0.5 shadow-sm"
                >
                    <div
                        class="relative overflow-hidden rounded-full border border-border bg-muted p-2.5"
                    >
                        <div
                            class="absolute inset-0 grid grid-cols-5 opacity-50"
                        >
                            <div
                                v-for="i in 5"
                                :key="`col-${i}`"
                                class="border-r border-border last:border-r-0"
                            />
                        </div>
                        <div
                            class="absolute inset-0 grid grid-rows-5 opacity-50"
                        >
                            <div
                                v-for="i in 5"
                                :key="`row-${i}`"
                                class="border-b border-border last:border-b-0"
                            />
                        </div>
                        <ScanLine
                            class="relative z-20 size-6 text-foreground"
                        />
                    </div>
                </div>
                <DialogTitle>{{ modalConfig.title }}</DialogTitle>
                <DialogDescription class="text-center">
                    {{ modalConfig.description }}
                </DialogDescription>
            </DialogHeader>

            <div
                class="relative flex w-auto flex-col items-center justify-center space-y-5"
            >
                <template v-if="!showVerificationStep">
                    <AlertError v-if="errors?.length" :errors="errors" />
                    <template v-else>
                        <div
                            class="relative mx-auto flex max-w-md items-center overflow-hidden"
                        >
                            <div
                                class="relative mx-auto aspect-square w-64 overflow-hidden rounded-lg border border-border"
                            >
                                <div
                                    v-if="!qrCodeSvg"
                                    class="absolute inset-0 z-10 flex aspect-square h-auto w-full animate-pulse items-center justify-center bg-background"
                                >
                                    <Spinner class="size-6" />
                                </div>
                                <div
                                    v-else
                                    class="relative z-10 overflow-hidden border p-5"
                                >
                                    <div
                                        v-html="qrCodeSvg"
                                        class="flex aspect-square size-full items-center justify-center"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex w-full items-center space-x-5">
                            <Button class="w-full" @click="handleModalNextStep">
                                {{ modalConfig.buttonText }}
                            </Button>
                        </div>

                        <div
                            class="relative flex w-full items-center justify-center"
                        >
                            <div
                                class="absolute inset-0 top-1/2 h-px w-full bg-border"
                            />
                            <span class="relative bg-card px-2 py-1 text-xs text-muted-foreground"
                                >or, enter the code manually</span
                            >
                        </div>

                        <div
                            class="flex w-full items-center justify-center space-x-2"
                        >
                            <div
                                class="flex w-full items-stretch overflow-hidden rounded-xl border border-border bg-muted/30 shadow-inner"
                            >
                                <div
                                    v-if="!manualSetupKey"
                                    class="flex h-11 w-full items-center justify-center p-3"
                                >
                                    <Spinner />
                                </div>
                                <template v-else>
                                    <input
                                        id="manual-setup-key-input"
                                        type="text"
                                        readonly
                                        :value="manualSetupKey"
                                        class="h-11 w-full bg-transparent px-4 text-sm font-mono tracking-wider text-foreground outline-none"
                                    />
                                    <button
                                        type="button"
                                        @click="copyToClipboard"
                                        class="relative block h-auto border-l border-border px-4 hover:bg-muted transition-colors"
                                        title="Copy setup key"
                                    >
                                        <Copy class="size-4" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>

                <template v-else>
                    <form
                        @submit.prevent="submitVerification"
                        class="w-full space-y-6"
                    >
                        <div
                            ref="pinInputContainerRef"
                            class="relative w-full space-y-4"
                        >
                            <div
                                class="flex w-full flex-col items-center justify-center space-y-4 py-2"
                            >
                                <InputOTP
                                    id="otp"
                                    v-model="code"
                                    :maxlength="6"
                                    :disabled="form.processing"
                                    autofocus
                                >
                                    <InputOTPGroup>
                                        <InputOTPSlot
                                            v-for="index in 6"
                                            :key="index"
                                            :index="index - 1"
                                        />
                                    </InputOTPGroup>
                                </InputOTP>
                                <InputError :message="form.errors.code" />
                            </div>

                            <div class="flex w-full items-center space-x-3">
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="flex-1 h-11"
                                    @click="showVerificationStep = false"
                                    :disabled="form.processing"
                                >
                                    Back
                                </Button>
                                <Button
                                    type="submit"
                                    class="flex-1 h-11"
                                    :disabled="form.processing || code.length < 6"
                                >
                                    <Spinner v-if="form.processing" class="mr-2" />
                                    Confirm
                                </Button>
                            </div>
                        </div>
                    </form>
                </template>
            </div>
        </DialogContent>
    </Dialog>
</template>
