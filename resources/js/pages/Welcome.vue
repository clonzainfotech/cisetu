<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { login, home } from '@/routes';
import { toast, Toaster } from 'vue-sonner';

const props = defineProps<{
    canRegister: boolean;
    plans: any[];
}>();

// --- STATE ---
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const isChatOpen = ref(false);
const chatInput = ref('');
const chatMsgs = ref<{ text: string; role: 'bot' | 'user'; time: string }[]>([]);
const showSuggestions = ref(true);
const chatbotConfig = ref<any>(null);
const isTyping = ref(false);
const isPlanDropdownOpen = ref(false);

const districtSearch = ref('');
const districtSuggestions = ref<{id: number, name_en: string}[]>([]);
const isDistrictDropdownOpen = ref(false);
const districtLoading = ref(false);
let districtTimeout: any = null;

const handleDistrictInput = () => {
    clearTimeout(districtTimeout);
    form.district_name = districtSearch.value;
    isDistrictDropdownOpen.value = true;
    
    if (districtSearch.value.length < 2) {
        districtSuggestions.value = [];
        return;
    }
    
    districtLoading.value = true;
    districtTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`/api/districts/search?q=${encodeURIComponent(districtSearch.value)}`);
            districtSuggestions.value = await res.json();
        } catch (e) {
            console.error('Failed to load districts', e);
        } finally {
            districtLoading.value = false;
        }
    }, 300);
};

const selectDistrict = (name: string) => {
    districtSearch.value = name;
    form.district_name = name;
    isDistrictDropdownOpen.value = false;
};

const selectedPlanLabel = computed(() => {
    const p = props.plans.find(p => p.id === form.plan_id);
    return p ? `${p.name} — ₹${Math.floor(p.price_per_year_inr).toLocaleString()}` : 'Select a plan';
});

// --- CURSOR LOGIC ---
const mx = ref(0);
const my = ref(0);
const rx = ref(0);
const ry = ref(0);
const curSize = ref({ w: 10, h: 10, bg: '#1a7d90', rw: 36, rh: 36 });

const updateCursor = (e: MouseEvent) => {
    mx.value = e.clientX;
    my.value = e.clientY;
};

const animateCursor = () => {
    rx.value += (mx.value - rx.value) * 0.1;
    ry.value += (my.value - ry.value) * 0.1;
    requestAnimationFrame(animateCursor);
};

const setCursorHover = (hover: boolean) => {
    if (hover) {
        curSize.value = { w: 20, h: 20, bg: '#72d41a', rw: 50, rh: 50 };
    } else {
        curSize.value = { w: 10, h: 10, bg: '#1a7d90', rw: 36, rh: 36 };
    }
};

// --- SCROLL PROGRESS ---
const scrollProgress = ref(0);
const updateScroll = () => {
    const p = window.scrollY / (document.body.scrollHeight - window.innerHeight);
    scrollProgress.value = p;
    isScrolled.value = window.scrollY > 50;
};

// --- CHATBOT LOGIC ---
const fetchChatbotConfig = async () => {
    try {
        const res = await fetch('/chatbot.json');
        chatbotConfig.value = await res.json();
    } catch (e) {
        console.error('Failed to load chatbot config', e);
    }
};

// --- INQUIRY FORM ---
const form = useForm({
    type: 'subscription',
    name: '',
    email: '',
    phone: '',
    village_name: '',
    district_name: '',
    plan_id: '',
    message: '',
});

const submitInquiry = () => {
    form.post('/inquiries', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Inquiry submitted successfully!', {
                description: 'Our technical team will contact you shortly.',
                position: 'top-right',
            });
        },
        onError: (errors) => {
            const firstErr = Object.values(errors)[0];
            toast.error('Submission Failed', {
                description: firstErr || 'Please check the required fields and try again.',
                position: 'top-right',
            });
        }
    });
};

const addMsg = (text: string, role: 'bot' | 'user') => {
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    chatMsgs.value.push({ text, role, time });
    
    // Auto scroll
    setTimeout(() => {
        const msgs = document.getElementById('chatMsgs');
        if (msgs) msgs.scrollTop = msgs.scrollHeight;
    }, 100);
};

const getReply = (input: string) => {
    if (!chatbotConfig.value) return "I'm still loading my configuration...";
    const l = input.toLowerCase();
    const replies = chatbotConfig.value.replies;
    for (const [k, v] of Object.entries(replies)) {
        if (l.includes(k)) return v as string;
    }
    return chatbotConfig.value.default_reply;
};

const sendMsg = () => {
    const txt = chatInput.value.trim();
    if (!txt) return;
    addMsg(txt, 'user');
    chatInput.value = '';
    showSuggestions.value = false;
    isTyping.value = true;
    
    setTimeout(() => {
        isTyping.value = false;
        addMsg(getReply(txt), 'bot');
    }, 1000 + Math.random() * 500);
};

const sendSug = (text: string) => {
    showSuggestions.value = false;
    addMsg(text, 'user');
    isTyping.value = true;
    setTimeout(() => {
        isTyping.value = false;
        addMsg(getReply(text), 'bot');
    }, 1000 + Math.random() * 500);
};

const closeDropdowns = (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    if (!target.closest('.custom-select')) {
        isPlanDropdownOpen.value = false;
    }
    if (!target.closest('.district-group')) {
        isDistrictDropdownOpen.value = false;
    }
};

// --- MOUNTED ---
onMounted(() => {
    window.addEventListener('mousemove', updateCursor);
    window.addEventListener('scroll', updateScroll);
    window.addEventListener('click', closeDropdowns);
    requestAnimationFrame(animateCursor);
    fetchChatbotConfig();

    // Initial greeting
    addMsg("👋 Namaste! I'm the CISETU assistant. How can I help you with your gram panchayat infrastructure today?", 'bot');

    // Reveal animation observer
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(r => obs.observe(r));

    // Counter animation logic
    const countObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target as HTMLElement;
                const targetText = el.dataset.count || '0';
                const target = parseFloat(targetText);
                const suffix = el.dataset.suf || '';
                const isDec = !Number.isInteger(target) || targetText.includes('.');
                let start: number | null = null;
                
                const step = (ts: number) => {
                    if (!start) start = ts;
                    const p = Math.min((ts - start) / 2000, 1);
                    const v = target * (1 - Math.pow(1 - p, 3));
                    el.textContent = (isDec ? v.toFixed(1) : Math.floor(v).toLocaleString()) + suffix;
                    if (p < 1) requestAnimationFrame(step);
                    else el.textContent = (isDec ? target.toFixed(1) : target.toLocaleString()) + suffix;
                };
                requestAnimationFrame(step);
                countObs.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-count]').forEach(el => countObs.observe(el));
});

onUnmounted(() => {
    window.removeEventListener('mousemove', updateCursor);
    window.removeEventListener('scroll', updateScroll);
    window.removeEventListener('click', closeDropdowns);
});

const formatLimit = (limit: number | null) => {
    if (limit === null || limit === -1) return 'Unlimited';
    return limit.toLocaleString();
};

const getPlanFeatures = (plan: any) => {
    const feats = [];
    feats.push({ label: `${formatLimit(plan.max_user_accounts)} User Account${plan.max_user_accounts !== 1 ? 's' : ''}`, check: true });
    feats.push({ label: `Up to ${formatLimit(plan.max_properties)} Properties`, check: true });
    
    if (plan.payment_qr_mode === 'static') feats.push({ label: 'Static Payment QR', check: true });
    else if (plan.payment_qr_mode === 'dynamic') feats.push({ label: 'Dynamic Payment QR', check: true });
    
    feats.push({ label: `${plan.max_bots} WhatsApp Bot${plan.max_bots !== 1 ? 's' : ''} Included`, check: true });
    
    if (plan.allows_custom_domain) feats.push({ label: 'Custom Domain + Firewall', check: true });
    if (plan.import_export) feats.push({ label: 'Import & Export Provision', check: true });
    if (plan.dedicated_support) feats.push({ label: '24×7 Dedicated Support', check: true });
    else feats.push({ label: 'No Dedicated Support', check: false });
    
    if (!plan.theme_customization) feats.push({ label: 'No Theme Customization', check: false });
    else feats.push({ label: 'Full Theme Customization', check: true });

    return feats;
};
</script>

<template>
    <Head title="CISETU — Village Governance Infrastructure" />
    <Toaster richColors closeButton />
    
    <div class="landing-body" :style="{ cursor: 'none' }">
        <div id="progress" :style="{ transform: `scaleX(${scrollProgress})` }"></div>
        <div id="cur" :style="{ left: `${mx}px`, top: `${my}px`, width: `${curSize.w}px`, height: `${curSize.h}px`, background: curSize.bg }"></div>
        <div id="cur-r" :style="{ left: `${rx}px`, top: `${ry}px`, width: `${curSize.rw}px`, height: `${curSize.rh}px` }"></div>

        <!-- MOBILE MENU -->
        <div class="mobile-menu" :class="{ open: isMobileMenuOpen }">
            <a href="#features" @click="isMobileMenuOpen = false">Architecture</a>
            <a href="#solutions" @click="isMobileMenuOpen = false">Modules</a>
            <a href="#process" @click="isMobileMenuOpen = false">Process</a>
            <a href="#pricing" @click="isMobileMenuOpen = false">Pricing</a>
            <a href="#testimonials" @click="isMobileMenuOpen = false">Reviews</a>
            <a href="#contact" class="btn-nav-primary" style="font-size:1rem;padding:14px 32px;" @click="isMobileMenuOpen = false">Contact Us</a>
        </div>

        <!-- NAV -->
        <nav id="nav" :class="{ scrolled: isScrolled }">
            <Link class="nav-logo" :href="home().url" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                <img src="/images/logo.svg" alt="CISETU Logo" class="logo-img">
            </Link>
            <ul class="nav-links">
                <li><a href="#features" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Architecture</a></li>
                <li><a href="#solutions" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Modules</a></li>
                <li><a href="#process" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Process</a></li>
                <li><a href="#pricing" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Pricing</a></li>
                <li><a href="#testimonials" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Reviews</a></li>
            </ul>
            <div class="nav-right">
                <a href="#contact" class="btn-nav-primary" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">Contact Us</a>
                <button class="hamburger" @click="isMobileMenuOpen = !isMobileMenuOpen" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </nav>

        <!-- HERO -->
        <section class="hero">
            <div class="hero-canvas">
                <div class="orb orb1"></div>
                <div class="orb orb2"></div>
                <div class="orb orb3"></div>
            </div>
            <div class="hero-grid"></div>

        

            <h1>
                Precision Built for<br>
                <em>Modern</em> <strong>Governance.</strong>
            </h1>

            <p class="hero-sub">
                Centrally manage property records, automate municipal financial calculations, and deploy high-speed communication nodes through one unified administrative portal.
            </p>

            <div class="hero-btns">
                <a href="#contact" class="btn-hero-p" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    Schedule Technical Walkthrough
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#features" class="btn-hero-s" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16"/></svg>
                    Explore Architecture
                </a>
            </div>

            <div class="stats-bar">
                <div class="stat-item" data-tip="Active property nodes" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <span class="stat-num" data-count="5000">0</span>
                    <span class="stat-label">Properties Managed</span>
                </div>
                <div class="stat-item" data-tip="SLA uptime guarantee" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <span class="stat-num" data-count="99.9" data-suf="%">0</span>
                    <span class="stat-label">Uptime Guaranteed</span>
                </div>
                <div class="stat-item" data-tip="Processed this fiscal year" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <span class="stat-num" data-count="100" data-suf="K+">0</span>
                    <span class="stat-label">Tax Records Processed</span>
                </div>
                <div class="stat-item" data-tip="Support availability" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <span class="stat-num" data-count="24" data-suf="/7">0</span>
                    <span class="stat-label">Expert Support</span>
                </div>
            </div>

            <div class="scroll-hint"><span>Scroll</span><div class="scroll-line"></div></div>
        </section>

        <!-- MARQUEE -->
        <div class="marquee-band">
            <div class="marquee-track">
                <div class="mq-item"><span class="mq-sep"></span>WhatsApp Broadcast Node</div>
                <div class="mq-item"><span class="mq-sep"></span>Smart Property Directory</div>
                <div class="mq-item"><span class="mq-sep"></span>Vyvsay Tax Engine</div>
                <div class="mq-item"><span class="mq-sep"></span>Real-time Analytics</div>
                <div class="mq-item"><span class="mq-sep"></span>Anti-ban Protocols</div>
                <div class="mq-item"><span class="mq-sep"></span>XLSX Ledger Export</div>
                <div class="mq-item"><span class="mq-sep"></span>Municipal Receipts</div>
                <div class="mq-item"><span class="mq-sep"></span>Encrypted Cloud Storage</div>
                <div class="mq-item"><span class="mq-sep"></span>REST API Access</div>
                <div class="mq-item"><span class="mq-sep"></span>Custom Domain Firewall</div>
                <!-- Duplicate for seamless loop -->
                <div class="mq-item"><span class="mq-sep"></span>WhatsApp Broadcast Node</div>
                <div class="mq-item"><span class="mq-sep"></span>Smart Property Directory</div>
                <div class="mq-item"><span class="mq-sep"></span>Vyvsay Tax Engine</div>
                <div class="mq-item"><span class="mq-sep"></span>Real-time Analytics</div>
                <div class="mq-item"><span class="mq-sep"></span>Anti-ban Protocols</div>
                <div class="mq-item"><span class="mq-sep"></span>XLSX Ledger Export</div>
                <div class="mq-item"><span class="mq-sep"></span>Municipal Receipts</div>
                <div class="mq-item"><span class="mq-sep"></span>Encrypted Cloud Storage</div>
                <div class="mq-item"><span class="mq-sep"></span>REST API Access</div>
                <div class="mq-item"><span class="mq-sep"></span>Custom Domain Firewall</div>
            </div>
        </div>

        <!-- FEATURES -->
        <section class="features-bg" id="features">
            <div class="features-center reveal">
                <div class="section-tag">Core Architecture</div>
                <h2 class="section-title">Built for scale<br>and security.</h2>
                <p class="section-sub">Three precision-engineered modules working in concert for complete panchayat infrastructure management.</p>
            </div>
            <div class="features-grid">
                <div v-for="(f, i) in [
                    { num: '01', title: 'Bulk Dispatch Node', desc: 'Transmit high-volume rich media messages across dynamic contact clusters with intelligent anti-ban delay protocols.', tag: 'WhatsApp API', icon: `<path d='M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.7 9.81 19.79 19.79 0 01.66 1.22 2 2 0 012.63 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.61a16 16 0 006.22 6.22l.97-.97a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z'/>` },
                    { num: '02', title: 'Smart Directory', desc: 'Manage complex property arrays, occupant details, and automated XLSX ledger exports through a unified command interface.', tag: 'Real-time Sync', icon: `<path d='M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z'/><polyline points='9,22 9,12 15,12 15,22'/>` },
                    { num: '03', title: 'Vyvsay Tax Engine', desc: 'Deploy robust calculation nodes for municipal business taxes, automated receipt generation, and pending tax tracking.', tag: 'Auto-Receipt', icon: `<rect x='2' y='3' width='20' height='14' rx='2'/><line x1='8' y1='21' x2='16' y2='21'/><line x1='12' y1='17' x2='12' y2='21'/>` },
                    { num: '04', title: 'Encrypted Cloud Storage', desc: 'All telemetric data flows through strictly encrypted nodes with zero plaintext storage beyond temporary dispatch queues.', tag: 'AES-256', icon: `<path d='M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'/>` },
                    { num: '05', title: 'Real-time Analytics', desc: 'Gain instant overhead view of active deployment nodes, delivery success ratios, and massive data directories.', tag: 'Live Data', icon: `<polyline points='22,12 18,12 15,21 9,3 6,12 2,12'/>` },
                    { num: '06', title: 'Master API Connectivity', desc: 'Full REST API access with cryptographic key assignments enabling seamless integration with existing governance infrastructure.', tag: 'REST API', icon: `<circle cx='12' cy='12' r='3'/><path d='M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83'/>` }
                ]" :key="f.num" class="f-card reveal" :class="`rd${(i%3)+1}`" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <div class="f-card-accent"></div>
                    <span class="f-num">{{ f.num }}</span>
                    <div class="f-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" v-html="f.icon"></svg>
                    </div>
                    <h3>{{ f.title }}</h3>
                    <p>{{ f.desc }}</p>
                    <div class="f-tag">{{ f.tag }}</div>
                </div>
            </div>
        </section>

        <!-- DASHBOARD -->
        <section class="dash-section" id="solutions">
            <div class="dash-wrap">
                <div class="reveal">
                    <div class="section-tag">Command Console</div>
                    <h2 class="section-title">Visualize your<br>entire network.</h2>
                    <p class="section-sub">Complete overhead visibility over your panchayat operations — property records, tax collections, and communication dispatch — all from one portal.</p>
                    <div class="dash-bullets">
                        <div class="db-item reveal rd1" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                            <div class="db-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg></div>
                            <div><h4>Live Node Monitoring</h4><p>Track all WhatsApp broadcast nodes in real time with delivery status and open rate tracking.</p></div>
                        </div>
                        <div class="db-item reveal rd2" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                            <div class="db-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg></div>
                            <div><h4>Automated Tax Ledgers</h4><p>Auto-generate XLSX exports of all municipal financial records with zero manual intervention.</p></div>
                        </div>
                        <div class="db-item reveal rd3" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                            <div class="db-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>
                            <div><h4>Property Grid Overview</h4><p>Manage 5,000+ property arrays with complete occupant details and pending records at a glance.</p></div>
                        </div>
                    </div>
                </div>

                <!-- Premium Dashboard Mockup -->
                <div class="dash-mockup reveal rd2" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <div class="mock-titlebar">
                        <div class="mtb-dots"><div class="mtb-dot"></div><div class="mtb-dot"></div><div class="mtb-dot"></div></div>
                        <div class="mtb-title">CISETU Command Console v4.2</div>
                        <div class="mtb-live"><div class="mtb-ldot"></div>LIVE</div>
                    </div>
                    <div class="mock-body">
                        <div class="kpi-row">
                            <div class="kpi" data-grad="teal" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <div class="kpi-val">5,284</div>
                                <div class="kpi-label">Properties</div>
                                <div class="kpi-delta up">↑ 12 new today</div>
                            </div>
                            <div class="kpi" data-grad="green" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <div class="kpi-val">98.2%</div>
                                <div class="kpi-label">Delivery Rate</div>
                                <div class="kpi-delta up">↑ 2.1% vs last week</div>
                            </div>
                            <div class="kpi" data-grad="amber" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <div class="kpi-val">₹4.2L</div>
                                <div class="kpi-label">Collected</div>
                                <div class="kpi-delta up">↑ 18% this month</div>
                            </div>
                            <div class="kpi" data-grad="purple" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <div class="kpi-val">143</div>
                                <div class="kpi-label">Pending</div>
                                <div class="kpi-delta dn">↓ 8 from yesterday</div>
                            </div>
                        </div>

                        <div class="chart-wrap">
                            <div class="chart-head">
                                <span class="chart-title">Monthly Tax Collection</span>
                                <div class="chart-tabs">
                                    <span class="ct-tab active">6M</span>
                                    <span class="ct-tab">1Y</span>
                                    <span class="ct-tab">All</span>
                                </div>
                            </div>
                            <div class="chart-body">
                                <div v-for="c in [
                                    { h: '38%', l: 'Nov', g: false }, { h: '52%', l: 'Dec', g: false }, { h: '44%', l: 'Jan', g: false }, { h: '68%', l: 'Feb', g: true },
                                    { h: '57%', l: 'Mar', g: false }, { h: '78%', l: 'Apr', g: true }, { h: '63%', l: 'May', g: false }, { h: '100%', l: 'Jun', g: true }
                                ]" :key="c.l" class="c-col">
                                    <div class="c-bar" :class="c.g ? 'gbar' : 'tbar'" :style="{ height: c.h }"></div>
                                    <div class="c-lbl">{{ c.l }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mock-lower">
                            <div class="mock-map">
                                <div class="mm-head"><span class="mm-title">Recent Payments</span><span class="mm-badge">Today</span></div>
                                <div class="activity-list">
                                    <div v-for="a in [
                                        { i: 'R', n: 'Rameshbhai Patel', d: 'House Tax • Plot 42A', a: '₹1,200', c: 'var(--green)', b: 'var(--teal)' },
                                        { i: 'P', n: 'Priya Sharma', d: 'Vyvsay Tax • Shop 7', a: '₹800', c: 'var(--green)', b: 'var(--green)' },
                                        { i: 'M', n: 'Mahesh Kumar', d: 'Water Tax • Plot 88', a: 'Pending', c: 'var(--amber)', b: 'var(--amber)' }
                                    ]" :key="a.n" class="act-row" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                        <div class="act-av" :style="{ background: a.b }">{{ a.i }}</div>
                                        <div class="act-info"><div class="act-name">{{ a.n }}</div><div class="act-det">{{ a.d }}</div></div>
                                        <div class="act-amt" :style="{ color: a.c }">{{ a.a }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mock-map">
                                <div class="mm-head"><span class="mm-title">Collection Split</span><span class="mm-badge">FY 2026</span></div>
                                <div class="mini-donut-wrap" style="margin-top:8px">
                                    <div v-for="d in [
                                        { l: 'House Tax', p: '72%', b: 'linear-gradient(90deg,var(--teal),var(--teal2))' },
                                        { l: 'Vyvsay', p: '54%', b: 'linear-gradient(90deg,var(--green),var(--green2))' },
                                        { l: 'Water', p: '38%', b: 'linear-gradient(90deg,#0ea5e9,#38bdf8)' },
                                        { l: 'Other', p: '21%', b: 'linear-gradient(90deg,var(--amber),#f5c842)' }
                                    ]" :key="d.l" class="donut-row">
                                        <div class="donut-label">{{ d.l }}</div>
                                        <div class="donut-bar-bg"><div class="donut-bar-fill" :style="{ width: d.p, background: d.b }"></div></div>
                                        <div class="donut-pct">{{ d.p }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- IMPACT NUMBERS -->
        <section class="impact-section reveal">
            <div class="impact-grid-bg"></div>
            <div class="impact-inner">
                <div class="impact-title-wrap">
                    <div class="section-tag">Platform Impact</div>
                    <h2 class="impact-h">Infrastructure that delivers<br>measurable results.</h2>
                </div>
                <div class="impact-grid">
                    <div v-for="imp in [
                        { n: '99', s: '%', l: 'Error Reduction', sb: 'in tax calculations' },
                        { n: '3', s: 'x', l: 'Faster Collection', sb: 'vs manual process' },
                        { n: '50', s: '+', l: 'Gram Panchayats', sb: 'live on platform' },
                        { n: '4.9', s: '★', l: 'Avg. Rating', sb: 'from active users' }
                    ]" :key="imp.l" class="imp-item" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                        <span class="imp-num" :data-count="imp.n" :data-suf="imp.s">0</span>
                        <div class="imp-label">{{ imp.l }}</div>
                        <div class="imp-sub">{{ imp.sb }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROCESS -->
        <section class="process-bg" id="process">
            <div style="max-width:1200px;margin:0 auto">
                <div class="reveal" style="max-width:680px;margin-bottom:72px">
                    <div class="section-tag">Deployment Protocol</div>
                    <h2 class="section-title">Structured integration<br>pipeline.</h2>
                    <p class="section-sub">Four-phase deployment methodology ensuring zero-disruption onboarding for your village governance infrastructure.</p>
                </div>
                <div class="process-grid">
                    <div v-for="(p, i) in [
                        { n: '01', t: 'Architecture Audit', d: 'Systematic review of existing data nodes and communication infrastructure.', i: `<circle cx='11' cy='11' r='8'/><line x1='21' y1='21' x2='16.65' y2='16.65'/>` },
                        { n: '02', t: 'Node Initialization', d: 'Secure gateway setup and cryptographic key assignments for tamper-proof access.', i: `<rect x='3' y='11' width='18' height='11' rx='2'/><path d='M7 11V7a5 5 0 0110 0v4'/>` },
                        { n: '03', t: 'Data Migration', d: 'Lossless transfer of existing directories and tax records with full validation.', i: `<path d='M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4'/><polyline points='17,8 12,3 7,8'/><line x1='12' y1='3' x2='12' y2='15'/>` },
                        { n: '04', t: 'Network Scaling', d: 'Live deployment with continuous monitoring and 99.9% uptime guarantee.', i: `<polyline points='23,6 13.5,15.5 8.5,10.5 1,18'/><polyline points='17,6 23,6 23,12'/>` }
                    ]" :key="p.n" class="p-step reveal" :class="`rd${i+1}`">
                        <div class="p-circle"><div class="p-num">{{ p.n }}</div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" v-html="p.i"></svg></div>
                        <h4>{{ p.t }}</h4>
                        <p>{{ p.d }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRICING -->
        <section id="pricing" style="background:var(--bg)">
            <div style="max-width:1100px;margin:0 auto">
                <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 64px">
                    <div class="section-tag" style="justify-content:center">Deployment Tiers</div>
                    <h2 class="section-title">Select your<br>architectural node.</h2>
                    <p class="section-sub" style="margin:14px auto 0">Transparent pricing for gram panchayats of every scale.</p>
                </div>
                <div class="pricing-grid">
                    <div v-for="(plan, index) in plans" :key="plan.id" class="p-card reveal" :class="[{ feat: index === 1 }, `rd${index+1}`]" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                        <div class="p-card-shine"></div>
                        <div v-if="index === 1" class="feat-ribbon">Most Popular</div>
                        <div class="pname">{{ plan.name }}</div>
                        <div class="pprice"><span class="pcur">₹</span><span class="pamt">{{ Math.floor(plan.price_per_year_inr).toLocaleString() }}</span><span class="pper">/yr</span></div>
                        <p class="pdesc">{{ plan.description || 'Enterprise solution for your village governance needs.' }}</p>
                        <ul class="pfeats">
                            <li v-for="feat in getPlanFeatures(plan)" :key="feat.label" :class="{ 'cross-i': !feat.check }">
                                <svg v-if="feat.check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="chk"><polyline points="20,6 9,17 4,12"/></svg>
                                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="crx"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                {{ feat.label }}
                            </li>
                        </ul>
                        <a href="#contact" class="btn-pln" :class="index === 1 ? 'btn-pln-fill' : 'btn-pln-out'">Initialize Inquiry</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="testi-bg" id="testimonials">
            <div style="max-width:1200px;margin:0 auto">
                <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 64px">
                    <div class="section-tag" style="justify-content:center">Field Reports</div>
                    <h2 class="section-title">From gram panchayat<br>commanders.</h2>
                </div>
                <div class="t-grid">
                    <div v-for="(t, i) in [
                        { i: 'R', n: 'Rameshbhai Patel', r: 'Gram Panchayat Pradhan', t: 'The WhatsApp dispatch node completely transformed our bulk communication. Delay protocols are flawless — zero blockages.', b: 'linear-gradient(135deg,var(--teal),var(--teal2))' },
                        { i: 'P', n: 'Priya Sharma', r: 'Panchayat Sachiv', t: 'Vyvsay Tax Engine deployed seamlessly into our workflow. It reduced manual calculation errors by 99% and fully automated our receipt generation.', b: 'linear-gradient(135deg,var(--green),var(--green2))' },
                        { i: 'M', n: 'Mahesh Kumar Joshi', r: 'Talati-cum-Mantri', t: 'Managing over 5,000 properties used to be a nightmare. The Smart Directory gives us an uncompromised overhead view of all occupant details.', b: 'linear-gradient(135deg,#1a3f6f,#2563eb)' }
                    ]" :key="t.n" class="t-card reveal" :class="`rd${i+1}`" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                        <div class="t-bg-char">"</div>
                        <div class="t-stars">
                            <svg v-for="s in 5" :key="s" class="t-star" viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                        </div>
                        <p class="t-text">{{ t.t }}</p>
                        <div class="t-author">
                            <div class="t-av" :style="{ background: t.b }">{{ t.i }}</div>
                            <div>
                                <div class="t-name">{{ t.n }}</div>
                                <div class="t-role">{{ t.r }}</div>
                                <div class="t-verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Verified User</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTACT / INQUIRY -->
        <section class="cta-outer" id="contact">
            <div class="cta-card">
                <div class="cta-grid-bg"></div>
                <div class="cta-inner reveal">
                    <div class="section-tag">Direct Inquiry Node</div>
                    <h2>Secure your<br>deployment node.</h2>
                    <p>Submit your village details below to initialize a technical audit and deployment consultation with our master administrators.</p>
                    
                    <form @submit.prevent="submitInquiry" class="inquiry-form reveal rd2" novalidate>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Your Full Name</label>
                                <input v-model="form.name" type="text" placeholder="e.g. Rajesh Patel" :class="{ 'err-field': form.errors.name }">
                                <span v-if="form.errors.name" class="err">{{ form.errors.name }}</span>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input v-model="form.email" type="email" placeholder="name@domain.com" :class="{ 'err-field': form.errors.email }">
                                <span v-if="form.errors.email" class="err">{{ form.errors.email }}</span>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input v-model="form.phone" type="tel" placeholder="+91 00000 00000" :class="{ 'err-field': form.errors.phone }">
                                <span v-if="form.errors.phone" class="err">{{ form.errors.phone }}</span>
                            </div>
                            <div class="form-group">
                                <label>Village Name</label>
                                <input v-model="form.village_name" type="text" placeholder="Village name" :class="{ 'err-field': form.errors.village_name }">
                                <span v-if="form.errors.village_name" class="err">{{ form.errors.village_name }}</span>
                            </div>
                            <div class="form-group district-group custom-select relative" @click.stop="isDistrictDropdownOpen = true">
                                <label>District</label>
                                <input v-model="districtSearch" @input="handleDistrictInput" type="text" placeholder="Search district name" :class="{ 'err-field': form.errors.district_name }" autocomplete="off">
                                
                                <div class="select-options-wrap" :class="{ show: isDistrictDropdownOpen && districtSearch.length >= 2 }">
                                    <div v-if="districtLoading" class="select-opt">
                                        <div class="opt-main">
                                            <span class="opt-name" style="color: rgba(255,255,255,0.5); font-style: italic;">Searching...</span>
                                        </div>
                                    </div>
                                    <template v-else>
                                        <div v-for="dist in districtSuggestions" :key="dist.id" class="select-opt" @click.stop="selectDistrict(dist.name_en)">
                                            <div class="opt-main">
                                                <div class="opt-dot"></div>
                                                <span class="opt-name">{{ dist.name_en }}</span>
                                            </div>
                                        </div>
                                        <div v-if="districtSuggestions.length === 0" class="select-opt">
                                            <div class="opt-main">
                                                <span class="opt-name" style="color: rgba(255,255,255,0.5); font-style: italic;">No districts found</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <span v-if="form.errors.district_name" class="err">{{ form.errors.district_name }}</span>
                            </div>
                            <div class="form-group">
                                <label>Preferred Plan</label>
                                <div class="custom-select" :class="{ open: isPlanDropdownOpen, 'err-field': form.errors.plan_id }" @click="isPlanDropdownOpen = !isPlanDropdownOpen" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                    <div class="select-trigger">
                                        <span>{{ selectedPlanLabel }}</span>
                                        <svg class="chevron" :class="{ rotate: isPlanDropdownOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                                    </div>
                                    <div class="select-options-wrap" :class="{ show: isPlanDropdownOpen }">
                                        <div v-for="plan in plans" :key="plan.id" class="select-opt" @click.stop="form.plan_id = plan.id; isPlanDropdownOpen = false">
                                            <div class="opt-main">
                                                <div class="opt-dot"></div>
                                                <span class="opt-name">{{ plan.name }}</span>
                                            </div>
                                            <span class="opt-price">₹{{ Math.floor(plan.price_per_year_inr).toLocaleString() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="form.errors.plan_id" class="err">{{ form.errors.plan_id }}</span>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:20px">
                            <label>Technical Message / Requirements</label>
                            <textarea v-model="form.message" placeholder="Describe your specific village governance requirements…" rows="4" :class="{ 'err-field': form.errors.message }"></textarea>
                            <span v-if="form.errors.message" class="err">{{ form.errors.message }}</span>
                        </div>
                        
                        <button type="submit" class="btn-cta-w" :disabled="form.processing" style="width:100%;margin-top:24px;justify-content:center">
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Initialize Inquiry Dispatch</span>
                        </button>
                    </form>

                    <div class="cta-contacts">
                        <a href="tel:+919558981800" class="cta-ct" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.7 9.81 19.79 19.79 0 01.66 1.22 2 2 0 012.63 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.61a16 16 0 006.22 6.22l.97-.97a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                            +91 95589 81800
                        </a>
                        <a href="mailto:hello@cisetu.com" class="cta-ct" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            hello@cisetu.com
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="foot-inner">
                <div class="foot-top">
                    <div class="foot-brand">
                        <div class="foot-logo">
                            <img src="/images/white-logo.svg" alt="CISETU Logo" class="logo-img-foot">
                        </div>
                        <p>Enterprise infrastructure gateway for village governance — property records, tax automation, and bulk communication in one unified portal.</p>
                        <div class="foot-social">
                            <a class="soc-btn" href="#" title="WhatsApp" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                            </a>
                            <a class="soc-btn" href="mailto:hello@cisetu.com" title="Email" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </a>
                            <a class="soc-btn" href="tel:+919558981800" title="Phone" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.7 9.81 19.79 19.79 0 01.66 1.22 2 2 0 012.63 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.61a16 16 0 006.22 6.22l.97-.97a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="foot-col"><h5>Platform</h5><a href="#features">Architecture</a><a href="#solutions">Modules</a><a href="#process">Deployment</a><a href="#pricing">Pricing</a></div>
                    <div class="foot-col"><h5>Company</h5><a href="#">About CISETU</a><a href="#">Blog</a><a href="#testimonials">Testimonials</a><a href="#contact">Contact</a></div>
                    <div class="foot-col"><h5>Legal</h5><a href="#">Privacy Policy</a><a href="#">Terms of Service</a><a href="#">Data Security</a><a href="#">Support SLA</a></div>
                </div>
                <div class="foot-bottom">
                    <p class="foot-copy">© 2026 CISETU Infrastructure. All Rights Reserved.</p>
                    <div class="foot-legal"><a href="#">Privacy</a><a href="#">Terms</a><a href="#contact">Support</a></div>
                </div>
            </div>
        </footer>

        <!-- CHATBOT -->
        <button class="chat-trigger" :class="{ open: isChatOpen }" @click="isChatOpen = !isChatOpen" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
            <div class="chat-pulse"></div>
            <svg v-if="!isChatOpen" class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            <svg v-else class="ix" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="chat-window" :class="{ open: isChatOpen }">
            <div class="chat-head">
                <div class="chat-av">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </div>
                <div class="chat-head-info">
                    <img src="/images/white-logo.svg" alt="CISETU Logo" class="logo-img" style="width: 75px;">
                    <p><span class="online-dot"></span>Online — replies instantly</p>
                </div>
            </div>
            <div class="chat-msgs" id="chatMsgs">
                <div v-for="(m, i) in chatMsgs" :key="i" class="msg" :class="m.role">
                    <div class="msg-bubble" v-html="m.text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')"></div>
                    <div class="msg-time">{{ m.time }}</div>
                </div>
                <div v-if="isTyping" class="msg bot">
                    <div class="msg-bubble typing"><div class="t-dot"></div><div class="t-dot"></div><div class="t-dot"></div></div>
                </div>
            </div>
            <div v-if="showSuggestions && chatbotConfig" class="chat-suggestions">
                <button v-for="s in chatbotConfig.suggestions" :key="s" class="sug-btn" @click="sendSug(s)" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">{{ s }}</button>
            </div>
            <div class="chat-input-wrap">
                <input class="chat-input" v-model="chatInput" type="text" placeholder="Type your message…" @keydown.enter="sendMsg">
                <button class="chat-send" @click="sendMsg" @mouseenter="setCursorHover(true)" @mouseleave="setCursorHover(false)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22,2 15,22 11,13 2,9"/></svg>
                </button>
            </div>
        </div>
    </div>
</template>

<style>
/* CSS copied from User Request with landing-body scoping */
:root {
  --teal:    #0d5c6a;
  --teal2:   #1a7d90;
  --teal3:   #cce8ee;
  --teal4:   #e5f4f7;
  --green:   #52a80d;
  --green2:  #72d41a;
  --green3:  #ddf2c4;
  --amber:   #e8a014;
  --bg:      #f3f6f7;
  --bg2:     #ffffff;
  --bg3:     #eaeff1;
  --text:    #0a1a1e;
  --text2:   #3a5560;
  --text3:   #7090a0;
  --border:  #d8e5e9;
  --border2: #b8d4da;
  --sh1:     0 2px 12px rgba(13,92,106,0.08);
  --sh2:     0 12px 48px rgba(13,92,106,0.15);
  --sh3:     0 32px 80px rgba(13,92,106,0.18);
  --r:       14px;
  --r2:      20px;
  --r3:      28px;
}

.landing-body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
    position: relative;
}

.landing-body::before {
    content: '';
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 9999;
    opacity: .025;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
}

/* ── CURSOR ── */
#cur {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: 99999;
    transform: translate(-50%, -50%);
    transition: width .2s, height .2s, background .2s, border-radius .2s;
}
#cur-r {
    position: fixed;
    border: 1.5px solid rgba(26, 125, 144, 0.35);
    border-radius: 50%;
    pointer-events: none;
    z-index: 99998;
    transform: translate(-50%, -50%);
    transition: border-color .3s, width .3s, height .3s;
}

/* ── PROGRESS BAR ── */
#progress {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--teal), var(--green2));
    z-index: 10001;
    transform-origin: left;
    transition: transform .1s linear;
}

/* ── NAV ── */
nav {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    z-index: 1000;
    height: 80px;
    padding: 0 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0);
    backdrop-filter: blur(0px);
    border: 1px solid transparent;
    border-radius: 0;
    transition: all .6s cubic-bezier(0.16, 1, 0.3, 1);
}
nav.scrolled {
    top: 20px;
    width: 92%;
    max-width: 1240px;
    height: 68px;
    padding: 0 32px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid var(--border);
    border-radius: 99px;
    box-shadow: var(--sh2);
}
.nav-logo {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}
.logo-img {
    height: 40px;
    width: auto;
    display: block;
}
.logo-img-foot {
    height: 34px;
    width: auto;
    display: block;
}

.nav-links {
    display: flex;
    gap: 32px;
    list-style: none;
}
.nav-links a {
    color: var(--text2);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    position: relative;
    transition: color .3s;
}
.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 100%;
    height: 1.5px;
    background: var(--teal2);
    border-radius: 2px;
    transition: right .3s;
}
.nav-links a:hover {
    color: var(--teal);
}
.nav-links a:hover::after {
    right: 0;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.btn-ghost-nav {
    color: var(--text2);
    background: none;
    border: 1.5px solid var(--border2);
    padding: 7px 18px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .3s;
}
.btn-ghost-nav:hover {
    border-color: var(--teal2);
    color: var(--teal);
}
.btn-nav-primary {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    color: #fff !important;
    padding: 8px 20px;
    border-radius: 8px;
    border: none;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: none;
    transition: all .3s;
    box-shadow: 0 4px 16px rgba(13, 92, 106, 0.3);
}
.btn-nav-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(13, 92, 106, 0.4);
}

.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    padding: 4px;
}
.hamburger span {
    width: 22px;
    height: 2px;
    background: var(--text2);
    border-radius: 2px;
    display: block;
    transition: all .3s;
}

@media(max-width:900px) {
    .nav-links, .btn-ghost-nav, .nav-right .btn-nav-primary { display: none; }
    .hamburger { display: flex; }
}

.mobile-menu {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.97);
    backdrop-filter: blur(24px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 24px;
    z-index: 1002;
    transform: translateX(100%);
    transition: transform .4s cubic-bezier(.77, 0, .175, 1);
}
.mobile-menu.open {
    transform: translateX(0);
}
.mobile-menu a {
    color: var(--text);
    font-family: 'Fraunces', serif;
    font-size: 2rem;
    font-weight: 700;
    text-decoration: none;
    transition: color .3s;
}

/* ── HERO ── */
.hero {
    min-height: 100vh;
    padding: 140px 5% 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-canvas {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}
.hero-canvas::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 900px 600px at 15% 20%, rgba(13, 92, 106, 0.07) 0%, transparent 70%),
        radial-gradient(ellipse 600px 500px at 85% 80%, rgba(82, 168, 13, 0.06) 0%, transparent 70%),
        radial-gradient(ellipse 500px 400px at 70% 10%, rgba(26, 125, 144, 0.05) 0%, transparent 60%);
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    animation: orbFloat 12s ease-in-out infinite;
    pointer-events: none;
}
.orb1 { width: 400px; height: 400px; background: rgba(13, 92, 106, 0.1); top: -100px; left: -100px; animation-delay: 0s; }
.orb2 { width: 300px; height: 300px; background: rgba(82, 168, 13, 0.09); bottom: 10%; right: -80px; animation-delay: -4s; }
.orb3 { width: 200px; height: 200px; background: rgba(26, 125, 144, 0.08); top: 40%; left: 60%; animation-delay: -8s; }

@keyframes orbFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -20px) scale(1.05); }
    66% { transform: translate(-20px, 25px) scale(.97); }
}

.hero-grid {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image: linear-gradient(var(--border) 1px, transparent 1px), linear-gradient(90deg, var(--border) 1px, transparent 1px);
    background-size: 60px 60px;
    opacity: .35;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid var(--border2);
    padding: 6px 16px 6px 10px;
    border-radius: 100px;
    font-size: 0.72rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--teal);
    margin-bottom: 40px;
    box-shadow: var(--sh1), inset 0 1px 0 rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    animation: fadeUp .8s ease .2s both;
    position: relative;
    z-index: 1;
}
.badge-icon {
    width: 22px;
    height: 22px;
    background: linear-gradient(135deg, var(--teal), var(--teal2));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge-icon svg { width: 11px; height: 11px; color: #fff; }
.badge-pulse {
    width: 7px;
    height: 7px;
    background: var(--green2);
    border-radius: 50%;
    animation: pulse2 2s ease infinite;
    flex-shrink: 0;
}

@keyframes pulse2 {
    0%, 100% { box-shadow: 0 0 0 0 rgba(114, 212, 26, .5); }
    50% { box-shadow: 0 0 0 6px rgba(114, 212, 26, 0); }
}

.hero h1 {
    font-family: 'Fraunces', serif;
    font-size: clamp(3.2rem, 8vw, 7.5rem);
    font-weight: 900;
    line-height: 1.02;
    letter-spacing: -0.03em;
    max-width: 1000px;
    position: relative;
    z-index: 1;
    animation: fadeUp .9s ease .35s both;
}
.hero h1 em { font-style: italic; font-weight: 300; color: var(--teal2); }
.hero h1 strong { position: relative; display: inline-block; color: var(--text); }
.hero h1 strong::after {
    content: '';
    position: absolute;
    left: -2px;
    right: -2px;
    bottom: 4px;
    height: 14px;
    background: linear-gradient(90deg, rgba(114, 212, 26, 0.35), rgba(82, 168, 13, 0.15));
    border-radius: 3px;
    z-index: -1;
    transform: rotate(-0.5deg);
}

.hero-sub {
    font-size: clamp(1rem, 1.5vw, 1.15rem);
    color: var(--text2);
    max-width: 560px;
    line-height: 1.8;
    margin: 28px auto 0;
    animation: fadeUp .8s ease .5s both;
    font-weight: 400;
    position: relative;
    z-index: 1;
}

.hero-btns {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 44px;
    animation: fadeUp .8s ease .65s both;
    position: relative;
    z-index: 1;
}
.btn-hero-p {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    color: #fff !important;
    padding: 16px 36px;
    border-radius: 12px;
    border: none;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-decoration: none;
    transition: all .3s;
    box-shadow: 0 6px 24px rgba(13, 92, 106, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}
.btn-hero-p::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 60%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
    transition: left .5s;
}
.btn-hero-p:hover::before { left: 150%; }
.btn-hero-p:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 40px rgba(13, 92, 106, 0.45);
}
.btn-hero-s {
    background: var(--bg2);
    color: var(--text);
    padding: 16px 36px;
    border-radius: 12px;
    border: 1.5px solid var(--border2);
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .3s;
    box-shadow: var(--sh1);
    display: inline-flex;
    align-items: center;
    gap: 10px;
}
.btn-hero-s:hover {
    border-color: var(--teal2);
    color: var(--teal);
    transform: translateY(-3px);
    box-shadow: var(--sh2);
}

.stats-bar {
    display: flex;
    border: 1px solid var(--border);
    border-radius: var(--r2);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(16px);
    overflow: hidden;
    margin-top: 72px;
    max-width: 860px;
    width: 100%;
    box-shadow: var(--sh2), inset 0 1px 0 rgba(255, 255, 255, 0.9);
    animation: fadeUp .8s ease .9s both;
    position: relative;
    z-index: 1;
}
.stat-item {
    flex: 1;
    padding: 30px 20px;
    text-align: center;
    border-right: 1px solid var(--border);
    transition: background .3s;
    position: relative;
    overflow: hidden;
}
.stat-item:last-child { border-right: none; }
.stat-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--teal4);
    transform: scaleY(0);
    transform-origin: bottom;
    transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
}
.stat-item:hover::before { transform: scaleY(1); }
.stat-num {
    font-family: 'Fraunces', serif;
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--teal);
    display: block;
    line-height: 1;
    position: relative;
    z-index: 1;
}
.stat-label {
    font-size: 0.7rem;
    color: var(--text3);
    margin-top: 6px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-weight: 600;
    position: relative;
    z-index: 1;
}

.scroll-hint {
    position: absolute;
    bottom: 36px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    animation: fadeIn 1.2s ease 1.4s both;
    z-index: 1;
}
.scroll-hint span { font-size: 0.65rem; color: var(--text3); letter-spacing: 0.14em; text-transform: uppercase; font-weight: 600; }
.scroll-line {
    width: 1px;
    height: 40px;
    background: linear-gradient(to bottom, var(--teal2), transparent);
    animation: scrollAnim 2.2s ease-in-out infinite;
}

@keyframes scrollAnim {
    0% { transform: scaleY(0); transform-origin: top; opacity: 1; }
    50% { transform: scaleY(1); opacity: 1; }
    100% { transform: scaleY(1); transform-origin: bottom; opacity: 0; }
}

/* ── MARQUEE ── */
.marquee-band {
    padding: 0;
    overflow: hidden;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    background: linear-gradient(90deg, var(--bg2) 0%, var(--teal4) 50%, var(--bg2) 100%);
    position: relative;
}
.marquee-band::before, .marquee-band::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 120px;
    z-index: 2;
}
.marquee-band::before { left: 0; background: linear-gradient(90deg, var(--bg2), transparent); }
.marquee-band::after { right: 0; background: linear-gradient(-90deg, var(--bg2), transparent); }
.marquee-track { display: flex; width: max-content; animation: marquee 30s linear infinite; }
.marquee-track:hover { animation-play-state: paused; }

@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.mq-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 40px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text3);
    white-space: nowrap;
    transition: color .3s;
}
.mq-item:hover { color: var(--teal); }
.mq-sep { width: 4px; height: 4px; background: var(--green2); border-radius: 50%; flex-shrink: 0; }

/* ── SECTIONS ── */
.landing-body section { padding: 100px 5%; }
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--teal);
    letter-spacing: 0.1em;
    text-transform: uppercase;
}
.section-tag::before { content: ''; width: 20px; height: 2px; background: linear-gradient(90deg, var(--green2), var(--green)); border-radius: 2px; }

/* Override section tag color on dark backgrounds */
.impact-section .section-tag,
.cta-card .section-tag {
    color: rgba(255, 255, 255, 0.85);
}
.section-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 900;
    letter-spacing: -0.03em;
    line-height: 1.05;
    color: var(--text);
}
.section-sub { font-size: 1rem; color: var(--text2); line-height: 1.8; margin-top: 14px; font-weight: 400; max-width: 500px; }

/* ── FEATURES ── */
.features-bg { background: var(--bg2); position: relative; overflow: hidden; }
.features-center { max-width: 680px; margin: 0 auto 72px; text-align: center; }
.features-center .section-tag { justify-content: center; }
.features-center .section-sub { margin: 14px auto 0; }

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    max-width: 1200px;
    margin: 0 auto;
}
@media(max-width:1000px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:600px) { .features-grid { grid-template-columns: 1fr; } }

.f-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--r2);
    padding: 36px 30px;
    transition: all .4s cubic-bezier(.34, 1.2, .64, 1);
    position: relative;
    overflow: hidden;
}
.f-card:hover {
    background: var(--bg2);
    border-color: var(--teal3);
    transform: translateY(-8px) scale(1.01);
    box-shadow: var(--sh3);
}
.f-card-accent {
    position: absolute;
    top: 0;
    left: 24px;
    width: 32px;
    height: 3px;
    background: linear-gradient(90deg, var(--teal), var(--green2));
    border-radius: 0 0 4px 4px;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
}
.f-card:hover .f-card-accent { transform: scaleX(1); }
.f-icon-wrap {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--teal4) 0%, var(--teal3) 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 22px;
    border: 1px solid var(--teal3);
    transition: all .4s;
    box-shadow: 0 2px 8px rgba(13, 92, 106, 0.1);
}
.f-card:hover .f-icon-wrap {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    border-color: var(--teal);
    box-shadow: 0 6px 20px rgba(13, 92, 106, 0.3);
}
.f-icon-wrap svg { width: 22px; height: 22px; color: var(--teal); transition: color .4s; }
.f-card:hover .f-icon-wrap svg { color: #fff; }
.f-num {
    position: absolute;
    top: 16px;
    right: 20px;
    font-family: 'Fraunces', serif;
    font-size: 2.5rem;
    font-weight: 900;
    color: rgba(13, 92, 106, 0.05);
    line-height: 1;
    pointer-events: none;
    transition: color .4s;
}
.f-card:hover .f-num { color: rgba(13, 92, 106, 0.08); }
.f-card h3 { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 10px; }
.f-card p { color: var(--text2); font-size: 0.875rem; line-height: 1.75; font-weight: 400; }
.f-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 18px;
    padding: 4px 10px;
    border-radius: 100px;
    background: var(--green3);
    border: 1px solid rgba(82, 168, 13, 0.2);
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--green);
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
.f-tag::before { content: ''; width: 5px; height: 5px; background: var(--green2); border-radius: 50%; animation: pulse2 2s infinite; }

/* ── DASHBOARD ── */
.dash-section { background: var(--bg); overflow: hidden; }
.dash-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}
@media(max-width:900px) { .dash-wrap { grid-template-columns: 1fr; gap: 48px; } }

.dash-mockup {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r3);
    overflow: hidden;
    box-shadow: var(--sh3);
    position: relative;
}
.mock-titlebar {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}
.mtb-dots { display: flex; gap: 6px; flex-shrink: 0; }
.mtb-dot { width: 11px; height: 11px; border-radius: 50%; }
.mtb-dot:nth-child(1) { background: #ff5f56; }
.mtb-dot:nth-child(2) { background: #ffbd2e; }
.mtb-dot:nth-child(3) { background: #27c93f; }
.mtb-title {
    flex: 1;
    text-align: center;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    letter-spacing: 0.08em;
}
.mtb-live {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.7);
}
.mtb-ldot { width: 6px; height: 6px; background: var(--green2); border-radius: 50%; animation: pulse2 2s infinite; }

.mock-body { padding: 18px; display: flex; flex-direction: column; gap: 12px; background: var(--bg); }

.kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.kpi {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 14px;
    transition: all .3s;
    position: relative;
    overflow: hidden;
}
.kpi::before { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; background: var(--grad); transform: scaleX(0); transform-origin: left; transition: transform .4s; }
.kpi:hover::before { transform: scaleX(1); }
.kpi[data-grad="teal"] { --grad: linear-gradient(90deg, var(--teal), var(--teal2)) }
.kpi[data-grad="green"] { --grad: linear-gradient(90deg, var(--green), var(--green2)) }
.kpi[data-grad="amber"] { --grad: linear-gradient(90deg, var(--amber), #f5c842) }
.kpi[data-grad="purple"] { --grad: linear-gradient(90deg, #7c3aed, #a78bfa) }
.kpi-val { font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 900; color: var(--text); line-height: 1; }
.kpi-label { font-family: 'JetBrains Mono', monospace; font-size: 0.58rem; color: var(--text3); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.08em; }
.kpi-delta { font-size: 0.65rem; font-weight: 700; margin-top: 5px; display: flex; align-items: center; gap: 3px; }
.up { color: var(--green); }
.dn { color: #e53e3e; }

.chart-wrap {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 16px;
}
.chart-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
.chart-title { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; font-weight: 600; color: var(--text2); text-transform: uppercase; letter-spacing: 0.1em; }
.chart-tabs { display: flex; gap: 2px; background: var(--bg); border: 1px solid var(--border); border-radius: 6px; padding: 2px; }
.ct-tab { font-family: 'JetBrains Mono', monospace; font-size: 0.58rem; padding: 3px 8px; border-radius: 4px; font-weight: 600; color: var(--text3); cursor: default; transition: all .2s; }
.ct-tab.active { background: var(--teal); color: #fff; box-shadow: 0 2px 6px rgba(13, 92, 106, 0.3); }
.chart-body { display: flex; gap: 6px; height: 80px; align-items: flex-end; }
.c-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px; }
.c-bar { width: 100%; border-radius: 4px 4px 0 0; min-height: 4px; position: relative; overflow: hidden; }
.c-bar.tbar { background: linear-gradient(to top, var(--teal), var(--teal2)); }
.c-bar.gbar { background: linear-gradient(to top, var(--green), var(--green2)); }
.c-lbl { font-family: 'JetBrains Mono', monospace; font-size: 0.5rem; color: var(--text3); }

.mock-lower { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.mock-map {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 14px;
}
.mm-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.mm-title { font-family: 'JetBrains Mono', monospace; font-size: 0.62rem; font-weight: 600; color: var(--text2); text-transform: uppercase; letter-spacing: 0.08em; }
.mm-badge { background: var(--green3); color: var(--green); padding: 2px 8px; border-radius: 100px; font-size: 0.58rem; font-weight: 700; border: 1px solid rgba(82, 168, 13, 0.2); }
.activity-list { display: flex; flex-direction: column; gap: 8px; }
.act-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.7rem;
    transition: all .25s;
}
.act-row:hover { border-color: var(--teal3); background: var(--teal4); }
.act-av {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Fraunces', serif;
    font-size: 0.65rem;
    font-weight: 700;
    color: #fff;
}
.act-info { flex: 1; }
.act-name { font-weight: 600; color: var(--text); font-size: 0.68rem; }
.act-det { color: var(--text3); font-size: 0.6rem; margin-top: 1px; }
.act-amt { font-weight: 700; font-size: 0.7rem; flex-shrink: 0; }

.mini-donut-wrap { display: flex; flex-direction: column; gap: 8px; margin-top: 4px; }
.donut-row { display: flex; align-items: center; gap: 10px; }
.donut-label { font-size: 0.65rem; color: var(--text2); font-weight: 500; flex: 1; font-family: 'JetBrains Mono', monospace; }
.donut-bar-bg { flex: 2; height: 5px; background: var(--bg3); border-radius: 3px; overflow: hidden; }
.donut-bar-fill { height: 100%; border-radius: 3px; transition: width .8s cubic-bezier(.34, 1.56, .64, 1); }
.donut-pct { font-family: 'JetBrains Mono', monospace; font-size: 0.6rem; color: var(--text3); font-weight: 600; flex-shrink: 0; min-width: 28px; text-align: right; }

.dash-bullets { display: flex; flex-direction: column; gap: 12px; margin-top: 40px; }
.db-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 20px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 14px;
    transition: all .3s;
}
.db-item:hover { border-color: var(--teal3); box-shadow: var(--sh2); transform: translateX(4px); }
.db-icon {
    width: 36px;
    height: 36px;
    background: var(--teal4);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid var(--teal3);
}
.db-icon svg { width: 16px; height: 16px; color: var(--teal); }
.db-item h4 { font-size: 0.9rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.db-item p { font-size: 0.82rem; color: var(--text2); line-height: 1.65; }

/* ── PROCESS ── */
.process-bg { background: var(--bg2); position: relative; }
.process-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
}
@media(max-width:900px) { .process-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:540px) { .process-grid { grid-template-columns: 1fr; } }
.process-grid::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 12.5%;
    right: 12.5%;
    height: 1px;
    background: repeating-linear-gradient(90deg, var(--border2) 0, var(--border2) 6px, transparent 6px, transparent 14px);
}
@media(max-width:900px) { .process-grid::after { display: none; } }
.p-step { text-align: center; position: relative; z-index: 1; }
.p-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--bg);
    border: 2px solid var(--border2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    margin: 0 auto 28px;
    transition: all .4s cubic-bezier(.34, 1.56, .64, 1);
    position: relative;
    box-shadow: var(--sh1);
}
.p-step:hover .p-circle { background: var(--teal); border-color: var(--teal); box-shadow: 0 8px 32px rgba(13, 92, 106, 0.3); }
.p-step:hover .p-num { color: rgba(255, 255, 255, 0.6); }
.p-step:hover .p-circle svg { color: #fff; }
.p-num { font-family: 'JetBrains Mono', monospace; font-size: 0.58rem; font-weight: 700; color: var(--teal); letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 3px; }
.p-circle svg { width: 22px; height: 22px; color: var(--teal2); transition: color .4s; }
.p-step h4 { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }
.p-step p { font-size: 0.82rem; color: var(--text2); line-height: 1.7; }

/* ── PRICING ── */
.pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; max-width: 860px; margin: 0 auto; }
.p-card {
    background: var(--bg2);
    border: 1.5px solid var(--border);
    border-radius: var(--r3);
    padding: 44px 36px;
    position: relative;
    transition: all .4s cubic-bezier(.34, 1.2, .64, 1);
    overflow: hidden;
}
.p-card-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(ellipse at 50% 50%, rgba(255, 255, 255, 0.08), transparent 60%);
    opacity: 0;
    transition: opacity .4s;
    pointer-events: none;
}
.p-card:hover .p-card-shine { opacity: 1; }
.p-card:hover { transform: translateY(-10px); box-shadow: var(--sh3); }
.p-card.feat {
    border-color: var(--teal2);
    background: linear-gradient(160deg, #fff 0%, var(--teal4) 100%);
    box-shadow: 0 8px 40px rgba(13, 92, 106, 0.14);
}
.feat-ribbon {
    position: absolute;
    top: 0;
    right: 0;
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    color: #fff;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 6px 20px 6px 14px;
    border-radius: 0 var(--r3) 0 12px;
}
.pname { font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; color: var(--text3); font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 12px; }
.pprice { display: flex; align-items: flex-start; gap: 3px; margin-bottom: 8px; }
.pcur { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 700; color: var(--text2); margin-top: 8px; }
.pamt { font-family: 'Fraunces', serif; font-size: 3rem; font-weight: 900; color: var(--text); line-height: 1; }
.pper { font-size: 0.85rem; color: var(--text3); align-self: flex-end; margin-bottom: 5px; font-weight: 500; }
.pdesc { font-size: 0.88rem; color: var(--text2); line-height: 1.7; margin-bottom: 28px; }
.pfeats { list-style: none; margin-bottom: 36px; display: flex; flex-direction: column; gap: 12px; }
.pfeats li { display: flex; align-items: flex-start; gap: 10px; font-size: 0.86rem; color: var(--text2); font-weight: 400; }
.pfeats li svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 2px; }
.chk { color: var(--green); }
.crx { color: var(--border2); }
.pfeats .cross-i { color: var(--text3); opacity: .7; }
.btn-pln {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    font-size: 0.83rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    border: none;
    transition: all .3s;
    text-decoration: none;
    display: block;
    text-align: center;
}
.btn-pln-out { background: transparent; color: var(--text2); border: 1.5px solid var(--border2); }
.btn-pln-out:hover { border-color: var(--teal2); color: var(--teal); }
.btn-pln-fill {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(13, 92, 106, 0.3);
}
.btn-pln-fill:hover { box-shadow: 0 10px 32px rgba(13, 92, 106, 0.45); transform: translateY(-2px); }

/* ── TESTIMONIALS ── */
.testi-bg { background: var(--bg3); position: relative; overflow: hidden; }
.t-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; max-width: 1200px; margin: 0 auto; }
@media(max-width:1000px) { .t-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:600px) { .t-grid { grid-template-columns: 1fr; } }
.t-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r2);
    padding: 32px;
    transition: all .4s cubic-bezier(.34, 1.2, .64, 1);
    position: relative;
    overflow: hidden;
}
.t-card:hover { transform: translateY(-8px); box-shadow: var(--sh3); border-color: var(--border2); }
.t-bg-char {
    position: absolute;
    top: -16px;
    right: 12px;
    font-family: 'Fraunces', serif;
    font-size: 8rem;
    color: rgba(13, 92, 106, 0.04);
    line-height: 1;
    font-weight: 900;
    pointer-events: none;
    user-select: none;
}
.t-stars { display: flex; gap: 3px; margin-bottom: 18px; }
.t-star { width: 14px; height: 14px; color: #f5a623; }
.t-text { font-size: 0.9rem; color: var(--text2); line-height: 1.8; margin-bottom: 24px; font-weight: 400; position: relative; z-index: 1; }
.t-author { display: flex; align-items: center; gap: 12px; }
.t-av {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.t-name { font-size: 0.88rem; font-weight: 700; color: var(--text); }
.t-role { font-size: 0.73rem; color: var(--text3); margin-top: 2px; }
.t-verified {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 4px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.6rem;
    color: var(--green);
    font-weight: 600;
    letter-spacing: 0.04em;
}
.t-verified svg { width: 10px; height: 10px; }

/* ── IMPACT NUMBERS ── */
.impact-section { background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%); position: relative; overflow: hidden; }
.impact-grid-bg {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
    background-size: 50px 50px;
}
.impact-inner { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; }
.impact-title-wrap { text-align: center; margin-bottom: 64px; }
.impact-h {
    font-family: 'Fraunces', serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    letter-spacing: -0.03em;
    color: #fff;
    line-height: 1.1;
}
.impact-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px; border-radius: var(--r2); overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.12); }
@media(max-width: 800px) { .impact-grid { grid-template-columns: repeat(2, 1fr); } }
.imp-item {
    padding: 40px 28px;
    text-align: center;
    background: rgba(255, 255, 255, 0.06);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    transition: background .3s;
}
.imp-item:last-child { border-right: none; }
.imp-item:hover { background: rgba(255, 255, 255, 0.12); }
.imp-num { font-family: 'Fraunces', serif; font-size: 2.8rem; font-weight: 900; color: #fff; line-height: 1; display: block; }
.imp-label { font-size: 0.75rem; color: rgba(255, 255, 255, 0.65); margin-top: 8px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; }
.imp-sub { font-size: 0.7rem; color: rgba(255, 255, 255, 0.4); margin-top: 4px; }

/* ── CTA ── */
.cta-card {
    max-width: 1200px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--text) 0%, #1a3540 100%);
    border-radius: var(--r3);
    padding: 80px 5%;
    position: relative;
    overflow: hidden;
}
.cta-grid-bg {
    position: absolute;
    inset: 0;
    background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
    background-size: 60px 60px;
}
.cta-inner { max-width: 700px; margin: 0 auto; text-align: center; position: relative; z-index: 1; }
.cta-inner h2 { font-family: 'Fraunces', serif; font-size: clamp(2rem, 4vw, 3.2rem); font-weight: 900; letter-spacing: -0.03em; color: #fff; line-height: 1.1; margin-bottom: 16px; }
.cta-inner p { color: rgba(255, 255, 255, 0.65); font-size: 1.05rem; line-height: 1.75; margin-bottom: 44px; }
.cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.btn-cta-w {
    background: #fff;
    color: var(--text) !important;
    padding: 15px 34px;
    border-radius: 11px;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-decoration: none;
    border: none;
    transition: all .3s;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.btn-cta-w:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35); }
.btn-cta-o {
    background: transparent;
    color: #fff !important;
    padding: 15px 34px;
    border-radius: 11px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid rgba(255, 255, 255, 0.3);
    transition: all .3s;
    display: inline-flex;
    align-items: center; gap: 9px;
}
.btn-cta-o:hover { border-color: rgba(255, 255, 255, 0.7); background: rgba(255, 255, 255, 0.08); transform: translateY(-3px); }
.cta-contacts { display: flex; gap: 28px; justify-content: center; margin-top: 40px; flex-wrap: wrap; }
.cta-ct { display: flex; align-items: center; gap: 8px; color: rgba(255, 255, 255, 0.5); font-size: 0.85rem; text-decoration: none; transition: color .3s; font-weight: 500; }
.cta-ct:hover { color: rgba(255, 255, 255, 0.9); }
.cta-ct svg { width: 15px; height: 15px; }

/* INQUIRY FORM STYLES */
.inquiry-form {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--r2);
    padding: 32px;
    margin-top: 48px;
    text-align: left;
    backdrop-filter: blur(10px);
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media(max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.form-group label {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 0.1em;
}
.form-group input, .form-group select, .form-group textarea {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    padding: 12px 16px;
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    outline: none;
    transition: all .3s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: var(--teal2);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 4px rgba(26, 125, 144, 0.2);
}
.form-group select option {
    background: #07131a;
    color: #fff;
}
.custom-select {
    position: relative;
    user-select: none;
}
.select-trigger {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    padding: 12px 16px;
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all .3s;
}
.custom-select.open .select-trigger {
    border-color: var(--teal2);
    background: rgba(255, 255, 255, 0.08);
}
.chevron { width: 14px; height: 14px; transition: transform .3s; }
.chevron.rotate { transform: rotate(180deg); }

.select-options-wrap {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #0d1e26;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 8px;
    z-index: 100;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    max-height: 240px;
    overflow-y: auto;
}
.select-options-wrap.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.select-opt {
    padding: 10px 12px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background .2s;
}
.select-opt:hover {
    background: rgba(255,255,255,0.05);
}
.opt-main { display: flex; align-items: center; gap: 10px; }
.opt-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--teal2); }
.opt-name { font-weight: 500; font-size: 0.9rem; color: #fff; }
.opt-price { font-size: 0.75rem; color: rgba(255,255,255,0.5); font-family: 'JetBrains Mono', monospace; }

.err-field {
    border-color: #ff5f56 !important;
    box-shadow: 0 0 0 4px rgba(255, 95, 86, 0.1) !important;
    background: rgba(255, 95, 86, 0.05) !important;
}

.custom-select.err-field .select-trigger {
    border-color: #ff5f56 !important;
    background: rgba(255, 95, 86, 0.05) !important;
}

.err {
    color: #ff5f56;
    font-size: 0.75rem;
    margin-top: 4px;
}

/* ── FOOTER ── */
footer { background: #07131a; padding: 72px 5% 32px; }
.foot-inner { max-width: 1200px; margin: 0 auto; }
.foot-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 56px; margin-bottom: 64px; }
@media(max-width: 900px) { .foot-top { grid-template-columns: 1fr 1fr; gap: 32px; } }
@media(max-width: 540px) { .foot-top { grid-template-columns: 1fr; } }
.foot-brand { max-width: 260px; }
.foot-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; }
.foot-logo .logo-mark { width: 34px; height: 34px; }
.foot-logo .logo-text { color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; }
.foot-brand p { font-size: 0.83rem; color: rgba(255, 255, 255, 0.35); line-height: 1.75; }
.foot-social { display: flex; gap: 8px; margin-top: 24px; }
.soc-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .3s;
    text-decoration: none;
}
.soc-btn:hover { background: var(--teal); border-color: var(--teal); }
.soc-btn svg { width: 15px; height: 15px; color: rgba(255, 255, 255, 0.5); transition: color .3s; }
.soc-btn:hover svg { color: #fff; }
.foot-col h5 { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; font-weight: 600; color: rgba(255, 255, 255, 0.35); letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 18px; }
.foot-col a { display: block; color: rgba(255, 255, 255, 0.45); text-decoration: none; font-size: 0.85rem; margin-bottom: 12px; transition: color .3s; font-weight: 400; }
.foot-col a:hover { color: rgba(255, 255, 255, 0.9); }
.foot-bottom { border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; }
.foot-copy { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: rgba(255, 255, 255, 0.25); }
.foot-legal { display: flex; gap: 20px; }
.foot-legal a { font-size: 0.78rem; color: rgba(255, 255, 255, 0.25); text-decoration: none; transition: color .3s; }
.foot-legal a:hover { color: rgba(255, 255, 255, 0.6); }

/* ── CHATBOT ── */
.chat-trigger {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 5000;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    border: none;
    box-shadow: 0 8px 32px rgba(13, 92, 106, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .35s cubic-bezier(.34, 1.56, .64, 1);
}
.chat-trigger:hover { transform: scale(1.12); box-shadow: 0 16px 48px rgba(13, 92, 106, 0.55); }
.chat-trigger svg { width: 26px; height: 26px; color: #fff; transition: all .3s; }
.chat-pulse { position: absolute; top: 8px; right: 0px; width: 11px; height: 11px; background: var(--green2); border: 2px solid #fff; border-radius: 50%; animation: pulse2 2s infinite; }

.chat-window {
    position: fixed;
    bottom: 112px;
    right: 32px;
    z-index: 4999;
    width: 360px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--r3);
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(0, 0, 0, 0.18);
    transform: scale(0.88) translateY(24px);
    transform-origin: bottom right;
    opacity: 0;
    pointer-events: none;
    transition: all .4s cubic-bezier(.34, 1.56, .64, 1);
}
.chat-window.open { transform: scale(1) translateY(0); opacity: 1; pointer-events: all; }

.chat-head {
    background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}
.chat-av { width: 44px; height: 44px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: 2px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.chat-av svg { width: 22px; height: 22px; color: #fff; }
.chat-head-info h4 { font-size: 0.9rem; font-weight: 700; color: #fff; font-family: 'Fraunces', serif; }
.chat-head-info p { font-size: 0.7rem; color: rgba(255, 255, 255, 0.75); margin-top: 3px; display: flex; align-items: center; gap: 6px; }
.online-dot { width: 6px; height: 6px; background: var(--green2); border-radius: 50%; animation: pulse2 2s infinite; }

.chat-msgs { padding: 16px; height: 290px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; background: var(--bg); }
.msg { max-width: 82%; display: flex; flex-direction: column; gap: 3px; }
.msg.bot { align-self: flex-start; }
.msg.user { align-self: flex-end; }
.msg-bubble { padding: 10px 14px; border-radius: 14px; font-size: 0.82rem; line-height: 1.6; font-weight: 400; }
.msg.bot .msg-bubble { background: var(--bg2); color: var(--text); border: 1px solid var(--border); border-bottom-left-radius: 4px; }
.msg.user .msg-bubble { background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%); color: #fff; border-bottom-right-radius: 4px; }
.msg-time { font-size: 0.6rem; color: var(--text3); font-weight: 500; padding: 0 4px; }
.msg.user .msg-time { text-align: right; }
.typing .msg-bubble { background: var(--bg2); border: 1px solid var(--border); padding: 12px 16px; display: flex; flex-direction: row; gap: 6px; align-items: center; width: fit-content; }
.t-dot { width: 7px; height: 7px; min-width: 7px; background: var(--text3); border-radius: 50%; display: inline-block; animation: tdot 1.4s ease infinite; }
.t-dot:nth-child(2) { animation-delay: .2s; }
.t-dot:nth-child(3) { animation-delay: .4s; }
@keyframes tdot { 0%, 80%, 100% { transform: translateY(0); opacity: .5; } 40% { transform: translateY(-5px); opacity: 1; } }

.chat-suggestions { padding: 10px 12px 10px; display: flex; flex-wrap: wrap; gap: 6px; }
.sug-btn { background: var(--teal4); color: var(--teal); border: 1px solid var(--teal3); padding: 5px 12px; border-radius: 100px; font-size: 0.73rem; font-weight: 600; transition: all .25s; white-space: nowrap; font-family: 'DM Sans', sans-serif; }
.sug-btn:hover { background: var(--teal); color: #fff; border-color: var(--teal); }

.chat-input-wrap { padding: 12px 14px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 8px; background: var(--bg2); }
.chat-input { flex: 1; border: 1px solid var(--border); border-radius: 9px; padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 0.82rem; background: var(--bg); color: var(--text); outline: none; transition: border-color .3s; resize: none; height: 40px; }
.chat-input:focus { border-color: var(--teal2); }
.chat-send { width: 38px; height: 38px; background: linear-gradient(135deg, var(--teal) 0%, var(--teal2) 100%); border: none; border-radius: 9px; display: flex; align-items: center; justify-content: center; transition: all .25s; flex-shrink: 0; }
.chat-send:hover { box-shadow: 0 4px 14px rgba(13, 92, 106, 0.4); }
.chat-send svg { width: 16px; height: 16px; color: #fff; }

@media(max-width: 500px) { .chat-window { width: calc(100vw - 32px); right: 16px; bottom: 100px; } .chat-trigger { bottom: 20px; right: 20px; } }

/* ── ANIMATIONS ── */
@keyframes fadeUp { from { opacity: 0; transform: translateY(32px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.reveal { opacity: 0; transform: translateY(32px); transition: opacity .7s ease, transform .7s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }
.rd1 { transition-delay: .08s; }
.rd2 { transition-delay: .18s; }
.rd3 { transition-delay: .28s; }
.rd4 { transition-delay: .38s; }
</style>
