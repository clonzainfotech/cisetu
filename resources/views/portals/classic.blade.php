<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @php
        $portalTheme = $portalTheme ?? 'classic';

        $themes = [
            'classic' => [
                'brand' => ['50' => '#f0f5fe', '100' => '#e3edfc', '200' => '#c1d8fc', '300' => '#8eb8f9', '400' => '#5692f5', '500' => '#3b82f6', '600' => '#2563eb', '800' => '#1e40af', '900' => '#1e3a8a', '950' => '#172554'],
                'pageBg' => '#f8fafc',
                'fontSans' => 'Outfit',
                'surface' => 'rgba(255, 255, 255, 0.70)',
                'surfaceBorder' => 'rgba(255, 255, 255, 0.80)',
                'heroBlobA' => 'rgba(59, 130, 246, 0.40)',
                'heroBlobB' => 'rgba(37, 211, 102, 0.28)',
            ],
            'vibrant' => [
                'brand' => ['50' => '#eef2ff', '100' => '#e0e7ff', '200' => '#c7d2fe', '300' => '#a5b4fc', '400' => '#818cf8', '500' => '#6366f1', '600' => '#4f46e5', '800' => '#3730a3', '900' => '#312e81', '950' => '#1e1b4b'],
                'pageBg' => '#f8fafc',
                'fontSans' => 'Plus Jakarta Sans',
                'surface' => 'rgba(255, 255, 255, 0.62)',
                'surfaceBorder' => 'rgba(255, 255, 255, 0.72)',
                'heroBlobA' => 'rgba(99, 102, 241, 0.42)',
                'heroBlobB' => 'rgba(244, 63, 94, 0.22)',
            ],
            'dark' => [
                'brand' => ['50' => '#f4f4f5', '100' => '#e4e4e7', '200' => '#d4d4d8', '300' => '#a1a1aa', '400' => '#71717a', '500' => '#52525b', '600' => '#3f3f46', '800' => '#27272a', '900' => '#18181b', '950' => '#09090b'],
                'pageBg' => '#0b1220',
                'fontSans' => 'Inter',
                'surface' => 'rgba(15, 23, 42, 0.55)',
                'surfaceBorder' => 'rgba(148, 163, 184, 0.18)',
                'heroBlobA' => 'rgba(99, 102, 241, 0.20)',
                'heroBlobB' => 'rgba(37, 211, 102, 0.14)',
            ],
            'eco' => [
                'brand' => ['50' => '#ecfdf5', '100' => '#d1fae5', '200' => '#a7f3d0', '300' => '#6ee7b7', '400' => '#34d399', '500' => '#10b981', '600' => '#059669', '800' => '#065f46', '900' => '#064e3b', '950' => '#022c22'],
                'pageBg' => '#f7faf9',
                'fontSans' => 'Outfit',
                'surface' => 'rgba(255, 255, 255, 0.68)',
                'surfaceBorder' => 'rgba(16, 185, 129, 0.18)',
                'heroBlobA' => 'rgba(16, 185, 129, 0.22)',
                'heroBlobB' => 'rgba(59, 130, 246, 0.12)',
            ],
            'royal' => [
                'brand' => ['50' => '#faf5ff', '100' => '#f3e8ff', '200' => '#e9d5ff', '300' => '#d8b4fe', '400' => '#c084fc', '500' => '#a855f7', '600' => '#9333ea', '800' => '#6b21a8', '900' => '#581c87', '950' => '#3b0764'],
                'pageBg' => '#fbfaff',
                'fontSans' => 'Plus Jakarta Sans',
                'surface' => 'rgba(255, 255, 255, 0.66)',
                'surfaceBorder' => 'rgba(168, 85, 247, 0.18)',
                'heroBlobA' => 'rgba(168, 85, 247, 0.24)',
                'heroBlobB' => 'rgba(244, 63, 94, 0.12)',
            ],
            'gradient' => [
                'brand' => ['50' => '#eff6ff', '100' => '#dbeafe', '200' => '#bfdbfe', '300' => '#93c5fd', '400' => '#60a5fa', '500' => '#3b82f6', '600' => '#2563eb', '800' => '#1e40af', '900' => '#1e3a8a', '950' => '#172554'],
                'pageBg' => '#f8fafc',
            ],
            'glass' => [
                'brand' => ['50' => '#f0f9ff', '100' => '#e0f2fe', '200' => '#bae6fd', '300' => '#7dd3fc', '400' => '#38bdf8', '500' => '#0ea5e9', '600' => '#0284c7', '800' => '#075985', '900' => '#0c4a6e', '950' => '#082f49'],
                'pageBg' => '#f8fafc',
            ],
            'modern' => [
                'brand' => ['50' => '#f5f3ff', '100' => '#ede9fe', '200' => '#ddd6fe', '300' => '#c4b5fd', '400' => '#a78bfa', '500' => '#8b5cf6', '600' => '#7c3aed', '800' => '#5b21b6', '900' => '#4c1d95', '950' => '#2e1065'],
                'pageBg' => '#fafafa',
            ],
            'minimal' => [
                'brand' => ['50' => '#f8fafc', '100' => '#f1f5f9', '200' => '#e2e8f0', '300' => '#cbd5e1', '400' => '#94a3b8', '500' => '#64748b', '600' => '#475569', '800' => '#1f2937', '900' => '#0f172a', '950' => '#020617'],
                'pageBg' => '#ffffff',
            ],
            'compact' => [
                'brand' => ['50' => '#fff7ed', '100' => '#ffedd5', '200' => '#fed7aa', '300' => '#fdba74', '400' => '#fb923c', '500' => '#f97316', '600' => '#ea580c', '800' => '#9a3412', '900' => '#7c2d12', '950' => '#431407'],
                'pageBg' => '#fffaf5',
            ],
            'corporate' => [
                'brand' => ['50' => '#f1f5f9', '100' => '#e2e8f0', '200' => '#cbd5e1', '300' => '#94a3b8', '400' => '#64748b', '500' => '#334155', '600' => '#1f2937', '800' => '#111827', '900' => '#0b1220', '950' => '#050a12'],
                'pageBg' => '#f8fafc',
            ],
            'simple' => [
                'brand' => ['50' => '#f8fafc', '100' => '#f1f5f9', '200' => '#e2e8f0', '300' => '#cbd5e1', '400' => '#94a3b8', '500' => '#64748b', '600' => '#475569', '800' => '#1f2937', '900' => '#0f172a', '950' => '#020617'],
                'pageBg' => '#f8fafc',
            ],
        ];

        $theme = $themes[$portalTheme] ?? $themes['classic'];
    @endphp

    <title>{{ $village->name_local }} Gram Panchayat | Digital Property Tax Portal</title>
    <meta name="description" content="Official Digital Portal for {{ $village->name_en }} Gram Panchayat. Pay your property tax instantly and securely via WhatsApp.">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: @js($theme['brand']['50']),
                            100: @js($theme['brand']['100']),
                            200: @js($theme['brand']['200']),
                            300: @js($theme['brand']['300']),
                            400: @js($theme['brand']['400']),
                            500: @js($theme['brand']['500']),
                            600: @js($theme['brand']['600']),
                            800: @js($theme['brand']['800']),
                            900: @js($theme['brand']['900']),
                            950: @js($theme['brand']['950']),
                        },
                        whatsapp: {
                            DEFAULT: '#25D366',
                            dark: '#128C7E',
                            light: '#DCF8C6'
                        }
                    },
                    fontFamily: {
                        sans: [@js($theme['fontSans'] ?? 'Outfit'), 'sans-serif'],
                        gujarati: ['Noto Sans Gujarati', 'sans-serif'],
                    },
                    boxShadow: {
                        'app': '0 -4px 20px rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 40px -10px rgba(23, 37, 84, 0.1)',
                        'glow': '0 0 20px rgba(59, 130, 246, 0.5)',
                        'glow-whatsapp': '0 0 20px rgba(37, 211, 102, 0.4)',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-x': 'bounce-x 1s infinite',
                        'blob': 'blob 7s infinite',
                        'shimmer': 'shimmer 2.5s infinite linear',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        'bounce-x': {
                            '0%, 100%': { transform: 'translateX(0)', animationTimingFunction: 'cubic-bezier(0.8,0,1,1)' },
                            '50%': { transform: 'translateX(25%)', animationTimingFunction: 'cubic-bezier(0,0,0.2,1)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        shimmer: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(100%)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: {{ $theme['pageBg'] }};
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        :root {
            --portal-surface: {{ $theme['surface'] ?? 'rgba(255, 255, 255, 0.70)' }};
            --portal-surface-border: {{ $theme['surfaceBorder'] ?? 'rgba(255, 255, 255, 0.80)' }};
            --portal-blob-a: {{ $theme['heroBlobA'] ?? 'rgba(59, 130, 246, 0.40)' }};
            --portal-blob-b: {{ $theme['heroBlobB'] ?? 'rgba(37, 211, 102, 0.28)' }};
        }
        
        /* Modern Glass Background */
        .bg-glass {
            background: var(--portal-surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--portal-surface-border);
        }

        /* Nav Floating Transition */
        #main-nav { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-scrolled {
            top: 1rem !important;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Shimmer Effect Utility */
        .overflow-hidden-shimmer { position: relative; overflow: hidden; }
        .overflow-hidden-shimmer::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            animation: shimmer 2.5s infinite linear;
            transform: skewX(-20deg);
        }

        /* Hide scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom) + 80px); }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white pb-safe md:pb-0 relative">

    <!-- Desktop & Top Navigation (Floating Island Style) -->
    <nav class="fixed top-0 left-0 right-0 w-full z-50 pt-4" id="main-nav">
        <div class="max-w-7xl mx-auto px-4 relative transition-all duration-300 rounded-full" id="nav-container">
            <div class="bg-glass rounded-full shadow-lg px-4 sm:px-6 flex justify-between items-center h-20 border border-white/40 relative overflow-hidden">
                <!-- Subtle glow effect inside nav -->
                <div class="absolute inset-0 bg-gradient-to-r from-brand-50/50 via-transparent to-whatsapp-light/30"></div>
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3 cursor-pointer relative z-10" onclick="window.scrollTo(0,0)">
                    
                    <!-- Uploaded Image Logo Integration -->
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-full shadow-md border-2 border-brand-100 flex-shrink-0 relative overflow-hidden group">
                        @if($village->logo)
                            <img src="{{ asset('storage/'.$village->logo) }}" alt="{{ $village->name_local }} Logo" class="w-full h-full object-cover rounded-full transform group-hover:scale-110 transition-transform duration-500">
                        @else
                            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ $village->name_local }} Logo" class="w-full h-full object-cover rounded-full transform group-hover:scale-110 transition-transform duration-500" onerror="this.style.display='none'; document.getElementById('svg-fallback-nav').style.display='block';">
                        @endif
                        
                        <!-- SVG Fallback (Triggers if image isn't loaded in local environment) -->
                        <svg id="svg-fallback-nav" style="display:none;" viewBox="0 0 100 100" class="w-full h-full bg-white p-0.5">
                            <circle cx="50" cy="50" r="48" fill="#ffffff" />
                            <circle cx="50" cy="50" r="46" fill="none" stroke="#1e3a8a" stroke-width="2"/>
                            <path id="navTxtCurve1" fill="none" d="M 18 55 A 32 32 0 1 1 82 55" />
                            <text font-family="Noto Sans Gujarati" font-size="8" font-weight="800" fill="#1e3a8a">
                                <textPath xlink:href="#navTxtCurve1" startOffset="50%" text-anchor="middle">ગ્રામ પંચાયત {{ $village->name_local }}</textPath>
                            </text>
                            <g transform="translate(25, 30) scale(0.5)">
                                <path d="M50 80 Q50 60 50 30 M30 75 Q50 65 70 75 M20 55 Q50 45 80 55 M25 40 Q50 25 75 40" fill="none" stroke="#1e3a8a" stroke-width="4" stroke-linecap="round"/>
                                <path d="M40 75 L60 75 L60 60 L50 45 L40 60 Z" fill="#1e3a8a"/>
                                <circle cx="50" cy="55" r="2.5" fill="white" />
                                <path d="M10 80 Q50 75 90 80" fill="none" stroke="#1e3a8a" stroke-width="3"/>
                            </g>
                            <path id="navBtmCurve1" fill="none" d="M 22 62 A 30 30 0 0 0 78 62" />
                            <text font-family="Noto Sans Gujarati" font-size="7.5" font-weight="800" fill="#1e3a8a">
                                <textPath xlink:href="#navBtmCurve1" startOffset="50%" text-anchor="middle">તા. જી. {{ $village->district->name_local ?? $village->district->name_en }}</textPath>
                            </text>
                        </svg>
                    </div>

                    <div>
                        <h1 class="font-bold text-brand-900 text-lg md:text-xl leading-tight font-gujarati tracking-tight">ગ્રામ પંચાયત {{ $village->name_local }} </h1>
                        <p class="text-[10px] md:text-xs font-bold text-brand-500 uppercase tracking-widest">Smart Village Portal</p>
                    </div>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-8 font-semibold text-sm text-slate-700 relative z-10">
                    <a href="#home" class="hover:text-brand-600 transition-colors relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#how-it-works" class="hover:text-brand-600 transition-colors relative group">
                        How to Pay
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#services" class="hover:text-brand-600 transition-colors relative group">
                        Services
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/login" class="hover:text-brand-600 transition-colors relative group">
                        Login
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="overflow-hidden-shimmer flex items-center gap-2 px-6 py-2.5 bg-whatsapp text-white rounded-full font-bold hover:bg-whatsapp-dark transition-all shadow-glow-whatsapp hover:-translate-y-0.5">
                        <i class="fa-brands fa-whatsapp text-lg"></i> Pay Tax Now
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Hero Section (With Animated Blobs) -->
    <section id="home" class="pt-32 pb-16 md:pt-40 md:pb-24 px-4 overflow-hidden min-h-[95vh] flex items-center relative">
        
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 w-full h-full overflow-hidden -z-10 bg-slate-50">
            <div class="absolute top-0 -left-10 w-96 h-96 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob" style="background: var(--portal-blob-a);"></div>
            <div class="absolute top-20 -right-10 w-96 h-96 rounded-full mix-blend-multiply filter blur-[80px] opacity-50 animate-blob animation-delay-2000" style="background: var(--portal-blob-b);"></div>
            <div class="absolute -bottom-32 left-1/2 w-96 h-96 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-4000" style="background: var(--portal-blob-a);"></div>
        </div>

        <div class="max-w-7xl mx-auto w-full relative z-10">
            <div class="flex flex-col-reverse md:flex-row items-center gap-12 md:gap-8">
                
                <!-- Hero Content -->
                <div class="w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left reveal">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-brand-100 shadow-sm text-brand-600 text-xs font-bold uppercase tracking-wide mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-whatsapp opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-whatsapp"></span>
                        </span>
                        Official Tax Payment Portal
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-brand-950 tracking-tight leading-[1.1] mb-6 font-gujarati drop-shadow-sm">
                        તમારો પ્રોપર્ટી વેરો <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-whatsapp">વોટ્સએપ</span> થી ભરો.
                    </h2>
                    
                    <p class="text-base md:text-lg text-slate-600 mb-8 max-w-xl leading-relaxed font-medium">
                        No more waiting in lines. {{ $village->name_local }} Gram Panchayat introduces an automated, highly secure WhatsApp system to view and pay your property tax instantly. Get your official digital receipt in seconds.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="overflow-hidden-shimmer w-full sm:w-auto px-8 py-4 bg-whatsapp text-white rounded-2xl font-bold text-lg hover:bg-whatsapp-dark transition-all shadow-glow-whatsapp flex items-center justify-center gap-3 group">
                            <i class="fa-brands fa-whatsapp text-2xl group-hover:rotate-12 transition-transform duration-300"></i>
                            Start on WhatsApp
                        </a>
                        <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-white/80 backdrop-blur-sm border border-slate-200 text-slate-700 rounded-2xl font-bold text-lg hover:bg-white hover:shadow-lg transition-all flex items-center justify-center gap-3 group">
                            <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-play text-sm ml-1"></i>
                            </div> 
                            Watch Demo
                        </a>
                    </div>

                    <div class="mt-10 flex items-center gap-6 text-sm font-bold text-slate-500 bg-white/50 px-6 py-3 rounded-2xl backdrop-blur-sm border border-white">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-check text-brand-500 text-lg"></i>
                            <span>100% Secure</span>
                        </div>
                        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-amber-500 text-lg"></i>
                            <span>Instant Receipt</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Illustration (Animated Tech) -->
                <div class="w-full md:w-1/2 relative reveal" style="transition-delay: 200ms;">
                    <div class="relative z-10 w-full max-w-lg mx-auto">
                        <!-- Custom SVG Illustration with floating elements -->
                        <svg viewBox="0 0 500 400" class="w-full h-auto drop-shadow-2xl">
                            <!-- Background elements -->
                            <rect x="50" y="50" width="400" height="300" rx="30" fill="rgba(255,255,255,0.9)" stroke="#e2e8f0" stroke-width="2"/>
                            
                            <!-- WhatsApp Interface Mockup (Floating) -->
                            <g class="animate-float">
                                <rect x="150" y="20" width="200" height="360" rx="24" fill="#f0f2f5" stroke="#cbd5e1" stroke-width="4"/>
                                <!-- Phone Top Bar -->
                                <path d="M 150 44 Q 150 20 174 20 L 326 20 Q 350 20 350 44 L 350 70 L 150 70 Z" fill="#075e54"/>
                                <circle cx="180" cy="45" r="12" fill="#ffffff" opacity="0.8"/>
                                <text x="200" y="50" font-family="Outfit, sans-serif" font-size="14" font-weight="bold" fill="#ffffff">{{ $village->name_en }} Panchayat</text>
                                
                                <!-- Chat Bubbles -->
                                <rect x="240" y="90" width="90" height="30" rx="10" fill="#dcf8c6" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.05))"/>
                                <text x="250" y="110" font-family="Outfit, sans-serif" font-size="12" font-weight="600" fill="#303030">Hi Panchayat</text>
                                
                                <rect x="160" y="130" width="160" height="60" rx="10" fill="#ffffff" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.05))"/>
                                <text x="170" y="150" font-family="Outfit, sans-serif" font-size="11" font-weight="600" fill="#303030">Welcome to {{ $village->name_local }} Digital!</text>
                                <text x="170" y="165" font-family="Outfit, sans-serif" font-size="11" fill="#475569">Your pending tax is ₹1250.</text>
                                <rect x="170" y="175" width="140" height="20" rx="6" fill="#3b82f6"/>
                                <text x="240" y="189" font-family="Outfit, sans-serif" font-size="10" font-weight="bold" fill="#ffffff" text-anchor="middle">Pay Now</text>
                            </g>

                            <!-- Success Modal Pop-out (Delayed Float) -->
                            <g transform="translate(260, 220)" class="animate-float-delayed">
                                <rect x="0" y="0" width="160" height="120" rx="16" fill="#ffffff" filter="drop-shadow(0 20px 25px rgba(0,0,0,0.15))" stroke="#f1f5f9" stroke-width="1"/>
                                <circle cx="80" cy="40" r="24" fill="#dcf8c6"/>
                                <path d="M 68 40 L 76 48 L 92 32" fill="none" stroke="#25D366" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <text x="80" y="85" font-family="Outfit, sans-serif" font-size="14" font-weight="800" fill="#1e293b" text-anchor="middle">Payment Successful</text>
                                <text x="80" y="100" font-family="Outfit, sans-serif" font-size="10" font-weight="600" fill="#64748b" text-anchor="middle">Receipt Downloaded</text>
                            </g>

                            <!-- Indian Village Scenery Accents (Static) -->
                            <g transform="translate(40, 260) scale(0.8)">
                                <!-- Trees -->
                                <path d="M 50 120 L 50 70" stroke="#78350f" stroke-width="8" stroke-linecap="round"/>
                                <circle cx="50" cy="60" r="30" fill="#22c55e" opacity="0.9"/>
                                <circle cx="30" cy="70" r="20" fill="#16a34a" opacity="0.9"/>
                                <circle cx="70" cy="70" r="20" fill="#15803d" opacity="0.9"/>
                                <!-- Small House -->
                                <path d="M 80 120 L 80 80 L 110 60 L 140 80 L 140 120 Z" fill="#fef08a"/>
                                <path d="M 70 80 L 110 50 L 150 80" fill="none" stroke="#ea580c" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="100" y="90" width="20" height="30" fill="#78350f"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats / Trust Band (Modernized) -->
    <div class="relative z-20 -mt-10 px-4">
        <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-card border border-slate-100 p-8 transform hover:-translate-y-1 transition-transform duration-300">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:divide-x divide-slate-100">
                <div class="text-center group">
                    <div class="w-10 h-10 mx-auto bg-brand-50 rounded-full flex items-center justify-center text-brand-500 mb-3 group-hover:scale-110 transition-transform"><i class="fa-solid fa-house-user"></i></div>
                    <h3 class="text-3xl font-black text-brand-900 mb-1">2,500+</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Homes Connected</p>
                </div>
                <div class="text-center group">
                    <div class="w-10 h-10 mx-auto bg-whatsapp/10 rounded-full flex items-center justify-center text-whatsapp mb-3 group-hover:scale-110 transition-transform"><i class="fa-solid fa-clock"></i></div>
                    <h3 class="text-3xl font-black text-brand-900 mb-1">24/7</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Uptime</p>
                </div>
                <div class="text-center group">
                    <div class="w-10 h-10 mx-auto bg-amber-50 rounded-full flex items-center justify-center text-amber-500 mb-3 group-hover:scale-110 transition-transform"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                    <h3 class="text-3xl font-black text-brand-900 mb-1">0%</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Convenience Fee</p>
                </div>
                <div class="text-center group">
                    <div class="w-10 h-10 mx-auto bg-brand-50 rounded-full flex items-center justify-center text-brand-500 mb-3 group-hover:scale-110 transition-transform"><i class="fa-solid fa-bullseye"></i></div>
                    <h3 class="text-3xl font-black text-brand-900 mb-1">100%</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Digital Accuracy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-24 md:py-32 px-4 relative">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-20 reveal">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-brand-600 text-[10px] font-black tracking-widest uppercase mb-4">Simple Process</div>
                <h3 class="text-4xl md:text-5xl font-extrabold text-brand-950 font-gujarati mb-6 tracking-tight">કરવેરો ભરવાની સરળ રીત</h3>
                <p class="text-lg text-slate-500 font-medium">Pay your property tax directly from your mobile phone in 3 simple steps. No app installation required.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connecting Line (Desktop only) -->
                <div class="hidden md:block absolute top-16 left-[15%] right-[15%] h-1 bg-gradient-to-r from-brand-100 via-brand-300 to-whatsapp/30 z-0 rounded-full"></div>

                <!-- Step 1 -->
                <div class="relative z-10 bg-glass rounded-[2rem] p-10 text-center shadow-app border border-white hover:border-brand-200 hover:shadow-card hover:-translate-y-3 transition-all duration-300 reveal group">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-whatsapp-light to-whatsapp text-white rounded-3xl flex items-center justify-center text-4xl mb-8 group-hover:rotate-6 transition-transform shadow-lg shadow-whatsapp/20">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-brand-900 text-white font-black text-lg flex items-center justify-center absolute -top-5 left-1/2 -translate-x-1/2 border-4 border-white shadow-sm">1</div>
                    <h5 class="text-2xl font-bold text-brand-950 mb-4 tracking-tight">Send a Message</h5>
                    <p class="text-slate-500 font-medium leading-relaxed">Simply send a "Hi" to our official Panchayat WhatsApp number to initiate the automated bot.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 bg-glass rounded-[2rem] p-10 text-center shadow-app border border-white hover:border-brand-200 hover:shadow-card hover:-translate-y-3 transition-all duration-300 reveal group" style="transition-delay: 150ms;">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-brand-100 to-brand-500 text-white rounded-3xl flex items-center justify-center text-4xl mb-8 group-hover:-rotate-6 transition-transform shadow-lg shadow-brand-500/20">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-brand-900 text-white font-black text-lg flex items-center justify-center absolute -top-5 left-1/2 -translate-x-1/2 border-4 border-white shadow-sm">2</div>
                    <h5 class="text-2xl font-bold text-brand-950 mb-4 tracking-tight">Verify Details</h5>
                    <p class="text-slate-500 font-medium leading-relaxed">Enter your house number or phone number to view your outstanding property & water tax instantly.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 bg-glass rounded-[2rem] p-10 text-center shadow-app border border-white hover:border-brand-200 hover:shadow-card hover:-translate-y-3 transition-all duration-300 reveal group" style="transition-delay: 300ms;">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-100 to-emerald-500 text-white rounded-3xl flex items-center justify-center text-4xl mb-8 group-hover:rotate-6 transition-transform shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-brand-900 text-white font-black text-lg flex items-center justify-center absolute -top-5 left-1/2 -translate-x-1/2 border-4 border-white shadow-sm">3</div>
                    <h5 class="text-2xl font-bold text-brand-950 mb-4 tracking-tight">Pay & Get Receipt</h5>
                    <p class="text-slate-500 font-medium leading-relaxed">Pay securely via UPI (GPay, PhonePe). The official signed PDF receipt will be sent on WhatsApp.</p>
                </div>
            </div>
            
            <div class="mt-16 text-center reveal">
                <button onclick="window.open('https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0', '_blank')" class="overflow-hidden-shimmer px-10 py-5 bg-brand-900 text-white rounded-full font-bold text-lg hover:bg-brand-800 transition-all shadow-xl shadow-brand-900/30 flex items-center justify-center gap-3 mx-auto group">
                    Try the Live Bot Now <i class="fa-solid fa-arrow-right animate-bounce-x text-brand-300"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Panchayat Services Grid -->
    <section id="services" class="py-24 md:py-32 px-4 bg-brand-950 relative overflow-hidden">
        <!-- Digital Grid Background for Modern Vibe -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:30px_30px] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_80%)]"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16 reveal">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-900/50 border border-brand-700 text-brand-300 text-[10px] font-black tracking-widest uppercase mb-4">Digital Governance</div>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-white font-gujarati mb-6 tracking-tight">ઓનલાઇન સેવાઓ</h3>
                    <p class="text-lg text-slate-400 font-medium">Bringing the Panchayat office directly to your smartphone. Accessible, transparent, and fast.</p>
                </div>
                <div class="bg-brand-800/50 border border-brand-600 px-5 py-3 rounded-xl text-sm font-bold text-brand-200 flex items-center gap-3 shadow-glow">
                    <i class="fa-solid fa-certificate text-brand-400 text-lg"></i> ISO Certified Process
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Service 1 -->
                <div class="bg-brand-900/40 backdrop-blur-md border border-brand-800 p-8 rounded-3xl hover:border-brand-500 hover:bg-brand-900 transition-all duration-300 group cursor-pointer reveal relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl group-hover:bg-brand-500/20 transition-all"></div>
                    <div class="w-14 h-14 bg-brand-800 rounded-2xl flex items-center justify-center text-brand-400 mb-6 group-hover:scale-110 group-hover:text-white transition-all shadow-lg">
                        <i class="fa-solid fa-house-chimney text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 font-gujarati">મિલકત વેરો</h4>
                    <p class="text-sm text-slate-400 font-medium mb-6 leading-relaxed">View and pay yearly property taxes for residential and commercial spaces.</p>
                    <span class="text-brand-400 font-bold flex items-center gap-2 group-hover:gap-3 transition-all group-hover:text-brand-300">
                        Pay Now <i class="fa-solid fa-arrow-right text-sm"></i>
                    </span>
                </div>

                <!-- Service 2 -->
                <div class="bg-brand-900/40 backdrop-blur-md border border-brand-800 p-8 rounded-3xl hover:border-blue-500 hover:bg-brand-900 transition-all duration-300 group cursor-pointer reveal relative overflow-hidden" style="transition-delay: 100ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="w-14 h-14 bg-brand-800 rounded-2xl flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 group-hover:text-white transition-all shadow-lg">
                        <i class="fa-solid fa-droplet text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 font-gujarati">પાણી વેરો</h4>
                    <p class="text-sm text-slate-400 font-medium mb-6 leading-relaxed">Clear your water connection bills seamlessly without visiting the office.</p>
                    <span class="text-blue-400 font-bold flex items-center gap-2 group-hover:gap-3 transition-all group-hover:text-blue-300">
                        Pay Now <i class="fa-solid fa-arrow-right text-sm"></i>
                    </span>
                </div>

                <!-- Service 3 -->
                <div class="bg-brand-900/40 backdrop-blur-md border border-brand-800 p-8 rounded-3xl hover:border-amber-500 hover:bg-brand-900 transition-all duration-300 group cursor-pointer reveal relative overflow-hidden" style="transition-delay: 200ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
                    <div class="w-14 h-14 bg-brand-800 rounded-2xl flex items-center justify-center text-amber-400 mb-6 group-hover:scale-110 group-hover:text-white transition-all shadow-lg">
                        <i class="fa-solid fa-bullhorn text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 font-gujarati">ગ્રામ પંચાયત નોટિસ</h4>
                    <p class="text-sm text-slate-400 font-medium mb-6 leading-relaxed">Receive important announcements and Gram Sabha schedules directly.</p>
                    <span class="text-amber-400 font-bold flex items-center gap-2 group-hover:gap-3 transition-all group-hover:text-amber-300">
                        Subscribe <i class="fa-solid fa-arrow-right text-sm"></i>
                    </span>
                </div>

                <!-- Service 4 -->
                <div class="bg-brand-900/40 backdrop-blur-md border border-brand-800 p-8 rounded-3xl transition-all duration-300 group reveal relative overflow-hidden opacity-80" style="transition-delay: 300ms;">
                    <div class="w-14 h-14 bg-brand-800/50 rounded-2xl flex items-center justify-center text-slate-500 mb-6 shadow-inner">
                        <i class="fa-solid fa-file-signature text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 font-gujarati">પ્રમાણપત્રો</h4>
                    <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">Apply for and download official certificates (Coming soon in Phase 2).</p>
                    <span class="text-slate-500 font-bold flex items-center gap-2 bg-brand-900 w-max px-3 py-1 rounded-md text-xs tracking-widest uppercase">
                        <i class="fa-solid fa-lock text-xs"></i> Locked
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Initiative / Tech Provider Info -->
    <section class="py-20 border-t border-slate-200 bg-white px-4">
        <div class="max-w-4xl mx-auto text-center reveal">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-brand-50 text-brand-600 mb-6 shadow-inner">
                <i class="fa-solid fa-microchip text-2xl"></i>
            </div>
            <h3 class="text-3xl font-extrabold text-brand-950 mb-6 tracking-tight">Empowering Digital India</h3>
            <p class="text-lg text-slate-600 mb-8 leading-relaxed font-medium max-w-2xl mx-auto">
                {{ $village->name_local }} Gram Panchayat is proud to be a pioneer in smart village governance. This highly secure, scalable, and user-friendly digital framework is powered by the <strong>CISETU Platform</strong>.
            </p>
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-slate-50 border border-slate-200 rounded-full text-xs font-black text-slate-400 uppercase tracking-[0.2em]">
                <span>Technology Partner:</span>
                <span class="text-brand-600">Clonza Infotech</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-950 text-slate-300 pt-20 pb-28 md:pb-12 px-4 relative border-t-[6px] border-brand-500 overflow-hidden">
        <!-- Footer Background Glow -->
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-brand-600/20 blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <!-- Branding -->
                <div class="md:col-span-5">
                    <div class="flex items-center gap-4 mb-8">
                        
                        <!-- Logo inside Footer -->
                        <div class="w-14 h-14 bg-white rounded-full p-0.5 shadow-lg flex-shrink-0 relative overflow-hidden">
                            @if($village->logo)
                                <img src="{{ asset('storage/'.$village->logo) }}" alt="{{ $village->name_local }} Logo" class="w-full h-full object-cover rounded-full">
                            @else
                                <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ $village->name_local }} Logo" class="w-full h-full object-cover rounded-full" onerror="this.style.display='none'; document.getElementById('svg-fallback-footer').style.display='block';">
                            @endif
                            <!-- SVG Fallback -->
                            <svg id="svg-fallback-footer" style="display:none;" viewBox="0 0 100 100" class="w-full h-full bg-white">
                                <circle cx="50" cy="50" r="48" fill="#ffffff" />
                                <circle cx="50" cy="50" r="46" fill="none" stroke="#1e3a8a" stroke-width="2"/>
                                <path d="M50 30 Q50 60 50 70 M40 65 Q50 60 60 65" fill="none" stroke="#1e3a8a" stroke-width="3" stroke-linecap="round"/>
                                <path d="M40 75 L60 75 L60 60 L50 45 L40 60 Z" fill="#1e3a8a"/>
                                <text x="50" y="88" font-family="Noto Sans Gujarati" font-size="7" font-weight="900" text-anchor="middle" fill="#1e3a8a">તા. જી. {{ $village->district->name_local ?? $village->district->name_en }}</text>
                            </svg>
                        </div>

                        <div>
                            <h4 class="font-bold text-white text-2xl font-gujarati tracking-tight">ગ્રામ પંચાયત {{ $village->name_local }}</h4>
                            <p class="text-brand-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Digital Citizen Portal</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-base font-medium leading-relaxed mb-8 max-w-sm">
                        Committed to transparent governance and making citizen services completely accessible through modern digital solutions.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 rounded-full bg-brand-900 border border-brand-800 flex items-center justify-center text-white hover:bg-brand-500 hover:-translate-y-1 transition-all shadow-lg"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-12 h-12 rounded-full bg-brand-900 border border-brand-800 flex items-center justify-center text-white hover:bg-brand-500 hover:-translate-y-1 transition-all shadow-lg"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div class="md:col-span-3">
                    <h5 class="text-white font-bold text-lg mb-8 tracking-wide">Quick Links</h5>
                    <ul class="space-y-4 font-medium text-slate-400">
                        <li><a href="#home" class="hover:text-brand-300 transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Home</a></li>
                        <li><a href="#how-it-works" class="hover:text-brand-300 transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> How to Pay Tax</a></li>
                        <li><a href="#services" class="hover:text-brand-300 transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Other Services</a></li>
                        <li><a href="#" class="hover:text-brand-300 transition-colors flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="md:col-span-4">
                    <h5 class="text-white font-bold text-lg mb-8 tracking-wide font-gujarati">સંપર્ક માહિતી</h5>
                    <ul class="space-y-6 text-slate-400 font-medium">
                        <li class="flex items-start gap-4 bg-brand-900/50 p-4 rounded-2xl border border-brand-800">
                            <div class="w-10 h-10 rounded-full bg-brand-800/50 flex items-center justify-center text-brand-400 flex-shrink-0"><i class="fa-solid fa-location-dot"></i></div>
                            <span class="font-gujarati leading-relaxed mt-1">ગ્રામ પંચાયત કચેરી, {{ $village->name_local }},<br>તાલુકો અને જિલ્લો: {{ $village->district->name_local ?? $village->district->name_en }}, ગુજરાત</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-brand-900 border border-brand-800 flex items-center justify-center text-brand-400 flex-shrink-0"><i class="fa-solid fa-envelope"></i></div>
                            <a href="mailto:info@gpt.cisetu.com" class="hover:text-white transition-colors">info@gpt.cisetu.com</a>
                        </li>
                        @if($village->whatsapp_number)
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-brand-900 border border-brand-800 flex items-center justify-center text-brand-400 flex-shrink-0"><i class="fa-solid fa-phone"></i></div>
                            <a href="tel:+{{ $village->whatsapp_number }}" class="hover:text-white transition-colors font-bold tracking-wide">+{{ $village->whatsapp_number }}</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-brand-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium text-slate-500">
                <p>&copy; {{date('Y')}} CISETU. All Rights Reserved.</p>
                <p class="flex items-center gap-2">Powered by <strong class="text-brand-300 px-2 py-1 bg-brand-900 rounded-md">CISETU / Clonza Infotech</strong></p>
            </div>
        </div>
    </footer>

    <!-- App-Like Mobile Bottom Navigation (Floating) -->
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-white/90 backdrop-blur-xl border border-white/50 rounded-3xl shadow-app z-[100] px-4 py-3 flex justify-between items-center pb-safe-area">
        
        <a href="#home" class="mobile-nav-link flex flex-col items-center gap-1.5 text-brand-600 transition-colors w-16 group">
            <div class="group-hover:-translate-y-1 transition-transform"><i class="fa-solid fa-house text-xl"></i></div>
            <span class="text-[9px] font-bold tracking-wider uppercase">Home</span>
        </a>
        
        <a href="#how-it-works" class="mobile-nav-link flex flex-col items-center gap-1.5 text-slate-400 hover:text-brand-600 transition-colors w-16 group">
            <div class="group-hover:-translate-y-1 transition-transform"><i class="fa-solid fa-circle-info text-xl"></i></div>
            <span class="text-[9px] font-bold tracking-wider uppercase">Guide</span>
        </a>
        
        <!-- Center Floating Action Button (Elevated) -->
        <div class="relative -top-8 mx-2">
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="w-16 h-16 bg-gradient-to-tr from-whatsapp-dark to-whatsapp rounded-full flex items-center justify-center text-white text-3xl shadow-glow-whatsapp border-4 border-white hover:scale-105 active:scale-95 transition-all">
                <i class="fa-brands fa-whatsapp animate-pulse-slow"></i>
            </a>
            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] font-black text-brand-900 w-max font-gujarati tracking-wide bg-white px-2 py-0.5 rounded-md shadow-sm">વેરો ભરો</span>
        </div>
        
        <a href="#services" class="mobile-nav-link flex flex-col items-center gap-1.5 text-slate-400 hover:text-brand-600 transition-colors w-16 group">
            <div class="group-hover:-translate-y-1 transition-transform"><i class="fa-solid fa-layer-group text-xl"></i></div>
            <span class="text-[9px] font-bold tracking-wider uppercase">Services</span>
        </a>
        
        <a href="/login" class="mobile-nav-link flex flex-col items-center gap-1.5 text-slate-400 hover:text-brand-600 transition-colors w-16 group">
            <div class="group-hover:-translate-y-1 transition-transform"><i class="fa-solid fa-user text-xl"></i></div>
            <span class="text-[9px] font-bold tracking-wider uppercase">Login</span>
        </a>
    </div>

    <script>
        // Scroll Reveal Animation Logic
        const revealElements = document.querySelectorAll('.reveal');
        
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        revealElements.forEach(el => revealObserver.observe(el));

        // Desktop Navbar Scroll Effect (Floating Island behavior)
        const nav = document.getElementById('main-nav');
        const navContainer = document.getElementById('nav-container');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                nav.classList.add('nav-scrolled');
                navContainer.classList.add('max-w-5xl');
                navContainer.classList.remove('max-w-7xl');
            } else {
                nav.classList.remove('nav-scrolled');
                navContainer.classList.add('max-w-7xl');
                navContainer.classList.remove('max-w-5xl');
            }
        });

        // Mobile Nav Active State Sync
        const sections = document.querySelectorAll('section');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');

        const navObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    mobileLinks.forEach(link => {
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('text-brand-600');
                            link.classList.remove('text-slate-400');
                        } else if (link.getAttribute('href').startsWith('#')) {
                            link.classList.remove('text-brand-600');
                            link.classList.add('text-slate-400');
                        }
                    });
                }
            });
        }, { threshold: 0.3, rootMargin: "-20% 0px -20% 0px" });

        sections.forEach(section => {
            navObserver.observe(section);
        });
    </script>
</body>
</html>
