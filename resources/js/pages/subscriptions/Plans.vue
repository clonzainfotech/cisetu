<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Check, X, Zap, MapPin, CreditCard, Users, Shield, Bot, LayoutGrid, FileInput, FileOutput, Headphones } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { contactUs } from '@/routes';
import Heading from '@/components/Heading.vue';

interface Plan {
    id: number;
    code: string;
    name: string;
    description: string;
    price_per_year_inr: string;
    max_user_accounts: number | null;
    max_properties: number | null;
    max_bots: number | null;
    theme_customization: boolean;
    import_export: boolean;
    report_export: boolean;
    dedicated_support: boolean;
    advanced_reports_analytics: boolean;
    message_report_system: boolean;
    allows_custom_domain: boolean;
    allows_dedicated_server: boolean;
    payment_qr_mode: 'static' | 'dynamic';
}

defineProps<{
    plans: Plan[];
}>();

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Pricing Plans' },
        ],
    }),
});
</script>

<template>
    <Head title="Pricing Plans" />

    <div class="flex flex-col gap-6 py-4">
        <Heading
            title="Subscription Plans"
            description="Professional village administration solutions tailored for your needs."
        />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:max-w-4xl w-full mx-auto">
            <Card 
                v-for="(plan, index) in plans" 
                :key="plan.id"
                :class="[
                    'relative flex flex-col border transition-all duration-300 rounded-3xl overflow-hidden',
                    index === 1 
                        ? 'bg-primary/5 dark:bg-primary/10 border-primary/40 ring-1 ring-primary/20' 
                        : 'bg-card text-card-foreground border-border'
                ]"
            >
                <!-- Featured Badge -->
                <div v-if="index === 1" class="absolute top-0 right-0">
                    <div class="bg-primary text-primary-foreground text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-tighter">
                        Premium
                    </div>
                </div>

                <CardHeader class="p-6 pb-2">
                    <CardTitle class="text-xl font-bold tracking-tight uppercase flex items-center gap-2">
                        <LayoutGrid v-if="index === 0" class="size-4 text-primary" />
                        <Shield v-else class="size-4 text-primary" />
                        {{ plan.name }}
                    </CardTitle>
                    <CardDescription class="text-xs font-medium opacity-70">
                        {{ plan.description }}
                    </CardDescription>
                    
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-4xl font-black tracking-tight">₹ {{ parseFloat(plan.price_per_year_inr).toLocaleString('en-IN') }}</span>
                        <span class="text-sm font-bold opacity-40">/year</span>
                    </div>
                </CardHeader>

                <CardContent class="flex-1 px-6 py-4 space-y-4">
                    <div class="space-y-3">
                        <!-- User Accounts -->
                        <div class="flex items-center gap-3">
                            <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <Users class="size-3 text-primary" />
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wide">
                                {{ plan.max_user_accounts === null ? 'Unlimited Admins' : (plan.max_user_accounts + ' Admin Account' + (plan.max_user_accounts !== 1 ? 's' : '')) }}
                            </span>
                        </div>

                        <!-- Properties -->
                        <div class="flex items-center gap-3">
                            <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <MapPin class="size-3 text-primary" />
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wide">
                                {{ plan.max_properties === null ? 'Unlimited Village Records' : (plan.max_properties?.toLocaleString() + ' Records Limit') }}
                            </span>
                        </div>

                        <!-- Bots -->
                        <div class="flex items-center gap-3">
                            <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <Bot class="size-3 text-primary" />
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wide">
                                {{ plan.max_bots === null ? 'Full Automation' : (plan.max_bots + ' Virtual Assistant' + (plan.max_bots !== 1 ? 's' : '')) }}
                            </span>
                        </div>

                        <!-- Theme Customization -->
                        <div class="flex items-center gap-3">
                            <Check v-if="plan.theme_customization" class="size-4 text-primary shrink-0" />
                            <X v-else class="size-4 text-muted-foreground/30 shrink-0" />
                            <span :class="['text-[11px] font-bold uppercase tracking-wide', !plan.theme_customization && 'opacity-40']">
                                Theme Customization
                            </span>
                            <Badge v-if="plan.theme_customization" variant="outline" class="h-3.5 px-1 text-[8px] border-primary/20 bg-primary/5 text-primary">WIP</Badge>
                        </div>

                        <!-- Import/Export -->
                        <div class="flex items-center gap-3">
                            <Check v-if="plan.import_export" class="size-4 text-primary shrink-0" />
                            <X v-else class="size-4 text-muted-foreground/30 shrink-0" />
                            <span :class="['text-[11px] font-bold uppercase tracking-wide', !plan.import_export && 'opacity-40']">
                                Data Migration Tools
                            </span>
                            <Badge v-if="plan.import_export" variant="outline" class="h-3.5 px-1 text-[8px] border-primary/20 bg-primary/5 text-primary">WIP</Badge>
                        </div>

                        <!-- Hosting -->
                        <div class="flex items-center gap-3">
                            <Check v-if="plan.allows_custom_domain" class="size-4 text-primary shrink-0" />
                            <Check v-else class="size-4 text-primary shrink-0" />
                            <span class="text-[11px] font-bold uppercase tracking-wide">
                                {{ plan.allows_custom_domain ? 'Dedicated Domain & Server' : 'Managed Hosting' }}
                            </span>
                        </div>

                        <!-- Support -->
                        <div class="flex items-center gap-3">
                            <Headphones class="size-4 text-primary shrink-0" />
                            <span class="text-[11px] font-bold uppercase tracking-wide">
                                {{ plan.dedicated_support ? 'Priority 24/7 Support' : 'Standard Email Support' }}
                            </span>
                        </div>
                    </div>
                </CardContent>

                <CardFooter class="p-6 pt-2">
                    <Link :href="contactUs().url" class="w-full">
                        <Button 
                            :variant="index === 1 ? 'default' : 'outline'"
                            class="w-full h-11 rounded-xl text-xs font-bold uppercase tracking-widest transition-all"
                        >
                            Select Plan
                        </Button>
                    </Link>
                </CardFooter>
            </Card>
        </div>

        <!-- Semantic Comparison Table -->
        <div class="mt-8 space-y-4 max-w-4xl mx-auto w-full pb-20">
            <h3 class="text-sm font-bold uppercase tracking-widest text-center opacity-60">Full Feature Breakdown</h3>
            <div class="border rounded-2xl overflow-hidden bg-card text-card-foreground shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-muted/30 border-b">
                            <th class="p-3 text-[10px] font-bold uppercase tracking-widest opacity-50">Infrastructure</th>
                            <th v-for="p in plans" :key="p.id" class="p-3 text-[10px] font-bold uppercase tracking-widest text-center text-primary">
                                {{ p.name }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-[10px]">
                        <tr class="border-b last:border-0 hover:bg-muted/20 transition-colors">
                            <td class="p-3 font-medium uppercase tracking-tighter opacity-80">Max Admin Seats</td>
                            <td v-for="p in plans" :key="p.id" class="p-3 text-center font-bold">
                                {{ p.max_user_accounts === null ? 'UNLIMITED' : p.max_user_accounts }}
                            </td>
                        </tr>
                        <tr class="border-b last:border-0 hover:bg-muted/20 transition-colors">
                            <td class="p-3 font-medium uppercase tracking-tighter opacity-80">Record Storage</td>
                            <td v-for="p in plans" :key="p.id" class="p-3 text-center font-bold">
                                {{ p.max_properties === null ? 'UNLIMITED' : p.max_properties?.toLocaleString() }}
                            </td>
                        </tr>
                        <tr class="border-b last:border-0 hover:bg-muted/20 transition-colors">
                            <td class="p-3 font-medium uppercase tracking-tighter opacity-80">Payment Processing</td>
                            <td v-for="p in plans" :key="p.id" class="p-3 text-center font-bold uppercase">
                                {{ p.payment_qr_mode }} QR
                            </td>
                        </tr>
                        <tr class="border-b last:border-0 hover:bg-muted/20 transition-colors">
                            <td class="p-3 font-medium uppercase tracking-tighter opacity-80">Bulk Operations</td>
                            <td v-for="p in plans" :key="p.id" class="p-3 text-center">
                                <Check v-if="p.import_export" class="size-3 mx-auto text-primary" />
                                <X v-else class="size-3 mx-auto opacity-20" />
                            </td>
                        </tr>
                        <tr class="border-b last:border-0 hover:bg-muted/20 transition-colors">
                            <td class="p-3 font-medium uppercase tracking-tighter opacity-80">Infrastructure Gating</td>
                            <td v-for="p in plans" :key="p.id" class="p-3 text-center">
                                <Zap v-if="p.allows_dedicated_server" class="size-3 mx-auto text-primary fill-primary/20" />
                                <Check v-else class="size-3 mx-auto text-primary" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
