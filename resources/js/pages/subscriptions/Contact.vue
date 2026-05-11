<script setup lang="ts">
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { logout } from '@/routes';
import contactUs from '@/routes/contact-us';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle, Mail, CreditCard, ShieldCheck } from 'lucide-vue-next';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { computed } from 'vue';

import { toast } from 'vue-sonner';

const props = defineProps<{
    village: { id: number; name_en: string; subdomain: string } | null;
    plans: Array<{ id: string; name: string }>;
    error?: string;
}>();

const page = usePage();
const csrfToken = computed(() => page.props.csrf_token as string);

const form = useForm({
    plan: '',
    message: '',
});

const submit = () => {
    form.post(contactUs.store().url, {
        onSuccess: () => {
            form.reset();
            toast.success('Renewal request sent successfully! We will contact you soon.');
        },
        onError: () => toast.error('Failed to send renewal request. Please check the form.'),
    });
};

const handleSignOut = () => {
    // Standard form submission to avoid Inertia "modal" interception of the redirect
    // This happens because the logout redirects to the home page which might return a Blade view
    const formElement = document.createElement('form');
    formElement.method = 'POST';
    formElement.action = logout().url;
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken.value;
    
    formElement.appendChild(tokenInput);
    document.body.appendChild(formElement);
    formElement.submit();
};

defineOptions({
    layout: AuthLayout,
});
</script>

<template>
    <Head title="Subscription Renewal" />

    <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[500px]">
        <div class="flex flex-col space-y-2 text-center">
            <h1 class="text-3xl font-bold tracking-tight">Subscription Required</h1>
            <p class="text-sm text-muted-foreground">
                Your portal access is currently restricted. Please request a renewal to continue.
            </p>
        </div>

        <Alert v-if="error || form.errors.plan || form.errors.message" variant="destructive">
            <AlertCircle class="h-4 w-4" />
            <AlertTitle>Action Required</AlertTitle>
            <AlertDescription>
                {{ error || 'Please complete the form to request a renewal.' }}
            </AlertDescription>
        </Alert>

        <Card>
            <CardHeader>
                <CardTitle>Renewal Request</CardTitle>
                <CardDescription>
                    Select your preferred plan and we'll send you the payment details.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" class="space-y-6" novalidate>
                    <div class="space-y-2">
                        <Label>Target Village</Label>
                        <Input 
                            :value="village?.name_en ?? 'Village Portal'" 
                            disabled 
                            class="bg-muted font-medium"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label>Preferred Plan</Label>
                        <Select v-model="form.plan">
                            <SelectTrigger :error="form.errors.plan">
                                <SelectValue placeholder="Select a plan" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="plan in plans" :key="plan.id" :value="plan.id">
                                        {{ plan.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.plan" />
                    </div>

                    <div class="space-y-2">
                        <Label for="message">Message to Master Admin</Label>
                        <Textarea
                            id="message"
                            v-model="form.message"
                            placeholder="e.g. Please send the payment QR for the CI Pragati annual plan."
                            class="min-h-[100px]"
                            :error="form.errors.message"
                        />
                        <InputError :message="form.errors.message" />
                    </div>

                    <Button type="submit" class="w-full" :disabled="form.processing">
                        <Mail class="mr-2 h-4 w-4" />
                        Send Renewal Request
                    </Button>
                </form>
            </CardContent>
        </Card>

        <!-- Process Information -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-start space-x-3 rounded-lg border p-3 bg-muted/20">
                <CreditCard class="mt-1 h-4 w-4 text-primary" />
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Payment</h4>
                    <p class="text-[11px] text-muted-foreground leading-tight">Master Admin will share a secure payment QR or Bank details.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 rounded-lg border p-3 bg-muted/20">
                <ShieldCheck class="mt-1 h-4 w-4 text-primary" />
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Activation</h4>
                    <p class="text-[11px] text-muted-foreground leading-tight">Your portal will be reactivated instantly after payment verification.</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <Button variant="link" class="text-xs text-muted-foreground" @click="handleSignOut">
                Sign out of portal
            </Button>
        </div>
    </div>
</template>
