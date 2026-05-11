<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10 bg-background bg-doodle-gray"
    >
        <div class="w-full max-w-[400px]">
            <div class="flex flex-col gap-10">
                <div class="flex flex-col items-center gap-6">
                    <Link
                        :href="home()"
                        class="transition-transform duration-300 hover:scale-105"
                    >
                         <AppLogoIcon class="h-12 w-auto" />
                    </Link>
                    
                    <div class="space-y-1.5 text-center">
                        <h1 class="text-2xl font-bold tracking-tight text-foreground">
                            {{ title }}
                        </h1>
                        <p class="text-base text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <slot />
                </div>
                
                <p class="text-center text-xs text-muted-foreground/60 font-medium">
                    &copy; {{ new Date().getFullYear() }} CiSeTU. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>
