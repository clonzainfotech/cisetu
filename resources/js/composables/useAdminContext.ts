import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useAdminContext() {
    const page = usePage();
    const auth = computed(() => page.props.auth as { user?: Record<string, unknown> });
    const currentVillage = computed(() => page.props.village as Record<string, unknown> | null | undefined);

    const isSuperAdmin = computed(
        () =>
            !!auth.value.user?.is_super_master_admin ||
            auth.value.user?.role === 'super_master_admin',
    );

    const isVillageAdmin = computed(() => auth.value.user?.role === 'village_admin');

    /** Super admin switched into a village subdomain (same UX as village admin). */
    const isInVillageContext = computed(() => !!currentVillage.value);

    /** Main admin portal: global menus (subscriptions, districts, villages, etc.). */
    const showsPlatformAdmin = computed(() => isSuperAdmin.value && !isInVillageContext.value);

    /** Village-scoped session (village admin or super admin on a village subdomain). */
    const actsAsVillageAdmin = computed(
        () => isVillageAdmin.value || (isSuperAdmin.value && isInVillageContext.value),
    );

    return {
        auth,
        currentVillage,
        isSuperAdmin,
        isVillageAdmin,
        isInVillageContext,
        showsPlatformAdmin,
        actsAsVillageAdmin,
    };
}
