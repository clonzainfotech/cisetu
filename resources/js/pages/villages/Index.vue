<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectItemText,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Pagination from '@/components/Pagination.vue';
import debounce from 'lodash/debounce';
import { Pencil, Trash2, Search, UserPlus, ExternalLink, Copy, Building2, CheckCircle2, Clock, XCircle, Globe, Languages, RefreshCw, Palette, Key, Terminal } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { dashboard } from '@/routes';
import { index as indexVillages, store as storeVillage, update as updateVillage, destroy as destroyVillage, regenerateToken as regenerateVillageToken } from '@/routes/villages';

import { computed, ref, watch, onMounted } from 'vue';

type Props = {
    villages: {
        data: Array<{
            id: number;
            name_en: string;
            name_local: string;
            subdomain: string;
            logo_url: string | null;
            upi_id: string | null;
            upi_name: string | null;
            payment_note: string | null;
            whatsapp_number: string | null;
            subscription_status?: string;
            is_active: boolean;
            admin_email: string | null;
            password_length: number;
            api_token: string | null;
            district: { id: number; name_en: string; state: { id: number; name_en: string } };
        }>;
        links: Array<any>;
        total: number;
        from: number;
        to: number;
    };
    filters: {
        search: string | null;
        limit: number;
    };
    districts: Array<{ id: number; name_en: string; state_name_en: string }>;
    plans: Array<{ id: number; name: string; code: string }>;
};

const props = defineProps<Props>();

// Search & Pagination Logic
const search = ref(props.filters.search || '');
const limit = ref(String(props.filters.limit || 10));

const updateList = debounce(() => {
    router.get(indexVillages().url, { search: search.value, limit: limit.value }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, limit], () => updateList());

const editingVillage = ref<Props['villages']['data'][0] | null>(null);
const managingApiVillageId = ref<number | null>(null);

const activeVillage = computed(() => {
    const id = managingApiVillageId.value || editingVillage.value?.id;
    if (!id) return null;
    return props.villages.data.find(v => v.id === id) || (managingApiVillageId.value ? null : editingVillage.value);
});

const deleteVillage = ref<Props['villages']['data'][0] | null>(null);
const isRegenerating = ref(false);
const showToken = ref(false);

const handleRegenerateToken = () => {
    if (!activeVillage.value) return;
    
    if (confirm('Are you sure you want to regenerate this token? Any existing integrations will stop working immediately.')) {
        isRegenerating.value = true;
        router.post(regenerateVillageToken(activeVillage.value.id).url, {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.success('Security token updated successfully');
            },
            onFinish: () => {
                isRegenerating.value = false;
            }
        });
    }
};

const copyToClipboard = (text: string | null, message: string) => {
    if (!text || String(text).toLowerCase() === 'null') {
        toast.error('Error: Data is missing');
        return;
    }

    const cleanText = String(text).trim();

    // Try modern API first (for HTTPS)
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(cleanText).then(() => {
            toast.success(message);
        }).catch(() => {
            // If modern fails, the user can still use the click-to-select feature
            toast.info('Please click the token to select and press Cmd+C');
        });
    } else {
        // For HTTP: We highlight the text and try to copy it. 
        // If it fails, the user already has the text selected to manual copy.
        const toastId = toast.info('Copying...', { duration: 1000 });
        
        try {
            // We use a temporary textarea because copying from a readonly input can be flaky in some browsers
            const textArea = document.createElement("textarea");
            textArea.value = cleanText;
            textArea.style.position = "fixed";
            textArea.style.left = "0";
            textArea.style.top = "0";
            textArea.style.opacity = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            
            if (successful) {
                toast.dismiss(toastId);
                toast.success(message);
            } else {
                throw new Error();
            }
        } catch (err) {
            toast.dismiss(toastId);
            toast.info('Click the token box & press Cmd+C');
        }
    }
};

const isDeleteOpen = ref(false);

const openInNewTab = (subdomain: string) => {
    const domain = usePage().props.app_url.replace(/^https?:\/\//, '');
    window.open(`${window.location.protocol}//${subdomain}.${domain}`, '_blank');
};

const copyCredentials = (v: Props['villages']['data'][0]) => {
    const domain = usePage().props.app_url.replace(/^https?:\/\//, '');
    const text = `Village: ${v.name_en}
Website: https://${v.subdomain}.${domain}
Admin Email: ${v.admin_email || 'Not set'}`;

    copyToClipboard(text, 'Village details copied');
};

const form = useForm({
    district_id: '',
    name_en: '',
    name_local: '',
    subdomain: '',
    logo: null as File | null,
    upi_id: '',
    upi_name: '',
    payment_note: '',
    whatsapp_number: '',
    portal_template: 'classic',
    is_active: true,
    admin_name: '',
    admin_email: '',
    admin_password: '',
    password_length: 16,
    subscription_plan_id: '',
    subscription_start_at: new Date().toISOString().slice(0, 10),
    subscription_expires_at: new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toISOString().slice(0, 10),
});

const isSubdomainTouched = ref(false);
const portalTemplates = [
    { id: 'classic', name: 'Classic', desc: 'Traditional & trusted.', color: 'bg-slate-500' },
    { id: 'modern', name: 'Modern', desc: 'Clean & spacious.', color: 'bg-blue-600' },
    { id: 'minimal', name: 'Minimal', desc: 'Simple & fast.', color: 'bg-zinc-400' },
    { id: 'vibrant', name: 'Vibrant', desc: 'Bright & energetic.', color: 'bg-orange-500' },
    { id: 'eco', name: 'Eco', desc: 'Green & organic.', color: 'bg-emerald-600' },
    { id: 'royal', name: 'Royal', desc: 'Elegant & premium.', color: 'bg-amber-700' },
    { id: 'corporate', name: 'Corporate', desc: 'Professional & formal.', color: 'bg-indigo-900' },
    { id: 'dark', name: 'Dark', desc: 'Sleek & high-contrast.', color: 'bg-zinc-900' },
    { id: 'gradient', name: 'Gradient', desc: 'Modern & colorful.', color: 'bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500' },
    { id: 'glass', name: 'Glass', desc: 'Blur & transparency.', color: 'bg-sky-400' },
    { id: 'compact', name: 'Compact', desc: 'Dense & efficient.', color: 'bg-teal-700' },
    { id: 'simple', name: 'Simple', desc: 'Easy & accessible.', color: 'bg-slate-200' },
];

const isAdminNameTouched = ref(false);
const showPreview = ref(false);
const isAdminEmailTouched = ref(false);
const isTransliterating = ref(false);
const isLogoTouched = ref(false);
const gujaratiTyping = ref(true);

const generateRandomPassword = (length: number = 16): string => {
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    let retVal = '';
    const buf = new Uint32Array(length);
    window.crypto.getRandomValues(buf);
    for (let i = 0; i < length; ++i) {
        retVal += charset[buf[i] % charset.length];
    }
    return retVal;
};

const openFullPreview = (template: string) => {
    window.open(`/template-preview/${template}`, '_blank');
};

const logoPreviewUrl = computed(() => {
    if (typeof File !== 'undefined' && form.logo instanceof File) {
        return URL.createObjectURL(form.logo);
    }
    return '';
});

const logoVariants = computed(() => {
    if (!form.name_en) return [];
    const colors = ['#0056b3', '#134e4a', '#10b981', '#ef4444', '#f59e0b', '#6366f1', '#ec4899', '#111827'];
    const villageLabel = form.name_local || form.name_en;
    
    // Find district label
    const district = props.districts.find(d => String(d.id) === form.district_id);
    const districtLabel = district ? district.name_en : 'Mumbai City';
    
    return colors.map((color, i) => ({
        id: i,
        svg: `
            <svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Administrative Ring -->
                <circle cx="200" cy="200" r="190" fill="none" stroke="${color}" stroke-width="8" />
                
                <!-- Double Inner Guide System (Centering Guides) -->
                <circle cx="200" cy="200" r="172" fill="none" stroke="${color}" stroke-width="1.5" />
                <circle cx="200" cy="200" r="115" fill="none" stroke="${color}" stroke-width="1.2" stroke-dasharray="4,3" />
                
                <!-- Center Graphic Area (Mathematically Centered Village Scene) -->
                <g transform="translate(122, 180) scale(0.52)">
                    <!-- Sunrise in the background -->
                    <g transform="translate(160, 40)">
                        <circle cx="0" cy="0" r="25" fill="none" stroke="${color}" stroke-width="2.5" opacity="0.4" />
                        <path d="M-35 0 L-45 0 M35 0 L45 0 M0 -35 L0 -45 M-25 -25 L-32 -32 M25 -25 L32 -32" stroke="${color}" stroke-width="2" opacity="0.3" />
                    </g>

                    <!-- Flying Birds -->
                    <g opacity="0.6">
                        <path d="M30 -20 Q35 -25, 40 -20 Q45 -25, 50 -20" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" />
                        <path d="M60 -35 Q65 -40, 70 -35 Q75 -40, 80 -35" fill="none" stroke="${color}" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M220 -10 Q225 -15, 230 -10 Q235 -15, 240 -10" fill="none" stroke="${color}" stroke-width="1.5" stroke-linecap="round" />
                    </g>

                    <!-- Organic 'Natural' Tree with Depth -->
                    <g transform="translate(0, 0)">
                        <path d="M40 85 L40 145 M25 145 L55 145" stroke="${color}" stroke-width="8" stroke-linecap="round" />
                        <path d="M40 10 
                                 C55 5, 75 10, 85 25 
                                 C100 25, 110 45, 105 65 
                                 C100 85, 80 95, 60 90 
                                 C50 105, 20 105, 5 90 
                                 C-15 85, -15 60, -5 40 
                                 C-5 20, 15 5, 40 10 Z" 
                              fill="none" stroke="${color}" stroke-width="6" />
                        <path d="M25 40 Q40 30 55 40" fill="none" stroke="${color}" stroke-width="2.5" opacity="0.5" />
                        <path d="M30 65 Q45 55 60 65" fill="none" stroke="${color}" stroke-width="2.5" opacity="0.5" />
                        <path d="M65 45 Q75 40 85 50" fill="none" stroke="${color}" stroke-width="2" opacity="0.4" />
                    </g>
                    
                    <!-- Complex Village Infrastructure (4 Houses) -->
                    <g transform="translate(85, 45)">
                        <!-- House 1 (Main) -->
                        <path d="M0 45 L45 0 L90 45 L90 100 L0 100 Z" fill="none" stroke="${color}" stroke-width="6" />
                        <rect x="15" y="60" width="20" height="20" fill="none" stroke="${color}" stroke-width="4" />
                        <path d="M50 60 L75 60 L75 100 L50 100 Z" fill="none" stroke="${color}" stroke-width="4" />
                        
                        <!-- House 2 -->
                        <path d="M90 55 L115 30 L140 55 L140 100 L90 100" fill="none" stroke="${color}" stroke-width="6" />
                        <rect x="105" y="65" width="15" height="15" fill="none" stroke="${color}" stroke-width="3" />
                        
                        <!-- House 3 -->
                        <path d="M140 65 L160 45 L180 65 L180 100 L140 100" fill="none" stroke="${color}" stroke-width="6" />
                        
                        <!-- House 4 (New Outer Unit) -->
                        <path d="M180 75 L200 55 L220 75 L220 100 L180 100" fill="none" stroke="${color}" stroke-width="6" />
                        <path d="M195 85 L205 85 L205 100 L195 100 Z" fill="none" stroke="${color}" stroke-width="3" />
                    </g>

                    <!-- Detailed Ground Environment -->
                    <path d="M-40 145 L320 145" fill="none" stroke="${color}" stroke-width="6" stroke-linecap="round" />
                    <path d="M-10 160 L300 160" fill="none" stroke="${color}" stroke-width="3" opacity="0.3" stroke-dasharray="8,5" />
                    <path d="M-15 145 L-20 135 M0 145 L-2 138 M250 145 L255 135" stroke="${color}" stroke-width="2" opacity="0.4" />
                </g>

                <!-- Thin Side Plus Elements -->
                <path d="M48 200 L62 200 M55 193 L55 207" stroke="${color}" stroke-width="2.5" stroke-linecap="round" />
                <path d="M338 200 L352 200 M345 193 L345 207" stroke="${color}" stroke-width="2.5" stroke-linecap="round" />

                <!-- Vertically Centered Typography -->
                <path id="topTrack${i}" d="M56.5,200 A143.5,143.5 0 0,1 343.5,200" fill="none" />
                <text font-family="'Inter', sans-serif" font-size="30" font-weight="950" fill="${color}">
                    <textPath href="#topTrack${i}" startOffset="50%" text-anchor="middle" dy="25">
                        ગ્રામ પંચાયત ${villageLabel.toUpperCase()}
                    </textPath>
                </text>

                <path id="bottomTrack${i}" d="M56.5,200 A143.5,143.5 0 0,0 343.5,200" fill="none" />
                <text font-family="'Inter', sans-serif" font-size="22" font-weight="950" fill="${color}">
                    <textPath href="#bottomTrack${i}" startOffset="50%" text-anchor="middle" dy="-18">
                        તા. જિ. ${districtLabel}
                    </textPath>
                </text>
            </svg>
        `
    }));
});

const pickGeneratedLogo = async (svg: string) => {
    const blob = new Blob([svg], { type: 'image/svg+xml' });
    const file = new File([blob], `${form.name_en || 'village'}_logo.svg`, { type: 'image/svg+xml' });
    form.logo = file;
    isLogoTouched.value = true;
};

const transliterate = async (text: string) => {
    if (!text) return '';
    try {
        const response = await fetch(`https://inputtools.google.com/request?text=${text}&itc=gu-t-i0-und&num=1&cp=0&cs=1&ie=utf-8&oe=utf-8&app=demopage`);
        const data = await response.json();
        if (data[0] === 'SUCCESS') {
            return data[1][0][1][0];
        }
    } catch (error) {
        console.error('Transliteration failed:', error);
    }
    return '';
};

const debouncedTransliterate = debounce(async (newVal: string) => {
    if (!gujaratiTyping.value || !newVal) return;
    isTransliterating.value = true;
    const result = await transliterate(newVal);
    if (result) {
        form.name_local = result;
    }
    isTransliterating.value = false;
}, 500);

const handleManualTransliteration = async (e: KeyboardEvent) => {
    if (!gujaratiTyping.value) return;
    if (e.key !== ' ' && e.key !== 'Enter') return;

    const input = e.target as HTMLInputElement;
    const value = input.value;
    const selectionStart = input.selectionStart || 0;

    const textBeforeCursor = value.substring(0, selectionStart).trim();
    const words = textBeforeCursor.split(/\s+/);
    const lastWord = words[words.length - 1];

    if (lastWord && /^[a-zA-Z0-9]+$/.test(lastWord)) {
        isTransliterating.value = true;
        const result = await transliterate(lastWord);
        if (result) {
            const newValue = value.substring(0, selectionStart - lastWord.length) + result + value.substring(selectionStart);
            form.name_local = newValue;
            setTimeout(() => {
                input.setSelectionRange(selectionStart - lastWord.length + result.length, selectionStart - lastWord.length + result.length);
            }, 0);
        }
        isTransliterating.value = false;
    }
};

const editVillage = (village: Props['villages']['data'][0]) => {
    editingVillage.value = village;
    form.district_id = String(village.district.id);
    form.name_en = village.name_en;
    form.name_local = village.name_local || '';
    form.subdomain = village.subdomain;
    form.upi_id = village.upi_id || '';
    form.upi_name = village.upi_name || '';
    form.payment_note = village.payment_note || '';
    form.whatsapp_number = village.whatsapp_number || '';
    form.portal_template = village.portal_template;
    form.is_active = !!village.is_active;
    form.password_length = village.password_length || 16;
    form.subscription_plan_id = village.subscription_plan_id ? String(village.subscription_plan_id) : '';
    form.subscription_start_at = village.subscription_start_at || new Date().toISOString().slice(0, 10);
    form.subscription_expires_at = village.subscription_expires_at || '';
    form.logo = null;
    isLogoTouched.value = false;
};

const cancelEdit = () => {
    editingVillage.value = null;
    form.reset();
};

const submit = () => {
    if (editingVillage.value) {
        form.post(updateVillage(editingVillage.value.id).url, {
            forceFormData: true,
            onSuccess: () => {
                cancelEdit();
                toast.success('Village updated successfully');
            },
            onError: () => {
                toast.error('Failed to update village. Please check for errors.');
            }
        });
    } else {
        form.post(storeVillage().url, {
            forceFormData: true,
            onSuccess: () => {
                form.reset();
                isSubdomainTouched.value = false;
                isAdminNameTouched.value = false;
                isAdminEmailTouched.value = false;
                isLogoTouched.value = false;
                toast.success('Village deployed successfully');
            },
            onError: () => {
                toast.error('Failed to deploy village. Please check for errors.');
            }
        });
    }
};

const confirmDelete = (village: Props['villages']['data'][0]) => {
    deleteVillage.value = village;
    isDeleteOpen.value = true;
};

const doDelete = () => {
    if (!deleteVillage.value) return;
    router.delete(destroyVillage(deleteVillage.value.id).url, {
        onSuccess: () => {
            isDeleteOpen.value = false;
            deleteVillage.value = null;
            toast.success('Village deleted');
        },
    });
};

watch(() => form.name_en, (newVal) => {
    if (editingVillage.value) return;
    if (newVal) {
        if (!isSubdomainTouched.value) {
            form.subdomain = newVal.toLowerCase().replace(/[^a-z0-9]/g, '');
        }
        if (!isAdminNameTouched.value) {
            form.admin_name = `${newVal} Admin`;
        }
        debouncedTransliterate(newVal);
    }
});

watch(() => form.subdomain, (newVal) => {
    if (editingVillage.value) return;
    if (newVal && !isAdminEmailTouched.value) {
        const domain = usePage().props.app_url.replace(/^https?:\/\//, '');
        form.admin_email = `admin@${newVal}.${domain}`;
    }
});
watch(() => form.subscription_start_at, (newVal) => {
    if (newVal && !form.subscription_expires_at) {
        const date = new Date(newVal);
        date.setFullYear(date.getFullYear() + 1);
        form.subscription_expires_at = date.toISOString().slice(0, 10);
    }
});

watch(() => form.subscription_plan_id, (newVal) => {
    if (newVal && !form.subscription_expires_at && form.subscription_start_at) {
        const date = new Date(form.subscription_start_at);
        date.setFullYear(date.getFullYear() + 1);
        form.subscription_expires_at = date.toISOString().slice(0, 10);
    }
});

defineOptions({
    layout: () => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Villages' },
        ],
    }),
});

onMounted(() => {
    const prefill = usePage().props.inquiry_prefill as any;
    if (prefill) {
        form.name_en = prefill.village_name || '';
        
        // Find matching district if possible by name
        if (prefill.district_name) {
            const matchedDistrict = props.districts.find(d => d.name_en.toLowerCase().includes(prefill.district_name.toLowerCase()));
            if (matchedDistrict) {
                form.district_id = String(matchedDistrict.id);
            }
        }
        
        if (prefill.plan_id) {
            form.subscription_plan_id = String(prefill.plan_id);
        }
        
        form.whatsapp_number = prefill.phone || '';
        
        // Wait for subdomain auto-generation, then set email
        setTimeout(() => {
            form.admin_email = prefill.email || form.admin_email;
            form.admin_name = prefill.name || form.admin_name;
            isAdminNameTouched.value = !!prefill.name;
            isAdminEmailTouched.value = !!prefill.email;
        }, 100);
        
        // Ensure form is visible (if it was hidden in a modal, we'd open it, but here it's on the page)
        toast.info('Form pre-filled from Inquiry!');
        
        // Scroll to form
        document.getElementById('subdomain')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<template>
    <Head title="Villages" />

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <Heading
                variant="small"
                title="Villages"
                description="Manage and create village administrative units."
            />
            
            <div class="flex items-center space-x-3 rounded-full border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 px-4 py-1.5 shadow-sm transition-all">
                <Switch id="gu-typing" v-model:checked="gujaratiTyping" />
                <Label for="gu-typing" class="text-xs font-bold cursor-pointer flex items-center gap-2 text-zinc-900 dark:text-zinc-100">
                    <Languages class="h-4 w-4 text-zinc-500 dark:text-zinc-400" /> Gujarati Typing
                </Label>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:h-[calc(100dvh-14rem)] lg:grid-cols-2 lg:items-start lg:overflow-hidden">
            <Card class="lg:h-full lg:overflow-hidden">
                <CardHeader>
                    <div class="flex items-center justify-between gap-4">
                        <CardTitle>Villages list</CardTitle>
                        <div class="relative w-64">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input v-model="search" placeholder="Search..." class="h-9 pl-9" />
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="flex flex-col gap-3 lg:h-[calc(100%-5rem)] lg:overflow-hidden">
                    <div
                        v-if="villages.data.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        No villages found.
                    </div>

                    <div v-else class="min-h-0 flex-1 overflow-y-auto pr-1 flex flex-col gap-3">
                        <div
                            v-for="v in villages.data"
                            :key="v.id"
                            class="flex items-start justify-between gap-4 rounded-lg border p-4 hover:bg-muted/20 transition-colors"
                        >
                            <div class="min-w-0 flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border bg-background p-1">
                                    <img v-if="v.logo_url" :src="v.logo_url" class="h-full w-full object-contain" />
                                    <Building2 v-else class="h-5 w-5 text-muted-foreground" />
                                </div>
                                <div class="min-w-0">
                                    <div class="truncate font-medium">{{ v.name_en }}</div>
                                    <div class="truncate text-sm text-muted-foreground">
                                        {{ v.district.name_en }}, {{ v.district.state.name_en }}
                                    </div>
                                    <div class="text-[10px] text-blue-600 font-medium">{{ v.subdomain }}.{{ $page.props.app_url.replace(/^https?:\/\//, '') }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-1">
                                    <Button variant="ghost" size="icon" class="h-8 w-8" @click="openInNewTab(v.subdomain)">
                                        <ExternalLink class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" class="h-8 w-8" @click="copyCredentials(v)">
                                        <Copy class="h-4 w-4" />
                                    </Button>
                                    <Button v-if="$page.props.auth.user.is_super_master_admin || $page.props.auth.user.role === 'super_master_admin'" variant="ghost" size="icon" class="h-8 w-8 text-blue-600" title="API Access" @click="managingApiVillageId = v.id">
                                        <Terminal class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" class="h-8 w-8" @click="editVillage(v)">
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-destructive" @click="confirmDelete(v)">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                                <div :class="['text-[10px] px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider', v.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700']">
                                    {{ v.is_active ? 'Active' : 'Inactive' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="villages.data.length > 0"
                        class="sticky bottom-0 -mx-6 mt-1 flex items-center justify-between gap-3 border-t bg-background px-6 py-3"
                    >
                        <div class="text-xs text-muted-foreground">
                            Total: {{ villages.total }} records
                        </div>
                        <div class="flex items-center gap-2">
                            <Select v-model="limit">
                                <SelectTrigger class="h-10 w-auto gap-1 px-3 shadow-sm">
                                    <span class="text-sm font-bold">{{ limit }}</span>
                                    <span class="text-sm text-muted-foreground">per page</span>
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="10">10</SelectItem>
                                    <SelectItem value="25">25</SelectItem>
                                    <SelectItem value="50">50</SelectItem>
                                    <SelectItem value="100">100</SelectItem>
                                </SelectContent>
                            </Select>
                            <Pagination :meta="{ from: villages.from, to: villages.to, total: villages.total, links: villages.links }" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="lg:h-full lg:overflow-hidden">
                <CardHeader class="flex flex-row items-center justify-between border-b py-4">
                    <CardTitle>{{ editingVillage ? 'Edit village' : 'Add village' }}</CardTitle>
                    <Button v-if="editingVillage" variant="ghost" size="sm" @click="cancelEdit">Cancel</Button>
                </CardHeader>
                <CardContent class="lg:h-[calc(100%-4rem)] overflow-y-auto p-6 custom-scrollbar">
                    <form @submit.prevent="submit" class="grid gap-5" novalidate>
                        <div class="grid gap-2">
                            <Label>District</Label>
                            <Select v-model="form.district_id">
                                <SelectTrigger :error="form.errors.district_id">
                                    <SelectValue placeholder="Select district" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="d in districts"
                                            :key="d.id"
                                            :value="String(d.id)"
                                        >
                                            <SelectItemText>{{ d.name_en }} ({{ d.state_name_en }})</SelectItemText>
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.district_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="subdomain">Subdomain</Label>
                            <Input
                                id="subdomain"
                                v-model="form.subdomain"
                                placeholder="e.g. tithal"
                                required
                                :error="form.errors.subdomain"
                                @input="isSubdomainTouched = true"
                            />
                            <div class="text-[10px] text-blue-600 font-medium flex items-center gap-1">
                                <Globe class="h-3 w-3" />
                                {{ form.subdomain || 'subdomain' }}.{{ $page.props.app_url.replace(/^https?:\/\//, '') }}
                            </div>
                            <InputError :message="form.errors.subdomain" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="name_en">Village name (EN)</Label>
                            <Input
                                id="name_en"
                                v-model="form.name_en"
                                placeholder="e.g. Tithal"
                                required
                                :error="form.errors.name_en"
                            />
                            <InputError :message="form.errors.name_en" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="name_local">Village name (Local)</Label>
                                <span v-if="isTransliterating" class="text-[10px] text-blue-500 animate-pulse">Transliterating...</span>
                            </div>
                            <div class="relative">
                                <Input
                                    id="name_local"
                                    v-model="form.name_local"
                                    placeholder="e.g. તિથલ"
                                    class="pr-10"
                                    :error="form.errors.name_local"
                                    @keydown="handleManualTransliteration($event)"
                                />
                                <Languages class="absolute right-3 top-2.5 h-4 w-4 text-muted-foreground/50" />
                            </div>
                            <div class="text-[9px] text-muted-foreground px-1 font-bold italic">
                                Type in English and press [SPACE] to transliterate manually.
                            </div>
                            <InputError :message="form.errors.name_local" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="logo">Village Logo</Label>
                            <div class="flex items-center gap-4 mb-2">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl border bg-muted/20 p-2 overflow-hidden shadow-inner relative group">
                                    <template v-if="form.logo">
                                        <img v-if="typeof File !== 'undefined' && form.logo instanceof File" :src="logoPreviewUrl" class="h-full w-full object-contain" />
                                        <div v-else class="text-[8px] font-black text-emerald-600 text-center uppercase tracking-tighter">Seal<br>Ready</div>
                                    </template>
                                    <template v-else-if="editingVillage?.logo_url">
                                        <img :src="editingVillage.logo_url" class="h-full w-full object-contain" />
                                    </template>
                                    <Building2 v-else class="h-6 w-6 text-muted-foreground" />
                                    
                                    <div v-if="form.logo || editingVillage?.logo_url" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" @click="form.logo = null; isLogoTouched = true">
                                        <XCircle class="h-5 w-5 text-white" />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <input
                                        id="logo"
                                        type="file"
                                        accept="image/*"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer file:text-[10px] file:font-bold file:uppercase file:bg-muted/50 file:border-none file:rounded-md file:mr-4 file:px-3 shadow-sm"
                                        @change="(e) => { 
                                            const file = e.target.files[0];
                                            if (file && file.size > 2 * 1024 * 1024) {
                                                toast.error('File is too large! Maximum size is 2MB.');
                                                e.target.value = '';
                                                return;
                                            }
                                            isLogoTouched = true; 
                                            form.logo = file; 
                                        }"
                                    />
                                </div>
                            </div>
                            
                            <!-- Logo Generator Grid -->
                            <div v-if="form.name_en" class="space-y-3 p-4 rounded-xl border border-dashed bg-muted/5">
                                <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground flex items-center gap-2">
                                    <Palette class="h-3 w-3" /> Same-to-Same Stamp Suggestions
                                </div>
                                <div class="grid grid-cols-4 gap-3 sm:grid-cols-4">
                                    <button
                                        v-for="v in logoVariants"
                                        :key="v.id"
                                        type="button"
                                        class="group relative aspect-square rounded-xl border bg-card p-1.5 shadow-sm transition-all hover:scale-105 hover:border-primary hover:shadow-lg dark:bg-zinc-900"
                                        @click="pickGeneratedLogo(v.svg)"
                                    >
                                        <div class="h-full w-full overflow-hidden rounded-lg opacity-90 group-hover:opacity-100">
                                            <img :src="'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(v.svg)" class="h-full w-full object-contain" />
                                        </div>
                                    </button>
                                </div>
                                <p class="text-[8px] text-muted-foreground text-center">Double-ring Gram Panchayat seal (Clean District info).</p>
                            </div>
                            <InputError :message="form.errors.logo" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="whatsapp_number">WhatsApp Number</Label>
                            <Input
                                id="whatsapp_number"
                                v-model="form.whatsapp_number"
                                placeholder="e.g. 9876543210"
                                :error="form.errors.whatsapp_number"
                            />
                            <InputError :message="form.errors.whatsapp_number" />
                        </div>

                        <div class="grid gap-2 border-t pt-4 mt-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-1">Financial Setup</div>
                            <Label for="upi_id">UPI ID</Label>
                            <Input
                                id="upi_id"
                                v-model="form.upi_id"
                                placeholder="e.g. village@upi"
                                :error="form.errors.upi_id"
                            />
                            <InputError :message="form.errors.upi_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="upi_name">UPI Merchant Name</Label>
                            <Input
                                id="upi_name"
                                v-model="form.upi_name"
                                placeholder="e.g. Tithal Gram Panchayat"
                                :error="form.errors.upi_name"
                            />
                            <InputError :message="form.errors.upi_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="payment_note">Payment Instructions</Label>
                            <Input
                                id="payment_note"
                                v-model="form.payment_note"
                                placeholder="e.g. Please pay tax before 31st March"
                                :error="form.errors.payment_note"
                            />
                            <InputError :message="form.errors.payment_note" />
                        </div>

                        <div class="grid gap-2 border-t pt-4 mt-2">
                            <Label>Website Template</Label>
                            <Select v-model="form.portal_template">
                                <SelectTrigger :error="form.errors.portal_template">
                                    <SelectValue placeholder="Select perspective" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="t in portalTemplates" :key="t.id" :value="t.id">
                                        <div class="flex items-center gap-2">
                                            <div :class="['h-3 w-3 rounded-full', t.color]"></div>
                                            <span>{{ t.name }} Perspective</span>
                                        </div>
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <!-- Direct Live Preview -->
                            <div v-if="form.portal_template" class="mt-2 group relative">
                                <div class="rounded-lg border bg-muted/10 overflow-hidden aspect-video relative shadow-inner">
                                    <iframe 
                                        :key="form.portal_template"
                                        :src="`/template-preview/${form.portal_template}`" 
                                        class="w-full h-full border-none scale-[0.3] origin-top-left"
                                        style="width: 333%; height: 333%;"
                                    ></iframe>
                                    <div class="absolute inset-0 bg-transparent z-10"></div>
                                    <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                        <Button 
                                            type="button"
                                            variant="secondary" 
                                            size="xs" 
                                            class="h-6 text-[9px] font-bold shadow-sm"
                                            @click="openFullPreview(form.portal_template)"
                                        >
                                            <ExternalLink class="h-3 w-3 mr-1" /> FULL VIEW
                                        </Button>
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center justify-between px-1">
                                    <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest flex items-center gap-1">
                                        <div class="h-1 w-1 rounded-full bg-emerald-500 animate-pulse"></div>
                                        Live Preview: {{ portalTemplates.find(t => t.id === form.portal_template)?.name }}
                                    </span>
                                </div>
                            </div>
                            <InputError :message="form.errors.portal_template" />
                        </div>

                        <div class="grid gap-2 border-t pt-4 mt-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-1">Subscription Details</div>
                            
                            <div class="grid gap-2">
                                <Label>Subscription Plan</Label>
                                <Select v-model="form.subscription_plan_id">
                                    <SelectTrigger :error="form.errors.subscription_plan_id">
                                        <SelectValue placeholder="Select plan" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="p in plans"
                                                :key="p.id"
                                                :value="String(p.id)"
                                            >
                                                <SelectItemText>{{ p.name }} ({{ p.code }})</SelectItemText>
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.subscription_plan_id" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label for="subscription_start_at">Start Date</Label>
                                    <Input
                                        id="subscription_start_at"
                                        v-model="form.subscription_start_at"
                                        type="date"
                                        required
                                        :error="form.errors.subscription_start_at"
                                    />
                                    <InputError :message="form.errors.subscription_start_at" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="subscription_expires_at">Auto End Date</Label>
                                    <Input
                                        id="subscription_expires_at"
                                        v-model="form.subscription_expires_at"
                                        type="date"
                                        required
                                        :error="form.errors.subscription_expires_at"
                                    />
                                    <InputError :message="form.errors.subscription_expires_at" />
                                </div>
                            </div>
                        </div>

                        <div v-if="!editingVillage" class="mt-4 rounded-lg border border-dashed p-4 space-y-4 bg-muted/10 shadow-inner">
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground flex items-center gap-2">
                                <UserPlus class="h-3 w-3" /> Administrative Provisioning
                            </div>
                            <div class="grid gap-2">
                                <Label for="admin_name">Admin Full Name</Label>
                                <Input
                                    id="admin_name"
                                    v-model="form.admin_name"
                                    required
                                    :error="form.errors.admin_name"
                                    @input="isAdminNameTouched = true"
                                />
                                <InputError :message="form.errors.admin_name" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="admin_email">Primary Admin Email</Label>
                                <Input
                                    id="admin_email"
                                    v-model="form.admin_email"
                                    type="email"
                                    required
                                    :error="form.errors.admin_email"
                                    @input="isAdminEmailTouched = true"
                                />
                                <InputError :message="form.errors.admin_email" />
                            </div>
                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="admin_password">Secure Access Key</Label>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5 bg-muted/20 px-2 py-0.5 rounded-md border shadow-inner">
                                            <span class="text-[8px] font-black uppercase text-muted-foreground">Len</span>
                                            <input 
                                                v-model.number="form.password_length" 
                                                type="number" 
                                                min="8" 
                                                max="64" 
                                                class="w-10 bg-transparent border-none p-0 text-[10px] font-bold focus:ring-0 text-center"
                                            />
                                        </div>
                                        <span v-if="form.admin_password" class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                                            {{ form.admin_password.length }} CHARS
                                        </span>
                                        <button 
                                            type="button" 
                                            class="text-[10px] text-blue-600 font-black uppercase tracking-widest hover:text-blue-700 transition-colors flex items-center gap-1" 
                                            @click="form.admin_password = generateRandomPassword(form.password_length)"
                                        >
                                            <RefreshCw class="h-3 w-3" /> Regenerate
                                        </button>
                                    </div>
                                </div>
                                <Input
                                    id="admin_password"
                                    v-model="form.admin_password"
                                    class="font-mono tracking-widest shadow-sm"
                                    required
                                    :error="form.errors.admin_password"
                                />
                                <InputError :message="form.errors.admin_password" />
                            </div>
                        </div>

                        <!-- Security Configuration (Super Master Admin Only) -->
                        <div v-if="activeVillage && !managingApiVillageId && ($page.props.auth.user.is_super_master_admin || $page.props.auth.user.role === 'super_master_admin')" class="grid gap-2 border-t pt-4 mt-2">
                            <Label for="password_length">Admin Password Length Requirement</Label>
                            <div class="flex items-center gap-4">
                                <Input
                                    id="password_length"
                                    v-model.number="form.password_length"
                                    type="number"
                                    min="6"
                                    max="32"
                                    required
                                    class="w-24"
                                    :error="form.errors.password_length"
                                />
                                <span class="text-xs text-muted-foreground">Enforces minimum complexity for all village users.</span>
                            </div>
                            <InputError :message="form.errors.password_length" />
                        </div>

                        <div class="pt-4 pb-2 border-t mt-4">
                            <Button type="submit" :disabled="form.processing" class="w-full bg-[#134e4a] hover:bg-[#0e5d6a] h-12 text-sm font-bold uppercase tracking-widest shadow-lg">
                                {{ editingVillage ? 'Save Node Configuration' : 'Deploy Virtual Village' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

        <ConfirmDialog
            :open="isDeleteOpen"
            title="Delete village?"
            :description="deleteVillage ? `This will permanently delete ${deleteVillage.name_en}.` : ''"
            confirm-text="Delete"
            confirm-variant="destructive"
            @update:open="isDeleteOpen = $event"
            @confirm="doDelete"
        />

        <!-- API ACCESS MODAL -->
        <Dialog :open="!!managingApiVillageId" @update:open="(val) => !val && (managingApiVillageId = null)">
            <DialogContent class="sm:max-w-xl p-0 overflow-hidden border-none shadow-2xl">
                <div class="bg-white dark:bg-zinc-950">
                    <!-- Global Theme Header -->
                    <div class="bg-[#134e4a] p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                                <Terminal class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold tracking-tight">{{ activeVillage?.name_en }}</h2>
                                <p class="text-emerald-100/70 text-[10px] font-black uppercase tracking-[0.2em]">API INFRASTRUCTURE GATEWAY</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="activeVillage" class="p-6 space-y-6">
                        <!-- Token Section -->
                        <div class="rounded-2xl border bg-zinc-50/50 dark:bg-zinc-900/30 p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full" :class="activeVillage.api_token ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-300'"></div>
                                    <Label class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Master Data Token</Label>
                                </div>
                                <span v-if="activeVillage.api_token" class="text-[8px] font-black bg-emerald-500/10 text-emerald-600 px-2 py-0.5 rounded border border-emerald-500/20 uppercase">Encrypted</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 group">
                                    <Input
                                        type="text"
                                        :value="activeVillage.api_token || 'Token not found...'"
                                        readonly
                                        @click="(e) => (e.target as HTMLInputElement).select()"
                                        class="h-11 font-mono bg-zinc-50 dark:bg-black border-zinc-200 dark:border-zinc-800 text-[13px] focus:ring-2 focus:ring-emerald-500 pr-10 overflow-ellipsis cursor-copy transition-all group-hover:bg-emerald-50/50"
                                    />
                                    <div class="absolute right-3 top-3.5">
                                        <div class="h-4 w-4 rounded-full bg-emerald-500 animate-pulse border-2 border-white shadow-sm"></div>
                                    </div>
                                </div>
                                <Button 
                                    v-if="activeVillage.api_token"
                                    variant="outline" 
                                    class="h-11 px-4 border-zinc-200 hover:bg-zinc-100"
                                    @click="copyToClipboard(activeVillage.api_token, 'API Token copied')"
                                >
                                    <Copy class="h-4 w-4" />
                                </Button>
                                <Button 
                                    variant="outline" 
                                    class="h-11 px-4 border-[#134e4a] text-[#134e4a] hover:bg-emerald-50 font-bold text-xs uppercase tracking-widest disabled:opacity-50"
                                    :disabled="isRegenerating"
                                    @click="handleRegenerateToken"
                                >
                                    <RefreshCw class="h-4 w-4 mr-2" :class="{ 'animate-spin': isRegenerating }" /> 
                                    {{ activeVillage.api_token ? 'REFRESH' : 'GENERATE' }}
                                </Button>
                            </div>
                        </div>

                        <!-- Playground / API Documentation -->
                        <div v-if="activeVillage.api_token" class="space-y-4 animate-in fade-in duration-700">
                            <div class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                <div class="h-px flex-1 bg-zinc-100"></div>
                                DEVELOPER PLAYGROUND
                                <div class="h-px flex-1 bg-zinc-100"></div>
                            </div>

                            <div class="grid gap-3">
                                <!-- Details Endpoint -->
                                <div class="p-4 rounded-xl border border-zinc-100 bg-zinc-50/30 hover:border-emerald-200 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">GET</span>
                                            <span class="text-[11px] font-bold text-zinc-700">Dynamic Details Search</span>
                                        </div>
                                        <Button variant="ghost" size="xs" class="h-7 text-[9px] font-black text-[#134e4a] hover:bg-emerald-50" @click="() => {
                                            const token = activeVillage?.api_token;
                                            if (token) {
                                                const protocol = window.location.protocol;
                                                const domain = $page.props.app_url.replace(/^https?:\/\//, '');
                                                const url = `${protocol}//${activeVillage.subdomain}.${domain}/api/v1/details?receipt=YOUR_NO&token=${token}`;
                                                copyToClipboard(url, 'Test URL copied');
                                            }
                                        }">COPY LINK</Button>
                                    </div>
                                    <p class="text-[10px] font-mono text-zinc-400 truncate">{{ activeVillage.subdomain }}.{{ $page.props.app_url.replace(/^https?:\/\//, '') }}/api/v1/details...</p>
                                </div>

                                <!-- Shops Endpoint -->
                                <div class="p-4 rounded-xl border border-zinc-100 bg-zinc-50/30 hover:border-emerald-200 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">GET</span>
                                            <span class="text-[11px] font-bold text-zinc-700">Shops Master List</span>
                                        </div>
                                        <Button variant="ghost" size="xs" class="h-7 text-[9px] font-black text-[#134e4a] hover:bg-emerald-50" @click="() => {
                                            const token = activeVillage?.api_token;
                                            if (token) {
                                                const protocol = window.location.protocol;
                                                const domain = $page.props.app_url.replace(/^https?:\/\//, '');
                                                const url = `${protocol}//${activeVillage.subdomain}.${domain}/api/v1/shops?token=${token}`;
                                                copyToClipboard(url, 'Shops URL copied');
                                            }
                                        }">COPY LINK</Button>
                                    </div>
                                    <p class="text-[10px] font-mono text-zinc-400 truncate">{{ activeVillage.subdomain }}.{{ $page.props.app_url.replace(/^https?:\/\//, '') }}/api/v1/shops...</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100">
                                <p class="text-[10px] text-amber-800 leading-relaxed font-medium">
                                    <strong>Production Recommendation:</strong> Always use the <code class="px-1.5 py-0.5 bg-amber-100 rounded font-bold">X-Village-Token</code> header for actual API integrations to keep the token out of server logs.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
        
    </div>
</template>
