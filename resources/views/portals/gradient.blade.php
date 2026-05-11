<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $village->name_en }} | Digital Property Tax Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=Sora:wght@400;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --emerald:   #00c896;
            --emerald-2: #00a87e;
            --emerald-3: #007d5e;
            --sky:       #38c0fc;
            --violet:    #a78bfa;
            --gold:      #fbbf24;
            --coral:     #fb7185;

            --dark:      #030b14;
            --dark-2:    #061020;
            --dark-3:    #0a1a2e;
            --panel:     rgba(255,255,255,0.04);
            --panel-b:   rgba(255,255,255,0.07);
            --panel-hov: rgba(255,255,255,0.09);

            --white:     #ffffff;
            --muted:     rgba(255,255,255,0.45);
            --faint:     rgba(255,255,255,0.15);
            --rule:      rgba(255,255,255,0.08);

            --r-sm: 10px;
            --r-md: 18px;
            --r-lg: 28px;
            --r-xl: 40px;

            --g-main: linear-gradient(135deg, #00c896 0%, #38c0fc 60%, #a78bfa 100%);
            --g-gold: linear-gradient(135deg, #fbbf24 0%, #f87171 100%);
            --g-cool: linear-gradient(135deg, #38c0fc 0%, #a78bfa 100%);
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--dark);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            line-height: 1.65;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        /* Radial ambient glows */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 80vw 60vh at 10% 10%, rgba(0,200,150,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 60vw 50vh at 90% 5%, rgba(56,192,252,0.10) 0%, transparent 70%),
                radial-gradient(ellipse 70vw 60vh at 50% 100%, rgba(167,139,250,0.09) 0%, transparent 70%);
        }

        /* Noise grain overlay */
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.018;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 256px 256px;
        }

        .font-gu { font-family: 'Noto Sans Gujarati', sans-serif; }
        .font-disp { font-family: 'Sora', sans-serif; }

        /* ── GRADIENT TEXT UTIL ── */
        .g-text {
            background: var(--g-main);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .g-text-cool {
            background: var(--g-cool);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .g-text-gold {
            background: var(--g-gold);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── NAV ── */
        #nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(3,11,20,0.7);
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border-bottom: 1px solid var(--rule);
            transition: background 0.3s;
        }
        #nav.scrolled { background: rgba(3,11,20,0.9); }

        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 70px; padding: 0 28px;
        }

        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo-badge {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--g-main);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }
        .nav-name {
            font-family: 'Sora', sans-serif;
            font-size: 15px; font-weight: 700; color: var(--white);
            line-height: 1.2;
        }
        .nav-sub {
            font-size: 10px; font-weight: 500; color: var(--muted);
            letter-spacing: 0.08em; text-transform: uppercase; margin-top: 2px;
        }

        .nav-links { display: none; gap: 36px; }
        @media (min-width: 768px) { .nav-links { display: flex; } }
        .nav-links a {
            font-size: 14px; font-weight: 500; color: var(--muted);
            text-decoration: none; transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--white); }

        .btn-nav {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; border-radius: 50px;
            background: var(--g-main);
            color: var(--dark-2); font-family: 'Sora', sans-serif;
            font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
            text-decoration: none; border: none; cursor: pointer;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: 0 0 24px rgba(0,200,150,0.3);
        }
        .btn-nav:hover { opacity: 0.88; transform: translateY(-1px); }

        /* ── HERO ── */
        #home {
            position: relative; z-index: 1;
            padding: 140px 28px 120px;
            min-height: 100vh;
            display: flex; flex-direction: column; justify-content: center;
        }

        .hero-inner { max-width: 1280px; margin: 0 auto; }

        .hero-grid {
            display: grid; grid-template-columns: 1fr;
            gap: 64px; align-items: center;
        }
        @media (min-width: 960px) { .hero-grid { grid-template-columns: 1fr 480px; gap: 80px; } }

        /* TICKER */
        .ticker-strip {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 6px 16px; border-radius: 50px;
            background: var(--panel);
            border: 1px solid var(--faint);
            font-size: 12px; font-weight: 500; color: var(--muted);
            margin-bottom: 28px; width: fit-content; backdrop-filter: blur(12px);
        }
        .ticker-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--emerald); flex-shrink: 0;
            box-shadow: 0 0 10px var(--emerald);
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0%,100%{opacity:1;box-shadow:0 0 8px var(--emerald)}
            50%{opacity:0.6;box-shadow:0 0 18px var(--emerald)}
        }

        .hero-h1 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(44px, 6.5vw, 88px);
            font-weight: 800; line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 24px;
        }

        .hero-gu-line {
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: clamp(18px, 2.5vw, 26px);
            font-weight: 600; color: var(--muted);
            margin-bottom: 28px;
        }

        .hero-p {
            font-size: 16px; color: var(--muted);
            line-height: 1.8; max-width: 520px; margin-bottom: 42px;
        }

        .hero-ctas { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 52px; }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 32px; border-radius: 50px;
            background: var(--g-main);
            color: #021a12; font-family: 'Sora', sans-serif;
            font-size: 15px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 0 6px 40px rgba(0,200,150,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 50px rgba(0,200,150,0.45); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 15px 30px; border-radius: 50px;
            background: transparent;
            border: 1px solid var(--faint); color: var(--white);
            font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover { border-color: rgba(255,255,255,0.35); background: var(--panel); }

        /* Trust badges */
        .trust-badges {
            display: flex; flex-wrap: wrap; gap: 18px;
            padding-top: 32px; border-top: 1px solid var(--rule);
        }
        .trust-badge {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 500; color: var(--muted);
        }
        .trust-badge-icon {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--panel); border: 1px solid var(--faint);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
        }

        /* ── PHONE CARD ── */
        .hero-right-wrap { position: relative; display: flex; justify-content: center; }

        /* Glow rings behind phone */
        .hero-right-wrap::before {
            content: '';
            position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
            width: 380px; height: 380px; border-radius: 50%;
            background: radial-gradient(circle, rgba(0,200,150,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .phone-frame {
            width: 270px; position: relative;
            filter: drop-shadow(0 30px 80px rgba(0,0,0,0.6));
            animation: float-gently 7s ease-in-out infinite;
        }
        @keyframes float-gently {
            0%,100%{transform:translateY(0) rotate(-1deg)}
            50%{transform:translateY(-18px) rotate(1deg)}
        }

        .phone-shell {
            background: linear-gradient(145deg, #0e2236, #061020);
            border-radius: 32px;
            border: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
        }

        .ph-notch-bar {
            height: 44px; padding: 0 20px;
            background: rgba(0,200,150,0.08);
            border-bottom: 1px solid rgba(0,200,150,0.15);
            display: flex; align-items: center; gap: 10px;
        }
        .ph-av-g {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--g-main);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-size: 10px; font-weight: 800; color: #021a12;
        }
        .ph-head-name { font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 600; color: var(--white); }
        .ph-head-status {
            font-size: 9px; color: var(--emerald); display: flex; align-items: center; gap: 3px;
        }
        .ph-s-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--emerald); animation: pulse-glow 2s infinite; }

        .ph-body { padding: 16px 14px; display: flex; flex-direction: column; gap: 10px; min-height: 280px; }

        .bubble {
            max-width: 85%; padding: 10px 13px;
            font-size: 11px; line-height: 1.55; border-radius: 14px;
        }
        .b-in {
            background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.8);
            align-self: flex-start; border-radius: 4px 14px 14px 14px;
            border-left: 2px solid var(--emerald);
        }
        .b-out {
            background: linear-gradient(135deg, rgba(0,200,150,0.25), rgba(56,192,252,0.2));
            color: var(--white); align-self: flex-end;
            border-radius: 14px 4px 14px 14px;
            border: 1px solid rgba(0,200,150,0.25);
        }

        .ph-tax-card {
            align-self: flex-start; width: 88%;
            background: linear-gradient(145deg, rgba(0,200,150,0.1), rgba(56,192,252,0.06));
            border: 1px solid rgba(0,200,150,0.2);
            border-radius: 14px; padding: 14px;
        }
        .ph-tax-lbl { font-size: 9px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 6px; }
        .ph-tax-amt {
            font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 800;
            background: var(--g-main);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            line-height: 1; margin-bottom: 4px;
        }
        .ph-tax-sub { font-size: 9px; color: var(--muted); margin-bottom: 12px; }
        .ph-pay-btn {
            display: block; width: 100%; padding: 9px;
            background: var(--g-main); border: none; border-radius: 10px;
            font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 700; color: #021a12;
            cursor: pointer; text-align: center; letter-spacing: 0.02em;
        }

        /* Floating chips */
        .f-chip {
            position: absolute; z-index: 10;
            background: rgba(3,11,20,0.85); backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px; padding: 10px 16px;
            font-family: 'Sora', sans-serif;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .f-chip .cv { font-size: 20px; font-weight: 800; }
        .f-chip .cl { font-size: 9px; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 2px; }
        .chip-a { top: 40px; right: -20px; animation: chip-float 7s ease-in-out 1.5s infinite; }
        .chip-b { bottom: 60px; left: -20px; animation: chip-float 7s ease-in-out 4s infinite; }
        @keyframes chip-float {
            0%,100%{transform:translateY(0)}
            50%{transform:translateY(-10px)}
        }

        /* ── STATS ── */
        #stats { position: relative; z-index: 1; }
        .stats-strip {
            border-top: 1px solid var(--rule); border-bottom: 1px solid var(--rule);
            background: linear-gradient(90deg, rgba(0,200,150,0.04), rgba(56,192,252,0.04), rgba(167,139,250,0.04));
        }
        .stats-inner { max-width: 1280px; margin: 0 auto; }
        .stats-grid {
            display: grid; grid-template-columns: repeat(2,1fr);
        }
        @media (min-width: 700px) { .stats-grid { grid-template-columns: repeat(4,1fr); } }
        .stat-cell {
            padding: 36px 28px; text-align: center;
            border-right: 1px solid var(--rule);
            position: relative; overflow: hidden;
        }
        .stat-cell:last-child { border-right: none; }
        .stat-cell::before {
            content:'';
            position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 60%; height: 2px;
            background: var(--g-main); opacity: 0; transition: opacity 0.3s;
        }
        .stat-cell:hover::before { opacity: 1; }
        .stat-n {
            font-family: 'Sora', sans-serif;
            font-size: 42px; font-weight: 800; line-height: 1;
            background: var(--g-main);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            margin-bottom: 8px;
        }
        .stat-l {
            font-size: 11px; font-weight: 500; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.12em;
        }

        /* ── SECTION BASE ── */
        .sec-label {
            display: inline-flex; align-items: center; gap: 10px;
            font-size: 11px; font-weight: 600; color: var(--emerald);
            text-transform: uppercase; letter-spacing: 0.16em; margin-bottom: 18px;
        }
        .sec-label::before {
            content:'';
            width: 28px; height: 1.5px;
            background: linear-gradient(90deg, var(--emerald), transparent);
        }
        .sec-h2 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(32px, 4.5vw, 58px);
            font-weight: 800; line-height: 1.08; letter-spacing: -0.025em;
            margin-bottom: 18px;
        }
        .sec-p { font-size: 15px; color: var(--muted); line-height: 1.8; }

        /* ── HOW IT WORKS ── */
        #how-it-works {
            padding: 120px 28px; position: relative; z-index: 1;
        }
        .hiw-inner { max-width: 1280px; margin: 0 auto; }
        .hiw-hdr { text-align: center; max-width: 580px; margin: 0 auto 72px; }

        .steps-wrap {
            display: grid; grid-template-columns: 1fr; gap: 3px;
        }
        @media (min-width: 720px) { .steps-wrap { grid-template-columns: repeat(3,1fr); } }

        .step-card {
            background: var(--panel);
            border: 1px solid var(--rule);
            border-radius: var(--r-lg);
            padding: 40px 32px;
            position: relative; overflow: hidden;
            transition: background 0.3s, transform 0.3s;
            cursor: default;
        }
        .step-card::before {
            content:'';
            position: absolute; inset: 0; opacity: 0;
            background: linear-gradient(135deg, rgba(0,200,150,0.08), rgba(56,192,252,0.05));
            transition: opacity 0.3s;
        }
        .step-card:hover::before { opacity: 1; }
        .step-card:hover { transform: translateY(-4px); }

        /* Gradient top border on hover */
        .step-card::after {
            content:'';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: var(--g-main); opacity: 0; transition: opacity 0.3s;
        }
        .step-card:hover::after { opacity: 1; }

        .step-num {
            font-family: 'Sora', sans-serif;
            font-size: 11px; font-weight: 700; color: var(--faint);
            text-transform: uppercase; letter-spacing: 0.14em; margin-bottom: 24px;
        }
        .step-icon {
            width: 56px; height: 56px; border-radius: 16px; margin-bottom: 22px;
            display: flex; align-items: center; justify-content: center; font-size: 24px;
        }
        .si-g { background: linear-gradient(135deg, rgba(0,200,150,0.15), rgba(56,192,252,0.12)); color: var(--emerald); }
        .si-s { background: linear-gradient(135deg, rgba(56,192,252,0.15), rgba(167,139,250,0.12)); color: var(--sky); }

        .step-h {
            font-family: 'Sora', sans-serif;
            font-size: 20px; font-weight: 700; margin-bottom: 12px;
        }
        .step-p { font-size: 14px; color: var(--muted); line-height: 1.75; }

        .hiw-cta { text-align: center; margin-top: 56px; }

        /* ── SERVICES ── */
        #services {
            padding: 120px 28px;
            position: relative; z-index: 1;
            background: linear-gradient(180deg, transparent 0%, rgba(0,200,150,0.03) 50%, transparent 100%);
        }

        .svc-inner { max-width: 1280px; margin: 0 auto; }
        .svc-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 24px; margin-bottom: 56px; }

        .iso-pill {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 18px; border-radius: 50px;
            border: 1px solid rgba(0,200,150,0.25);
            background: rgba(0,200,150,0.07);
            font-size: 12px; font-weight: 600; color: var(--emerald);
            letter-spacing: 0.08em; flex-shrink: 0;
        }

        .svc-grid {
            display: grid; grid-template-columns: 1fr; gap: 16px;
        }
        @media (min-width: 600px) { .svc-grid { grid-template-columns: repeat(2,1fr); } }
        @media (min-width: 1000px) { .svc-grid { grid-template-columns: repeat(4,1fr); } }

        .svc-card {
            background: var(--panel);
            border: 1px solid var(--rule);
            border-radius: var(--r-lg);
            padding: 32px 28px;
            position: relative; overflow: hidden;
            transition: transform 0.3s, background 0.3s;
            cursor: pointer;
        }
        .svc-card:hover { transform: translateY(-5px); background: var(--panel-hov); }

        /* Accent glow strip */
        .svc-card .glow-top {
            position: absolute; top: 0; left: 0; right: 0; height: 1.5px;
            transition: opacity 0.3s;
        }
        .svc-card:hover .glow-top { opacity: 1 !important; }

        .svc-num { font-size: 10px; font-weight: 600; color: var(--faint); text-transform: uppercase; letter-spacing: 0.14em; margin-bottom: 22px; }
        .svc-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 18px;
        }
        .svc-h {
            font-family: 'Sora', sans-serif;
            font-size: 18px; font-weight: 700; margin-bottom: 10px; line-height: 1.2;
        }
        .svc-p { font-size: 13px; color: var(--muted); line-height: 1.7; margin-bottom: 26px; }
        .svc-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; text-decoration: none;
            transition: gap 0.2s;
        }
        .svc-link:hover { gap: 10px; }
        .svc-locked {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 600; color: var(--faint);
            padding: 5px 14px; border-radius: 50px;
            border: 1px solid var(--rule);
        }

        /* ── INITIATIVE ── */
        #initiative {
            padding: 120px 28px; position: relative; z-index: 1;
            border-top: 1px solid var(--rule);
        }
        .init-inner { max-width: 1280px; margin: 0 auto; }
        .init-grid {
            display: grid; grid-template-columns: 1fr; gap: 56px; align-items: center;
        }
        @media (min-width: 820px) { .init-grid { grid-template-columns: 1fr 1fr; gap: 90px; } }

        .init-p { font-size: 15px; color: var(--muted); line-height: 1.85; margin-bottom: 28px; }
        .tech-badge {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 500; color: var(--muted);
        }
        .tech-badge span { color: var(--emerald); font-weight: 700; }

        .init-panel {
            background: linear-gradient(145deg, rgba(0,200,150,0.06), rgba(56,192,252,0.04), rgba(167,139,250,0.05));
            border: 1px solid rgba(0,200,150,0.15);
            border-radius: var(--r-xl); padding: 40px 36px;
            position: relative; overflow: hidden;
        }
        .init-panel::before {
            content:'';
            position: absolute; top: -80px; right: -80px;
            width: 220px; height: 220px; border-radius: 50%;
            background: radial-gradient(circle, rgba(0,200,150,0.1), transparent 70%);
            pointer-events: none;
        }

        .init-stat-row {
            display: flex; align-items: center; gap: 20px;
            padding: 18px 0; border-bottom: 1px solid var(--rule);
            position: relative; z-index: 1;
        }
        .init-stat-row:last-child { border-bottom: none; padding-bottom: 0; }
        .is-icon {
            width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .is-1 { background: linear-gradient(135deg, rgba(0,200,150,0.15), rgba(56,192,252,0.1)); color: var(--emerald); }
        .is-2 { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.5); }
        .is-3 { background: linear-gradient(135deg, rgba(251,191,36,0.12), rgba(251,113,133,0.08)); color: var(--gold); }
        .is-val {
            font-family: 'Sora', sans-serif;
            font-size: 30px; font-weight: 800;
            background: var(--g-main);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            line-height: 1;
        }
        .is-lbl { font-size: 11px; color: var(--muted); margin-top: 4px; }

        /* ── FOOTER ── */
        footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--rule);
            padding: 80px 28px 56px;
        }

        /* Gradient line on top */
        footer::before {
            content:'';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: var(--g-main);
        }

        .foot-inner { max-width: 1280px; margin: 0 auto; }
        .foot-grid {
            display: grid; grid-template-columns: 1fr; gap: 52px; margin-bottom: 60px;
        }
        @media (min-width: 900px) { .foot-grid { grid-template-columns: 2fr 1fr 1.4fr; gap: 72px; } }

        .foot-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
        .foot-logo-badge {
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--g-main);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 800; color: #021a12;
        }
        .foot-name { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700; color: var(--white); }
        .foot-sub { font-size: 10px; font-weight: 500; color: var(--emerald); letter-spacing: 0.1em; text-transform: uppercase; margin-top: 2px; }
        .foot-desc { font-size: 13px; color: var(--muted); line-height: 1.8; max-width: 300px; margin-bottom: 26px; }

        .soc-row { display: flex; gap: 8px; }
        .soc-btn {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--panel); border: 1px solid var(--rule);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 14px; text-decoration: none;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .soc-btn:hover { background: rgba(0,200,150,0.12); color: var(--emerald); border-color: rgba(0,200,150,0.25); }

        .foot-h {
            font-size: 11px; font-weight: 600; color: var(--faint);
            text-transform: uppercase; letter-spacing: 0.16em; margin-bottom: 24px;
        }
        .foot-links { list-style: none; display: flex; flex-direction: column; gap: 14px; }
        .foot-links a {
            font-size: 14px; color: var(--muted); text-decoration: none;
            display: flex; align-items: center; gap: 8px;
            transition: color 0.2s;
        }
        .foot-links a::before {
            content:''; display: inline-block;
            width: 14px; height: 1px;
            background: var(--emerald); opacity: 0.5; flex-shrink: 0;
        }
        .foot-links a:hover { color: var(--white); }

        .cinfo {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 16px; border-radius: var(--r-md);
            background: var(--panel); border: 1px solid var(--rule);
            margin-bottom: 10px; transition: border-color 0.2s;
        }
        .cinfo:hover { border-color: rgba(0,200,150,0.2); }
        .cinfo-i {
            width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
            background: rgba(0,200,150,0.1); color: var(--emerald);
            display: flex; align-items: center; justify-content: center; font-size: 13px;
        }
        .cinfo-t { font-size: 13px; color: var(--muted); line-height: 1.65; }
        .cinfo-t a { color: var(--muted); text-decoration: none; }
        .cinfo-t a:hover { color: var(--white); }

        .foot-bottom {
            padding-top: 28px; border-top: 1px solid var(--rule);
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;
        }
        .foot-copy { font-size: 12px; color: var(--faint); }
        .foot-powered {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: var(--faint);
        }
        .foot-powered em {
            font-style: normal;
            background: var(--g-main);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            font-weight: 700;
        }

        /* ── MOBILE NAV ── */
        #mobile-nav {
            display: flex; position: fixed; bottom: 10px; left: 10px; right: 10px; z-index: 100;
            background: rgba(6,16,32,0.9);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 6px 4px;
            justify-content: space-around; align-items: center;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5);
        }
        @media (min-width: 768px) { #mobile-nav { display: none; } }

        .mob-lnk {
            display: flex; flex-direction: column; align-items: center; gap: 3px;
            color: var(--muted); text-decoration: none; font-size: 9px; font-weight: 600;
            letter-spacing: 0.08em; text-transform: uppercase;
            width: 56px; padding: 8px 0; border-radius: 12px;
            transition: color 0.2s, background 0.2s;
        }
        .mob-lnk i { font-size: 18px; }
        .mob-lnk.active, .mob-lnk:hover { color: var(--emerald); background: rgba(0,200,150,0.08); }

        .mob-fab { position: relative; top: -12px; }
        .mob-fab a {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--g-main);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #021a12; text-decoration: none;
            box-shadow: 0 0 28px rgba(0,200,150,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .mob-fab a:active { transform: scale(0.93); }

        .pb-mob { padding-bottom: 96px; }
        @media (min-width: 768px) { .pb-mob { padding-bottom: 0; } }

        /* ── REVEAL ── */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.vis { opacity: 1; transform: none; }
        .reveal.d1 { transition-delay: 0.1s; }
        .reveal.d2 { transition-delay: 0.22s; }
        .reveal.d3 { transition-delay: 0.36s; }

        /* ── DIVIDER ── */
        .g-divider {
            width: 100%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,200,150,0.3), rgba(56,192,252,0.25), transparent);
            border: none;
        }
    </style>
</head>
<body class="pb-mob">

    <!-- NAV -->
    <nav id="nav" role="navigation" aria-label="Main navigation">
        <div class="nav-inner">
            <a class="nav-logo" href="#home">
                <div class="nav-logo-badge">
                    @if($village->logo)
                        <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                    @else
                        {{ substr($village->name_en, 0, 1) }}
                    @endif
                </div>
                <div>
                    <div class="nav-name font-disp">GRAM PANCHAYAT {{ strtoupper($village->name_en) }}</div>
                    <div class="nav-sub">Smart Village Portal</div>
                </div>
            </a>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#how-it-works">How to Pay</a>
                <a href="#services">Services</a>
            </div>
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-nav" rel="noopener">
                <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section id="home" aria-labelledby="hero-h">
        <div class="hero-inner">
            <div class="hero-grid">

                <!-- Left content -->
                <div class="reveal">
                    <div class="ticker-strip">
                        <span class="ticker-dot"></span>
                        Official Portal &nbsp;·&nbsp; Live &amp; Secure &nbsp;·&nbsp; Powered by CISETU
                    </div>

                    <h1 id="hero-h" class="hero-h1 font-disp">
                        Pay Your<br>
                        Property Tax<br>
                        <span class="g-text">Instantly.</span>
                    </h1>

                    <div class="hero-gu-line font-gu">
                        {{ $village->name_local }} · ડિજિટલ નાગરિક પોર્ટલ
                    </div>

                    <p class="hero-p">
                        A fully automated, secure WhatsApp system — view and pay your property tax from any phone, any time. No app installation required. Zero convenience fee.
                    </p>

                    <div class="hero-ctas">
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-primary" rel="noopener">
                            <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
                            Start on WhatsApp
                        </a>
                        <a href="#how-it-works" class="btn-outline">
                            How It Works <i class="fa-solid fa-arrow-right" style="font-size:13px;"></i>
                        </a>
                    </div>

                    <div class="trust-badges">
                        <div class="trust-badge">
                            <div class="trust-badge-icon"><i class="fa-solid fa-shield-halved" style="color:var(--emerald);font-size:12px;"></i></div>
                            100% Secure
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon"><i class="fa-solid fa-receipt" style="color:var(--sky);font-size:12px;"></i></div>
                            Instant Receipt
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon"><i class="fa-solid fa-indian-rupee-sign" style="color:var(--gold);font-size:12px;"></i></div>
                            Zero Extra Fee
                        </div>
                        <div class="trust-badge">
                            <div class="trust-badge-icon"><i class="fa-solid fa-clock" style="color:var(--violet);font-size:12px;"></i></div>
                            Available 24/7
                        </div>
                    </div>
                </div>

                <!-- Right phone -->
                <div class="hero-right-wrap reveal d2">
                    <!-- Floating chips -->
                    <div class="f-chip chip-a" aria-hidden="true">
                        <div class="cv g-text-cool">24/7</div>
                        <div class="cl">Always On</div>
                    </div>
                    <div class="f-chip chip-b" aria-hidden="true">
                        <div class="cv g-text-gold">₹ 0</div>
                        <div class="cl">Extra Fee</div>
                    </div>

                    <div class="phone-frame">
                        <div class="phone-shell">
                            <div class="ph-notch-bar">
                                <div class="ph-av-g">
                                    @if($village->logo)
                                        <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                    @else
                                        {{ substr($village->name_en, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="ph-head-name font-disp">{{ $village->name_en }}</div>
                                    <div class="ph-head-status"><span class="ph-s-dot"></span> Online Now</div>
                                </div>
                            </div>
                            <div class="ph-body">
                                <div class="bubble b-out">Hi Panchayat 👋</div>
                                <div class="bubble b-in">Welcome! Send your house number to check your outstanding tax.</div>
                                <div class="bubble b-out">House No. 142</div>
                                <div class="ph-tax-card">
                                    <div class="ph-tax-lbl">Outstanding Tax — 2024–25</div>
                                    <div class="ph-tax-amt font-disp">₹ 1,250</div>
                                    <div class="ph-tax-sub">House #142 · Gram Panchayat</div>
                                    <button class="ph-pay-btn font-disp">
                                        <i class="fa-solid fa-lock-open" style="font-size:10px;margin-right:4px;"></i>Pay Now via UPI
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- STATS -->
    <div id="stats" class="reveal">
        <div class="stats-strip">
            <div class="stats-inner">
                <div class="stats-grid">
                    <div class="stat-cell">
                        <div class="stat-n font-disp">2,500+</div>
                        <div class="stat-l">Homes Connected</div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-n font-disp">24/7</div>
                        <div class="stat-l">System Uptime</div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-n font-disp">0%</div>
                        <div class="stat-l">Convenience Fee</div>
                    </div>
                    <div class="stat-cell">
                        <div class="stat-n font-disp">100%</div>
                        <div class="stat-l">Digital Accuracy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" aria-labelledby="hiw-h">
        <div class="hiw-inner">
            <div class="hiw-hdr reveal">
                <div class="sec-label">Simple Process</div>
                <h2 id="hiw-h" class="sec-h2 font-disp">
                    3 Steps to Pay<br>
                    <span class="g-text">Your Tax</span>
                </h2>
                <p class="sec-p">Directly from your phone. No app download needed.</p>
            </div>

            <div class="steps-wrap">
                <div class="step-card reveal d1">
                    <div class="step-num">Step 01</div>
                    <div class="step-icon si-g"><i class="fa-brands fa-whatsapp"></i></div>
                    <h3 class="step-h font-disp">Send a Message</h3>
                    <p class="step-p">Send "Hi" to our official Panchayat WhatsApp number to launch the automated bot instantly. Works on any phone.</p>
                </div>
                <div class="step-card reveal d2">
                    <div class="step-num">Step 02</div>
                    <div class="step-icon si-s"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <h3 class="step-h font-disp">Verify Your Details</h3>
                    <p class="step-p">Enter your house number or registered phone number to instantly view your outstanding property &amp; water tax.</p>
                </div>
                <div class="step-card reveal d3">
                    <div class="step-num">Step 03</div>
                    <div class="step-icon si-g"><i class="fa-solid fa-receipt"></i></div>
                    <h3 class="step-h font-disp">Pay &amp; Get Receipt</h3>
                    <p class="step-p">Pay via UPI — GPay, PhonePe, or any UPI app. Your official signed PDF receipt arrives on WhatsApp instantly.</p>
                </div>
            </div>

            <div class="hiw-cta reveal">
                <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-primary" rel="noopener">
                    Try the Live Bot &nbsp;<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <hr class="g-divider" style="max-width:1280px;margin:0 auto;" aria-hidden="true">

    <!-- SERVICES -->
    <section id="services" aria-labelledby="svc-h">
        <div class="svc-inner">
            <div class="svc-header reveal">
                <div>
                    <div class="sec-label">Digital Governance</div>
                    <h2 id="svc-h" class="sec-h2 font-disp">
                        Online<br><span class="g-text">Services</span>
                    </h2>
                    <p class="sec-p" style="max-width:400px;">Panchayat services, directly on your phone. Anytime.</p>
                </div>
                <div class="iso-pill"><i class="fa-solid fa-certificate"></i> ISO Certified</div>
            </div>

            <div class="svc-grid">

                <!-- Property Tax -->
                <div class="svc-card reveal d1">
                    <div class="glow-top" style="background:linear-gradient(90deg,var(--emerald),var(--sky));opacity:0;"></div>
                    <div class="svc-num">01 · Property</div>
                    <div class="svc-icon" style="background:linear-gradient(135deg,rgba(0,200,150,0.15),rgba(56,192,252,0.1));color:var(--emerald);">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <h3 class="svc-h font-disp font-gu" style="font-family:'Sora',sans-serif;">
                        <span style="font-family:'Noto Sans Gujarati',sans-serif;">મિલકત વેરો</span>
                    </h3>
                    <p class="svc-p">View and pay yearly property taxes for residential and commercial spaces without visiting the office.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-link" style="color:var(--emerald);" rel="noopener">
                        Pay Now <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
                    </a>
                </div>

                <!-- Water Tax -->
                <div class="svc-card reveal d2">
                    <div class="glow-top" style="background:linear-gradient(90deg,var(--sky),var(--violet));opacity:0;"></div>
                    <div class="svc-num">02 · Water</div>
                    <div class="svc-icon" style="background:linear-gradient(135deg,rgba(56,192,252,0.15),rgba(167,139,250,0.1));color:var(--sky);">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                    <h3 class="svc-h font-disp">
                        <span style="font-family:'Noto Sans Gujarati',sans-serif;">પાણી વેરો</span>
                    </h3>
                    <p class="svc-p">Clear your water connection bills seamlessly without visiting the office. Quick, easy, and fully digital.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-link" style="color:var(--sky);" rel="noopener">
                        Pay Now <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
                    </a>
                </div>

                <!-- Notices -->
                <div class="svc-card reveal d3">
                    <div class="glow-top" style="background:linear-gradient(90deg,var(--gold),var(--coral));opacity:0;"></div>
                    <div class="svc-num">03 · Notices</div>
                    <div class="svc-icon" style="background:linear-gradient(135deg,rgba(251,191,36,0.15),rgba(251,113,133,0.08));color:var(--gold);">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <h3 class="svc-h font-disp">
                        <span style="font-family:'Noto Sans Gujarati',sans-serif;">ગ્રામ નોટિસ</span>
                    </h3>
                    <p class="svc-p">Receive important announcements and Gram Sabha schedules directly on your phone via WhatsApp.</p>
                    <a href="#" class="svc-link" style="color:var(--gold);">
                        Subscribe <i class="fa-solid fa-arrow-right" style="font-size:11px;"></i>
                    </a>
                </div>

                <!-- Certificates — Coming Soon -->
                <div class="svc-card reveal" style="pointer-events:none;opacity:0.35;">
                    <div class="glow-top" style="background:var(--rule);opacity:1;"></div>
                    <div class="svc-num">04 · Certificates</div>
                    <div class="svc-icon" style="background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h3 class="svc-h font-disp">
                        <span style="font-family:'Noto Sans Gujarati',sans-serif;">પ્રમાણપત્રો</span>
                    </h3>
                    <p class="svc-p">Apply for and download official certificates — arriving in Phase 2 of the portal rollout.</p>
                    <span class="svc-locked"><i class="fa-solid fa-lock" style="font-size:10px;"></i> Coming Soon</span>
                </div>

            </div>
        </div>
    </section>

    <!-- INITIATIVE -->
    <section id="initiative" aria-labelledby="init-h">
        <div class="init-inner">
            <div class="init-grid reveal">
                <div>
                    <div class="sec-label">Empowering Digital India</div>
                    <h2 id="init-h" class="sec-h2 font-disp">
                        Transparent<br>Governance.<br>
                        <span class="g-text-cool">Powered by Tech.</span>
                    </h2>
                    <p class="init-p">
                        This Gram Panchayat is proud to pioneer smart village governance. The secure, scalable digital framework is powered by the <strong style="color:var(--white);">CISETU Platform</strong> built by Clonza Infotech — built for Bharat, built to last.
                    </p>
                    <div class="tech-badge">
                        <i class="fa-solid fa-microchip" style="color:var(--emerald);"></i>
                        Technology Partner: <span>Clonza Infotech</span>
                    </div>
                </div>

                <div class="init-panel">
                    <div class="init-stat-row">
                        <div class="is-icon is-1"><i class="fa-solid fa-house-user"></i></div>
                        <div>
                            <div class="is-val font-disp">2,500+</div>
                            <div class="is-lbl">Homes Connected</div>
                        </div>
                    </div>
                    <div class="init-stat-row">
                        <div class="is-icon is-2"><i class="fa-solid fa-clock"></i></div>
                        <div>
                            <div class="is-val font-disp">24/7</div>
                            <div class="is-lbl">Always Available</div>
                        </div>
                    </div>
                    <div class="init-stat-row">
                        <div class="is-icon is-3"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                        <div>
                            <div class="is-val font-disp">₹ 0</div>
                            <div class="is-lbl">Convenience Fee to Citizens</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="foot-inner">
            <div class="foot-grid">
                <div>
                    <div class="foot-logo-row">
                        <div class="foot-logo-badge font-disp">
                            @if($village->logo)
                                <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                            @else
                                {{ substr($village->name_en, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="foot-name font-disp">GRAM PANCHAYAT {{ strtoupper($village->name_en) }}</div>
                            <div class="foot-sub">Digital Citizen Portal</div>
                        </div>
                    </div>
                    <p class="foot-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
                    <div class="soc-row">
                        <a href="#" class="soc-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="soc-btn" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="soc-btn" aria-label="WhatsApp" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <div class="foot-h">Quick Links</div>
                    <ul class="foot-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#how-it-works">How to Pay Tax</a></li>
                        <li><a href="#services">Other Services</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>

                <div>
                    <div class="foot-h">Contact Info</div>
                    <div class="cinfo">
                        <div class="cinfo-i"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="cinfo-t font-gu">
                            {{ $village->name_local }} કચેરી,<br>
                            ગુજરાત, ભારત
                        </div>
                    </div>
                    <div class="cinfo">
                        <div class="cinfo-i"><i class="fa-solid fa-envelope"></i></div>
                        <div class="cinfo-t"><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
                    </div>
                    <div class="cinfo">
                        <div class="cinfo-i"><i class="fa-solid fa-phone"></i></div>
                        <div class="cinfo-t"><a href="tel:+{{ $village->whatsapp_number }}">+91 {{ $village->whatsapp_number }}</a></div>
                    </div>
                </div>
            </div>

            <div class="foot-bottom">
                <p class="foot-copy">&copy; 2025 CISETU. All Rights Reserved.</p>
                <div class="foot-powered">Powered by <em>&nbsp;CISETU / Clonza Infotech</em></div>
            </div>
        </div>
    </footer>

    <!-- MOBILE NAV -->
    <nav id="mobile-nav" aria-label="Mobile navigation">
        <a href="#home" class="mob-lnk active" aria-current="page">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Home
        </a>
        <a href="#how-it-works" class="mob-lnk">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i> Guide
        </a>
        <div class="mob-fab">
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" aria-label="Pay Tax via WhatsApp" rel="noopener">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
            </a>
        </div>
        <a href="#services" class="mob-lnk">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i> Services
        </a>
        <a href="#" class="mob-lnk">
            <i class="fa-solid fa-user" aria-hidden="true"></i> Profile
        </a>
    </nav>

    <script>
        const revEls = document.querySelectorAll('.reveal');
        const revObs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('vis'); });
        }, { threshold: 0.06, rootMargin: '0px 0px -30px 0px' });
        revEls.forEach(el => revObs.observe(el));

        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 24);
        }, { passive: true });

        const sections = document.querySelectorAll('section[id]');
        const mobLinks = document.querySelectorAll('#mobile-nav .mob-lnk[href^="#"]');
        const secObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const id = e.target.id;
                    mobLinks.forEach(l => {
                        const active = l.getAttribute('href') === '#' + id;
                        l.classList.toggle('active', active);
                        active ? l.setAttribute('aria-current','page') : l.removeAttribute('aria-current');
                    });
                }
            });
        }, { threshold: 0.4, rootMargin: '-15% 0px -15% 0px' });
        sections.forEach(s => secObs.observe(s));

        /* Glow-top on hover — handled via CSS opacity transitions */
        document.querySelectorAll('.svc-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const g = card.querySelector('.glow-top');
                if (g) g.style.opacity = '1';
            });
            card.addEventListener('mouseleave', () => {
                const g = card.querySelector('.glow-top');
                if (g) g.style.opacity = '0';
            });
        });
    </script>
</body>
</html>