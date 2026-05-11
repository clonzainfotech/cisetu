<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    @php
        $portalTheme = $portalTheme ?? 'classic';
        $themes = [
            'classic' => [
                'brand' => ['50'=>'#f0fdf4','100'=>'#dcfce7','500'=>'#22c55e','600'=>'#16a34a','900'=>'#14532d'],
                'pageBg'=>'#ffffff',
            ],
        ];
        $theme = $themes[$portalTheme] ?? $themes['classic'];
    @endphp

    <title>{{ $village->name_local }} Gram Panchayat | Digital Property Tax Portal</title>
    <meta name="description" content="Official Digital Portal for {{ $village->name_en }} Gram Panchayat. Pay your property tax instantly and securely via WhatsApp.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --forest:   #0a3d2b;
            --forest-2: #0f5235;
            --forest-3: #145e3c;
            --mint:     #00c970;
            --mint-lt:  #e6fff4;
            --mint-2:   #b3f0d4;
            --wa:       #25D366;
            --wa-dark:  #128C7E;
            --white:    #ffffff;
            --off:      #f7f9f8;
            --off-2:    #eef3f0;
            --ink:      #0d1f17;
            --ink-2:    #1f3d2e;
            --ink-3:    #4a7060;
            --ink-4:    #8aaa98;
            --rule:     rgba(10,61,43,0.1);
            --rule-2:   rgba(10,61,43,0.06);
            --r-sm:     8px;
            --r-md:     16px;
            --r-lg:     24px;
            --r-xl:     36px;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--white);
            color: var(--ink);
            font-family: 'Barlow', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        /* Geometric grid overlay */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(var(--rule-2) 1px, transparent 1px),
                linear-gradient(90deg, var(--rule-2) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .font-gu  { font-family: 'Noto Sans Gujarati', sans-serif; }
        .font-cond { font-family: 'Barlow Condensed', sans-serif; }

        /* ── NAV ── */
        #nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: var(--white);
            border-bottom: 2px solid var(--forest);
            transition: box-shadow 0.3s;
        }
        #nav.scrolled { box-shadow: 0 4px 24px rgba(10,61,43,0.12); }

        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 68px; padding: 0 28px;
        }

        .nav-logo { display: flex; align-items: center; gap: 12px; cursor: pointer; text-decoration: none; }
        .nav-logo-img {
            width: 44px; height: 44px; border-radius: 50%;
            border: 2px solid var(--forest);
            object-fit: cover; background: var(--off);
        }
        .nav-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 19px; font-weight: 800; color: var(--forest);
            text-transform: uppercase; letter-spacing: 0.04em; line-height: 1.1;
        }
        .nav-sub {
            font-size: 9px; font-weight: 600; color: var(--ink-3);
            letter-spacing: 0.2em; text-transform: uppercase;
        }

        .nav-links { display: none; gap: 32px; }
        @media (min-width: 768px) { .nav-links { display: flex; } }
        .nav-links a {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 14px; font-weight: 700; color: var(--ink-3);
            text-decoration: none; letter-spacing: 0.06em; text-transform: uppercase;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--forest); }

        .btn-nav {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 20px;
            background: var(--forest); color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;
            text-decoration: none; border: none; cursor: pointer;
            border-radius: var(--r-sm);
            transition: background 0.2s, transform 0.2s;
        }
        .btn-nav:hover { background: var(--forest-3); transform: translateY(-1px); }

        /* ── HERO ── */
        #home {
            position: relative; z-index: 1;
            padding-top: 68px;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* Top accent bar */
        .hero-accent-bar {
            background: var(--forest);
            padding: 10px 28px;
            display: flex; align-items: center; gap: 16px;
            overflow: hidden; white-space: nowrap;
        }
        .accent-ticker {
            display: flex; gap: 48px; align-items: center;
            animation: ticker 28s linear infinite;
        }
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .ticker-item {
            display: flex; align-items: center; gap: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.7);
            letter-spacing: 0.1em; text-transform: uppercase; flex-shrink: 0;
        }
        .ticker-item i { color: var(--mint); font-size: 11px; }
        .ticker-sep { width: 4px; height: 4px; background: rgba(255,255,255,0.2); border-radius: 50%; flex-shrink: 0; }

        /* Big split layout */
        .hero-split {
            flex: 1; display: grid; grid-template-columns: 1fr;
        }
        @media (min-width: 900px) { .hero-split { grid-template-columns: 1fr 1fr; } }

        /* Left panel */
        .hero-left {
            background: var(--forest);
            padding: 64px 48px 80px;
            display: flex; flex-direction: column; justify-content: center;
            position: relative; overflow: hidden;
        }

        /* Big number watermark */
        .hero-left::before {
            content: '24/7';
            position: absolute; right: -20px; bottom: -20px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 180px; font-weight: 900; line-height: 1;
            color: rgba(255,255,255,0.04); pointer-events: none; user-select: none;
        }

        /* Diagonal stripe accent */
        .hero-left::after {
            content: '';
            position: absolute; top: 0; right: 0; bottom: 0; width: 6px;
            background: repeating-linear-gradient(
                -45deg,
                var(--mint) 0px, var(--mint) 6px,
                transparent 6px, transparent 14px
            );
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 5px 12px; border-radius: 4px;
            background: rgba(0,201,112,0.15); border: 1px solid rgba(0,201,112,0.3);
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px; font-weight: 700; color: var(--mint);
            letter-spacing: 0.14em; text-transform: uppercase; margin-bottom: 28px;
            width: fit-content;
        }
        .live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--mint); animation: ldot 2s infinite; }
        @keyframes ldot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.4)} }

        .hero-h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(52px, 7vw, 96px);
            font-weight: 900;
            line-height: 0.95;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 8px;
        }
        .hero-h1 .mint-line { color: var(--mint); display: block; }
        .hero-gu {
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: clamp(20px, 3vw, 34px);
            font-weight: 800; color: rgba(255,255,255,0.55);
            margin-bottom: 32px; line-height: 1.3;
        }

        .hero-p {
            font-size: 15px; color: rgba(255,255,255,0.6);
            line-height: 1.75; max-width: 440px; margin-bottom: 40px;
        }

        .hero-ctas { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 48px; }

        .btn-mint {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 26px; border-radius: var(--r-sm);
            background: var(--mint); color: var(--forest);
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 15px; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase;
            text-decoration: none; border: none; cursor: pointer;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .btn-mint:hover { background: #00e07c; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,201,112,0.4); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 13px 24px; border-radius: var(--r-sm);
            background: transparent; border: 1.5px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 15px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
            text-decoration: none; cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-ghost:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.06); }

        .hero-trust-row {
            display: flex; flex-wrap: wrap; gap: 20px;
            padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.1);
        }
        .trust-item {
            display: flex; align-items: center; gap: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.5);
            letter-spacing: 0.06em; text-transform: uppercase;
        }
        .trust-item i { color: var(--mint); font-size: 15px; }

        /* Right panel */
        .hero-right {
            background: var(--off);
            padding: 64px 48px 80px;
            display: flex; flex-direction: column; justify-content: center;
            align-items: center;
            position: relative; overflow: hidden;
        }

        /* Grid crosshairs deco */
        .crosshair {
            position: absolute; pointer-events: none;
        }
        .crosshair-tl { top: 32px; left: 32px; }
        .crosshair-br { bottom: 32px; right: 32px; transform: rotate(180deg); }
        .crosshair svg { width: 32px; height: 32px; }

        /* Phone mockup - flat style */
        .phone-wrap {
            width: 240px; position: relative;
            animation: ph-float 8s ease-in-out infinite;
        }
        @keyframes ph-float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-16px)} }

        .phone-outer {
            width: 240px; background: var(--white);
            border: 2px solid var(--forest);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 8px 8px 0 var(--forest), 0 20px 60px rgba(10,61,43,0.15);
        }

        .ph-top-bar {
            background: var(--forest); padding: 12px 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .ph-av {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--mint); display: flex; align-items: center; justify-content: center;
            font-family: 'Barlow Condensed', sans-serif; font-size: 11px; font-weight: 800; color: var(--forest);
        }
        .ph-name { font-family: 'Barlow Condensed', sans-serif; font-size: 12px; font-weight: 700; color: #fff; letter-spacing: 0.05em; text-transform: uppercase; }
        .ph-status { font-size: 10px; color: var(--mint); display: flex; align-items: center; gap: 4px; }
        .ph-status-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--mint); }

        .ph-chat { padding: 14px 12px; background: var(--white); display: flex; flex-direction: column; gap: 10px; min-height: 260px; }

        .ph-bubble {
            max-width: 82%; padding: 9px 12px;
            font-size: 11px; line-height: 1.5; border-radius: 10px;
        }
        .ph-in {
            background: var(--off-2); color: var(--ink);
            align-self: flex-start; border-radius: 2px 10px 10px 10px;
            border-left: 3px solid var(--forest);
        }
        .ph-out {
            background: var(--forest); color: #fff;
            align-self: flex-end; border-radius: 10px 2px 10px 10px;
        }

        .ph-card {
            background: var(--off); border: 1.5px solid var(--rule);
            border-radius: 10px; padding: 12px; font-size: 11px; color: var(--ink);
            margin-top: 2px; align-self: flex-start; max-width: 90%; width: 90%;
        }
        .ph-card .lbl { font-size: 9px; font-weight: 700; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.1em; }
        .ph-card .amount { font-family: 'Barlow Condensed', sans-serif; font-size: 26px; font-weight: 900; color: var(--forest); line-height: 1.1; margin: 4px 0; }
        .ph-pay-btn {
            display: block; width: 100%; margin-top: 10px; padding: 8px;
            background: var(--mint); color: var(--forest); border: none; border-radius: 6px;
            font-family: 'Barlow Condensed', sans-serif; font-size: 11px; font-weight: 800;
            text-align: center; letter-spacing: 0.08em; text-transform: uppercase; cursor: pointer;
        }

        /* Floating stat chips */
        .stat-chip {
            position: absolute; z-index: 10;
            background: var(--white); border: 1.5px solid var(--forest);
            border-radius: var(--r-sm); padding: 10px 14px;
            box-shadow: 4px 4px 0 var(--forest);
            font-family: 'Barlow Condensed', sans-serif;
        }
        .stat-chip .sv { font-size: 22px; font-weight: 900; color: var(--forest); line-height: 1; }
        .stat-chip .sl { font-size: 10px; font-weight: 600; color: var(--ink-4); text-transform: uppercase; letter-spacing: 0.1em; }
        .chip-1 { top: 48px; right: 24px; animation: chip-float 8s ease-in-out 2s infinite; }
        .chip-2 { bottom: 80px; left: 16px; animation: chip-float 8s ease-in-out 5s infinite; }
        @keyframes chip-float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

        /* ── STATS BAND ── */
        #stats { position: relative; z-index: 1; }
        .stats-band {
            display: grid; grid-template-columns: repeat(2,1fr); border-top: 2px solid var(--forest); border-bottom: 2px solid var(--forest);
        }
        @media (min-width: 700px) { .stats-band { grid-template-columns: repeat(4,1fr); } }

        .sb-cell {
            padding: 32px 24px; text-align: center;
            border-right: 1px solid var(--rule);
            background: var(--white);
            transition: background 0.2s;
        }
        .sb-cell:last-child { border-right: none; }
        .sb-cell:hover { background: var(--mint-lt); }
        .sb-n {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 44px; font-weight: 900; color: var(--forest);
            line-height: 1; margin-bottom: 6px;
        }
        .sb-l {
            font-size: 10px; font-weight: 600; color: var(--ink-4);
            text-transform: uppercase; letter-spacing: 0.14em;
        }

        /* ── SEC HEADER ── */
        .sec-tag-m {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 10px; font-weight: 800; color: var(--forest-2);
            letter-spacing: 0.22em; text-transform: uppercase; margin-bottom: 16px;
        }
        .sec-tag-m::before { content:''; width: 24px; height: 2px; background: var(--mint); }
        .sec-h2-m {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(36px, 5vw, 62px); font-weight: 900;
            line-height: 0.95; letter-spacing: -0.01em; text-transform: uppercase;
            color: var(--forest); margin-bottom: 16px;
        }
        .sec-p-m { font-size: 15px; color: var(--ink-3); line-height: 1.75; }

        /* ── HOW IT WORKS ── */
        #how-it-works { padding: 100px 28px; position: relative; z-index: 1; }
        .hiw-inner { max-width: 1280px; margin: 0 auto; }
        .hiw-hdr { text-align: center; max-width: 600px; margin: 0 auto 64px; }

        .steps-row {
            display: grid; grid-template-columns: 1fr; gap: 2px;
            border: 2px solid var(--forest); border-radius: var(--r-md); overflow: hidden;
        }
        @media (min-width: 700px) { .steps-row { grid-template-columns: repeat(3,1fr); } }

        .step-m {
            background: var(--white);
            padding: 40px 32px; position: relative;
            border-right: 1px solid var(--rule);
            transition: background 0.3s;
            cursor: default;
        }
        .step-m:last-child { border-right: none; }
        .step-m:hover { background: var(--mint-lt); }

        /* Step number — big corner stamp */
        .step-m-num {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 80px; font-weight: 900; line-height: 1;
            color: var(--off-2); position: absolute;
            top: 12px; right: 20px; user-select: none; pointer-events: none;
            transition: color 0.3s;
        }
        .step-m:hover .step-m-num { color: var(--mint-2); }

        .step-m-icon {
            width: 56px; height: 56px; border-radius: var(--r-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 24px;
            border: 1.5px solid var(--rule);
            transition: background 0.3s, border-color 0.3s;
        }
        .si-forest { background: var(--forest); color: var(--mint); border-color: var(--forest); }
        .si-mint-bg { background: var(--mint-lt); color: var(--forest); border-color: var(--mint-2); }

        .step-m-h {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 24px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.02em; color: var(--forest); margin-bottom: 10px;
        }
        .step-m-p { font-size: 14px; color: var(--ink-3); line-height: 1.7; }

        .hiw-cta { text-align: center; margin-top: 48px; }
        .btn-forest {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 32px; border-radius: var(--r-sm);
            background: var(--forest); color: #fff;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 15px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 4px 4px 0 var(--mint-2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-forest:hover { transform: translate(-2px,-2px); box-shadow: 6px 6px 0 var(--mint-2); }

        /* ── SERVICES ── */
        #services {
            padding: 100px 28px;
            background: var(--forest);
            position: relative; z-index: 1; overflow: hidden;
        }

        #services::before {
            content: 'સેવા';
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: 300px; font-weight: 800; line-height: 1;
            color: rgba(255,255,255,0.025);
            position: absolute; right: -20px; bottom: -60px;
            pointer-events: none; user-select: none;
        }

        /* Mint strip at top */
        #services::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: repeating-linear-gradient(90deg, var(--mint) 0, var(--mint) 20px, transparent 20px, transparent 40px);
        }

        .svc-inner { max-width: 1280px; margin: 0 auto; position: relative; z-index: 1; }

        .svc-top-row {
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end;
            gap: 24px; margin-bottom: 52px;
        }
        .sec-tag-dk-m {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 10px; font-weight: 800; color: var(--mint);
            letter-spacing: 0.22em; text-transform: uppercase; margin-bottom: 14px;
        }
        .sec-tag-dk-m::before { content:''; width: 24px; height: 2px; background: var(--mint); }
        .sec-h2-dk-m {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(36px, 5vw, 60px); font-weight: 900;
            line-height: 0.95; text-transform: uppercase; color: #fff; margin-bottom: 12px;
        }
        .sec-p-dk-m { font-size: 15px; color: rgba(255,255,255,0.45); line-height: 1.7; }

        .iso-tag-m {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 16px; border-radius: var(--r-sm);
            border: 1px solid rgba(0,201,112,0.25);
            background: rgba(0,201,112,0.08);
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--mint);
            letter-spacing: 0.08em; text-transform: uppercase; flex-shrink: 0;
        }

        .svc-grid-m {
            display: grid; grid-template-columns: 1fr; gap: 1px;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--r-md); overflow: hidden;
        }
        @media (min-width: 600px) { .svc-grid-m { grid-template-columns: repeat(2,1fr); } }
        @media (min-width: 1000px) { .svc-grid-m { grid-template-columns: repeat(4,1fr); } }

        .svc-cell {
            background: rgba(255,255,255,0.03);
            padding: 32px 26px;
            transition: background 0.3s;
            cursor: pointer; position: relative; overflow: hidden;
        }
        .svc-cell:hover { background: rgba(0,201,112,0.08); }
        .svc-cell.prime { background: rgba(0,201,112,0.07); }
        .svc-cell.prime:hover { background: rgba(0,201,112,0.14); }

        .svc-cell-num {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.2);
            letter-spacing: 0.16em; text-transform: uppercase; margin-bottom: 20px;
        }
        .svc-cell-icon {
            width: 50px; height: 50px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 18px;
        }
        .ci-mint { background: rgba(0,201,112,0.12); color: var(--mint); }
        .ci-blue { background: rgba(56,189,248,0.1); color: #7dd3fc; }
        .ci-amber { background: rgba(251,191,36,0.1); color: #fde68a; }
        .ci-muted { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.2); }

        .svc-cell-h {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.02em; color: #fff; margin-bottom: 10px;
        }
        .svc-cell-p { font-size: 13px; color: rgba(255,255,255,0.4); line-height: 1.65; margin-bottom: 24px; }

        .svc-cell-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase;
            text-decoration: none; transition: gap 0.2s;
        }
        .svc-cell-link:hover { gap: 10px; }
        .lk-mint { color: var(--mint); }
        .lk-blue { color: #7dd3fc; }
        .lk-amber { color: #fde68a; }
        .svc-cell-locked {
            display: inline-flex; align-items: center; gap: 6px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.08); padding: 5px 12px;
            border-radius: 4px; letter-spacing: 0.1em; text-transform: uppercase;
        }

        /* ── INITIATIVE ── */
        #initiative {
            padding: 100px 28px; position: relative; z-index: 1;
            background: var(--off);
            border-top: 2px solid var(--forest);
        }
        .init-inner { max-width: 1280px; margin: 0 auto; }

        .init-grid {
            display: grid; grid-template-columns: 1fr; gap: 48px; align-items: center;
        }
        @media (min-width: 800px) { .init-grid { grid-template-columns: 1fr 1fr; gap: 80px; } }

        .init-left-m .sec-tag-m { margin-bottom: 16px; }
        .init-h-m {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(36px, 4.5vw, 56px); font-weight: 900; text-transform: uppercase;
            line-height: 0.95; color: var(--forest); margin-bottom: 20px;
        }
        .init-p-m { font-size: 15px; color: var(--ink-3); line-height: 1.8; margin-bottom: 28px; }
        .tech-row-m {
            display: flex; align-items: center; gap: 10px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--ink-4);
            letter-spacing: 0.1em; text-transform: uppercase;
        }
        .tech-row-m span { color: var(--forest); font-weight: 900; }

        .init-right-m {
            background: var(--forest); border-radius: var(--r-lg);
            padding: 36px 32px;
            box-shadow: 6px 6px 0 var(--mint-2);
        }
        .init-stat-m {
            display: flex; align-items: center; gap: 16px;
            padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .init-stat-m:last-child { border-bottom: none; padding-bottom: 0; }
        .init-stat-ico {
            width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 18px;
        }
        .isi-1 { background: rgba(0,201,112,0.12); color: var(--mint); }
        .isi-2 { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); }
        .isi-3 { background: rgba(251,191,36,0.1); color: #fde68a; }
        .init-stat-val-m {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 28px; font-weight: 900; color: #fff; line-height: 1;
        }
        .init-stat-lbl-m { font-size: 11px; color: rgba(255,255,255,0.4); font-weight: 500; margin-top: 2px; }

        /* ── FOOTER ── */
        footer {
            background: var(--ink);
            padding: 72px 28px 56px;
            position: relative; z-index: 1;
            border-top: 4px solid var(--mint);
        }
        .foot-inner { max-width: 1280px; margin: 0 auto; }

        .foot-grid {
            display: grid; grid-template-columns: 1fr; gap: 48px; margin-bottom: 56px;
        }
        @media (min-width: 900px) { .foot-grid { grid-template-columns: 2fr 1fr 1.5fr; gap: 64px; } }

        .foot-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
        .foot-logo-img {
            width: 46px; height: 46px; border-radius: 50%; object-fit: cover;
            border: 2px solid rgba(0,201,112,0.4);
        }
        .foot-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 18px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.04em;
        }
        .foot-sub { font-size: 9px; font-weight: 700; color: var(--mint); letter-spacing: 0.2em; text-transform: uppercase; margin-top: 2px; }
        .foot-desc { font-size: 14px; color: rgba(255,255,255,0.35); line-height: 1.75; max-width: 300px; margin-bottom: 26px; }

        .soc-row { display: flex; gap: 8px; }
        .soc-btn-m {
            width: 38px; height: 38px; border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.35); font-size: 14px; text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .soc-btn-m:hover { background: rgba(0,201,112,0.12); color: var(--mint); }

        .foot-h {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px; font-weight: 800; color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.14em; margin-bottom: 22px;
        }
        .foot-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .foot-links a {
            font-size: 14px; color: rgba(255,255,255,0.3); text-decoration: none;
            display: flex; align-items: center; gap: 8px; transition: color 0.2s;
            font-family: 'Barlow Condensed', sans-serif; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase;
        }
        .foot-links a::before { content:'→'; font-size: 12px; color: var(--mint); opacity: 0.6; }
        .foot-links a:hover { color: rgba(255,255,255,0.75); }

        .cinfo-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 14px; border-radius: var(--r-sm);
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.025); margin-bottom: 8px;
            transition: border-color 0.2s;
        }
        .cinfo-row:hover { border-color: rgba(0,201,112,0.2); }
        .cinfo-ico {
            width: 32px; height: 32px; border-radius: 6px;
            background: rgba(0,201,112,0.1); color: var(--mint);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; flex-shrink: 0;
        }
        .cinfo-txt { font-size: 13px; color: rgba(255,255,255,0.35); line-height: 1.6; }
        .cinfo-txt a { color: rgba(255,255,255,0.35); text-decoration: none; }
        .cinfo-txt a:hover { color: rgba(255,255,255,0.7); }

        .foot-bottom {
            padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.06);
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;
        }
        .foot-bottom p { font-size: 12px; color: rgba(255,255,255,0.2); font-family:'Barlow Condensed',sans-serif; letter-spacing:0.06em; text-transform:uppercase; }
        .foot-powered {
            display: flex; align-items: center; gap: 8px;
            font-family: 'Barlow Condensed', sans-serif; font-size: 12px; color: rgba(255,255,255,0.2);
            letter-spacing: 0.08em; text-transform: uppercase;
        }
        .foot-powered span {
            padding: 3px 10px; border-radius: 4px;
            background: rgba(0,201,112,0.1); border: 1px solid rgba(0,201,112,0.2);
            color: var(--mint); font-weight: 800; font-size: 11px;
        }

        /* ── MOBILE NAV ── */
        #mobile-nav {
            display: flex; position: fixed; bottom: 12px; left: 12px; right: 12px; z-index: 100;
            background: var(--white);
            border: 2px solid var(--forest);
            border-radius: var(--r-md);
            padding: 6px 4px;
            justify-content: space-around; align-items: center;
            box-shadow: 4px 4px 0 var(--forest);
        }
        @media (min-width: 768px) { #mobile-nav { display: none; } }

        .mob-lnk-m {
            display: flex; flex-direction: column; align-items: center; gap: 3px;
            color: var(--ink-4); text-decoration: none;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 9px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
            width: 58px; padding: 7px 0; border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .mob-lnk-m i { font-size: 18px; }
        .mob-lnk-m.active { color: var(--forest); background: var(--mint-lt); }
        .mob-lnk-m:hover { color: var(--forest); }

        .mob-fab-m { position: relative; top: -14px; }
        .mob-fab-m a {
            width: 58px; height: 58px; border-radius: 50%;
            background: var(--mint); display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: var(--forest); text-decoration: none;
            border: 2px solid var(--forest);
            box-shadow: 3px 3px 0 var(--forest);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .mob-fab-m a:active { transform: translate(3px,3px); box-shadow: 0 0 0 var(--forest); }

        .pb-mob { padding-bottom: 96px; }
        @media (min-width: 768px) { .pb-mob { padding-bottom: 0; } }

        /* ── REVEAL ── */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.65s ease, transform 0.65s ease; }
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
                    <div class="nav-name font-cond font-gu" style="font-family:'Barlow Condensed',sans-serif;">{{ $village->name_local }} GRAM PANCHAYAT</div>
                    <div class="nav-sub">Smart Village Portal</div>
                </div>
            </a>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#how-it-works">How to Pay</a>
                <a href="#services">Services</a>
            </div>
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-nav" rel="noopener">
                <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
            </a>
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section id="home" aria-labelledby="hero-h">

        <!-- Scrolling ticker -->
        <div class="hero-accent-bar" aria-hidden="true">
            <div class="accent-ticker">
                <span class="ticker-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-clock"></i> Available 24/7</span>
                <span class="ticker-sep"></span>
                <!-- Duplicate for seamless loop -->
                <span class="ticker-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span>
                <span class="ticker-sep"></span>
                <span class="ticker-item"><i class="fa-solid fa-clock"></i> Available 24/7</span>
                <span class="ticker-sep"></span>
            </div>
        </div>

        <div class="hero-split">
            <!-- Left dark panel -->
            <div class="hero-left reveal">
                <div class="hero-badge">
                    <span class="live-dot"></span>
                    Official Portal — Live
                </div>

                <h1 id="hero-h" class="hero-h1">
                    Pay Your<br>
                    Property<br>
                    <span class="mint-line">Tax Now.</span>
                </h1>
                <div class="hero-gu font-gu">
                    {{ $village->name_local }} ગ્રામ પંચાયત
                </div>

                <p class="hero-p">
                    {{ $village->name_local }} Gram Panchayat introduces a fully automated, secure WhatsApp system — view and pay your property tax instantly from any phone. No app needed.
                </p>

                <div class="hero-ctas">
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-mint" rel="noopener">
                        <i class="fa-brands fa-whatsapp" style="font-size:17px;"></i>
                        Start on WhatsApp
                    </a>
                    <a href="#how-it-works" class="btn-ghost">
                        How It Works →
                    </a>
                </div>

                <div class="hero-trust-row">
                    <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> Secure</div>
                    <div class="trust-item"><i class="fa-solid fa-receipt"></i> Instant Receipt</div>
                    <div class="trust-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Fee</div>
                </div>
            </div>

            <!-- Right light panel -->
            <div class="hero-right reveal d2">
                <!-- Corner crosshairs -->
                <div class="crosshair crosshair-tl" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 16 H10 M16 0 V10" stroke="#0a3d2b" stroke-width="1.5" opacity="0.3"/>
                        <circle cx="16" cy="16" r="2" fill="#00c970"/>
                    </svg>
                </div>
                <div class="crosshair crosshair-br" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 16 H10 M16 0 V10" stroke="#0a3d2b" stroke-width="1.5" opacity="0.3"/>
                        <circle cx="16" cy="16" r="2" fill="#00c970"/>
                    </svg>
                </div>

                <!-- Phone mockup -->
                <div class="phone-wrap">
                    <div class="phone-outer">
                        <div class="ph-top-bar">
                            <div class="ph-av">GP</div>
                            <div>
                                <div class="ph-name">{{ $village->name_local }} Panchayat</div>
                                <div class="ph-status"><span class="ph-status-dot"></span> Online</div>
                            </div>
                        </div>
                        <div class="ph-chat">
                            <div class="ph-bubble ph-out">Hi Panchayat 👋</div>
                            <div class="ph-bubble ph-in">Welcome! Enter your house number to check your tax.</div>
                            <div class="ph-bubble ph-out">House No. 142</div>
                            <div class="ph-card">
                                <div class="lbl">Outstanding Tax — 2024–25</div>
                                <div class="amount">₹ 1,250</div>
                                <div style="font-size:10px;color:var(--ink-4);">House #142 · {{ $village->name_local }}</div>
                                <button class="ph-pay-btn">
                                    <i class="fa-solid fa-lock-open" style="font-size:10px;"></i> Pay Now via UPI
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Floating chips -->
                <div class="stat-chip chip-1" aria-hidden="true">
                    <div class="sv">24/7</div>
                    <div class="sl">Always On</div>
                </div>
                <div class="stat-chip chip-2" aria-hidden="true">
                    <div class="sv">₹0</div>
                    <div class="sl">Extra Fee</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── STATS ── -->
    <div id="stats" class="reveal">
        <div class="stats-band">
            <div class="sb-cell">
                <div class="sb-n">2,500+</div>
                <div class="sb-l">Homes Connected</div>
            </div>
            <div class="sb-cell">
                <div class="sb-n">24/7</div>
                <div class="sb-l">System Uptime</div>
            </div>
            <div class="sb-cell">
                <div class="sb-n">0%</div>
                <div class="sb-l">Convenience Fee</div>
            </div>
            <div class="sb-cell">
                <div class="sb-n">100%</div>
                <div class="sb-l">Digital Accuracy</div>
            </div>
        </div>
    </div>

    <!-- ── HOW IT WORKS ── -->
    <section id="how-it-works" aria-labelledby="hiw-h">
        <div class="hiw-inner">
            <div class="hiw-hdr reveal">
                <div class="sec-tag-m">Simple Process</div>
                <h2 id="hiw-h" class="sec-h2-m font-gu" style="font-family:'Barlow Condensed',sans-serif;">
                    3 Steps to<br>Pay Your Tax
                </h2>
                <p class="sec-p-m">Direct from your phone. No app installation required.</p>
            </div>

            <div class="steps-row">
                <div class="step-m reveal d1">
                    <div class="step-m-num" aria-hidden="true">01</div>
                    <div class="step-m-icon si-forest"><i class="fa-brands fa-whatsapp"></i></div>
                    <h3 class="step-m-h">Send a Message</h3>
                    <p class="step-m-p">Send "Hi" to our official Panchayat WhatsApp number to start the automated bot instantly.</p>
                </div>
                <div class="step-m reveal d2">
                    <div class="step-m-num" aria-hidden="true">02</div>
                    <div class="step-m-icon si-mint-bg"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <h3 class="step-m-h">Verify Details</h3>
                    <p class="step-m-p">Enter your house number or phone number to see your outstanding property & water tax.</p>
                </div>
                <div class="step-m reveal d3">
                    <div class="step-m-num" aria-hidden="true">03</div>
                    <div class="step-m-icon si-forest"><i class="fa-solid fa-receipt"></i></div>
                    <h3 class="step-m-h">Pay & Get Receipt</h3>
                    <p class="step-m-p">Pay via UPI (GPay, PhonePe). Your official signed PDF receipt arrives on WhatsApp instantly.</p>
                </div>
            </div>

            <div class="hiw-cta reveal">
                <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-forest" rel="noopener">
                    Try the Live Bot &nbsp;<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ── SERVICES ── -->
    <section id="services" aria-labelledby="svc-h">
        <div class="svc-inner">
            <div class="svc-top-row reveal">
                <div>
                    <div class="sec-tag-dk-m">Digital Governance</div>
                    <h2 id="svc-h" class="sec-h2-dk-m font-gu" style="font-family:'Barlow Condensed',sans-serif;">Online<br>Services</h2>
                    <p class="sec-p-dk-m">Panchayat services, directly on your phone.</p>
                </div>
                <div class="iso-tag-m"><i class="fa-solid fa-certificate"></i> ISO Certified</div>
            </div>

            <div class="svc-grid-m">
                <div class="svc-cell prime reveal d1">
                    <div class="svc-cell-num">01 / Property</div>
                    <div class="svc-cell-icon ci-mint"><i class="fa-solid fa-house-chimney"></i></div>
                    <h3 class="svc-cell-h font-gu" style="font-family:'Barlow Condensed',sans-serif;">મિલકત વેરો</h3>
                    <p class="svc-cell-p">View and pay yearly property taxes for residential and commercial spaces without visiting the office.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-cell-link lk-mint" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="svc-cell reveal d2">
                    <div class="svc-cell-num">02 / Water</div>
                    <div class="svc-cell-icon ci-blue"><i class="fa-solid fa-droplet"></i></div>
                    <h3 class="svc-cell-h font-gu" style="font-family:'Barlow Condensed',sans-serif;">પાણી વેરો</h3>
                    <p class="svc-cell-p">Clear your water connection bills seamlessly without visiting the office. Quick and easy.</p>
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-cell-link lk-blue" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="svc-cell reveal d3">
                    <div class="svc-cell-num">03 / Notice</div>
                    <div class="svc-cell-icon ci-amber"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3 class="svc-cell-h font-gu" style="font-family:'Barlow Condensed',sans-serif;">ગ્રામ પંચાયત નોટિસ</h3>
                    <p class="svc-cell-p">Receive important announcements and Gram Sabha schedules directly on your phone.</p>
                    <a href="#" class="svc-cell-link lk-amber">Subscribe <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="svc-cell reveal" style="pointer-events:none;opacity:0.45;">
                    <div class="svc-cell-num">04 / Certificates</div>
                    <div class="svc-cell-icon ci-muted"><i class="fa-solid fa-file-signature"></i></div>
                    <h3 class="svc-cell-h font-gu" style="font-family:'Barlow Condensed',sans-serif;">પ્રમાણપત્રો</h3>
                    <p class="svc-cell-p">Apply for and download official certificates — coming in Phase 2 of this portal.</p>
                    <span class="svc-cell-locked"><i class="fa-solid fa-lock" style="font-size:10px;"></i> Coming Soon</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── INITIATIVE ── -->
    <section id="initiative" aria-labelledby="init-h">
        <div class="init-inner">
            <div class="init-grid reveal">
                <div class="init-left-m">
                    <div class="sec-tag-m">Empowering Digital India</div>
                    <h2 id="init-h" class="init-h-m">Transparent Governance. Powered by Tech.</h2>
                    <p class="init-p-m">
                        {{ $village->name_local }} Gram Panchayat is proud to pioneer smart village governance. This secure, scalable digital framework is powered by the <strong>CISETU Platform</strong> built by Clonza Infotech.
                    </p>
                    <div class="tech-row-m">
                        <i class="fa-solid fa-microchip" style="color:var(--mint);font-size:15px;"></i>
                        Technology Partner: <span>Clonza Infotech</span>
                    </div>
                </div>
                <div class="init-right-m">
                    <div class="init-stat-m">
                        <div class="init-stat-ico isi-1"><i class="fa-solid fa-house-user"></i></div>
                        <div>
                            <div class="init-stat-val-m">2,500+</div>
                            <div class="init-stat-lbl-m">Homes Connected</div>
                        </div>
                    </div>
                    <div class="init-stat-m">
                        <div class="init-stat-ico isi-2"><i class="fa-solid fa-clock"></i></div>
                        <div>
                            <div class="init-stat-val-m">24/7</div>
                            <div class="init-stat-lbl-m">Always Available</div>
                        </div>
                    </div>
                    <div class="init-stat-m">
                        <div class="init-stat-ico isi-3"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                        <div>
                            <div class="init-stat-val-m">₹ 0</div>
                            <div class="init-stat-lbl-m">Convenience Fee to Citizens</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer>
        <div class="foot-inner">
            <div class="foot-grid">
                <div>
                    <div class="foot-logo-row">
                        @if($village->logo)
                            <img src="{{ asset('storage/'.$village->logo) }}" alt="{{ $village->name_local }} Logo" class="foot-logo-img">
                        @else
                            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ $village->name_local }} Logo" class="foot-logo-img" onerror="this.style.opacity=0">
                        @endif
                        <div>
                            <div class="foot-name font-gu" style="font-family:'Barlow Condensed',sans-serif;">{{ $village->name_local }} GRAM PANCHAYAT</div>
                            <div class="foot-sub">Digital Citizen Portal</div>
                        </div>
                    </div>
                    <p class="foot-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
                    <div class="soc-row">
                        <a href="#" class="soc-btn-m" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="soc-btn-m" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" class="soc-btn-m" aria-label="WhatsApp" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
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
                    <div class="foot-h font-gu" style="font-family:'Barlow Condensed',sans-serif;">Contact Info</div>
                    <div class="cinfo-row">
                        <div class="cinfo-ico"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="cinfo-txt font-gu">
                            ગ્રામ પંચાયત કચેરી, {{ $village->name_local }},<br>
                            {{ $village->district->name_local ?? $village->district->name_en }}, ગુજરાત
                        </div>
                    </div>
                    <div class="cinfo-row">
                        <div class="cinfo-ico"><i class="fa-solid fa-envelope"></i></div>
                        <div class="cinfo-txt"><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
                    </div>
                    @if($village->whatsapp_number)
                    <div class="cinfo-row">
                        <div class="cinfo-ico"><i class="fa-solid fa-phone"></i></div>
                        <div class="cinfo-txt"><a href="tel:+{{ $village->whatsapp_number }}">+{{ $village->whatsapp_number }}</a></div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="foot-bottom">
                <p>&copy; {{ date('Y') }} CISETU. All Rights Reserved.</p>
                <div class="foot-powered">Powered by <span>CISETU / Clonza Infotech</span></div>
            </div>
        </div>
    </footer>

    <!-- ── MOBILE NAV ── -->
    <nav id="mobile-nav" aria-label="Mobile navigation">
        <a href="#home" class="mob-lnk-m active" aria-current="page">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Home
        </a>
        <a href="#how-it-works" class="mob-lnk-m">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i> Guide
        </a>
        <div class="mob-fab-m">
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number ?? '919316781820' }}&text&type=phone_number&app_absent=0" target="_blank" aria-label="Pay Tax via WhatsApp" rel="noopener">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
            </a>
        </div>
        <a href="#services" class="mob-lnk-m">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i> Services
        </a>
        <a href="#" class="mob-lnk-m">
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
        const mobLinks = document.querySelectorAll('#mobile-nav .mob-lnk-m[href^="#"]');
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
    </script>
</body>
</html>