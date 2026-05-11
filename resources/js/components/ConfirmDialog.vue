<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const props = withDefaults(
    defineProps<{
        open: boolean;
        title: string;
        description?: string;
        confirmText?: string;
        confirmVariant?: 'default' | 'destructive';
    }>(),
    {
        description: undefined,
        confirmText: 'Confirm',
        confirmVariant: 'destructive',
    },
);

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
    (e: 'confirm'): void;
}>();

const close = () => emit('update:open', false);

const confirm = () => {
    emit('confirm');
    close();
};
</script>

<template>
    <Dialog :open="open" @update:open="close">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="space-y-2">
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription v-if="description">
                    {{ description }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-2">
                <DialogClose as-child>
                    <Button variant="secondary">Cancel</Button>
                </DialogClose>

                <Button :variant="confirmVariant" @click="confirm">
                    {{ confirmText }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

