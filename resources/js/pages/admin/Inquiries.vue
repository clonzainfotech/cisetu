<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { 
    MessageSquare, 
    Search, 
    Filter, 
    MoreHorizontal, 
    Clock, 
    CheckCircle, 
    XCircle,
    User,
    Mail,
    Phone,
    MapPin,
    Calendar,
    ArrowRight,
    Trash2,
    ChevronDown
} from 'lucide-vue-next';
import { 
    Card, 
    CardContent, 
    CardDescription, 
    CardHeader, 
    CardTitle 
} from '@/components/ui/card';
import { 
    Table, 
    TableBody, 
    TableCell, 
    TableHead, 
    TableHeader, 
    TableRow 
} from '@/components/ui/table';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuLabel, 
    DropdownMenuSeparator, 
    DropdownMenuTrigger 
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update as updateInquiry, destroy as destroyInquiryAction, index as inquiriesIndex } from '@/routes/inquiries';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Separator } from '@/components/ui/separator';
import { computed, ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { dashboard } from '@/routes';

const props = defineProps<{
    inquiries: {
        data: Array<{
            id: number;
            type: 'demo' | 'subscription';
            name: string;
            email: string;
            phone: string;
            village_name: string;
            district_name: string;
            plan?: { name: string };
            message: string;
            status: 'pending' | 'contacted' | 'completed' | 'rejected';
            created_at: string;
        }>;
        links: any[];
        total: number;
        from: number;
        to: number;
    };
    filters: {
        search: string | null;
        status: string | null;
        type: string | null;
    };
}>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const updateStatus = (id: number, status: string) => {
    router.patch(updateInquiry(id).url, { status }, {
        preserveScroll: true,
    });
};

const deleteId = ref<number | null>(null);
const isDeleteOpen = ref(false);

const deleteInquiry = (id: number) => {
    deleteId.value = id;
    isDeleteOpen.value = true;
};

const doDelete = () => {
    if (!deleteId.value) return;
    router.delete(destroyInquiryAction(deleteId.value).url, {
        preserveScroll: true,
        onFinish: () => {
            deleteId.value = null;
        },
    });
};

const getStatusBadge = (status: string) => {
    switch (status) {
        case 'pending': return { variant: 'warning', icon: Clock, label: 'Pending' };
        case 'contacted': return { variant: 'secondary', icon: MessageSquare, label: 'Contacted' };
        case 'completed': return { variant: 'success', icon: CheckCircle, label: 'Completed' };
        case 'rejected': return { variant: 'destructive', icon: XCircle, label: 'Rejected' };
        default: return { variant: 'outline', icon: Clock, label: status };
    }
};

// Search + filters
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || 'all');
const typeFilter = ref(props.filters.type || 'all');

const activeFilterCount = computed(() => {
    let count = 0;
    if (statusFilter.value && statusFilter.value !== 'all') count++;
    if (typeFilter.value && typeFilter.value !== 'all') count++;
    return count;
});

const updateList = debounce(() => {
    const params: any = {};
    if (search.value) params.search = search.value;
    if (statusFilter.value && statusFilter.value !== 'all') params.status = statusFilter.value;
    if (typeFilter.value && typeFilter.value !== 'all') params.type = typeFilter.value;

    router.get(inquiriesIndex().url, params, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
}, 400);

watch([search, statusFilter, typeFilter], () => updateList());

// Details drawer/modal (client-side)
const selectedInquiry = ref<(typeof props.inquiries.data)[number] | null>(null);
const isDetailsOpen = ref(false);
const openDetails = (inquiry: (typeof props.inquiries.data)[number]) => {
    selectedInquiry.value = inquiry;
    isDetailsOpen.value = true;
};

// Email Dialog
const isEmailOpen = ref(false);
import { useForm } from '@inertiajs/vue3';
const emailForm = useForm({
    subject: 'Update on your CISETU Inquiry',
    message: 'Hello,\n\nThank you for reaching out to us regarding your village node deployment. We have reviewed your request and would like to proceed with the next steps.\n\nPlease let us know when you are available for a brief discussion.\n\nBest regards,\nCISETU Operations Team'
});

const openEmail = (inquiry: (typeof props.inquiries.data)[number]) => {
    selectedInquiry.value = inquiry;
    isEmailOpen.value = true;
};

const sendEmail = () => {
    if (!selectedInquiry.value) return;
    emailForm.post(`/inquiries-list/${selectedInquiry.value.id}/email`, {
        onSuccess: () => {
            isEmailOpen.value = false;
        }
    });
};

defineOptions({
    layout: () => ({
        breadcrumbs: [{ title: 'Dashboard', href: dashboard().url }, { title: 'Inquiries' }],
    }),
});
</script>

<template>
    <Head title="Inquiry Management" />

    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                variant="small"
                title="Inquiry Management"
                description="Manage demo and subscription requests from the platform"
            />
        </div>

        <Card class="overflow-hidden border-none shadow-sm ring-1 ring-border">
            <CardHeader class="border-b bg-muted/30 px-6 py-4">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <CardTitle class="text-base font-bold uppercase tracking-wider text-foreground/70">Incoming Stream</CardTitle>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="relative w-full sm:w-64">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                type="search"
                                placeholder="Search leads..."
                                class="pl-9 h-9 text-xs ring-0 focus-visible:ring-1"
                                v-model="search"
                            />
                        </div>
                        <Select v-model="statusFilter">
                            <SelectTrigger class="w-[130px] h-9 text-xs">
                                <SelectValue placeholder="Status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Statuses</SelectItem>
                                <SelectItem value="pending">Pending</SelectItem>
                                <SelectItem value="contacted">Contacted</SelectItem>
                                <SelectItem value="completed">Completed</SelectItem>
                                <SelectItem value="rejected">Rejected</SelectItem>
                            </SelectContent>
                        </Select>
                        <Select v-model="typeFilter">
                            <SelectTrigger class="w-[130px] h-9 text-xs">
                                <SelectValue placeholder="Type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Types</SelectItem>
                                <SelectItem value="demo">Demo</SelectItem>
                                <SelectItem value="subscription">Subscription</SelectItem>
                            </SelectContent>
                        </Select>
                        <Button 
                            v-if="activeFilterCount > 0" 
                            variant="ghost" 
                            size="sm" 
                            class="h-9 px-2 text-xs text-muted-foreground" 
                            @click="statusFilter = 'all'; typeFilter = 'all'"
                        >
                            Clear
                        </Button>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader class="bg-muted/20">
                        <TableRow>
                            <TableHead class="w-[200px] text-[10px] font-black uppercase tracking-widest py-4">Prospect Details</TableHead>
                            <TableHead class="text-[10px] font-black uppercase tracking-widest">Village Node</TableHead>
                            <TableHead class="text-[10px] font-black uppercase tracking-widest">Request Profile</TableHead>
                            <TableHead class="text-[10px] font-black uppercase tracking-widest text-center">Status</TableHead>
                            <TableHead class="w-[100px] text-right"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="inquiries.data.length === 0">
                            <TableCell colspan="5" class="h-32 text-center text-muted-foreground">
                                <div class="flex flex-col items-center gap-2">
                                    <MessageSquare class="h-8 w-8 opacity-20" />
                                    <p class="text-sm font-medium">No inquiries found in the stream.</p>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-for="inquiry in inquiries.data" :key="inquiry.id" class="group transition-colors">
                            <TableCell class="py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="font-bold text-foreground">{{ inquiry.name }}</span>
                                    <div class="flex items-center gap-2 text-[10px] text-muted-foreground font-medium">
                                        <Mail class="h-3 w-3" />
                                        {{ inquiry.email }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-muted-foreground font-medium">
                                        <Phone class="h-3 w-3" />
                                        {{ inquiry.phone }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell>
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2 text-xs font-bold text-foreground">
                                        <MapPin class="h-3 w-3 text-primary" />
                                        {{ inquiry.village_name }}
                                    </div>
                                    <span class="text-[10px] text-muted-foreground uppercase tracking-widest pl-5">{{ inquiry.district_name }}</span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <Badge :variant="inquiry.type === 'subscription' ? 'default' : 'secondary'" class="text-[9px] font-black uppercase px-2 h-5">
                                            {{ inquiry.type }}
                                        </Badge>
                                        <span v-if="inquiry.plan" class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">
                                            {{ inquiry.plan.name }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-muted-foreground font-medium">
                                        <Calendar class="h-3 w-3" />
                                        {{ formatDate(inquiry.created_at) }}
                                    </div>
                                </div>
                            </TableCell>
                            <TableCell class="text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="sm" class="h-7 px-2 gap-2 text-[10px] font-black uppercase tracking-widest ring-0 focus-visible:ring-0">
                                            <component :is="getStatusBadge(inquiry.status).icon" class="h-3.5 w-3.5" :class="`text-${getStatusBadge(inquiry.status).variant}`" />
                                            {{ getStatusBadge(inquiry.status).label }}
                                            <ChevronDown class="h-3 w-3 opacity-50" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-40">
                                        <DropdownMenuLabel class="text-[10px] font-black uppercase tracking-widest opacity-50">Transition Status</DropdownMenuLabel>
                                        <DropdownMenuItem @click="updateStatus(inquiry.id, 'pending')" class="gap-2 text-xs">
                                            <Clock class="h-3.5 w-3.5 text-amber-500" /> Pending
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(inquiry.id, 'contacted')" class="gap-2 text-xs">
                                            <MessageSquare class="h-3.5 w-3.5 text-blue-500" /> Contacted
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(inquiry.id, 'completed')" class="gap-2 text-xs">
                                            <CheckCircle class="h-3.5 w-3.5 text-emerald-500" /> Completed
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="updateStatus(inquiry.id, 'rejected')" class="gap-2 text-xs">
                                            <XCircle class="h-3.5 w-3.5 text-destructive" /> Rejected
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8 ring-0 focus-visible:ring-0">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-48">
                                        <DropdownMenuLabel class="text-[10px] font-black uppercase tracking-widest opacity-50">Node Actions</DropdownMenuLabel>
                                        <DropdownMenuItem class="gap-2 text-xs" @click="openEmail(inquiry)">
                                            <Mail class="h-3.5 w-3.5" /> Email Contact
                                        </DropdownMenuItem>
                                        <DropdownMenuItem class="gap-2 text-xs" @click="openDetails(inquiry)">
                                            <ArrowRight class="h-3.5 w-3.5" /> View Details
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem @click="deleteInquiry(inquiry.id)" class="gap-2 text-xs text-destructive focus:bg-destructive/10 focus:text-destructive">
                                            <Trash2 class="h-3.5 w-3.5" /> Purge Record
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>

        <div class="mt-4 flex justify-center">
            <Pagination :meta="{ from: inquiries.from, to: inquiries.to, total: inquiries.total, links: inquiries.links }" />
        </div>

        <ConfirmDialog
            :open="isDeleteOpen"
            title="Purge inquiry?"
            description="This will permanently delete the inquiry record."
            confirm-text="Purge"
            confirm-variant="destructive"
            @update:open="isDeleteOpen = $event"
            @confirm="doDelete"
        />

        <Dialog :open="isDetailsOpen" @update:open="isDetailsOpen = $event">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2 text-xl">
                        <MessageSquare class="h-5 w-5 text-primary" />
                        Inquiry Details
                    </DialogTitle>
                    <DialogDescription>
                        Complete information submitted for this village deployment node.
                    </DialogDescription>
                </DialogHeader>

                <div v-if="selectedInquiry" class="grid gap-6 py-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Prospect Name</span>
                            <div class="flex items-center gap-2 font-bold">
                                <User class="h-4 w-4 text-muted-foreground" />
                                {{ selectedInquiry.name }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Contact Email</span>
                            <div class="flex items-center gap-2 font-medium">
                                <Mail class="h-4 w-4 text-muted-foreground" />
                                {{ selectedInquiry.email }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Phone Number</span>
                            <div class="flex items-center gap-2 font-medium">
                                <Phone class="h-4 w-4 text-muted-foreground" />
                                {{ selectedInquiry.phone }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Submission Date</span>
                            <div class="flex items-center gap-2 font-medium">
                                <Calendar class="h-4 w-4 text-muted-foreground" />
                                {{ formatDate(selectedInquiry.created_at) }}
                            </div>
                        </div>
                    </div>

                    <Separator />

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Village Node</span>
                            <div class="flex items-center gap-2 font-bold text-primary">
                                <MapPin class="h-4 w-4" />
                                {{ selectedInquiry.village_name }}
                            </div>
                            <p class="text-xs text-muted-foreground pl-6 uppercase tracking-wider">{{ selectedInquiry.district_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Request Profile</span>
                            <div class="flex items-center gap-2">
                                <Badge :variant="selectedInquiry.type === 'subscription' ? 'default' : 'secondary'" class="text-[9px] font-black uppercase px-2 h-5 flex items-center">
                                    {{ selectedInquiry.type }}
                                </Badge>
                                <span v-if="selectedInquiry.plan" class="text-[10px] font-bold text-emerald-600 uppercase">
                                    {{ selectedInquiry.plan.name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 rounded-lg bg-muted/50 p-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground opacity-70">Technical Message / Requirements</span>
                        <p class="text-sm leading-relaxed text-foreground/80">
                            {{ selectedInquiry.message || 'No technical requirements were specified in the initial inquiry.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-muted-foreground">Current Status:</span>
                            <Badge :variant="getStatusBadge(selectedInquiry.status).variant as any" class="gap-1.5 py-1">
                                <component :is="getStatusBadge(selectedInquiry.status).icon" class="h-3.5 w-3.5" />
                                {{ getStatusBadge(selectedInquiry.status).label }}
                            </Badge>
                        </div>
                        <div class="flex gap-2">
                            <Button variant="outline" size="sm" @click="isDetailsOpen = false">Close</Button>
                            <Button size="sm" class="gap-2" @click="() => { isDetailsOpen = false; openEmail(selectedInquiry!); }">
                                <Mail class="h-4 w-4" />
                                Send Email
                            </Button>
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>

        <Dialog :open="isEmailOpen" @update:open="isEmailOpen = $event">
            <DialogContent class="max-w-xl">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2 text-xl">
                        <Mail class="h-5 w-5 text-primary" />
                        Compose Email to {{ selectedInquiry?.name }}
                    </DialogTitle>
                    <DialogDescription>
                        Send an automated or customized message directly to <span class="font-bold text-foreground">{{ selectedInquiry?.email }}</span>.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="subject">Subject Line</Label>
                        <Input id="subject" v-model="emailForm.subject" placeholder="Email subject..." />
                        <span v-if="emailForm.errors.subject" class="text-[10px] text-destructive">{{ emailForm.errors.subject }}</span>
                    </div>
                    <div class="grid gap-2">
                        <Label for="message">Message Body (ZeptoMail Template Wrapper)</Label>
                        <textarea
                            id="message"
                            v-model="emailForm.message"
                            rows="8"
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Type your message here..."
                        ></textarea>
                        <span v-if="emailForm.errors.message" class="text-[10px] text-destructive">{{ emailForm.errors.message }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-muted-foreground italic">Email will be sent via ZeptoMail integration.</span>
                    <div class="flex gap-2">
                        <Button variant="outline" @click="isEmailOpen = false" :disabled="emailForm.processing">Cancel</Button>
                        <Button @click="sendEmail" class="gap-2" :disabled="emailForm.processing">
                            <Mail class="h-4 w-4" />
                            {{ emailForm.processing ? 'Sending...' : 'Send Message' }}
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>

<style scoped>
.text-warning { color: #f59e0b; }
.text-success { color: #10b981; }
.text-destructive { color: #ef4444; }
.text-secondary { color: #3b82f6; }
</style>
