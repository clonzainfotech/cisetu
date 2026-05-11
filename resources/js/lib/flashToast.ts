import { router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { FlashToast } from '@/types/ui';

let isInitialized = false;
let lastToastKey: string | null = null;
let lastToastAt = 0;

export function initializeFlashToast(): void {
    // In Vite dev/HMR this file can be evaluated multiple times.
    // Ensure we only register router listeners once.
    if (isInitialized) {
        return;
    }

    isInitialized = true;

    router.on('httpException', (event: any) => {
        const status =
            event?.detail?.response?.status ??
            event?.detail?.status ??
            event?.status;

        if (status === 429) {
            toast.error('Too many login attempts. Please wait a moment and try again.');
        } else if (status === 403) {
            toast.error('You do not have permission to perform this action.');
        } else if (status >= 500) {
            toast.error('A server error occurred. Please try again later.');
        }
    });

    router.on('finish', () => {
        const page = usePage() as any;
        const data = (page.props.toast || page.flash?.toast) as FlashToast | undefined;

        if (data && data.message) {
            const key = `${data.type}:${data.message}`;
            const now = Date.now();

            // Prevent duplicate toast if multiple listeners fire.
            if (lastToastKey === key && now - lastToastAt < 1200) {
                return;
            }

            lastToastKey = key;
            lastToastAt = now;

            toast[data.type](data.message);
        }
    });
}
