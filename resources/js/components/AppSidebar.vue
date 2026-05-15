<script setup lang="ts">
import { Link, useHttp, usePage } from '@inertiajs/vue3';
import { 
    CreditCard, 
    LayoutGrid, 
    MapPin, 
    MapPinned, 
    Users, 
    Home as HomeIcon, 
    Store,
    ChevronDown,
    Building2,
    MessageSquare
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { dashboard } from '@/routes';
import { index as usersIndex } from '@/routes/managed-users';
import { index as villagesIndex } from '@/routes/villages';
import { index as districtsIndex } from '@/routes/districts';
import { index as subscriptionsIndex } from '@/routes/subscriptions';
import { index as homesIndex } from '@/routes/homes';
import { index as shopsIndex } from '@/routes/shops';
import { index as inquiriesIndex } from '@/routes/inquiries';
import { plans } from '@/routes/subscriptions';
// These routes need to be defined in wayfinder or imported manually
// Since I just added them to web.php, I'll use strings or update wayfinder
// Assuming wayfinder will pick them up after build
import type { NavItem } from '@/types';

const page = usePage();

const dashboardUrl = computed(() => dashboard().url);
const auth = computed(() => page.props.auth as any);
const currentVillage = computed(() => page.props.village as any);

const isSuperAdmin = computed(() => auth.value.user?.is_super_master_admin || auth.value.user?.role === 'super_master_admin');
const isVillageAdmin = computed(() => auth.value.user?.role === 'village_admin');

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboardUrl.value,
            icon: LayoutGrid,
        }
    ];

    if (isSuperAdmin.value) {
        items.push(
            {
                title: 'Users',
                href: usersIndex().url,
                icon: Users,
            },
            {
                title: 'Inquiries',
                href: inquiriesIndex().url,
                icon: MessageSquare,
            },
            {
                title: 'Subscriptions',
                href: subscriptionsIndex().url,
                icon: CreditCard,
            },
            {
                title: 'Districts',
                href: districtsIndex().url,
                icon: MapPinned,
            },
            {
                title: 'Villages',
                href: villagesIndex().url,
                icon: MapPin,
            }
        );
    }

    if (currentVillage.value) {
        const userPermissions = auth.value.user?.permissions || [];
        const hasPersonalTax = isSuperAdmin.value || isVillageAdmin.value || userPermissions.includes('personal_tax');
        const hasProfessionalTax = isSuperAdmin.value || isVillageAdmin.value || userPermissions.includes('professional_tax');

        if (hasPersonalTax) {
            items.push({
                title: 'Homes (Personal)',
                href: homesIndex().url,
                icon: HomeIcon,
            });
        }

        if (hasProfessionalTax) {
            items.push({
                title: 'Shops (Prof.)',
                href: shopsIndex().url,
                icon: Store,
            });
        }

        // Add Team Management and Plans for Village Admins
        if (isVillageAdmin.value && !isSuperAdmin.value) {
            items.push({
                title: 'Manage Team',
                href: usersIndex().url,
                icon: Users,
            });
            
            items.push({
                title: 'Pricing Plans',
                href: plans().url,
                icon: CreditCard,
            });
        }
    } else if (auth.value.user?.village_id && !isSuperAdmin.value) {
        // Fallback for Village Admins if currentVillage is not injected but they have village_id
        items.push({
            title: 'Manage Team',
            href: usersIndex().url,
            icon: Users,
        });
        
        items.push({
            title: 'Pricing Plans',
            href: plans().url,
            icon: CreditCard,
        });
    }

    return items;
});

const http = useHttp();
const villageQuery = ref('');
const villageResults = ref<any[]>([]);
const isSearchingVillages = ref(false);

let searchTimeout: number | undefined;
const searchVillages = (): void => {
    if (searchTimeout) {
        window.clearTimeout(searchTimeout);
    }

    searchTimeout = window.setTimeout(async () => {
        const q = villageQuery.value.trim();

        if (!q) {
            villageResults.value = [];
            return;
        }

        isSearchingVillages.value = true;

        try {
            villageResults.value = (await http.get(
                `/api/villages/search?q=${encodeURIComponent(q)}`,
            )) as any[];
        } catch {
            villageResults.value = [];
        } finally {
            isSearchingVillages.value = false;
        }
    }, 250);
};

const switchVillage = (village: any) => {
    const protocol = window.location.protocol;
    const baseDomain = (page.props as any).app_url?.replace(/^https?:\/\//, '') || 'cis.test';
    const newHost = `${village.subdomain}.${baseDomain}`;

    window.location.href = `${protocol}//${newHost}/dashboard`;
};

const switchToMainAdmin = () => {
    const protocol = window.location.protocol;
    const baseDomain = (page.props as any).app_url?.replace(/^https?:\/\//, '') || 'cis.test';
    
    console.log('Switching to main admin:', baseDomain);
    window.location.href = `${protocol}//${baseDomain}/dashboard`;
};

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <!-- Village Identity / Switcher -->
                <SidebarMenuItem v-if="currentVillage || isSuperAdmin">
                    <DropdownMenu v-if="isSuperAdmin">
                        <DropdownMenuTrigger as-child>
                            <SidebarMenuButton
                                size="lg"
                                class="text-foreground data-[state=open]:bg-sidebar-accent data-[state=open]:text-foreground"
                            >
                                <div
                                    class="flex aspect-square size-10 items-center justify-center overflow-hidden rounded-lg border border-border bg-white text-foreground"
                                >
                                    <img
                                        v-if="currentVillage?.logo"
                                        :src="'/storage/' + currentVillage.logo"
                                        :alt="currentVillage.name_en"
                                        class="size-full object-contain p-1"
                                    />
                                    <AppLogoIcon v-else class="size-7 text-foreground" />
                                </div>
                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate text-[10px] font-black tracking-widest text-zinc-500 uppercase">
                                        {{ currentVillage ? 'Village Node' : 'System Context' }}
                                    </span>
                                    <span class="truncate font-bold text-foreground">
                                        {{ currentVillage?.name_en || 'Main Admin Portal' }}
                                    </span>
                                </div>
                                <ChevronDown class="ml-auto size-4 text-muted-foreground" />
                            </SidebarMenuButton>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent
                            class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                            align="start"
                            side="bottom"
                            :side-offset="4"
                        >
                            <DropdownMenuLabel class="text-xs text-muted-foreground">
                                Village Context Switcher
                            </DropdownMenuLabel>
                            
                            <!-- Option to switch back to Main Admin -->
                            <DropdownMenuItem 
                                v-if="currentVillage"
                                @click="switchToMainAdmin"
                                class="gap-2 p-2 font-medium text-primary"
                            >
                                <div class="flex size-6 items-center justify-center rounded-sm border bg-primary/5">
                                    <Building2 class="size-4 shrink-0 text-primary" />
                                </div>
                                Switch to Main Admin
                            </DropdownMenuItem>
                            <DropdownMenuSeparator v-if="currentVillage" />

                            <div class="p-2">
                                <Input
                                    v-model="villageQuery"
                                    placeholder="Search village name…"
                                    @input="searchVillages"
                                />
                                <p v-if="isSearchingVillages" class="mt-2 text-[10px] text-muted-foreground animate-pulse">
                                    Searching database…
                                </p>
                            </div>
                            
                            <div class="max-h-[300px] overflow-y-auto">
                                <DropdownMenuItem
                                    v-for="v in villageResults"
                                    :key="v.id"
                                    @click="switchVillage(v)"
                                    class="gap-2 p-2"
                                    :disabled="currentVillage?.id === v.id"
                                >
                                    <div class="flex size-6 items-center justify-center rounded-sm border">
                                        <MapPin class="size-4 shrink-0" />
                                    </div>
                                    <span class="flex-1 truncate">{{ v.name_en }}</span>
                                    <span v-if="currentVillage?.id === v.id" class="text-[8px] font-black uppercase text-primary bg-primary/10 px-1 rounded">Active</span>
                                </DropdownMenuItem>
                            </div>

                            <p v-if="villageQuery.trim().length > 0 && villageResults.length === 0 && !isSearchingVillages" class="p-4 text-center text-xs text-muted-foreground">
                                No villages found.
                            </p>
                            <p v-else-if="villageQuery.trim().length === 0 && !currentVillage" class="p-4 text-center text-[10px] text-muted-foreground italic">
                                Start typing to switch village context
                            </p>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    
                    <!-- Village Admin static header -->
                    <SidebarMenuButton v-else size="lg" as-child class="hover:bg-transparent">
                        <Link :href="dashboardUrl">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>

                <!-- Regular CISETU Logo for Master Admin Home -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboardUrl">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- NavFooter removed (Repository/Documentation) -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
