<script setup lang="ts">
import { computed } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { toast } from 'vue-sonner';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

const page = usePage();
defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

type InertiaErrors =
    | Record<string, string | string[]>
    | { default?: Record<string, string | string[]> }
    | undefined;

const normalizedPageErrors = computed<Record<string, string>>(() => {
    const raw = (page.props.errors as InertiaErrors) ?? {};

    const bag = 'default' in raw && raw.default ? raw.default : raw;
    const out: Record<string, string> = {};

    for (const [key, value] of Object.entries(bag)) {
        if (Array.isArray(value)) {
            out[key] = value[0] ?? '';
            continue;
        }

        out[key] = value ?? '';
    }

    return out;
});

const allErrors = computed(() => {
    return {
        ...normalizedPageErrors.value,
        ...form.errors,
    };
});

const alertErrors = computed(() => {
    const fieldKeys = new Set(['email', 'password', 'remember']);

    return Object.entries(allErrors.value)
        .filter(([key]) => !fieldKeys.has(key))
        .map(([, message]) => message)
        .filter(Boolean);
});

const submit = () => {
    form.post(store().url, {
        onSuccess: (page) => {
            if ((page.props as any).two_factor) {
                router.visit('/two-factor-challenge');
            }
        },
        onError: () => toast.error('Invalid credentials. Please check your email and password.'),
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-6" novalidate>
        <AlertError
            v-if="alertErrors.length > 0"
            :errors="alertErrors"
            title="Authentication System"
        />

        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    v-model="form.email"
                    name="email"
                    type="email"
                    required
                    autofocus
                    tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    :error="allErrors.email"
                />
                <InputError :message="allErrors.email" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-sm"
                        tabindex="5"
                    >
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    name="password"
                    required
                    tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                    :error="allErrors.password"
                />
                <InputError :message="allErrors.password" />
            </div>

            <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                    <Checkbox id="remember" v-model:checked="form.remember" name="remember" tabindex="3" />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="mt-4 w-full"
                tabindex="4"
                :disabled="form.processing"
                data-test="login-button"
            >
                <Spinner v-if="form.processing" />
                Log in
            </Button>
        </div>

        <div
            class="text-center text-sm text-muted-foreground"
            v-if="canRegister"
        >
            Don't have an account?
            <TextLink href="/register" tabindex="5">Sign up</TextLink>
        </div>
    </form>
</template>
