<!DOCTYPE html>
<html lang="gu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $village->name_local }} | Digital Property Tax Portal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600&family=Noto+Sans+Gujarati:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --leaf:#1a6b3a;--leaf2:#2d8c50;--leaf3:#4aab6d;--leaf4:#a8d5b5;--leaf5:#e4f4ea;
  --earth:#2c1f0e;--earth2:#5a3e28;--earth3:#9c7a5a;
  --sun:#f0c040;--sun2:#e8a800;
  --sky:#d6eef5;--sky2:#a8d8e8;
  --cream:#faf8f4;--cream2:#f2ede4;--cream3:#e8e0d0;
  --white:#ffffff;
  --wa:#25D366;--wa2:#1aab52;
  --display:'Playfair Display',Georgia,serif;
  --body:'Outfit',sans-serif;
  --gu:'Noto Sans Gujarati','Outfit',sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--cream);color:var(--earth);-webkit-font-smoothing:antialiased;overflow-x:hidden}

/* ===== FLOATING SHAPES BG ===== */
.bg-shapes{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
.bg-shapes svg{position:absolute;width:100%;height:100%}

/* ===== NAV ===== */
nav{position:sticky;top:0;z-index:100;background:rgba(250,248,244,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(26,107,58,0.1)}
.nav-i{max-width:1200px;margin:auto;height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 32px}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.brand-icon{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--leaf2),var(--leaf3));display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;box-shadow:0 4px 12px rgba(26,107,58,0.25)}
.brand-icon img{width:100%;height:100%;object-fit:cover}
.brand-icon i{font-size:20px;color:#fff}
.brand-name{font-size:14px;font-weight:600;color:var(--earth);line-height:1.2;font-family:var(--gu)}
.brand-sub{font-size:10.5px;color:var(--earth3);font-weight:300}
.nav-links{display:flex;align-items:center;gap:8px}
.nav-links a{font-size:13.5px;font-weight:400;color:var(--earth2);text-decoration:none;padding:6px 14px;border-radius:20px;transition:all .2s}
.nav-links a:hover{background:var(--leaf5);color:var(--leaf)}
.nav-cta{display:flex;align-items:center;gap:7px;padding:9px 20px;background:var(--leaf);color:#fff!important;border-radius:24px;font-size:13px;font-weight:500;text-decoration:none;transition:all .2s;box-shadow:0 4px 14px rgba(26,107,58,0.3)}
.nav-cta:hover{background:var(--leaf2);transform:translateY(-1px);box-shadow:0 6px 18px rgba(26,107,58,0.4)}
.nav-cta i{font-size:17px;color:#25D366}

/* ===== HERO ===== */
.hero{min-height:92vh;display:flex;align-items:center;padding:80px 32px;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 80% at 50% 10%, rgba(74,171,109,0.08) 0%, transparent 70%);pointer-events:none}
.hero-blob{position:absolute;right:-120px;top:50%;transform:translateY(-50%);width:620px;height:620px;border-radius:60% 40% 30% 70%/60% 30% 70% 40%;background:linear-gradient(135deg,rgba(74,171,109,0.12),rgba(168,213,181,0.18));animation:morph 12s ease-in-out infinite;pointer-events:none}
.hero-blob2{position:absolute;left:-80px;bottom:-60px;width:340px;height:340px;border-radius:40% 60% 70% 30%/40% 50% 60% 50%;background:linear-gradient(135deg,rgba(240,192,64,0.1),rgba(168,213,181,0.1));animation:morph 10s ease-in-out infinite reverse;pointer-events:none}
@keyframes morph{0%,100%{border-radius:60% 40% 30% 70%/60% 30% 70% 40%}50%{border-radius:30% 60% 70% 40%/50% 60% 30% 60%}}
.hero-i{max-width:1200px;margin:auto;display:grid;grid-template-columns:1fr 420px;gap:80px;align-items:center;position:relative;z-index:1;width:100%}
.hero-pill{display:inline-flex;align-items:center;gap:8px;background:var(--leaf5);border:1px solid rgba(26,107,58,0.15);border-radius:24px;padding:6px 16px 6px 8px;margin-bottom:28px}
.pill-dot{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--leaf2),var(--leaf3));display:flex;align-items:center;justify-content:center}
.pill-dot i{font-size:13px;color:#fff}
.hero-pill span{font-size:12px;font-weight:500;color:var(--leaf)}
.hero h1{font-family:var(--display);font-size:clamp(2.8rem,4.5vw,4.2rem);font-weight:400;line-height:1.05;letter-spacing:-.02em;color:var(--earth);margin-bottom:20px}
.hero h1 .accent{color:var(--leaf);font-style:italic}
.hero h1 .wa-hl{color:var(--wa);font-style:italic}
.hero-sub{font-size:15px;color:var(--earth3);line-height:1.8;max-width:440px;margin-bottom:36px;font-weight:300}
.hero-btns{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:36px}
.btn-primary{display:inline-flex;align-items:center;gap:9px;padding:14px 28px;background:var(--leaf);color:#fff;border-radius:32px;font-size:14px;font-weight:500;text-decoration:none;transition:all .25s;box-shadow:0 6px 20px rgba(26,107,58,0.35)}
.btn-primary:hover{background:var(--leaf2);transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,107,58,0.4)}
.btn-primary i{font-size:20px;color:#25D366}
.btn-ghost{display:inline-flex;align-items:center;gap:8px;padding:14px 24px;border:1.5px solid rgba(26,107,58,0.2);color:var(--leaf);border-radius:32px;font-size:14px;font-weight:500;text-decoration:none;transition:all .2s;background:var(--leaf5)}
.btn-ghost:hover{border-color:var(--leaf);background:rgba(26,107,58,0.08)}
.trust-row{display:flex;gap:20px;flex-wrap:wrap}
.trust-item{display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--earth3)}
.trust-item i{font-size:16px;color:var(--leaf3)}

/* ===== PHONE MOCKUP ===== */
.phone-scene{position:relative;display:flex;justify-content:center;align-items:center}
.phone-glow{position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(74,171,109,0.2) 0%,transparent 70%);pointer-events:none;animation:glow-pulse 3s ease-in-out infinite}
@keyframes glow-pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.1);opacity:.7}}
.phone-frame{width:256px;background:#111;border-radius:36px;border:2.5px solid #1e1e1e;overflow:hidden;box-shadow:0 32px 64px rgba(0,0,0,0.3),0 0 0 1px rgba(255,255,255,0.05);position:relative;z-index:1;animation:float 5s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.phone-notch{height:22px;background:#111;display:flex;align-items:center;justify-content:center}
.phone-notch-pill{width:80px;height:6px;background:#1e1e1e;border-radius:4px}
.wa-header{background:linear-gradient(135deg,#075E54,#128C7E);padding:12px 14px;display:flex;align-items:center;gap:10px}
.wa-back{color:rgba(255,255,255,.7);font-size:20px}
.wa-av{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
.wa-av img{width:100%;height:100%;object-fit:cover}
.wa-av i{font-size:16px;color:#fff}
.wa-info{flex:1}
.wa-name{font-size:13px;font-weight:600;color:#fff}
.wa-status{font-size:10px;color:rgba(255,255,255,.55)}
.wa-chat{background:#e5ddd5;padding:14px 10px;display:flex;flex-direction:column;gap:8px;min-height:280px;position:relative}
.wa-chat::before{content:'';position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30m-2 0a2 2 0 1 1 4 0 2 2 0 1 1-4 0' fill='rgba(0,0,0,0.04)'/%3E%3C/svg%3E");opacity:.5}
.msg-out{align-self:flex-end;background:#d9fdd3;padding:8px 11px;border-radius:12px 12px 2px 12px;font-size:11.5px;color:#1a2e1a;max-width:78%;position:relative;z-index:1;box-shadow:0 1px 2px rgba(0,0,0,.1)}
.msg-in{align-self:flex-start;background:#fff;padding:10px 12px;border-radius:12px 12px 12px 2px;font-size:11.5px;color:#1a1a1a;max-width:86%;position:relative;z-index:1;box-shadow:0 1px 2px rgba(0,0,0,.1);line-height:1.5}
.msg-in strong{display:block;font-size:12px;color:#056449;font-weight:600;margin-bottom:4px}
.pay-btn{display:block;margin-top:8px;padding:7px 12px;background:linear-gradient(135deg,#1a6b3a,#2d8c50);color:#fff;text-align:center;border-radius:7px;font-size:11px;font-weight:600;text-decoration:none}
.msg-receipt{align-self:flex-start;background:#fff;padding:10px 12px;border-radius:12px 12px 12px 2px;display:flex;align-items:center;gap:9px;box-shadow:0 1px 2px rgba(0,0,0,.1);position:relative;z-index:1}
.receipt-icon{width:28px;height:28px;border-radius:50%;background:#e4f4ea;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.receipt-icon i{font-size:14px;color:#1a6b3a}
.receipt-title{font-size:11.5px;font-weight:600;color:#1a1a1a}
.receipt-sub{font-size:10px;color:#888}

/* ===== STATS STRIP ===== */
.stats-strip{background:var(--leaf);padding:0}
.stats-i{max-width:1200px;margin:auto;display:grid;grid-template-columns:repeat(4,1fr)}
.stat-cell{padding:28px 24px;display:flex;flex-direction:column;gap:4px;border-right:1px solid rgba(255,255,255,0.12)}
.stat-cell:last-child{border-right:none}
.stat-num{font-family:var(--display);font-size:2.2rem;font-weight:400;color:#fff;line-height:1}
.stat-label{font-size:11px;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.1em;font-weight:400}

/* ===== SECTION COMMON ===== */
.section{padding:88px 32px}
.section-max{max-width:1200px;margin:auto}
.chip{display:inline-flex;align-items:center;gap:6px;background:var(--leaf5);border:1px solid rgba(26,107,58,0.15);border-radius:20px;padding:5px 14px;font-size:11px;font-weight:500;color:var(--leaf);text-transform:uppercase;letter-spacing:.1em;margin-bottom:18px}
.chip i{font-size:14px}
.section-h{font-family:var(--display);font-size:clamp(2rem,3.2vw,3rem);font-weight:400;letter-spacing:-.025em;line-height:1.1;color:var(--earth);margin-bottom:12px}
.section-sub{font-size:14.5px;color:var(--earth3);line-height:1.8;max-width:480px;font-weight:300}

/* ===== HOW IT WORKS ===== */
.how-bg{background:var(--cream2);border-top:1px solid rgba(0,0,0,.05);border-bottom:1px solid rgba(0,0,0,.05)}
.how-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2px;background:rgba(0,0,0,.06);border-radius:20px;overflow:hidden;margin-top:48px}
.how-card{background:var(--cream);padding:36px 28px;position:relative;transition:background .2s}
.how-card:hover{background:var(--white)}
.step-num{font-family:var(--display);font-size:4rem;font-weight:600;color:rgba(26,107,58,.07);line-height:1;margin-bottom:20px;font-style:italic}
.step-icon-wrap{width:46px;height:46px;border-radius:12px;background:var(--leaf5);border:1px solid rgba(26,107,58,.15);display:flex;align-items:center;justify-content:center;margin-bottom:18px}
.step-icon-wrap i{font-size:22px;color:var(--leaf)}
.how-card h4{font-size:15.5px;font-weight:600;color:var(--earth);margin-bottom:10px}
.how-card p{font-size:13px;color:var(--earth3);line-height:1.7;font-weight:300}
.how-cta{text-align:center;margin-top:36px}

/* ===== SERVICES ===== */
.svc-layout{display:grid;grid-template-columns:340px 1fr;gap:56px;align-items:start}
.svc-sticky{position:sticky;top:88px}
.svc-illustration{margin-top:28px;background:var(--leaf5);border:1px solid rgba(26,107,58,.12);border-radius:16px;padding:20px;display:flex;align-items:flex-start;gap:14px}
.svc-illustration i{font-size:22px;color:var(--leaf2);flex-shrink:0;margin-top:2px}
.svc-illustration h5{font-size:13px;font-weight:600;color:var(--earth);margin-bottom:5px}
.svc-illustration p{font-size:12px;color:var(--earth3);line-height:1.6;font-weight:300}
.svc-cards{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.svc-card{background:var(--white);border:1px solid rgba(0,0,0,.07);border-radius:16px;padding:24px;position:relative;overflow:hidden;transition:all .2s;cursor:pointer}
.svc-card:hover{border-color:rgba(26,107,58,.25);transform:translateY(-2px);box-shadow:0 8px 24px rgba(26,107,58,.1)}
.svc-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--leaf3),var(--leaf4));opacity:0;transition:opacity .2s}
.svc-card:hover::before{opacity:1}
.svc-card.coming{opacity:.55;cursor:default}
.svc-card.coming:hover{transform:none;box-shadow:none;border-color:rgba(0,0,0,.07)}
.svc-card.coming::before{display:none}
.svc-card-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px}
.svc-ico{width:44px;height:44px;border-radius:10px;background:var(--leaf5);display:flex;align-items:center;justify-content:center;border:1px solid rgba(26,107,58,.12)}
.svc-ico i{font-size:20px;color:var(--leaf)}
.badge-live{padding:4px 10px;border-radius:12px;font-size:11px;font-weight:500;background:rgba(37,211,102,.12);color:var(--wa2)}
.badge-soon{padding:4px 10px;border-radius:12px;font-size:11px;font-weight:500;background:var(--cream2);color:var(--earth3);border:1px solid var(--cream3)}
.svc-card h4{font-size:14px;font-weight:600;color:var(--earth);margin-bottom:6px;font-family:var(--gu)}
.svc-card p{font-size:12.5px;color:var(--earth3);line-height:1.6;font-weight:300}

/* ===== INITIATIVE / DARK SECTION ===== */
.dark-section{background:var(--earth);position:relative;overflow:hidden}
.dark-section::before{content:'';position:absolute;top:-100px;right:-100px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(74,171,109,0.08) 0%,transparent 70%);pointer-events:none}
.dark-inner{max-width:1200px;margin:auto;padding:88px 32px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;position:relative;z-index:1}
.dark-section .chip{background:rgba(74,171,109,0.1);border-color:rgba(74,171,109,0.2);color:var(--leaf4)}
.dark-section .section-h{color:#fff}
.dark-desc{font-size:14px;color:rgba(255,255,255,.45);line-height:1.8;margin-top:12px;font-weight:300}
.tech-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden}
.tech-row{padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;justify-content:space-between;transition:background .15s}
.tech-row:last-child{border-bottom:none}
.tech-row:hover{background:rgba(255,255,255,.03)}
.tech-key{font-size:13px;color:rgba(255,255,255,.35);font-weight:300}
.tech-val{font-size:13px;font-weight:500;color:rgba(255,255,255,.85)}

/* ===== CTA BANNER ===== */
.cta-banner{background:linear-gradient(135deg,var(--leaf) 0%,var(--leaf2) 50%,#1d5c32 100%);padding:80px 32px;position:relative;overflow:hidden;text-align:center}
.cta-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='40' cy='40' r='1.5' fill='rgba(255,255,255,0.06)'/%3E%3C/svg%3E");pointer-events:none}
.cta-banner-inner{max-width:600px;margin:auto;position:relative;z-index:1}
.cta-banner h2{font-family:var(--display);font-size:clamp(2rem,3.5vw,3rem);font-weight:400;color:#fff;line-height:1.1;margin-bottom:16px}
.cta-banner p{font-size:15px;color:rgba(255,255,255,.7);line-height:1.75;margin-bottom:32px;font-weight:300}
.btn-white{display:inline-flex;align-items:center;gap:9px;padding:14px 32px;background:#fff;color:var(--leaf);border-radius:32px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;box-shadow:0 6px 20px rgba(0,0,0,.2)}
.btn-white:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,0,0,.25)}
.btn-white i{font-size:20px;color:var(--wa)}

/* ===== FOOTER ===== */
footer{background:var(--earth2);color:rgba(255,255,255,.5)}
.footer-top{max-width:1200px;margin:auto;padding:56px 32px 40px;display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:40px;border-bottom:1px solid rgba(255,255,255,.07)}
.footer-brand-row{display:flex;align-items:center;gap:10px;margin-bottom:16px}
.footer-brand-icon{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--leaf2),var(--leaf3));display:flex;align-items:center;justify-content:center;overflow:hidden}
.footer-brand-icon img{width:100%;height:100%;object-fit:cover}
.footer-brand-icon i{font-size:18px;color:#fff}
.footer-brand-name{font-size:14px;font-weight:600;color:rgba(255,255,255,.85);font-family:var(--gu)}
.footer-brand-sub{font-size:10.5px;color:rgba(255,255,255,.3)}
.footer-desc{font-size:13px;color:rgba(255,255,255,.35);line-height:1.75;max-width:260px;font-weight:300;margin-bottom:16px}
.footer-social{display:flex;gap:8px}
.social-btn{width:32px;height:32px;border-radius:8px;border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:15px;color:rgba(255,255,255,.35);text-decoration:none;transition:all .2s}
.social-btn:hover{border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.8)}
.footer-col h5{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.5);margin-bottom:16px}
.footer-col ul{list-style:none}
.footer-col li{margin-bottom:10px}
.footer-col a{font-size:13px;color:rgba(255,255,255,.3);text-decoration:none;font-weight:300;transition:color .15s}
.footer-col a:hover{color:rgba(255,255,255,.8)}
.footer-contact-item{display:flex;align-items:flex-start;gap:9px;margin-bottom:11px;font-size:13px;font-weight:300}
.footer-contact-item i{font-size:16px;color:rgba(255,255,255,.3);flex-shrink:0;margin-top:1px}
.footer-contact-item a{color:rgba(255,255,255,.45);text-decoration:none;transition:color .15s}
.footer-contact-item a:hover{color:rgba(255,255,255,.8)}
.footer-contact-item .wa-link{color:rgba(37,211,102,.5)}
.footer-contact-item .wa-link:hover{color:var(--wa)}
.footer-bottom{max-width:1200px;margin:auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;font-size:12px;flex-wrap:wrap;gap:8px}
.footer-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border:1px solid rgba(255,255,255,.08);border-radius:20px;font-size:11px;color:rgba(255,255,255,.3)}
.footer-badge strong{color:rgba(255,255,255,.55);font-weight:500}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
  .hero-i,.dark-inner{grid-template-columns:1fr}
  .phone-scene{display:none}
  .svc-layout{grid-template-columns:1fr}
  .svc-sticky{position:static}
  .svc-cards{grid-template-columns:1fr}
  .stats-i{grid-template-columns:repeat(2,1fr)}
  .stat-cell{border-right:none;border-bottom:1px solid rgba(255,255,255,.12)}
  .footer-top{grid-template-columns:1fr 1fr;gap:28px}
}
@media(max-width:600px){
  .hero,.section{padding:60px 20px}
  .how-grid{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr}
  nav .nav-links a:not(.nav-cta){display:none}
  .dark-inner{padding:60px 20px}
}

/* ===== ANIMATIONS ===== */
.fade-up{opacity:0;transform:translateY(30px);animation:fade-up .7s ease forwards}
.fade-up:nth-child(1){animation-delay:.1s}
.fade-up:nth-child(2){animation-delay:.2s}
.fade-up:nth-child(3){animation-delay:.3s}
@keyframes fade-up{to{opacity:1;transform:none}}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-i">
    <a href="#" class="brand">
      <div class="brand-icon">
        @if($village->logo)
          <img src="{{ Storage::url($village->logo) }}" alt="Logo">
        @else
          <i class="ti ti-building-community" aria-hidden="true"></i>
        @endif
      </div>
      <div>
        <div class="brand-name">ગ્રામ પંચાયત — {{ $village->name_local }}</div>
        <div class="brand-sub">Smart Village Portal</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#hero">Home</a>
      <a href="#how">How to Pay</a>
      <a href="#services">Services</a>
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="nav-cta">
        <i class="ti ti-brand-whatsapp"></i> Pay on WhatsApp
      </a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-blob" aria-hidden="true"></div>
  <div class="hero-blob2" aria-hidden="true"></div>
  <div class="hero-i">
    <div>
      <div class="hero-pill">
        <div class="pill-dot"><i class="ti ti-leaf" aria-hidden="true"></i></div>
        <span>Official Digital Tax Portal — {{ $village->name_local }}</span>
      </div>
      <h1>
        Pay your tax<br>
        <span class="accent">instantly</span> via<br>
        <span class="wa-hl">WhatsApp</span>
      </h1>
      <p class="hero-sub">No queues. No offices. {{ $village->name_local }} Gram Panchayat's smart WhatsApp bot lets you view dues, pay in seconds, and receive an official digital receipt — all on your phone.</p>
      <div class="hero-btns">
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-primary">
          <i class="ti ti-brand-whatsapp"></i> Start on WhatsApp
        </a>
        <a href="#how" class="btn-ghost">
          <i class="ti ti-player-play" style="font-size:15px"></i> How it works
        </a>
      </div>
      <div class="trust-row">
        <span class="trust-item"><i class="ti ti-shield-check"></i> 100% Secure</span>
        <span class="trust-item"><i class="ti ti-bolt"></i> Instant Receipt</span>
        <span class="trust-item"><i class="ti ti-currency-rupee"></i> Zero Fee</span>
        <span class="trust-item"><i class="ti ti-lock"></i> Encrypted</span>
      </div>
    </div>

    <!-- PHONE -->
    <div class="phone-scene">
      <div class="phone-glow" aria-hidden="true"></div>
      <div class="phone-frame">
        <div class="phone-notch"><div class="phone-notch-pill"></div></div>
        <div class="wa-header">
          <i class="ti ti-arrow-left wa-back" aria-hidden="true"></i>
          <div class="wa-av">
            @if($village->logo)
              <img src="{{ Storage::url($village->logo) }}" alt="logo">
            @else
              <i class="ti ti-building-community" aria-hidden="true"></i>
            @endif
          </div>
          <div class="wa-info">
            <div class="wa-name">{{ $village->name_local }} GP</div>
            <div class="wa-status">Online · Official Portal</div>
          </div>
          <i class="ti ti-phone" style="color:rgba(255,255,255,.7);font-size:18px" aria-hidden="true"></i>
        </div>
        <div class="wa-chat">
          <div class="msg-out">Hi Panchayat 👋</div>
          <div class="msg-in">
            <strong>Welcome, Citizen!</strong>
            Your property tax due is <strong>₹ 1,250</strong><br>
            Last paid: March 2024
            <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="pay-btn">Pay Now via UPI →</a>
          </div>
          <div class="msg-out">Paid ✓</div>
          <div class="msg-receipt">
            <div class="receipt-icon"><i class="ti ti-receipt" aria-hidden="true"></i></div>
            <div>
              <div class="receipt-title">Payment Confirmed</div>
              <div class="receipt-sub">PDF receipt sent • ₹ 1,250</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS STRIP -->
<div class="stats-strip">
  <div class="stats-i">
    <div class="stat-cell"><div class="stat-num">2,500+</div><div class="stat-label">Homes Connected</div></div>
    <div class="stat-cell"><div class="stat-num">24/7</div><div class="stat-label">System Uptime</div></div>
    <div class="stat-cell"><div class="stat-num">0%</div><div class="stat-label">Convenience Fee</div></div>
    <div class="stat-cell"><div class="stat-num">100%</div><div class="stat-label">Digital Accuracy</div></div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div class="how-bg" id="how">
  <div class="section">
    <div class="section-max">
      <div class="chip"><i class="ti ti-route" aria-hidden="true"></i> Process</div>
      <h2 class="section-h">કરવેરો ભરવાની<br><em>સરળ રીત</em></h2>
      <p class="section-sub">Pay property tax from your phone in three simple steps. No app download required.</p>
      <div class="how-grid">
        <div class="how-card">
          <div class="step-num">01</div>
          <div class="step-icon-wrap"><i class="ti ti-brand-whatsapp" style="color:#25D366" aria-hidden="true"></i></div>
          <h4>Send a message</h4>
          <p>Send "Hi" to the official Panchayat WhatsApp number to wake up the automated assistant. No app install needed.</p>
        </div>
        <div class="how-card">
          <div class="step-num">02</div>
          <div class="step-icon-wrap"><i class="ti ti-file-invoice" aria-hidden="true"></i></div>
          <h4>Verify your details</h4>
          <p>Enter your house number or mobile number to view your outstanding property and water tax instantly.</p>
        </div>
        <div class="how-card">
          <div class="step-num">03</div>
          <div class="step-icon-wrap"><i class="ti ti-receipt" aria-hidden="true"></i></div>
          <h4>Pay &amp; get receipt</h4>
          <p>Pay via GPay, PhonePe, or any UPI app. Official signed PDF receipt delivered on WhatsApp within seconds.</p>
        </div>
      </div>
      <div class="how-cta">
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-primary" style="display:inline-flex">
          <i class="ti ti-brand-whatsapp"></i> Try the live bot
        </a>
      </div>
    </div>
  </div>
</div>

<!-- SERVICES -->
<div style="background:var(--cream)" id="services">
  <div class="section">
    <div class="section-max">
      <div class="svc-layout">
        <div class="svc-sticky">
          <div class="chip"><i class="ti ti-apps" aria-hidden="true"></i> Services</div>
          <h2 class="section-h">ઓનલાઇન<br>સેવાઓ</h2>
          <p class="section-sub" style="margin-top:8px">Citizen services delivered directly to your smartphone, no office visit required.</p>
          <div class="svc-illustration">
            <i class="ti ti-certificate" aria-hidden="true"></i>
            <div>
              <h5>ISO Certified Process</h5>
              <p>All transactions are end-to-end encrypted and instantly recorded in the Panchayat system for full transparency.</p>
            </div>
          </div>
        </div>
        <div class="svc-cards">
          <div class="svc-card">
            <div class="svc-card-head">
              <div class="svc-ico"><i class="ti ti-home" aria-hidden="true"></i></div>
              <span class="badge-live">Active</span>
            </div>
            <h4>મિલકત વેરો</h4>
            <p>Yearly property taxes for residential and commercial spaces, paid instantly via WhatsApp.</p>
          </div>
          <div class="svc-card">
            <div class="svc-card-head">
              <div class="svc-ico"><i class="ti ti-droplet" aria-hidden="true"></i></div>
              <span class="badge-live">Active</span>
            </div>
            <h4>પાણી વેરો</h4>
            <p>Clear your water connection bills without visiting the Panchayat office.</p>
          </div>
          <div class="svc-card">
            <div class="svc-card-head">
              <div class="svc-ico"><i class="ti ti-speakerphone" aria-hidden="true"></i></div>
              <span class="badge-live">Active</span>
            </div>
            <h4>ગ્રામ પંચાયત નોટિસ</h4>
            <p>Official announcements and Gram Sabha schedules sent directly to your WhatsApp.</p>
          </div>
          <div class="svc-card coming">
            <div class="svc-card-head">
              <div class="svc-ico"><i class="ti ti-file-certificate" aria-hidden="true"></i></div>
              <span class="badge-soon">Coming Soon</span>
            </div>
            <h4>પ્રમાણપત્રો</h4>
            <p>Official certificates and documents — launching in Phase 2.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- INITIATIVE -->
<div class="dark-section">
  <div class="dark-inner">
    <div>
      <div class="chip"><i class="ti ti-world" aria-hidden="true"></i> Platform</div>
      <h2 class="section-h">Empowering<br><em>Digital India</em></h2>
      <p class="dark-desc">{{ $village->name_local }} Gram Panchayat pioneers smart governance for Gujarat. This secure, scalable citizen-service framework runs on the CISETU Platform, built by Clonza Infotech.</p>
    </div>
    <div class="tech-card">
      <div class="tech-row"><span class="tech-key">Platform</span><span class="tech-val">CISETU</span></div>
      <div class="tech-row"><span class="tech-key">Technology Partner</span><span class="tech-val">Clonza Infotech</span></div>
      <div class="tech-row"><span class="tech-key">Payment Gateway</span><span class="tech-val">UPI / Razorpay</span></div>
      <div class="tech-row"><span class="tech-key">Uptime SLA</span><span class="tech-val">99.9%</span></div>
      <div class="tech-row"><span class="tech-key">Security</span><span class="tech-val">End-to-end Encrypted</span></div>
    </div>
  </div>
</div>

<!-- CTA BANNER -->
<div class="cta-banner">
  <div class="cta-banner-inner">
    <h2>Ready to pay your<br>property tax?</h2>
    <p>Join thousands of citizens who've switched to the easiest way to pay. It takes less than 2 minutes.</p>
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-white">
      <i class="ti ti-brand-whatsapp"></i> Open WhatsApp Now
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div>
      <div class="footer-brand-row">
        <div class="footer-brand-icon">
          @if($village->logo)
            <img src="{{ Storage::url($village->logo) }}" alt="Logo">
          @else
            <i class="ti ti-building-community" aria-hidden="true"></i>
          @endif
        </div>
        <div>
          <div class="footer-brand-name">ગ્રામ પંચાયત — {{ $village->name_local }}</div>
          <div class="footer-brand-sub">Digital Citizen Portal</div>
        </div>
      </div>
      <p class="footer-desc">Committed to transparent governance and making citizen services accessible through smart digital solutions.</p>
      <div class="footer-social">
        <a href="#" class="social-btn"><i class="ti ti-brand-facebook" aria-hidden="true"></i></a>
        <a href="#" class="social-btn"><i class="ti ti-brand-x" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h5>Navigation</h5>
      <ul>
        <li><a href="#hero">Home</a></li>
        <li><a href="#how">How to Pay</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#">Privacy Policy</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Services</h5>
      <ul>
        <li><a href="#">Property Tax</a></li>
        <li><a href="#">Water Tax</a></li>
        <li><a href="#">Notices</a></li>
        <li><a href="#">Certificates</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Contact</h5>
      <div class="footer-contact-item"><i class="ti ti-map-pin" aria-hidden="true"></i><span>ગ્રામ પંચાયત કચેરી, {{ $village->name_local }}, Gujarat</span></div>
      <div class="footer-contact-item"><i class="ti ti-mail" aria-hidden="true"></i><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
      <div class="footer-contact-item"><i class="ti ti-brand-whatsapp" aria-hidden="true"></i><a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" class="wa-link">+91 {{ $village->whatsapp_number }}</a></div>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; {{ date('Y') }} CISETU. All Rights Reserved.</span>
    <div class="footer-badge">Powered by <strong>CISETU / Clonza Infotech</strong></div>
  </div>
</footer>

</body>
</html>