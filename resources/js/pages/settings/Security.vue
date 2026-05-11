<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { ShieldCheck } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { edit } from '@/routes/security';
import { disable, enable } from '@/routes/two-factor';
import { toast } from 'vue-sonner';

type Props = {
    canManageTwoFactor?: boolean;
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    canManageTwoFactor: false,
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

defineOptions({
    layout: () => ({
        breadcrumbs: [
            {
                title: 'Security settings',
                href: edit(),
            },
        ],
    }),
});

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    passwordForm.put(SecurityController.update.url(), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            toast.success('Password updated successfully');
        },
        onError: () => toast.error('Failed to update password. Please check for errors.'),
    });
};

const enable2faForm = useForm({});
const enable2fa = () => {
    enable2faForm.post(enable.url(), {
        onSuccess: () => {
            showSetupModal.value = true;
        },
    });
};

const disable2faForm = useForm({});
const disable2fa = () => {
    disable2faForm.delete(disable.url(), {
        onSuccess: () => {
            toast.success('Two-factor authentication disabled');
        },
    });
};

onUnmounted(() => clearTwoFactorAuthData());
</script>

<template>
    <Head title="Security settings" />

    <h1 class="sr-only">Security settings</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Update password"
            description="Ensure your account is using a long, random password to stay secure"
        />

        <form @submit.prevent="updatePassword" class="space-y-6" novalidate>
            <div class="grid gap-2">
                <Label for="current_password">Current password</Label>
                <PasswordInput
                    id="current_password"
                    v-model="passwordForm.current_password"
                    name="current_password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    placeholder="Current password"
                    :error="passwordForm.errors.current_password"
                />
                <InputError :message="passwordForm.errors.current_password" />
            </div>

            <div class="grid gap-2">
                <Label for="password">New password</Label>
                <PasswordInput
                    id="password"
                    v-model="passwordForm.password"
                    name="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    placeholder="New password"
                    :error="passwordForm.errors.password"
                />
                <InputError :message="passwordForm.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm password</Label>
                <PasswordInput
                    id="password_confirmation"
                    v-model="passwordForm.password_confirmation"
                    name="password_confirmation"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    :error="passwordForm.errors.password_confirmation"
                />
                <InputError :message="passwordForm.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <Button
                    :disabled="passwordForm.processing"
                    data-test="update-password-button"
                >
                    Save password
                </Button>
            </div>
        </form>
    </div>

    <div v-if="canManageTwoFactor" class="pt-10 space-y-6 border-t mt-10">
        <Heading
            variant="small"
            title="Two-factor authentication"
            description="Add an extra layer of security to your account"
        />

        <div
            v-if="!twoFactorEnabled"
            class="flex flex-col items-start justify-start space-y-6 bg-muted/20 p-6 rounded-xl border border-dashed"
        >
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-full bg-primary/10 border border-primary/20">
                    <ShieldCheck class="size-6 text-primary" />
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-bold text-foreground">Enhanced account protection</p>
                    <p class="text-sm text-muted-foreground max-w-xl">
                        When you enable two-factor authentication, you will be prompted
                        for a secure pin during login. This pin can be retrieved from a
                        TOTP-supported application on your phone like Google Authenticator or Authy.
                    </p>
                </div>
            </div>

            <div>
                <Button v-if="hasSetupData" @click="showSetupModal = true" class="gap-2">
                    <ShieldCheck class="size-4" />Continue setup
                </Button>
                <form
                    v-else
                    @submit.prevent="enable2fa"
                >
                    <Button type="submit" :disabled="enable2faForm.processing" class="gap-2">
                        <ShieldCheck class="size-4" /> Enable 2FA
                    </Button>
                </form>
            </div>
        </div>

        <div v-else class="space-y-8">
            <div class="flex flex-col items-start justify-start space-y-6 bg-emerald-500/5 p-6 rounded-xl border border-emerald-500/20">
                <div class="flex items-start gap-4">
                    <div class="p-3 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                        <ShieldCheck class="size-6 text-emerald-600" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-emerald-900 dark:text-emerald-400">Two-factor authentication is active</p>
                        <p class="text-sm text-emerald-800/70 dark:text-emerald-400/70 max-w-xl">
                            Your account is protected by an additional security layer. You will be prompted for a secure, random pin from your authenticator app during every login.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="disable2fa">
                    <Button
                        variant="destructive"
                        type="submit"
                        size="sm"
                        :disabled="disable2faForm.processing"
                        class="gap-2"
                    >
                        Disable 2FA
                    </Button>
                </form>
            </div>

            <TwoFactorRecoveryCodes />
        </div>

        <TwoFactorSetupModal
            v-model:isOpen="showSetupModal"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />
    </div>
</template>
