<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
    Home, 
    Store, 
    IndianRupee, 
    Layers,
    ChevronRight,
    TrendingUp,
    ShieldAlert,
    CreditCard,
    MessageSquare,
    MapPin,
    Mail,
    ShieldCheck
} from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { index as inquiriesIndex } from '@/routes/inquiries';
import Heading from '@/components/Heading.vue';
import { useAdminContext } from '@/composables/useAdminContext';

const props = defineProps<{
    stats: {
        is_super_admin?: boolean;
        total_properties?: number;
        total_homes?: number;
        total_shops?: number;
        total_revenue: number;
        home_revenue: number;
        shop_revenue: number;
        // Super admin specific
        total_villages?: number;
        active_villages?: number;
        expired_villages?: number;
        total_records?: number;
        home_records?: number;
        shop_records?: number;
        pending_inquiries?: number;
        plans_breakdown?: Array<{ name: string; code: string; count: number }>;
    } | null;
    recent_inquiries?: Array<{
        id: number;
        type: string;
        name: string;
        email: string;
        phone: string;
        village_name: string;
        district_name: string;
        plan?: { name: string };
        status: string;
        created_at: string;
    }>;
}>();

const page = usePage();
const { actsAsVillageAdmin, showsPlatformAdmin } = useAdminContext();
const auth = computed(() => page.props.auth as any);
const user = computed(() => auth.value.user);
const permissions = computed(() => user.value?.permissions || []);

const hasPersonalTax = computed(
    () =>
        actsAsVillageAdmin.value ||
        showsPlatformAdmin.value ||
        permissions.value.includes('personal_tax'),
);
const hasProfessionalTax = computed(
    () =>
        actsAsVillageAdmin.value ||
        showsPlatformAdmin.value ||
        permissions.value.includes('professional_tax'),
);

const showPlatformDashboard = computed(() => props.stats?.is_super_admin === true);

const formatCurrency = (val: number) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0,
    }).format(val);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};

defineOptions({
    layout: () => ({
        breadcrumbs: [{ title: 'Dashboard' }],
    }),
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                variant="small"
                title="Dashboard"
                :description="showPlatformDashboard ? 'Platform-wide statistics and management overview' : 'Real-time statistics and tax records overview'"
            />
        </div>

        <!-- No Permissions View -->
        <div v-if="!hasPersonalTax && !hasProfessionalTax && !showsPlatformAdmin && !actsAsVillageAdmin" class="flex flex-col items-center justify-center py-20 text-center">
            <ShieldAlert class="size-12 text-muted-foreground mb-4" />
            <h3 class="text-lg font-bold">Access Restricted</h3>
            <p class="text-sm text-muted-foreground max-w-xs mx-auto">
                Your account does not have any module permissions. Please contact your Village Admin to request access.
            </p>
        </div>

        <div v-else class="space-y-8">
            <!-- Global Stats (Super Admin) -->
            <div 
                v-if="stats && showPlatformDashboard" 
                class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4"
            >
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Total Villages</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.total_villages }}</div>
                            <div class="rounded-lg bg-primary/10 p-2 text-primary">
                                <IndianRupee class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Coverage:</span>
                            <span class="font-bold text-emerald-600">{{ stats.active_villages }} Active</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Inquiries Console</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.pending_inquiries }}</div>
                            <div class="rounded-lg bg-emerald-500/10 text-emerald-600 p-2">
                                <MessageSquare class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Status:</span>
                            <span class="font-bold text-emerald-600">Pending Requests</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">System Records</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.total_records }}</div>
                            <div class="rounded-lg bg-muted/50 p-2 text-muted-foreground">
                                <Layers class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Homes / Shops:</span>
                            <span class="font-bold">{{ stats.home_records }} / {{ stats.shop_records }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Gross Collection</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ formatCurrency(stats.total_revenue) }}</div>
                            <div class="rounded-lg bg-emerald-100 dark:bg-emerald-950/30 p-2 text-emerald-600">
                                <TrendingUp class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Growth:</span>
                            <span class="font-bold text-emerald-600">+12.5%</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Village Specific Stats -->
            <div 
                v-else-if="stats" 
                :class="[
                    'grid gap-6',
                    hasPersonalTax && hasProfessionalTax ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4' : 'grid-cols-1 md:grid-cols-2 max-w-4xl'
                ]"
            >
                <!-- Total Revenue Card -->
                <Card v-if="isVillageAdmin || (hasPersonalTax && hasProfessionalTax)">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Total Revenue</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ formatCurrency(stats.total_revenue) }}</div>
                            <div class="rounded-lg bg-muted/50 p-2 text-muted-foreground">
                                <IndianRupee class="size-5" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Total Properties Card -->
                <Card v-if="isVillageAdmin || (hasPersonalTax && hasProfessionalTax)">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Total Records</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.total_properties }}</div>
                            <div class="rounded-lg bg-muted/50 p-2 text-muted-foreground">
                                <Layers class="size-5" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Homes Card -->
                <Card v-if="hasPersonalTax">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Homes (Personal Tax)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.total_homes }}</div>
                            <div class="rounded-lg bg-muted/50 p-2 text-muted-foreground">
                                <Home class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Collection:</span>
                            <span class="font-bold">{{ formatCurrency(stats.home_revenue) }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Shops Card -->
                <Card v-if="hasProfessionalTax">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Shops (Prof. Tax)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold tracking-tight text-foreground">{{ stats.total_shops }}</div>
                            <div class="rounded-lg bg-muted/50 p-2 text-muted-foreground">
                                <Store class="size-5" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-[11px]">
                            <span class="text-muted-foreground">Collection:</span>
                            <span class="font-bold">{{ formatCurrency(stats.shop_revenue) }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Secondary Section -->
            <div 
                :class="[
                    'grid gap-6',
                    (stats && (showPlatformDashboard || (hasPersonalTax && hasProfessionalTax))) ? 'grid-cols-1 lg:grid-cols-3' : 'grid-cols-1 max-w-4xl'
                ]"
            >
                <!-- Revenue Distribution Chart / Plan Breakdown -->
                <Card v-if="stats" class="lg:col-span-2">
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div v-if="showPlatformDashboard">
                            <CardTitle class="text-sm font-bold uppercase">Subscription Breakdown</CardTitle>
                            <p class="text-[11px] text-muted-foreground mt-1">Village distribution across plans</p>
                        </div>
                        <div v-else>
                            <CardTitle class="text-sm font-bold uppercase">Revenue Distribution</CardTitle>
                            <p class="text-[11px] text-muted-foreground mt-1">Homes vs Professional Tax split</p>
                        </div>
                        <TrendingUp class="size-4 text-muted-foreground/30" />
                    </CardHeader>
                    <CardContent class="pt-6">
                        <div v-if="showPlatformDashboard" class="space-y-6">
                            <div v-for="plan in stats.plans_breakdown" :key="plan.code" class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-medium">
                                    <span class="text-muted-foreground">{{ plan.name }} Plan</span>
                                    <span class="font-bold">{{ plan.count }} Villages</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-muted overflow-hidden">
                                    <div 
                                        class="h-full transition-all"
                                        :class="plan.code === 'pragati' ? 'bg-primary' : 'bg-muted-foreground/30'"
                                        :style="{ width: `${(plan.count / (stats.total_villages || 1)) * 100}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="space-y-6">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-medium">
                                    <span class="text-muted-foreground">Homes Collection</span>
                                    <span class="font-bold">{{ Math.round((stats.home_revenue / (stats.total_revenue || 1)) * 100) }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-muted overflow-hidden">
                                    <div 
                                        class="h-full bg-blue-600 transition-all"
                                        :style="{ width: `${(stats.home_revenue / (stats.total_revenue || 1)) * 100}%` }"
                                    ></div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-medium">
                                    <span class="text-muted-foreground">Shops Collection</span>
                                    <span class="font-bold">{{ Math.round((stats.shop_revenue / (stats.total_revenue || 1)) * 100) }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-muted overflow-hidden">
                                    <div 
                                        class="h-full bg-emerald-600 transition-all"
                                        :style="{ width: `${(stats.shop_revenue / (stats.total_revenue || 1)) * 100}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-4 border-t pt-6">
                            <div class="text-center p-3 rounded-lg bg-muted/30">
                                <p class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">
                                    {{ showPlatformDashboard ? 'Avg. per Village' : 'Avg. per Property' }}
                                </p>
                                <p class="text-sm font-bold mt-1">
                                    {{ showPlatformDashboard
                                        ? formatCurrency(stats.total_revenue / (stats.total_villages || 1))
                                        : formatCurrency(stats.total_revenue / (stats.total_properties || 1)) 
                                    }}
                                </p>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-muted/30">
                                <p class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider">Growth</p>
                                <p class="text-sm font-bold text-emerald-600 mt-1">+12.5%</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Quick Actions -->
                <Card :class="[!(hasPersonalTax && hasProfessionalTax || showPlatformDashboard) && 'md:max-w-md']">
                    <CardHeader>
                        <CardTitle class="text-sm font-bold uppercase">Quick Actions</CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-3">
                        <template v-if="showPlatformDashboard">
                            <Button variant="outline" as-child class="justify-between h-11 px-4">
                                <Link href="/villages">
                                    <div class="flex items-center gap-3">
                                        <div class="size-2 rounded-full bg-primary animate-pulse" />
                                        <span class="text-xs font-medium">Manage Villages</span>
                                    </div>
                                    <ChevronRight class="size-3 text-muted-foreground" />
                                </Link>
                            </Button>
                            <Button variant="outline" as-child class="justify-between h-11 px-4">
                                <Link href="/subscriptions">
                                    <div class="flex items-center gap-3">
                                        <CreditCard class="size-4 text-primary" />
                                        <span class="text-xs font-medium">Review Subscriptions</span>
                                    </div>
                                    <ChevronRight class="size-3 text-muted-foreground" />
                                </Link>
                            </Button>
                        </template>

                        <template v-else>
                            <Button v-if="hasPersonalTax" variant="outline" as-child class="justify-between h-11 px-4">
                                <Link href="/homes">
                                    <div class="flex items-center gap-3">
                                        <Home class="size-4 text-blue-600" />
                                        <span class="text-xs font-medium">Manage Homes</span>
                                    </div>
                                    <ChevronRight class="size-3 text-muted-foreground" />
                                </Link>
                            </Button>
                            <Button v-if="hasProfessionalTax" variant="outline" as-child class="justify-between h-11 px-4">
                                <Link href="/shops">
                                    <div class="flex items-center gap-3">
                                        <Store class="size-4 text-emerald-600" />
                                        <span class="text-xs font-medium">Manage Shops</span>
                                    </div>
                                    <ChevronRight class="size-3 text-muted-foreground" />
                                </Link>
                            </Button>
                        </template>

                        <div v-if="!hasPersonalTax && !hasProfessionalTax && !showPlatformDashboard && !actsAsVillageAdmin" class="py-4 text-center text-xs text-muted-foreground">
                            No actions available.
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Inquiries Section (Super Admin) -->
            <div v-if="showPlatformDashboard && recent_inquiries && recent_inquiries.length > 0" class="space-y-6">
                <div class="flex items-center justify-between">
                    <Heading
                        variant="small"
                        title="Recent Connectivity Requests"
                        description="Monitor incoming demo and subscription inquiries"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Card v-for="inquiry in recent_inquiries" :key="inquiry.id" class="relative overflow-hidden group">
                        <div 
                            class="absolute top-0 right-0 px-4 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-bl-xl"
                            :class="inquiry.type === 'subscription' ? 'bg-emerald-500 text-white' : 'bg-blue-500 text-white'"
                        >
                            {{ inquiry.type }}
                        </div>
                        <CardHeader class="pb-2">
                            <CardTitle class="text-sm font-bold truncate pr-16">{{ inquiry.name }}</CardTitle>
                            <p class="text-[10px] text-muted-foreground uppercase font-black tracking-widest">{{ formatDate(inquiry.created_at) }}</p>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-3 text-xs">
                                <MapPin class="size-3.5 text-muted-foreground" />
                                <span class="font-medium">{{ inquiry.village_name }}, {{ inquiry.district_name }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <Mail class="size-3.5 text-muted-foreground" />
                                <span class="truncate">{{ inquiry.email }}</span>
                            </div>
                            <div v-if="inquiry.plan" class="flex items-center gap-3 text-xs">
                                <ShieldCheck class="size-3.5 text-emerald-500" />
                                <span class="font-bold text-emerald-600 uppercase tracking-tighter">{{ inquiry.plan.name }} Tier</span>
                            </div>
                            
                            <div class="pt-4 border-t flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest" :class="inquiry.status === 'pending' ? 'text-amber-500' : 'text-zinc-500'">
                                    Status: {{ inquiry.status }}
                                </span>
                                <Button variant="ghost" size="sm" class="h-8 text-[10px] font-black uppercase tracking-widest" as-child>
                                    <Link :href="inquiriesIndex().url">Review Node</Link>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>

