<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    @php
        $portalTheme = $portalTheme ?? 'classic';
        $themes = [
            'classic' => [
                'brand' => ['50'=>'#f0f5fe','100'=>'#e3edfc','200'=>'#c1d8fc','300'=>'#8eb8f9','400'=>'#5692f5','500'=>'#3b82f6','600'=>'#2563eb','800'=>'#1e40af','900'=>'#1e3a8a','950'=>'#172554'],
                'pageBg'=>'#faf7f2','fontSans'=>'Outfit',
                'surface'=>'rgba(255,255,255,0.85)','surfaceBorder'=>'rgba(0,0,0,0.07)',
                'heroBlobA'=>'rgba(220,100,40,0.18)','heroBlobB'=>'rgba(37,211,102,0.12)',
            ],
        ];
        $theme = $themes[$portalTheme] ?? $themes['classic'];
    @endphp

    <title>{{ $village->name_local }} Gram Panchayat | Digital Property Tax Portal</title>
    <meta name="description" content="Official Digital Portal for {{ $village->name_en }} Gram Panchayat. Pay your property tax instantly and securely via WhatsApp.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cream:    #faf7f2;
            --cream-2:  #f3ede3;
            --cream-3:  #ebe3d6;
            --terra:    #c1440e;
            --terra-2:  #e05a20;
            --terra-lt: #fdeee7;
            --saffron:  #f59e0b;
            --wa:       #25D366;
            --wa-dark:  #128C7E;
            --ink:      #1a100a;
            --ink-2:    #3d2a1e;
            --ink-3:    #7a5c48;
            --ink-4:    #b8a090;
            --border:   rgba(26,16,10,0.1);
            --border-2: rgba(26,16,10,0.06);
            --r-sm: 10px;
            --r-md: 18px;
            --r-lg: 28px;
            --r-xl: 40px;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            line-height: 1.65;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        /* Subtle noise texture overlay */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            background-size: 200px;
            opacity: 0.5;
        }

        .font-gu { font-family: 'Noto Sans Gujarati', sans-serif; }
        .font-disp { font-family: 'Playfair Display', serif; }

        /* ── NAV ── */
        #nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0 24px;
            transition: background 0.4s ease, box-shadow 0.4s ease;
        }
        #nav.scrolled {
            background: rgba(250,247,242,0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 1px 0 var(--border);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 72px;
        }

        .nav-logo { display: flex; align-items: center; gap: 12px; cursor: pointer; text-decoration: none; }
        .nav-logo-img {
            width: 42px; height: 42px; border-radius: 50%;
            border: 2px solid var(--cream-3);
            object-fit: cover; flex-shrink: 0;
            background: var(--cream-2);
        }
        .nav-name { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 700; color: var(--ink); line-height: 1.2; }
        .nav-sub { font-size: 10px; font-weight: 600; color: var(--terra); letter-spacing: 0.16em; text-transform: uppercase; }

        .nav-links { display: none; gap: 36px; }
        @media (min-width: 768px) { .nav-links { display: flex; } }
        .nav-links a {
            font-size: 13px; font-weight: 600; color: var(--ink-3);
            text-decoration: none; transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--ink); }

        .btn-nav-wa {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; border-radius: 100px;
            background: var(--wa); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            white-space: nowrap;
        }
        .btn-nav-wa:hover { background: var(--wa-dark); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,211,102,0.28); }

        /* ── HERO ── */
        #home {
            padding: 100px 24px 0;
            position: relative; z-index: 1;
            overflow: hidden;
        }

        /* Decorative arch background */
        #home::before {
            content: '';
            position: absolute;
            top: -10%; left: 50%; transform: translateX(-50%);
            width: 900px; height: 900px;
            border-radius: 50%;
            background: radial-gradient(ellipse at center, var(--terra-lt) 0%, transparent 65%);
            pointer-events: none; z-index: 0;
        }

        .hero-wrap {
            max-width: 1200px; margin: 0 auto;
            position: relative; z-index: 1;
        }

        /* Top label strip */
        .hero-kicker {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 32px;
        }
        .kicker-line { flex: 0 0 40px; height: 2px; background: var(--terra); }
        .kicker-text {
            font-size: 11px; font-weight: 700; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--terra);
        }
        .kicker-live {
            display: flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 600; color: var(--wa-dark);
            background: rgba(37,211,102,0.12); padding: 4px 10px; border-radius: 100px;
        }
        .kicker-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--wa); animation: pdot 2s infinite; }
        @keyframes pdot { 0%,100%{opacity:1} 50%{opacity:0.4} }

        .hero-layout {
            display: grid; grid-template-columns: 1fr; gap: 0;
            align-items: flex-end;
        }
        @media (min-width: 900px) {
            .hero-layout { grid-template-columns: 1.1fr 0.9fr; align-items: center; }
        }

        .hero-copy { padding-bottom: 60px; }

        .hero-h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(44px, 7vw, 88px);
            font-weight: 800;
            line-height: 1.0;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 12px;
        }
        .hero-h1 .gu-line {
            font-family: 'Noto Sans Gujarati', sans-serif;
            display: block;
            font-size: clamp(28px, 4vw, 52px);
            font-weight: 800;
            line-height: 1.15;
            color: var(--ink);
        }
        .hero-h1 .accent-word {
            color: var(--terra);
            font-style: italic;
        }

        /* Big decorative number stamp */
        .hero-stamp {
            font-family: 'Playfair Display', serif;
            font-size: 200px; font-weight: 800;
            line-height: 1; color: var(--terra-lt);
            position: absolute; right: -20px; top: -40px; z-index: 0;
            pointer-events: none; user-select: none;
            letter-spacing: -0.05em;
        }
        @media (max-width: 900px) { .hero-stamp { display: none; } }

        .hero-p {
            font-size: 16px; color: var(--ink-3); line-height: 1.8;
            max-width: 500px; margin-bottom: 40px;
            position: relative; z-index: 1;
        }

        .hero-ctas { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 48px; position: relative; z-index: 1; }

        .btn-primary-lt {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 28px; border-radius: var(--r-sm);
            background: var(--wa); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 700;
            text-decoration: none; cursor: pointer; border: none;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .btn-primary-lt:hover { background: var(--wa-dark); transform: translateY(-2px); box-shadow: 0 12px 32px rgba(37,211,102,0.28); }

        .btn-secondary-lt {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 15px 26px; border-radius: var(--r-sm);
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--ink-2);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 15px; font-weight: 600;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s, transform 0.2s;
        }
        .btn-secondary-lt:hover { border-color: var(--terra); background: var(--terra-lt); transform: translateY(-2px); }

        .hero-pills { display: flex; flex-wrap: wrap; gap: 10px; position: relative; z-index: 1; }
        .hero-pill {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 14px; border-radius: 100px;
            background: #fff; border: 1px solid var(--border);
            font-size: 12px; font-weight: 600; color: var(--ink-3);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .hero-pill i { font-size: 13px; }
        .hero-pill .i-terra { color: var(--terra); }
        .hero-pill .i-saf { color: var(--saffron); }
        .hero-pill .i-wa { color: var(--wa); }

        /* Hero right — receipt card stack */
        .hero-visual {
            position: relative; padding: 40px 0 80px;
            display: flex; justify-content: center;
        }
        @media (min-width:900px) { .hero-visual { padding: 60px 0; } }

        .receipt-stack { position: relative; width: 300px; height: 420px; }

        /* Card behind */
        .receipt-bg {
            position: absolute; top: 24px; left: 24px; right: -24px; bottom: -24px;
            background: var(--terra-lt);
            border: 1px solid rgba(193,68,14,0.15);
            border-radius: var(--r-lg);
            transform: rotate(3deg);
        }
        .receipt-bg2 {
            position: absolute; top: 12px; left: 12px; right: -12px; bottom: -12px;
            background: var(--cream-2);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            transform: rotate(1.2deg);
        }

        /* Main card */
        .receipt-card {
            position: relative; z-index: 2;
            width: 300px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(26,16,10,0.1), 0 4px 12px rgba(26,16,10,0.06);
            animation: card-float 7s ease-in-out infinite;
        }
        @keyframes card-float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }

        .receipt-header {
            background: var(--terra); padding: 20px 20px 16px;
            display: flex; align-items: center; gap: 12px;
        }
        .receipt-logo {
            width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #fff; font-weight: 800; font-family: 'Playfair Display', serif;
            flex-shrink: 0;
        }
        .receipt-hname { font-size: 12px; font-weight: 700; color: #fff; font-family: 'Noto Sans Gujarati', sans-serif; }
        .receipt-hsub { font-size: 10px; color: rgba(255,255,255,0.65); }

        .receipt-body { padding: 20px; }
        .receipt-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 10px 0; border-bottom: 1px dashed var(--border-2);
            font-size: 13px;
        }
        .receipt-row:last-child { border-bottom: none; }
        .receipt-row .lbl { color: var(--ink-4); font-weight: 500; }
        .receipt-row .val { font-weight: 700; color: var(--ink); text-align: right; }
        .receipt-row .val.big { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--terra); }

        .receipt-status {
            margin: 16px 0 0;
            padding: 12px 16px;
            background: rgba(37,211,102,0.08);
            border: 1px solid rgba(37,211,102,0.2);
            border-radius: var(--r-sm);
            display: flex; align-items: center; gap: 10px;
        }
        .receipt-status-icon { color: var(--wa); font-size: 16px; }
        .receipt-status-text .t1 { font-size: 12px; font-weight: 700; color: var(--ink); }
        .receipt-status-text .t2 { font-size: 10px; color: var(--ink-3); }

        /* Floating badge */
        .tax-badge {
            position: absolute; top: -16px; right: -16px; z-index: 10;
            background: var(--saffron); color: var(--ink);
            border-radius: 16px 16px 16px 4px;
            padding: 10px 16px;
            font-size: 11px; font-weight: 700;
            box-shadow: 0 8px 24px rgba(245,158,11,0.35);
            animation: badge-pop 7s ease-in-out 3.5s infinite;
        }
        @keyframes badge-pop { 0%,100%{transform:translateY(0) rotate(-2deg)} 50%{transform:translateY(-8px) rotate(-2deg)} }
        .tax-badge .big { display: block; font-family:'Playfair Display',serif; font-size: 22px; line-height: 1.1; }

        /* ── STATS ── */
        #stats { position: relative; z-index: 1; padding: 0 24px 80px; }
        .stats-wrap {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(2,1fr); gap: 2px;
            background: var(--border); border-radius: var(--r-lg); overflow: hidden;
            border: 1px solid var(--border);
        }
        @media (min-width:700px) { .stats-wrap { grid-template-columns: repeat(4,1fr); } }

        .stat-cell {
            background: #fff; padding: 32px 24px; text-align: center;
            transition: background 0.2s;
        }
        .stat-cell:hover { background: var(--cream); }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 40px; font-weight: 800; color: var(--terra);
            line-height: 1; margin-bottom: 6px;
        }
        .stat-lbl { font-size: 11px; font-weight: 600; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.12em; }

        /* ── SEC HEADER ── */
        .sec-tag-lt {
            display: inline-block; padding: 5px 14px; border-radius: 100px;
            background: var(--terra-lt); border: 1px solid rgba(193,68,14,0.18);
            font-size: 10px; font-weight: 700; color: var(--terra);
            letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 18px;
        }
        .sec-h2-lt {
            font-family: 'Playfair Display', serif;
            font-size: clamp(30px, 4vw, 52px); font-weight: 800;
            line-height: 1.1; letter-spacing: -0.01em; color: var(--ink); margin-bottom: 16px;
        }
        .sec-p-lt { font-size: 16px; color: var(--ink-3); line-height: 1.75; }

        /* ── HOW IT WORKS ── */
        #how-it-works { padding: 100px 24px; position: relative; z-index: 1; }

        .hiw-wrap { max-width: 1200px; margin: 0 auto; }
        .hiw-header { text-align: center; max-width: 560px; margin: 0 auto 72px; }

        /* Timeline layout */
        .timeline { position: relative; }
        .timeline::before {
            content: ''; position: absolute;
            left: 40px; top: 40px; bottom: 40px; width: 1px;
            background: linear-gradient(to bottom, var(--terra), var(--wa));
        }
        @media (min-width: 700px) {
            .timeline::before {
                left: 50%; top: auto; bottom: auto;
                width: auto; height: 1px;
                top: 56px; bottom: auto;
                left: calc(16.67% + 40px); right: calc(16.67% + 40px);
                background: linear-gradient(to right, var(--terra), var(--wa));
            }
        }

        .steps-grid-lt {
            display: grid; grid-template-columns: 1fr; gap: 32px;
            position: relative; z-index: 1;
        }
        @media (min-width: 700px) { .steps-grid-lt { grid-template-columns: repeat(3,1fr); } }

        .step-lt {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: 36px 28px 32px;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
        }
        .step-lt:hover { transform: translateY(-6px); box-shadow: 0 20px 48px rgba(26,16,10,0.1); }

        .step-lt-num {
            position: absolute; top: -18px; left: 32px;
            width: 36px; height: 36px; border-radius: 10px;
            background: var(--terra); color: #fff;
            font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            border: 3px solid var(--cream);
        }

        .step-lt-icon {
            width: 60px; height: 60px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 20px;
        }
        .si-wa { background: rgba(37,211,102,0.1); color: var(--wa-dark); }
        .si-blue { background: rgba(37,99,235,0.08); color: #1d4ed8; }
        .si-green { background: rgba(5,150,105,0.08); color: #065f46; }

        .step-lt-h { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin-bottom: 10px; }
        .step-lt-p { font-size: 14px; color: var(--ink-3); line-height: 1.7; }

        .hiw-cta { text-align: center; margin-top: 56px; }
        .btn-terra {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 28px; border-radius: 100px;
            background: var(--terra); color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; font-weight: 700;
            text-decoration: none; cursor: pointer; border: none;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .btn-terra:hover { background: #a8370a; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(193,68,14,0.28); }

        /* ── SERVICES ── */
        #services {
            padding: 100px 24px;
            background: var(--ink);
            position: relative; z-index: 1;
            overflow: hidden;
        }

        /* Big faded Gujarati text behind */
        #services::before {
            content: 'સેવા';
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: 280px; font-weight: 800;
            color: rgba(255,255,255,0.025);
            position: absolute; right: -20px; bottom: -40px; z-index: 0;
            line-height: 1; pointer-events: none; user-select: none;
        }

        .svc-wrap { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; }

        .svc-top {
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end;
            gap: 24px; margin-bottom: 56px;
        }
        .svc-top .sec-tag-dk {
            display: inline-block; padding: 5px 14px; border-radius: 100px;
            background: rgba(193,68,14,0.15); border: 1px solid rgba(193,68,14,0.3);
            font-size: 10px; font-weight: 700; color: #f97316;
            letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 18px;
        }
        .svc-top .sec-h2-dk {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 48px); font-weight: 800;
            line-height: 1.1; color: #fff; margin-bottom: 14px;
        }
        .svc-top .sec-p-dk { font-size: 15px; color: rgba(255,255,255,0.45); line-height: 1.75; }

        .svc-grid {
            display: grid; grid-template-columns: 1fr; gap: 20px;
        }
        @media (min-width: 600px) { .svc-grid { grid-template-columns: repeat(2,1fr); } }
        @media (min-width: 1000px) { .svc-grid { grid-template-columns: repeat(4,1fr); } }

        .svc-card-lt {
            border-radius: var(--r-lg);
            padding: 32px 26px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.03);
            transition: transform 0.3s, border-color 0.3s, background 0.3s;
            cursor: pointer; position: relative; overflow: hidden;
        }
        .svc-card-lt:hover { transform: translateY(-6px); background: rgba(255,255,255,0.06); }
        .svc-card-lt.featured { border-color: rgba(193,68,14,0.4); background: rgba(193,68,14,0.06); }
        .svc-card-lt.featured:hover { border-color: rgba(193,68,14,0.6); background: rgba(193,68,14,0.1); }

        .svc-icon-lt {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 22px;
        }
        .si-terra { background: rgba(193,68,14,0.15); color: #f97316; }
        .si-blue-dk { background: rgba(59,130,246,0.12); color: #60a5fa; }
        .si-amber-dk { background: rgba(245,158,11,0.12); color: #fbbf24; }
        .si-muted-dk { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.25); }

        .svc-h-lt { font-family: 'Playfair Display', serif; font-size: 19px; font-weight: 700; color: #fff; margin-bottom: 10px; }
        .svc-p-lt { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.65; margin-bottom: 24px; }

        .svc-lnk {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 700; text-decoration: none;
            transition: gap 0.2s;
        }
        .svc-lnk:hover { gap: 10px; }
        .svc-lnk-terra { color: #f97316; }
        .svc-lnk-blue { color: #60a5fa; }
        .svc-lnk-amber { color: #fbbf24; }
        .svc-locked-lt {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.05); padding: 5px 12px;
            border-radius: 100px; letter-spacing: 0.08em; text-transform: uppercase;
        }

        /* ── INITIATIVE ── */
        #initiative {
            padding: 100px 24px; position: relative; z-index: 1;
            background: var(--cream);
        }
        .initiative-wrap { max-width: 800px; margin: 0 auto; }

        .init-layout {
            display: grid; grid-template-columns: 1fr; gap: 48px;
            align-items: center;
        }
        @media (min-width: 700px) { .init-layout { grid-template-columns: 1fr 1fr; gap: 80px; } }

        .init-left .eyebrow {
            font-size: 11px; font-weight: 700; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--terra); margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }
        .init-left .eyebrow::before { content:''; flex: 0 0 24px; height: 2px; background: var(--terra); }
        .init-h { font-family:'Playfair Display',serif; font-size: 34px; font-weight: 800; color: var(--ink); line-height: 1.15; margin-bottom: 20px; }
        .init-p { font-size: 15px; color: var(--ink-3); line-height: 1.8; }

        .init-right {
            background: var(--cream-2); border: 1px solid var(--border);
            border-radius: var(--r-lg); padding: 32px 28px;
        }
        .init-stat-row { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--border-2); }
        .init-stat-row:last-child { border-bottom: none; padding-bottom: 0; }
        .init-icon { width: 40px; height: 40px; border-radius: 10px; display:flex;align-items:center;justify-content:center; font-size:16px; flex-shrink:0; }
        .init-icon.i1 { background: var(--terra-lt); color: var(--terra); }
        .init-icon.i2 { background: rgba(37,211,102,0.1); color: var(--wa-dark); }
        .init-icon.i3 { background: rgba(245,158,11,0.1); color: var(--saffron); }
        .init-stat-val { font-family:'Playfair Display',serif; font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
        .init-stat-lbl { font-size: 12px; color: var(--ink-4); font-weight: 500; }

        .tech-block {
            margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
            font-size: 12px; color: var(--ink-4); font-weight: 600; letter-spacing: 0.06em;
        }
        .tech-name { font-weight: 800; color: var(--terra); }

        /* ── FOOTER ── */
        footer {
            background: var(--ink-2);
            padding: 72px 24px 56px;
            position: relative; z-index: 1;
            border-top: 4px solid var(--terra);
        }

        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .footer-grid {
            display: grid; grid-template-columns: 1fr; gap: 48px; margin-bottom: 56px;
        }
        @media (min-width: 900px) { .footer-grid { grid-template-columns: 2fr 1fr 1.5fr; gap: 64px; } }

        .footer-logo-row { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
        .footer-logo-img {
            width: 48px; height: 48px; border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(255,255,255,0.15);
        }
        .footer-name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: #fff; }
        .footer-sub { font-size: 10px; color: var(--terra-2); font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; margin-top: 2px; }
        .footer-desc { font-size: 14px; color: rgba(255,255,255,0.4); line-height: 1.75; max-width: 300px; margin-bottom: 28px; }

        .social-row { display: flex; gap: 10px; }
        .soc-btn {
            width: 38px; height: 38px; border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.4); font-size: 14px; text-decoration: none;
            transition: background 0.2s, color 0.2s, transform 0.2s;
        }
        .soc-btn:hover { background: rgba(255,255,255,0.08); color: #fff; transform: translateY(-2px); }

        .ftr-h { font-family:'Playfair Display',serif; font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.7); margin-bottom: 22px; letter-spacing: 0.04em; }
        .ftr-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .ftr-links a { font-size: 14px; color: rgba(255,255,255,0.35); text-decoration: none; transition: color 0.2s; display:flex;align-items:center;gap:8px; }
        .ftr-links a::before { content:''; width:3px; height:3px; border-radius:50%; background:var(--terra); flex-shrink:0; }
        .ftr-links a:hover { color: rgba(255,255,255,0.8); }

        .contact-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 13px 15px; border-radius: var(--r-sm);
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 10px; transition: border-color 0.2s;
        }
        .contact-row:hover { border-color: rgba(193,68,14,0.3); }
        .contact-ico {
            width: 34px; height: 34px; border-radius: 8px;
            background: rgba(193,68,14,0.12); color: var(--terra-2);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; flex-shrink: 0; margin-top: 1px;
        }
        .contact-txt { font-size: 13px; color: rgba(255,255,255,0.4); line-height: 1.6; }
        .contact-txt a { color: rgba(255,255,255,0.4); text-decoration:none; }
        .contact-txt a:hover { color: rgba(255,255,255,0.75); }

        .footer-bottom {
            padding-top: 28px; border-top: 1px solid rgba(255,255,255,0.06);
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;
        }
        .footer-bottom p { font-size: 13px; color: rgba(255,255,255,0.2); }
        .ftr-powered {
            font-size: 12px; color: rgba(255,255,255,0.2);
            display: flex; align-items: center; gap: 8px;
        }
        .ftr-powered span { padding: 3px 10px; border-radius: 5px; background: rgba(193,68,14,0.15); border: 1px solid rgba(193,68,14,0.2); color: var(--terra-2); font-weight: 700; font-size: 11px; }

        /* ── MOBILE NAV ── */
        #mobile-nav {
            display: flex; position: fixed; bottom: 14px; left: 14px; right: 14px; z-index: 100;
            background: rgba(250,247,242,0.95);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 6px 4px;
            justify-content: space-around; align-items: center;
            box-shadow: 0 8px 32px rgba(26,16,10,0.1);
        }
        @media (min-width: 768px) { #mobile-nav { display: none; } }

        .mob-lnk {
            display: flex; flex-direction: column; align-items: center; gap: 3px;
            color: var(--ink-4); text-decoration: none;
            font-size: 9px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
            width: 58px; padding: 7px 0; border-radius: 12px;
            transition: color 0.2s, background 0.2s;
        }
        .mob-lnk i { font-size: 18px; }
        .mob-lnk.active { color: var(--terra); background: var(--terra-lt); }
        .mob-lnk:hover { color: var(--terra); }

        .mob-fab-lt { position: relative; top: -14px; }
        .mob-fab-lt a {
            width: 58px; height: 58px; border-radius: 50%;
            background: var(--wa); display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff; text-decoration: none;
            border: 3px solid var(--cream); box-shadow: 0 6px 20px rgba(37,211,102,0.28);
            transition: transform 0.2s;
        }
        .mob-fab-lt a:active { transform: scale(0.93); }

        .pb-mob { padding-bottom: 100px; }
        @media (min-width: 768px) { .pb-mob { padding-bottom: 0; } }

        /* ── REVEAL ── */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s cubic-bezier(0.4,0,0.2,1), transform 0.7s cubic-bezier(0.4,0,0.2,1); }
        .reveal.vis { opacity: 1; transform: none; }
        .reveal.d1 { transition-delay: 0.1s; }
        .reveal.d2 { transition-delay: 0.2s; }
        .reveal.d3 { transition-delay: 0.3s; }
    </style>
</head>
<body class="pb-mob">

    <!-- ── NAV ── -->
    <nav id="nav" role="navigation" aria-label="Main navigation">
        <div class="nav-inner">
            <a class="nav-logo" href="#home">
                @if($village->logo)
                    <img src="{{ asset('storage/'.$village->logo) }}" alt="{{ $village->name_local }} Logo" class="nav-logo-img">
                @else
                    <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ $village->name_local }} Logo" class="nav-logo-img" onerror="this.style.opacity=0">
                @endif
                <div>
                    <div class="nav-name font-gu">ગ્રામ પંચાયત {{ $village->name_local }}</div>
                    <div class="nav-sub">Smart Village Portal</div>
                </div>
            </a>

            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#how-it-works">How to Pay</a>
                <a href="#services">Services</a>
            </div>

            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-nav-wa" rel="noopener">
                <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
            </a>
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section id="home" aria-labelledby="hero-h1">
        <div class="hero-wrap">
            <div class="hero-kicker">
                <div class="kicker-line"></div>
                <span class="kicker-text">Official Tax Payment Portal</span>
                <div class="kicker-live">
                    <span class="kicker-dot"></span> Live System
                </div>
            </div>

            <div class="hero-layout">
                <div class="hero-copy reveal">
                    <!-- Big decorative stamp behind -->
                    <div class="hero-stamp" aria-hidden="true">GP</div>

                    <h1 id="hero-h1" class="hero-h1">
                        Pay Your<br>
                        Property Tax<br>
                        <em class="accent-word">Instantly.</em>
                    </h1>
                    <h2 class="hero-h1 font-gu" style="font-size:clamp(20px,3vw,36px); font-style:normal; color:var(--ink-3); margin-top:8px; font-weight:700; font-family:'Noto Sans Gujarati',sans-serif;">
                        તમારો વેરો <span style="color:var(--terra);">WhatsApp</span> પર ભરો
                    </h2>

                    <p class="hero-p" style="margin-top:24px;">
                        No more waiting in lines. {{ $village->name_local }} Gram Panchayat introduces a fully automated, highly secure WhatsApp system to view and pay your property tax. Get your official digital receipt in seconds.
                    </p>

                    <div class="hero-ctas">
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-primary-lt" rel="noopener">
                            <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
                            Start on WhatsApp
                        </a>
                        <a href="#how-it-works" class="btn-secondary-lt">
                            <i class="fa-solid fa-play" style="font-size:12px;"></i>
                            See How It Works
                        </a>
                    </div>

                    <div class="hero-pills">
                        <div class="hero-pill">
                            <i class="fa-solid fa-shield-halved i-terra"></i> 100% Secure
                        </div>
                        <div class="hero-pill">
                            <i class="fa-solid fa-bolt i-saf"></i> Instant Receipt
                        </div>
                        <div class="hero-pill">
                            <i class="fa-solid fa-indian-rupee-sign i-wa"></i> Zero Fees
                        </div>
                        <div class="hero-pill">
                            <i class="fa-solid fa-clock i-terra"></i> 24/7 Available
                        </div>
                    </div>
                </div>

                <!-- Receipt Card Visual -->
                <div class="hero-visual reveal d2">
                    <div class="receipt-stack">
                        <div class="receipt-bg"></div>
                        <div class="receipt-bg2"></div>

                        <div class="receipt-card">
                            <div class="receipt-header">
                                <div class="receipt-logo">GP</div>
                                <div>
                                    <div class="receipt-hname">{{ $village->name_local }} ગ્રામ પંચાયત</div>
                                    <div class="receipt-hsub">Official Property Tax Receipt</div>
                                </div>
                            </div>

                            <div class="receipt-body">
                                <div class="receipt-row">
                                    <span class="lbl">Receipt No.</span>
                                    <span class="val">#GP-2024-4891</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="lbl">House No.</span>
                                    <span class="val">142, {{ $village->name_local }}</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="lbl">Tax Year</span>
                                    <span class="val">2024 – 2025</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="lbl">Payment Mode</span>
                                    <span class="val">UPI / GPay</span>
                                </div>
                                <div class="receipt-row">
                                    <span class="lbl">Amount Paid</span>
                                    <span class="val big">₹ 1,250</span>
                                </div>

                                <div class="receipt-status">
                                    <i class="fa-solid fa-circle-check receipt-status-icon"></i>
                                    <div class="receipt-status-text">
                                        <div class="t1">Payment Confirmed</div>
                                        <div class="t2">PDF sent to your WhatsApp</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tax-badge">
                            <span class="big">₹0</span>
                            Convenience Fee
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── STATS ── -->
    <div id="stats" class="reveal">
        <div class="stats-wrap">
            <div class="stat-cell">
                <div class="stat-num">2,500+</div>
                <div class="stat-lbl">Homes Connected</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">24/7</div>
                <div class="stat-lbl">System Uptime</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">0%</div>
                <div class="stat-lbl">Convenience Fee</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num">100%</div>
                <div class="stat-lbl">Digital Accuracy</div>
            </div>
        </div>
    </div>

    <!-- ── HOW IT WORKS ── -->
    <section id="how-it-works" aria-labelledby="steps-h">
        <div class="hiw-wrap">
            <div class="hiw-header reveal">
                <div class="sec-tag-lt">Simple Process</div>
                <h2 id="steps-h" class="sec-h2-lt font-gu">કરવેરો ભરવાની<br>સરળ રીત</h2>
                <p class="sec-p-lt">Pay your property tax directly from your mobile phone in 3 simple steps. No app installation required.</p>
            </div>

            <div class="steps-grid-lt">
                <div class="step-lt reveal d1">
                    <div class="step-lt-num">1</div>
                    <div class="step-lt-icon si-wa">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h3 class="step-lt-h">Send a Message</h3>
                    <p class="step-lt-p">Simply send "Hi" to our official Panchayat WhatsApp number to initiate the automated bot.</p>
                </div>

                <div class="step-lt reveal d2">
                    <div class="step-lt-num">2</div>
                    <div class="step-lt-icon si-blue">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="step-lt-h">Verify Details</h3>
                    <p class="step-lt-p">Enter your house number or phone number to view your outstanding property & water tax instantly.</p>
                </div>

                <div class="step-lt reveal d3">
                    <div class="step-lt-num">3</div>
                    <div class="step-lt-icon si-green">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h3 class="step-lt-h">Pay & Get Receipt</h3>
                    <p class="step-lt-p">Pay securely via UPI (GPay, PhonePe). The official signed PDF receipt will be sent on WhatsApp.</p>
                </div>
            </div>

            <div class="hiw-cta reveal">
                <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-terra" rel="noopener">
                    Try the Live Bot Now &nbsp;<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ── SERVICES ── -->
    <section id="services" aria-labelledby="svc-h">
        <div class="svc-wrap">
            <div class="svc-top reveal">
                <div>
                    <div class="sec-tag-dk">Digital Governance</div>
                    <h2 id="svc-h" class="sec-h2-dk font-gu">ઓનલાઇન સેવાઓ</h2>
                    <p class="sec-p-dk">Bringing the Panchayat office to your smartphone.</p>
                </div>
                <div style="display:flex;align-items:center;gap:9px;padding:10px 18px;border-radius:var(--r-sm);border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.03);font-size:12px;font-weight:600;color:rgba(255,255,255,0.4);">
                    <i class="fa-solid fa-certificate" style="color:#fbbf24;"></i> ISO Certified Process
                </div>
            </div>

            <div class="svc-grid">
                <div class="svc-card-lt featured reveal d1">
                    <div class="svc-icon-lt si-terra"><i class="fa-solid fa-house-chimney"></i></div>
                    <h3 class="svc-h-lt font-gu">મિલકત વેરો</h3>
                    <p class="svc-p-lt">View and pay yearly property taxes for residential and commercial spaces seamlessly.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-lnk svc-lnk-terra" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="svc-card-lt reveal d2">
                    <div class="svc-icon-lt si-blue-dk"><i class="fa-solid fa-droplet"></i></div>
                    <h3 class="svc-h-lt font-gu">પાણી વેરો</h3>
                    <p class="svc-p-lt">Clear your water connection bills seamlessly without visiting the office. Quick and easy.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-lnk svc-lnk-blue" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="svc-card-lt reveal d3">
                    <div class="svc-icon-lt si-amber-dk"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3 class="svc-h-lt font-gu">ગ્રામ પંચાયત નોટિસ</h3>
                    <p class="svc-p-lt">Receive important announcements and Gram Sabha schedules directly on your phone.</p>
                    <a href="#" class="svc-lnk svc-lnk-amber">Subscribe <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="svc-card-lt reveal" style="opacity:0.5;pointer-events:none;">
                    <div class="svc-icon-lt si-muted-dk"><i class="fa-solid fa-file-signature"></i></div>
                    <h3 class="svc-h-lt font-gu">પ્રમાણપત્રો</h3>
                    <p class="svc-p-lt">Apply for and download official certificates — coming soon in Phase 2 of the portal.</p>
                    <span class="svc-locked-lt"><i class="fa-solid fa-lock" style="font-size:10px;"></i> Coming Soon</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── INITIATIVE ── -->
    <section id="initiative" aria-labelledby="init-h">
        <div class="initiative-wrap">
            <div class="init-layout reveal">
                <div class="init-left">
                    <div class="eyebrow">Empowering Digital India</div>
                    <h2 id="init-h" class="init-h">Transparent Governance, Powered by Tech</h2>
                    <p class="init-p">
                        {{ $village->name_local }} Gram Panchayat is proud to be a pioneer in smart village governance. This highly secure, scalable, and user-friendly digital framework is powered by the <strong>CISETU Platform</strong> by Clonza Infotech.
                    </p>
                    <div class="tech-block">
                        <i class="fa-solid fa-microchip" style="color:var(--terra);font-size:16px;"></i>
                        Technology Partner:&nbsp;<span class="tech-name">Clonza Infotech</span>
                    </div>
                </div>

                <div class="init-right">
                    <div class="init-stat-row">
                        <div class="init-icon i1"><i class="fa-solid fa-house-user"></i></div>
                        <div>
                            <div class="init-stat-val">2,500+</div>
                            <div class="init-stat-lbl">Homes Connected</div>
                        </div>
                    </div>
                    <div class="init-stat-row">
                        <div class="init-icon i2"><i class="fa-solid fa-bolt"></i></div>
                        <div>
                            <div class="init-stat-val">24/7</div>
                            <div class="init-stat-lbl">System Always Available</div>
                        </div>
                    </div>
                    <div class="init-stat-row">
                        <div class="init-icon i3"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                        <div>
                            <div class="init-stat-val">₹ 0</div>
                            <div class="init-stat-lbl">Convenience Fee to Citizens</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="footer-inner">
            <div class="footer-grid">

                <div>
                    <div class="footer-logo-row">
                        @if($village->logo)
                            <img src="{{ asset('storage/'.$village->logo) }}" alt="{{ $village->name_local }} Logo" class="footer-logo-img">
                        @else
                            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ $village->name_local }} Logo" class="footer-logo-img" onerror="this.style.opacity=0">
                        @endif
                        <div>
                            <div class="footer-name font-gu">ગ્રામ પંચાયત {{ $village->name_local }}</div>
                            <div class="footer-sub">Digital Citizen Portal</div>
                        </div>
                    </div>
                    <p class="footer-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
                    <div class="social-row">
                        <a href="#" class="soc-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="soc-btn" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="soc-btn" aria-label="WhatsApp" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <div class="ftr-h">Quick Links</div>
                    <ul class="ftr-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#how-it-works">How to Pay Tax</a></li>
                        <li><a href="#services">Other Services</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>

                <div>
                    <div class="ftr-h font-gu">સંપર્ક માહિતી</div>

                    <div class="contact-row">
                        <div class="contact-ico"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="contact-txt font-gu">
                            ગ્રામ પંચાયત કચેરી, {{ $village->name_local }},<br>
                            તાલુકો અને જિલ્લો: {{ $village->district->name_local ?? $village->district->name_en }}, ગુજરાત
                        </div>
                    </div>
                    <div class="contact-row">
                        <div class="contact-ico"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-txt"><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
                    </div>
                    @if($village->whatsapp_number)
                    <div class="contact-row">
                        <div class="contact-ico"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-txt"><a href="tel:+{{ $village->whatsapp_number }}">+{{ $village->whatsapp_number }}</a></div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} CISETU. All Rights Reserved.</p>
                <div class="ftr-powered">
                    Powered by <span>CISETU / Clonza Infotech</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- ── MOBILE NAV ── -->
    <nav id="mobile-nav" aria-label="Mobile navigation">
        <a href="#home" class="mob-lnk active" aria-current="page">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Home
        </a>
        <a href="#how-it-works" class="mob-lnk">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i> Guide
        </a>
        <div class="mob-fab-lt">
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" aria-label="Pay Tax via WhatsApp" rel="noopener">
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
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        revEls.forEach(el => revObs.observe(el));

        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });

        const sections = document.querySelectorAll('section[id]');
        const mobLinks = document.querySelectorAll('#mobile-nav .mob-lnk[href^="#"]');
        const secObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const id = e.target.id;
                    mobLinks.forEach(l => {
                        const isActive = l.getAttribute('href') === '#' + id;
                        l.classList.toggle('active', isActive);
                        isActive ? l.setAttribute('aria-current','page') : l.removeAttribute('aria-current');
                    });
                }
            });
        }, { threshold: 0.4, rootMargin: '-15% 0px -15% 0px' });
        sections.forEach(s => secObs.observe(s));
    </script>
</body>
</html>