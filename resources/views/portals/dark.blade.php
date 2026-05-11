<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $village->name_local }} | Digital Property Tax Portal</title>
    <meta name="description" content="Official Digital Portal for {{ $village->name_local }}. Pay your property tax instantly and securely via WhatsApp.">

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink: #07080F;
            --ink-2: #0E1220;
            --ink-3: #151928;
            --saffron: #FF6B2C;
            --saffron-light: #FF8C55;
            --saffron-glow: rgba(255,107,44,0.22);
            --gold: #F0A500;
            --gold-glow: rgba(240,165,0,0.18);
            --wa: #25D366;
            --wa-dark: #1aab55;
            --wa-glow: rgba(37,211,102,0.25);
            --surface: rgba(255,255,255,0.04);
            --surface-2: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.09);
            --border-hover: rgba(255,107,44,0.4);
            --text-primary: #F0EDE8;
            --text-muted: #8B8FA8;
            --text-faint: #5a5e73;
            --radius-sm: 12px;
            --radius-md: 20px;
            --radius-lg: 32px;
            --radius-xl: 48px;
        }

        html { font-size: 16px; }
        body {
            background-color: var(--ink);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ── Mandala Background ── */
        .mandala-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .mandala-bg::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(255,107,44,0.10) 0%, transparent 65%);
            border-radius: 50%;
        }
        .mandala-bg::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -15%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(37,211,102,0.07) 0%, transparent 65%);
            border-radius: 50%;
        }
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 80%);
        }

        /* ── Typography ── */
        .font-display { font-family: 'Syne', sans-serif; }
        .font-gujarati { font-family: 'Noto Sans Gujarati', 'Syne', sans-serif; }

        /* ── Glassmorphism Card ── */
        .glass {
            background: var(--surface);
            border: 1px solid var(--border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .glass-2 {
            background: var(--surface-2);
            border: 1px solid var(--border);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* ── Buttons ── */
        .btn-wa {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--wa);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            padding: 14px 28px;
            border-radius: 100px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 0 var(--wa-glow);
            white-space: nowrap;
        }
        .btn-wa:hover {
            background: var(--wa-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--wa-glow);
        }
        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--surface-2);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            padding: 14px 28px;
            border-radius: 100px;
            border: 1px solid var(--border);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .btn-ghost:hover {
            border-color: var(--border-hover);
            background: rgba(255,107,44,0.08);
            transform: translateY(-2px);
        }
        .btn-saffron {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--saffron);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            padding: 16px 36px;
            border-radius: 100px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px var(--saffron-glow);
        }
        .btn-saffron:hover {
            background: var(--saffron-light);
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(255,107,44,0.35);
        }

        /* ── Tags ── */
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,107,44,0.12);
            color: var(--saffron-light);
            border: 1px solid rgba(255,107,44,0.25);
            border-radius: 100px;
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 6px 16px;
        }
        .tag.wa-tag {
            background: rgba(37,211,102,0.12);
            color: #4dd984;
            border-color: rgba(37,211,102,0.25);
        }

        /* ── Navigation ── */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 20px 24px;
            transition: all 0.4s ease;
        }
        #navbar.scrolled {
            padding: 12px 24px;
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            background: rgba(7,8,15,0.75);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 10px 10px 10px 20px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: all 0.4s ease;
        }
        #navbar.scrolled .nav-inner {
            padding: 8px 8px 8px 18px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .nav-logo-img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid rgba(255,107,44,0.4);
            object-fit: cover;
            background: var(--ink-2);
            flex-shrink: 0;
        }
        .nav-logo-text h1 {
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }
        .nav-logo-text p {
            font-family: 'Syne', sans-serif;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--saffron);
        }

        .nav-links {
            display: none;
            align-items: center;
            gap: 6px;
        }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 100px;
            transition: all 0.2s;
        }
        .nav-links a:hover {
            color: var(--text-primary);
            background: var(--surface-2);
        }

        @media (min-width: 768px) {
            .nav-links { display: flex; }
        }

        /* ── Hero ── */
        #hero {
            min-height: 100svh;
            display: flex;
            align-items: center;
            padding: 120px 24px 80px;
            position: relative;
            z-index: 1;
        }
        .hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 60px;
            align-items: center;
        }
        @media (min-width: 900px) {
            .hero-inner { grid-template-columns: 1fr 1fr; gap: 48px; }
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--wa);
            position: relative;
            flex-shrink: 0;
        }
        .pulse-dot::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid var(--wa);
            opacity: 0.5;
            animation: pulsering 2s ease-out infinite;
        }
        @keyframes pulsering {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .hero-headline {
            font-family: 'Noto Sans Gujarati', 'Syne', sans-serif;
            font-size: clamp(2.4rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 24px;
            color: var(--text-primary);
        }
        .hero-headline .accent {
            background: linear-gradient(135deg, var(--saffron) 0%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 1.0625rem;
            color: var(--text-muted);
            line-height: 1.75;
            max-width: 480px;
            margin-bottom: 40px;
        }
        .hero-ctas {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 48px;
        }
        .hero-trust {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: center;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
        }
        .trust-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        /* ── Hero Visual / Phone Mockup ── */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .phone-orbit {
            position: relative;
            width: 300px;
            height: 560px;
        }
        @media (min-width: 480px) {
            .phone-orbit { width: 340px; height: 620px; }
        }
        .phone-frame {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 270px;
            background: var(--ink-2);
            border-radius: 44px;
            border: 2px solid rgba(255,255,255,0.12);
            overflow: hidden;
            box-shadow:
                0 40px 80px rgba(0,0,0,0.6),
                inset 0 1px 0 rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) translateY(0px); }
            50% { transform: translate(-50%, -50%) translateY(-18px); }
        }
        .phone-notch {
            width: 120px;
            height: 32px;
            background: var(--ink-2);
            border-radius: 0 0 22px 22px;
            margin: 0 auto;
        }
        .phone-status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 20px 4px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
        }
        .phone-chat-header {
            background: #075e54;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chat-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--wa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .chat-header-text h4 {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
        }
        .chat-header-text p {
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            font-family: 'DM Sans', sans-serif;
        }
        .phone-body {
            background: #ECE5DD;
            min-height: 360px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .chat-bubble {
            max-width: 85%;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 12px;
            font-family: 'DM Sans', sans-serif;
            line-height: 1.5;
        }
        .chat-bubble.received {
            background: #fff;
            color: #111;
            border-radius: 2px 12px 12px 12px;
            align-self: flex-start;
        }
        .chat-bubble.sent {
            background: #DCF8C6;
            color: #111;
            border-radius: 12px 2px 12px 12px;
            align-self: flex-end;
        }
        .chat-bubble .bubble-label {
            font-size: 10px;
            font-weight: 700;
            color: #128C7E;
            display: block;
            margin-bottom: 4px;
        }
        .pay-btn {
            display: block;
            background: #25D366;
            color: #fff;
            text-align: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .phone-input-bar {
            background: #F0F2F5;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
        }
        .phone-input-mock {
            flex: 1;
            background: #fff;
            border-radius: 22px;
            height: 32px;
        }

        /* Floating Cards */
        .float-card {
            position: absolute;
            border-radius: var(--radius-md);
            padding: 12px 18px;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 12px 40px rgba(0,0,0,0.35);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 10;
        }
        .float-card-1 {
            top: 4%;
            right: -5%;
            background: rgba(14,18,32,0.9);
            border: 1px solid rgba(37,211,102,0.3);
            animation: floatcard1 5s ease-in-out infinite;
        }
        .float-card-2 {
            bottom: 8%;
            left: -5%;
            background: rgba(14,18,32,0.9);
            border: 1px solid rgba(255,107,44,0.3);
            animation: floatcard2 6s ease-in-out 1.5s infinite;
        }
        @keyframes floatcard1 {
            0%,100%{transform:translateY(0) rotate(1deg);}
            50%{transform:translateY(-12px) rotate(-1deg);}
        }
        @keyframes floatcard2 {
            0%,100%{transform:translateY(0) rotate(-1deg);}
            50%{transform:translateY(-10px) rotate(1deg);}
        }
        .float-card-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 4px;
        }
        .float-card-value {
            font-size: 18px;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
        }

        /* ── Section Commons ── */
        section { position: relative; z-index: 1; }
        .section-pad { padding: 100px 24px; }
        .container { max-width: 1280px; margin: 0 auto; }

        .section-header { text-align: center; margin-bottom: 72px; }
        .section-headline {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 16px 0 20px;
            color: var(--text-primary);
        }
        .section-headline.gu { font-family: 'Noto Sans Gujarati', sans-serif; }
        .section-sub {
            font-size: 1.0625rem;
            color: var(--text-muted);
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.75;
        }

        /* ── Stats Band ── */
        #stats {
            background: var(--ink-2);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
        }
        @media (min-width: 768px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
        }
        .stat-item {
            padding: 40px 32px;
            text-align: center;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            transition: background 0.3s;
        }
        .stat-item:hover { background: var(--surface); }
        .stat-item:last-child, .stat-item:nth-child(4) { border-right: none; }
        @media (min-width: 768px) {
            .stat-item { border-bottom: none; }
            .stat-item:last-child { border-right: none; }
            .stat-item:nth-child(4) { border-right: none; }
            .stat-item:nth-child(2), .stat-item:nth-child(3) { border-right: 1px solid var(--border); }
        }
        @media (max-width: 767px) {
            .stat-item:nth-child(2n) { border-right: none; }
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin: 0 auto 16px;
        }
        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 8px;
        }
        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-faint);
        }

        /* ── Steps / How It Works ── */
        #how {
            background: var(--ink);
        }
        .steps-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            position: relative;
        }
        @media (min-width: 768px) {
            .steps-grid { grid-template-columns: repeat(3, 1fr); gap: 32px; }
        }
        .step-card {
            border-radius: var(--radius-lg);
            padding: 40px 32px;
            position: relative;
            overflow: hidden;
            transition: all 0.35s ease;
            cursor: default;
        }
        .step-card:hover {
            transform: translateY(-8px);
        }
        .step-card::before {
            content: attr(data-num);
            position: absolute;
            top: -20px;
            right: 24px;
            font-family: 'Syne', sans-serif;
            font-size: 8rem;
            font-weight: 800;
            color: rgba(255,255,255,0.025);
            line-height: 1;
            pointer-events: none;
            user-select: none;
        }
        .step-card-1 {
            background: linear-gradient(135deg, rgba(37,211,102,0.08) 0%, rgba(37,211,102,0.03) 100%);
            border: 1px solid rgba(37,211,102,0.18);
        }
        .step-card-1:hover { border-color: rgba(37,211,102,0.4); box-shadow: 0 20px 60px rgba(37,211,102,0.1); }
        .step-card-2 {
            background: linear-gradient(135deg, rgba(255,107,44,0.08) 0%, rgba(255,107,44,0.03) 100%);
            border: 1px solid rgba(255,107,44,0.18);
        }
        .step-card-2:hover { border-color: rgba(255,107,44,0.4); box-shadow: 0 20px 60px rgba(255,107,44,0.1); }
        .step-card-3 {
            background: linear-gradient(135deg, rgba(240,165,0,0.08) 0%, rgba(240,165,0,0.03) 100%);
            border: 1px solid rgba(240,165,0,0.18);
        }
        .step-card-3:hover { border-color: rgba(240,165,0,0.4); box-shadow: 0 20px 60px rgba(240,165,0,0.1); }

        .step-badge {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 24px;
        }
        .step-badge-1 { background: rgba(37,211,102,0.15); color: var(--wa); }
        .step-badge-2 { background: rgba(255,107,44,0.15); color: var(--saffron); }
        .step-badge-3 { background: rgba(240,165,0,0.15); color: var(--gold); }

        .step-num-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .step-num-tag-1 { background: rgba(37,211,102,0.2); color: var(--wa); }
        .step-num-tag-2 { background: rgba(255,107,44,0.2); color: var(--saffron); }
        .step-num-tag-3 { background: rgba(240,165,0,0.2); color: var(--gold); }

        .step-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-primary);
        }
        .step-desc {
            font-size: 0.9375rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── Services ── */
        #services {
            background: var(--ink-2);
        }
        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 640px) { .services-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .services-grid { grid-template-columns: repeat(4, 1fr); } }

        .service-card {
            border-radius: var(--radius-md);
            padding: 32px 28px;
            border: 1px solid var(--border);
            background: var(--surface);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .service-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(255,107,44,0.1);
        }
        .service-card.locked {
            opacity: 0.6;
            cursor: default;
        }
        .service-card.locked:hover { transform: none; box-shadow: none; border-color: var(--border); }
        .service-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
            background: rgba(255,107,44,0.1);
            color: var(--saffron);
            transition: all 0.3s;
        }
        .service-card:hover .service-icon { background: var(--saffron); color: #fff; transform: scale(1.1); }
        .service-card.locked:hover .service-icon { background: rgba(255,107,44,0.1); color: var(--saffron); transform: none; }

        .service-icon.blue { background: rgba(56,189,248,0.1); color: #38bdf8; }
        .service-card:hover .service-icon.blue { background: #38bdf8; color: #fff; }
        .service-icon.amber { background: rgba(240,165,0,0.1); color: var(--gold); }
        .service-card:hover .service-icon.amber { background: var(--gold); color: #fff; }
        .service-icon.gray { background: rgba(100,116,139,0.1); color: #64748b; }

        .service-title {
            font-family: 'Noto Sans Gujarati', 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-primary);
        }
        .service-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 20px;
        }
        .service-link {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--saffron);
            display: flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.2s;
        }
        .service-card:hover .service-link { gap: 10px; }
        .service-link.blue { color: #38bdf8; }
        .service-link.amber { color: var(--gold); }
        .locked-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-faint);
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 4px 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── About/CTA Banner ── */
        #about {
            background: var(--ink);
        }
        .about-inner {
            display: grid;
            grid-template-columns: 1fr;
            gap: 60px;
            align-items: center;
        }
        @media (min-width: 900px) {
            .about-inner { grid-template-columns: 1fr 1fr; }
        }
        .about-badge-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 40px;
        }
        .about-badge-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            background: var(--surface);
            border: 1px solid var(--border);
            transition: border-color 0.3s;
        }
        .about-badge-item:hover { border-color: rgba(255,107,44,0.3); }
        .about-badge-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .about-badge-text h5 {
            font-family: 'Syne', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 2px;
        }
        .about-badge-text p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .about-cta-block {
            border-radius: var(--radius-lg);
            padding: 48px 40px;
            background: linear-gradient(135deg, rgba(255,107,44,0.08) 0%, rgba(37,211,102,0.05) 100%);
            border: 1px solid rgba(255,107,44,0.2);
            text-align: center;
        }
        .about-cta-block h3 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 800;
            color: var(--text-primary);
            margin: 20px 0 14px;
        }
        .about-cta-block p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .wa-big-icon {
            width: 64px;
            height: 64px;
            border-radius: 22px;
            background: rgba(37,211,102,0.15);
            border: 2px solid rgba(37,211,102,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--wa);
            margin: 0 auto 8px;
        }

        /* ── Footer ── */
        footer {
            background: var(--ink-2);
            border-top: 1px solid var(--border);
            padding: 80px 24px 120px;
            position: relative;
            z-index: 1;
        }
        @media (min-width: 768px) {
            footer { padding: 80px 24px 80px; }
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 48px;
        }
        @media (min-width: 640px) { .footer-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 900px) { .footer-grid { grid-template-columns: 5fr 3fr 4fr; } }

        .footer-brand-name {
            font-family: 'Noto Sans Gujarati', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 16px 0 4px;
        }
        .footer-brand-sub {
            font-family: 'Syne', sans-serif;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--saffron);
            margin-bottom: 16px;
        }
        .footer-brand-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.75;
            max-width: 340px;
            margin-bottom: 28px;
        }
        .social-links {
            display: flex;
            gap: 10px;
        }
        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .social-btn:hover { background: var(--saffron); color: #fff; border-color: var(--saffron); transform: translateY(-2px); }

        .footer-heading {
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-faint);
            margin-bottom: 24px;
        }
        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9375rem;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer-links a:hover { color: var(--saffron); }
        .footer-links a::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--saffron);
            opacity: 0.4;
            flex-shrink: 0;
        }

        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .contact-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--saffron);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-bottom {
            margin-top: 64px;
            padding-top: 28px;
            border-top: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            text-align: center;
        }
        @media (min-width: 640px) {
            .footer-bottom { flex-direction: row; justify-content: space-between; text-align: left; }
        }
        .footer-bottom p {
            font-size: 0.8125rem;
            color: var(--text-faint);
        }
        .powered-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 6px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
        }
        .powered-badge span { color: var(--saffron); font-family: 'Syne', sans-serif; font-weight: 700; }

        /* ── Mobile Nav Bottom Bar ── */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 16px;
            left: 16px;
            right: 16px;
            background: rgba(14,18,32,0.95);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        @media (min-width: 768px) { .mobile-bottom-nav { display: none; } }

        .mob-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-faint);
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s;
            padding: 4px 10px;
        }
        .mob-nav-item i { font-size: 18px; }
        .mob-nav-item:hover, .mob-nav-item.active { color: var(--saffron); }

        .mob-nav-wa {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--wa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            margin-top: -28px;
            border: 3px solid var(--ink-2);
            box-shadow: 0 4px 20px var(--wa-glow);
            transition: all 0.3s;
            text-decoration: none;
            flex-shrink: 0;
        }
        .mob-nav-wa:hover { transform: scale(1.08); background: var(--wa-dark); }

        /* ── Scroll Animations ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ── Divider ── */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 0;
        }

        /* ── Responsive utilities ── */
        @media (max-width: 479px) {
            .hero-headline { font-size: 2rem; }
            .section-headline { font-size: 1.6rem; }
            .btn-wa, .btn-ghost, .btn-saffron { font-size: 0.9rem; padding: 12px 22px; }
        }
        @media (min-width: 1400px) {
            .container { max-width: 1400px; }
        }
    </style>
</head>
<body>

    <!-- ── Atmospheric Background ── -->
    <div class="mandala-bg">
        <div class="grid-pattern"></div>
    </div>

    <!-- ── Navigation ── -->
    <nav id="navbar">
        <div class="nav-inner">
            <a href="#" class="nav-logo">
                <img src="{{ $village->logo ? Storage::url($village->logo) : asset('assets/images/logo.jpeg') }}" alt="Logo" class="nav-logo-img" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
                <div class="nav-logo-text">
                    <h1>ગ્રામ પંચાયત {{ $village->name_local }}</h1>
                    <p>Smart Village Portal</p>
                </div>
            </a>
            <div class="nav-links">
                <a href="#hero">Home</a>
                <a href="#how">How to Pay</a>
                <a href="#services">Services</a>
                <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-wa" style="font-size:0.85rem;padding:10px 22px;">
                    <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
                </a>
            </div>
        </div>
    </nav>

    <!-- ── Hero ── -->
    <section id="hero">
        <div class="hero-inner container">

            <!-- Left: Content -->
            <div>
                <div class="hero-eyebrow">
                    <div class="pulse-dot"></div>
                    <span class="tag wa-tag">Live System · Secure Payment</span>
                </div>

                <h2 class="hero-headline font-gujarati">
                    ગ્રામ પંચાયત ગ્રામ પંચાયત {{ $village->name_local }}<br>
                    <span class="accent">વોટ્સએપ</span> થી<br>
                    વેરો ભરો
                </h2>

                <p class="hero-sub">
                    No queues. No paperwork. Pay your property and water tax securely via WhatsApp — anytime, anywhere. Official digital receipt delivered in seconds.
                </p>

                <div class="hero-ctas">
                    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-wa">
                        <i class="fa-brands fa-whatsapp" style="font-size:20px;"></i>
                        Start on WhatsApp
                    </a>
                    <a href="#how" class="btn-ghost">
                        <i class="fa-solid fa-circle-play"></i>
                        How it works
                    </a>
                </div>

                <div class="hero-trust">
                    <div class="trust-item">
                        <div class="trust-icon" style="background:rgba(37,211,102,0.12);color:var(--wa);">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>
                        <span>100% Secure</span>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon" style="background:rgba(255,107,44,0.12);color:var(--saffron);">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <span>Instant Receipt</span>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon" style="background:rgba(240,165,0,0.12);color:var(--gold);">
                            <i class="fa-solid fa-indian-rupee-sign"></i>
                        </div>
                        <span>Zero Fee</span>
                    </div>
                </div>
            </div>

            <!-- Right: Phone Mockup -->
            <div class="hero-visual">
                <div class="phone-orbit">

                    <!-- Success card -->
                    <div class="float-card float-card-1">
                        <div class="float-card-label" style="color:var(--wa);">
                            <i class="fa-solid fa-circle-check"></i> Payment Done
                        </div>
                        <div class="float-card-value" style="color:#fff;">₹1,250 ✓</div>
                    </div>

                    <!-- Receipt card -->
                    <div class="float-card float-card-2">
                        <div class="float-card-label" style="color:var(--gold);">
                            <i class="fa-solid fa-file-pdf"></i> Receipt
                        </div>
                        <div class="float-card-value" style="color:#fff; font-size:14px;">PDF Sent!</div>
                    </div>

                    <!-- Phone -->
                    <div class="phone-frame">
                        <div class="phone-notch"></div>
                        <div class="phone-status">
                            <span>9:41</span>
                            <span style="display:flex;gap:4px;align-items:center;">
                                <i class="fa-solid fa-signal" style="font-size:9px;"></i>
                                <i class="fa-solid fa-wifi" style="font-size:9px;"></i>
                                <i class="fa-solid fa-battery-three-quarters" style="font-size:9px;"></i>
                            </span>
                        </div>
                        <div class="phone-chat-header">
                            <div class="chat-avatar"><i class="fa-brands fa-whatsapp" style="color:#fff;font-size:18px;"></i></div>
                            <div class="chat-header-text">
                                <h4>{{ $village->name_local }}</h4>
                                <p>Official · Verified</p>
                            </div>
                        </div>
                        <div class="phone-body">
                            <div class="chat-bubble received">
                                <span class="bubble-label">GP Portal</span>
                                🙏 Welcome!<br>
                                <strong>House #A-42 · Rambhai Patel</strong><br>
                                Property Tax: ₹1,250
                                <div class="pay-btn">💳 Pay Now via UPI</div>
                            </div>
                            <div class="chat-bubble sent">Okay, pay ₹1,250 now</div>
                            <div class="chat-bubble received">
                                <span class="bubble-label">GP Portal</span>
                                ✅ Payment Successful!<br>
                                Receipt #GP2024-3241<br>
                                <strong>PDF attached below ↓</strong>
                            </div>
                        </div>
                        <div class="phone-input-bar">
                            <div class="phone-input-mock"></div>
                            <div style="width:32px;height:32px;border-radius:50%;background:var(--wa);display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-microphone" style="color:#fff;font-size:12px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ── Stats Band ── -->
    <section id="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon" style="background:rgba(37,211,102,0.12);color:var(--wa);">
                        <i class="fa-solid fa-house-user"></i>
                    </div>
                    <div class="stat-num" style="color:var(--text-primary);">2,500<span style="color:var(--saffron);">+</span></div>
                    <div class="stat-label">Homes Connected</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background:rgba(255,107,44,0.12);color:var(--saffron);">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="stat-num" style="color:var(--text-primary);">24<span style="color:var(--saffron);">/7</span></div>
                    <div class="stat-label">System Uptime</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background:rgba(240,165,0,0.12);color:var(--gold);">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <div class="stat-num" style="color:var(--text-primary);">0<span style="color:var(--saffron);">%</span></div>
                    <div class="stat-label">Convenience Fee</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background:rgba(168,85,247,0.12);color:#c084fc;">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <div class="stat-num" style="color:var(--text-primary);">100<span style="color:var(--saffron);">%</span></div>
                    <div class="stat-label">Digital Accuracy</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── How It Works ── -->
    <section id="how" class="section-pad">
        <div class="container">
            <div class="section-header reveal">
                <span class="tag">Simple Process · 3 Steps</span>
                <h3 class="section-headline gu" style="margin-top:20px;">કરવેરો ભરવાની<br>સૌથી સરળ રીત</h3>
                <p class="section-sub">Pay from your phone in minutes. No app needed, no office visit required.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card step-card-1 reveal" data-num="1">
                    <div class="step-num-tag step-num-tag-1">01</div>
                    <div class="step-badge step-badge-1">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h5 class="step-title">Send a Message</h5>
                    <p class="step-desc">Simply send "Hi" to our official Panchayat WhatsApp number. The intelligent automated system responds instantly.</p>
                </div>

                <div class="step-card step-card-2 reveal reveal-delay-1" data-num="2">
                    <div class="step-num-tag step-num-tag-2">02</div>
                    <div class="step-badge step-badge-2">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h5 class="step-title">Verify Details</h5>
                    <p class="step-desc">Enter your house number or registered phone number. Your outstanding property and water tax appear immediately.</p>
                </div>

                <div class="step-card step-card-3 reveal reveal-delay-2" data-num="3">
                    <div class="step-num-tag step-num-tag-3">03</div>
                    <div class="step-badge step-badge-3">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h5 class="step-title">Pay & Get Receipt</h5>
                    <p class="step-desc">Pay securely via GPay, PhonePe, or any UPI app. Your official signed PDF receipt is sent to WhatsApp instantly.</p>
                </div>
            </div>

            <div style="text-align:center;margin-top:56px;" class="reveal reveal-delay-3">
                <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-saffron">
                    Try the Live Bot <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- ── Services ── -->
    <section id="services" class="section-pad">
        <div class="container">
            <div class="section-header reveal">
                <span class="tag">Digital Governance</span>
                <h3 class="section-headline gu" style="margin-top:20px;">ઓનલાઇન સેવાઓ</h3>
                <p class="section-sub">All Panchayat services, accessible directly from your smartphone — transparent and always available.</p>
            </div>

            <div class="services-grid">
                <div class="service-card reveal">
                    <div class="service-icon"><i class="fa-solid fa-house-chimney"></i></div>
                    <h4 class="service-title">મિલકત વેરો</h4>
                    <p class="service-desc">View and pay yearly property taxes for residential and commercial properties with a single tap.</p>
                    <span class="service-link">Pay Now <i class="fa-solid fa-arrow-right"></i></span>
                </div>

                <div class="service-card reveal reveal-delay-1">
                    <div class="service-icon blue"><i class="fa-solid fa-droplet"></i></div>
                    <h4 class="service-title">પાણી વેરો</h4>
                    <p class="service-desc">Clear your water connection bills seamlessly without visiting the office. Fast and reliable.</p>
                    <span class="service-link blue">Pay Now <i class="fa-solid fa-arrow-right"></i></span>
                </div>

                <div class="service-card reveal reveal-delay-2">
                    <div class="service-icon amber"><i class="fa-solid fa-bullhorn"></i></div>
                    <h4 class="service-title">ગ્રામ પંચાયત નોટિસ</h4>
                    <p class="service-desc">Receive important announcements and Gram Sabha schedules directly to your WhatsApp.</p>
                    <span class="service-link amber">Subscribe <i class="fa-solid fa-arrow-right"></i></span>
                </div>

                <div class="service-card locked reveal reveal-delay-3">
                    <div class="service-icon gray"><i class="fa-solid fa-file-signature"></i></div>
                    <h4 class="service-title">પ્રમાણપત્રો</h4>
                    <p class="service-desc">Apply for and download official certificates. Coming soon in the next platform phase.</p>
                    <span class="locked-badge"><i class="fa-solid fa-lock"></i> Coming Soon</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── About / CTA ── -->
    <section id="about" class="section-pad">
        <div class="container">
            <div class="about-inner">

                <!-- Left: Info -->
                <div class="reveal">
                    <span class="tag">Empowering Digital India</span>
                    <h3 class="section-headline" style="text-align:left;margin:20px 0 16px;">Smart Village,<br>Smart Governance</h3>
                    <p style="color:var(--text-muted);font-size:1rem;line-height:1.75;margin-bottom:8px;">
                        This Gram Panchayat is a pioneer in digital village governance. Built on the secure <strong style="color:var(--text-primary);">CISETU Platform</strong>, our system ensures transparent, accessible, and efficient citizen services.
                    </p>

                    <div class="about-badge-list">
                        <div class="about-badge-item">
                            <div class="about-badge-icon" style="background:rgba(37,211,102,0.1);color:var(--wa);">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="about-badge-text">
                                <h5>Bank-Grade Security</h5>
                                <p>All transactions encrypted end-to-end via certified payment gateways</p>
                            </div>
                        </div>
                        <div class="about-badge-item">
                            <div class="about-badge-icon" style="background:rgba(255,107,44,0.1);color:var(--saffron);">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <div class="about-badge-text">
                                <h5>Official Digital Receipts</h5>
                                <p>Government-signed PDF receipts accepted for all official purposes</p>
                            </div>
                        </div>
                        <div class="about-badge-item">
                            <div class="about-badge-icon" style="background:rgba(240,165,0,0.1);color:var(--gold);">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div class="about-badge-text">
                                <h5>WhatsApp Native</h5>
                                <p>No app to download — works on any phone with WhatsApp installed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: CTA Block -->
                <div class="reveal reveal-delay-2">
                    <div class="about-cta-block">
                        <div class="wa-big-icon">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <h3>Ready to Pay Your Tax?</h3>
                        <p>Join thousands of villagers who have switched to the smart, paperless way of paying property tax.</p>
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-wa" style="font-size:1.05rem;padding:16px 36px;justify-content:center;width:100%;max-width:280px;margin:0 auto;">
                            <i class="fa-brands fa-whatsapp" style="font-size:22px;"></i>
                            Start on WhatsApp
                        </a>
                        <p style="font-size:0.8rem;color:var(--text-faint);margin-top:16px;margin-bottom:0;">
                            Powered by <strong style="color:var(--saffron);">CISETU / Clonza Infotech</strong>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── Footer ── -->
    <footer>
        <div class="container">
            <div class="footer-grid">

                <!-- Brand -->
                <div class="reveal">
                    <img src="{{ $village->logo ? Storage::url($village->logo) : asset('assets/images/logo.jpeg') }}" alt="Logo" style="width:52px;height:52px;border-radius:50%;border:2px solid rgba(255,107,44,0.3);object-fit:cover;" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
                    <div class="footer-brand-name">ગ્રામ પંચાયત {{ $village->name_local }}</div>
                    <div class="footer-brand-sub">Digital Citizen Portal</div>
                    <p class="footer-brand-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
                    <div class="social-links">
                        <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="social-btn" style="background:rgba(37,211,102,0.1);color:var(--wa);border-color:rgba(37,211,102,0.2);">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="reveal reveal-delay-1">
                    <div class="footer-heading">Quick Links</div>
                    <ul class="footer-links">
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#how">How to Pay Tax</a></li>
                        <li><a href="#services">Other Services</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="reveal reveal-delay-2">
                    <div class="footer-heading">સંપર્ક માહિતી</div>
                    <div class="contact-list">
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <span style="font-family:'Noto Sans Gujarati',sans-serif;">ગ્રામ પંચાયત કચેરી,<br>ગુજરાત, ભારત</span>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                            <a href="mailto:info@gpt.cisetu.com" style="color:var(--text-muted);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#FF6B2C'" onmouseout="this.style.color=''">info@gpt.cisetu.com</a>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                            <a href="tel:+{{ $village->whatsapp_number }}" style="color:var(--text-muted);text-decoration:none;font-weight:600;transition:color 0.2s;" onmouseover="this.style.color='#FF6B2C'" onmouseout="this.style.color=''">+91 9316 781 820</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 CISETU. All Rights Reserved. | Gram Panchayat Digital Portal</p>
                <div class="powered-badge">
                    Powered by <span>CISETU · Clonza Infotech</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- ── Mobile Bottom Nav ── -->
    <div class="mobile-bottom-nav">
        <a href="#hero" class="mob-nav-item active">
            <i class="fa-solid fa-house"></i>
            Home
        </a>
        <a href="#how" class="mob-nav-item">
            <i class="fa-solid fa-circle-info"></i>
            Guide
        </a>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="mob-nav-wa">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <a href="#services" class="mob-nav-item">
            <i class="fa-solid fa-layer-group"></i>
            Services
        </a>
        <a href="#about" class="mob-nav-item">
            <i class="fa-solid fa-building-columns"></i>
            About
        </a>
    </div>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        }, { passive: true });

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => observer.observe(el));

        // Mobile nav active state
        const sections = document.querySelectorAll('section[id]');
        const mobItems = document.querySelectorAll('.mob-nav-item');
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const id = e.target.getAttribute('id');
                    mobItems.forEach(item => {
                        item.classList.toggle('active', item.getAttribute('href') === `#${id}`);
                    });
                }
            });
        }, { threshold: 0.4 });
        sections.forEach(s => sectionObserver.observe(s));

        // Smooth click scroll on mobile nav
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>