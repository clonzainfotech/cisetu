<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectItemText,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { dashboard } from '@/routes';
import { update as updateSubscription } from '@/routes/subscriptions';
import { toast } from 'vue-sonner';

type Props = {
    village: { id: number; token: string; name_en: string; subdomain: string };
    subscription: null | {
        plan_id: number;
        status: string;
        starts_at: string | null;
        ends_at: string | null;
        grace_ends_at: string | null;
        billing_reference: string | null;
    };
    plans: Array<{ id: number; code: string; name: string; max_user_accounts: number | null }>;
    history: Array<{
        id: number;
        event_type: string;
        previous_ends_at: string | null;
        new_ends_at: string | null;
        note: string | null;
        created_at: string;
    }>;
};

const props = defineProps<Props>();

defineOptions({
    layout: (props: { village: { name_en: string } }) => ({
        breadcrumbs: [
            { title: 'Dashboard', href: dashboard().url },
            { title: 'Subscriptions', href: '/subscriptions' },
            { title: props.village.name_en },
        ],
    }),
});

const today = new Date().toISOString().slice(0, 10);
</script>

<template>
    <Head :title="`Subscription · ${village.name_en}`" />

    <div class="flex flex-col gap-8">
        <Heading
            variant="small"
            :title="village.name_en"
            :description="`${village.subdomain}.${$page.props.app_url.replace(/^https?:\/\//, '')}`"
        />

        <div class="grid grid-cols-1 gap-6 lg:h-[calc(100dvh-14rem)] lg:grid-cols-2 lg:items-start lg:overflow-hidden">
            <Card class="lg:sticky lg:top-6 lg:self-start">
                <CardHeader>
                    <CardTitle>Update subscription</CardTitle>
                </CardHeader>
                <CardContent>
                    <Form
                        :action="updateSubscription(village.token).url"
                        method="post"
                        class="grid gap-5"
                        novalidate
                        @success="toast.success('Subscription updated successfully')"
                        @error="toast.error('Failed to update subscription. Please check for errors.')"
                        v-slot="{ errors, processing }"
                    >
                        <input type="hidden" name="_method" value="PUT" />

                        <div class="grid gap-2">
                            <Label>Plan</Label>
                            <Select
                                name="plan_id"
                                :default-value="subscription?.plan_id ? String(subscription.plan_id) : undefined"
                            >
                                <SelectTrigger :error="errors.plan_id">
                                    <SelectValue placeholder="Select plan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="p in plans"
                                            :key="p.id"
                                            :value="String(p.id)"
                                        >
                                            <SelectItemText>
                                                {{ p.name }} ({{ p.code }})
                                            </SelectItemText>
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.plan_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label>Status</Label>
                            <Select
                                name="status"
                                :default-value="subscription?.status ?? 'active'"
                            >
                                <SelectTrigger :error="errors.status">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="active">
                                            <SelectItemText>Active</SelectItemText>
                                        </SelectItem>
                                        <SelectItem value="grace">
                                            <SelectItemText>Grace</SelectItemText>
                                        </SelectItem>
                                        <SelectItem value="expired">
                                            <SelectItemText>Expired</SelectItemText>
                                        </SelectItem>
                                        <SelectItem value="suspended">
                                            <SelectItemText>Suspended</SelectItemText>
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.status" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="starts_at">Start date</Label>
                            <Input
                                id="starts_at"
                                name="starts_at"
                                type="date"
                                :default-value="subscription?.starts_at ?? today"
                                required
                                :error="errors.starts_at"
                            />
                            <InputError :message="errors.starts_at" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="ends_at">End date (expiry)</Label>
                            <Input
                                id="ends_at"
                                name="ends_at"
                                type="date"
                                :default-value="subscription?.ends_at ?? today"
                                required
                                :error="errors.ends_at"
                            />
                            <InputError :message="errors.ends_at" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="grace_ends_at">Grace end date</Label>
                            <Input
                                id="grace_ends_at"
                                name="grace_ends_at"
                                type="date"
                                :default-value="subscription?.grace_ends_at ?? ''"
                                :error="errors.grace_ends_at"
                            />
                            <InputError :message="errors.grace_ends_at" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="note">Note</Label>
                            <Input id="note" name="note" placeholder="Optional note" :error="errors.note" />
                            <InputError :message="errors.note" />
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <Button type="submit" :disabled="processing">
                                Save subscription
                            </Button>
                        </div>
                    </Form>
                </CardContent>
            </Card>

            <Card class="lg:h-full lg:overflow-hidden">
                <CardHeader>
                    <CardTitle>History</CardTitle>
                </CardHeader>
                <CardContent class="space-y-3 lg:h-[calc(100%-5rem)] lg:overflow-y-auto">
                    <div
                        v-if="history.length === 0"
                        class="rounded-lg border border-dashed p-6 text-sm text-muted-foreground"
                    >
                        No history yet.
                    </div>

                    <div
                        v-for="h in history"
                        :key="h.id"
                        class="rounded-lg border p-4 text-sm"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div class="font-medium capitalize">
                                {{ h.event_type }}
                            </div>
                            <div class="text-muted-foreground">
                                {{ h.created_at }}
                            </div>
                        </div>
                        <div class="mt-2 text-muted-foreground">
                            End: {{ h.previous_ends_at ?? '-' }} → {{ h.new_ends_at ?? '-' }}
                        </div>
                        <div v-if="h.note" class="mt-2">
                            {{ h.note }}
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

