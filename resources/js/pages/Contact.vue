<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Check, 
    ChevronDown, 
    Mail, 
    MessageSquare, 
    Phone, 
    Send,
    Building2,
    MapPin,
    ShieldCheck
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    plans: any[];
}>();

const form = useForm({
    type: 'demo',
    name: '',
    email: '',
    phone: '',
    village_name: '',
    district_name: '',
    plan_id: '',
    message: '',
});

const submit = () => {
    form.post(route('inquiries.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};

</script>

<template>
    <Head title="CISETU - Contact Connectivity Hub" />

    <div class="min-h-screen bg-white text-slate-900 font-sans selection:bg-slate-900 selection:text-white relative overflow-hidden">
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('/images/bg-pattern.png')] opacity-[0.03] pointer-events-none"></div>
        
        <!-- Background Gradients -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-emerald-50 rounded-full blur-[120px] -z-10 opacity-60"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-50 rounded-full blur-[100px] -z-10 opacity-40"></div>

        <!-- Navigation -->
        <nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[calc(100%-3rem)] max-w-6xl z-50">
            <div class="flex items-center justify-between px-8 py-3 rounded-2xl bg-white/80 backdrop-blur-xl border border-slate-200 shadow-xl shadow-slate-200/50">
                <Link href="/" class="flex items-center group gap-2">
                    <ArrowLeft class="w-4 h-4 text-slate-400 group-hover:text-slate-950 transition-colors" />
                    <img src="/images/logo.svg" alt="CISETU" class="h-6 w-auto filter brightness-[0.2] transition-all group-hover:brightness-0">
                </Link>
                
                <div class="flex items-center gap-6">
                    <Link href="/login" class="text-[11px] font-black uppercase tracking-widest text-slate-600 hover:text-slate-950 transition-colors">Portal Access</Link>
                </div>
            </div>
        </nav>

        <main class="pt-40 pb-24 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-start">
                    <!-- Left Side: Information -->
                    <div>
                        <div class="inline-flex items-center gap-3 bg-white border border-slate-200 px-4 py-1.5 rounded-full mb-10 shadow-sm animate-fade-in-up">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Infrastructure Support</span>
                        </div>

                        <h1 class="text-5xl md:text-7xl font-black tracking-tight text-slate-950 mb-10 leading-[0.95] uppercase">
                            Establish <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">Connectivity.</span>
                        </h1>

                        <p class="text-xl text-slate-600 max-w-lg mb-16 leading-relaxed font-medium">
                            Ready to modernize your village administration? Submit your details and our technical experts will establish a secure communication channel.
                        </p>

                        <div class="space-y-8">
                            <div class="flex items-center gap-6 group">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-emerald-500 shadow-sm group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                    <MessageSquare class="w-7 h-7" />
                                </div>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Response Time</div>
                                    <div class="text-lg font-black text-slate-950 uppercase">Within 120 Minutes</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-6 group">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-blue-500 shadow-sm group-hover:bg-blue-500 group-hover:text-white transition-all">
                                    <Mail class="w-7 h-7" />
                                </div>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Direct Email</div>
                                    <div class="text-lg font-black text-slate-950 uppercase">support@cisetu.com</div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badge -->
                        <div class="mt-20 p-8 rounded-[2.5rem] bg-slate-50 border border-slate-100 flex items-center gap-6 max-w-sm">
                            <ShieldCheck class="w-12 h-12 text-slate-300" />
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed">
                                Your information is protected by enterprise-grade encryption protocol.
                            </p>
                        </div>
                    </div>

                    <!-- Right Side: Contact Form -->
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-emerald-100 rounded-[4rem] blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                        <div class="relative bg-white border border-slate-200 rounded-[3.5rem] p-10 md:p-14 shadow-4xl overflow-hidden">
                            <form @submit.prevent="submit" class="space-y-8">
                                <!-- Request Type Selection -->
                                <div class="flex p-1.5 bg-slate-50 rounded-2xl border border-slate-100">
                                    <button 
                                        type="button"
                                        @click="form.type = 'demo'"
                                        class="flex-1 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all"
                                        :class="form.type === 'demo' ? 'bg-white text-slate-950 shadow-sm border border-slate-100' : 'text-slate-400 hover:text-slate-600'"
                                    >
                                        Demo Request
                                    </button>
                                    <button 
                                        type="button"
                                        @click="form.type = 'subscription'"
                                        class="flex-1 py-4 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all"
                                        :class="form.type === 'subscription' ? 'bg-white text-slate-950 shadow-sm border border-slate-100' : 'text-slate-400 hover:text-slate-600'"
                                    >
                                        Subscription Request
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Full Name</label>
                                        <input 
                                            v-model="form.name"
                                            type="text" 
                                            placeholder="Enter your name"
                                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none"
                                            required
                                        >
                                    </div>
                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Email Console</label>
                                        <input 
                                            v-model="form.email"
                                            type="email" 
                                            placeholder="admin@village.com"
                                            class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Village -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Village Name</label>
                                        <div class="relative">
                                            <Building2 class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" />
                                            <input 
                                                v-model="form.village_name"
                                                type="text" 
                                                placeholder="Target Village"
                                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none"
                                                required
                                            >
                                        </div>
                                    </div>
                                    <!-- District -->
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">District Name</label>
                                        <div class="relative">
                                            <MapPin class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" />
                                            <input 
                                                v-model="form.district_name"
                                                type="text" 
                                                placeholder="Geographic District"
                                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Plan (if subscription) -->
                                <div v-if="form.type === 'subscription'" class="space-y-2 animate-fade-in">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Target Infrastructure Tier</label>
                                    <select 
                                        v-model="form.plan_id"
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none appearance-none cursor-pointer"
                                        required
                                    >
                                        <option value="" disabled>Select a tier</option>
                                        <option v-for="plan in plans" :key="plan.id" :value="plan.id">{{ plan.name }} - ₹{{ plan.price_per_year_inr }}/year</option>
                                    </select>
                                </div>

                                <!-- Message -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Additional Context (Optional)</label>
                                    <textarea 
                                        v-model="form.message"
                                        rows="4" 
                                        placeholder="Any specific requirements or questions..."
                                        class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-slate-950/5 focus:border-slate-950 transition-all outline-none resize-none"
                                    ></textarea>
                                </div>

                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-slate-950 text-white py-6 rounded-3xl font-black text-sm uppercase tracking-[0.2em] hover:bg-slate-800 transition-all flex items-center justify-center gap-4 shadow-2xl shadow-slate-950/20 active:scale-95 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Establishing Link...' : 'Submit Request' }}
                                    <Send v-if="!form.processing" class="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Professional Footer -->
        <footer class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-6 md:px-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="flex flex-col md:flex-row items-center gap-16">
                        <img src="/images/logo.svg" alt="Logo" class="h-8 w-auto filter brightness-0 opacity-80">
                        <div class="flex gap-10 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                            <a href="#" class="hover:text-slate-950 transition-colors">Privacy Protocol</a>
                            <a href="#" class="hover:text-slate-950 transition-colors">Service Level Agreement</a>
                            <a href="#" class="hover:text-slate-950 transition-colors">Documentation</a>
                        </div>
                    </div>
                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                        &copy; 2026 CISETU. INFRASTRUCTURE BY DESIGN.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.shadow-4xl {
    box-shadow: 0 70px 150px -30px rgba(0, 0, 0, 0.15);
}

@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-up {
    animation: fade-in-up 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-fade-in {
    animation: fade-in 0.5s ease forwards;
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
