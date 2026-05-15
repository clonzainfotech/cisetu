<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Toaster } from '@/components/ui/sonner';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative min-h-screen bg-background">
        <Toaster />
        
        <div
            class="grid h-dvh lg:max-w-none lg:grid-cols-2 lg:px-0"
        >
            <!-- Left Panel: Branding & Stats (Robust Adaptive Theme) -->
            <div 
                class="auth-left-panel relative hidden h-full flex-col p-12 text-white lg:flex overflow-hidden transition-colors duration-500"
            >
            <!-- High-Fidelity Doodles Layer -->
            <div 
                class="absolute inset-0 pointer-events-none z-0 opacity-15"
                style="background-image: url('/images/bg-pattern.png'); background-repeat: repeat; background-size: 600px; mix-blend-mode: soft-light;"
            ></div>
            
            <!-- Branding Content -->
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center mb-20">
                    <AppLogoIcon class="h-10 w-auto" white />
                </div>

                <div class="mt-auto mb-20 max-w-lg">
                    <h2 class="text-6xl font-serif-premium mb-8 leading-[1.1] text-white">
                        Digital villages, <br/>
                        <span class="italic opacity-80 font-normal">connected</span> at the scale of your community.
                    </h2>
                    <p class="text-white/70 text-lg leading-relaxed font-medium">
                        The CiSeTU platform for modern rural administration — automated, secure, and built for a transparent future.
                    </p>
                </div>

                <!-- Stats Bar -->
                <div class="mt-auto grid grid-cols-3 gap-8 border-t border-white/10 pt-8">
                    <div>
                        <div class="text-3xl font-bold mb-1 text-white">12.4k</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-white/50">Villages Active</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-1 text-white">98%</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-white/50">Service Rate</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-1 text-white">24/7</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-white/50">Admin Support</div>
                    </div>
                </div>

                <div class="mt-12 flex items-center justify-between text-[11px] font-medium text-white/40 uppercase tracking-widest">
                    <div>&copy; {{ new Date().getFullYear() }} CiSeTU</div>
                    <div>Made for CiSeTU</div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Form (Robust Adaptive Theme) -->
        <div class="auth-right-panel relative flex h-full items-center justify-center p-6 lg:p-16 overflow-y-auto transition-colors duration-500">
            <!-- High-Fidelity Doodles Layer -->
            <div 
                class="absolute inset-0 pointer-events-none z-0 opacity-[0.05]"
                style="background-image: url('/images/bg-pattern.png'); background-repeat: repeat; background-size: 600px; mix-blend-mode: multiply;"
            ></div>
            
            <!-- Mobile Navigation -->
            <div class="absolute top-6 left-6 right-6 flex items-center justify-between z-20 lg:hidden">
                <Link :href="home()" class="flex items-center">
                    <AppLogoIcon class="h-8 w-auto text-foreground" />
                </Link>
            </div>

            <!-- Form Content -->
            <div class="relative z-10 mx-auto flex w-full flex-col justify-center space-y-12 py-12 sm:w-[440px]">
                <div class="flex flex-col space-y-4 text-center lg:text-left">
                    <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Sign In</div>
                    <h1 class="text-5xl font-serif-premium tracking-tight text-foreground" v-if="title">
                        Welcome back.
                    </h1>
                    <p class="text-lg text-muted-foreground leading-relaxed" v-if="description">
                        {{ description }}
                    </p>
                </div>
                
                <!-- Form Area -->
                <div class="relative">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</div>
</template>
