<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>{{ $village->name_local }} | Digital Property Tax Portal</title>
<meta name="description" content="Official Digital Portal. Pay your property tax instantly via WhatsApp.">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&family=Figtree:wght@300;400;500;600;700;800&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

:root{
  --paper:#FBF7F0;
  --paper-2:#F5EFE4;
  --paper-3:#EDE5D8;
  --navy:#0A1628;
  --navy-2:#162240;
  --saffron:#F4572A;
  --saffron-2:#FF7A52;
  --turmeric:#E8A500;
  --turmeric-2:#F5BD35;
  --wa:#25D366;
  --wa-dark:#1aab55;
  --ink:#1C1C1E;
  --ink-2:#3D3D3D;
  --muted:#7A7068;
  --faint:#B0A898;
  --border:#D9D0C4;
  --border-2:#E8E1D8;
}

html{font-size:16px;scroll-behavior:smooth;}
body{
  background:var(--paper);
  color:var(--ink);
  font-family:'Figtree',sans-serif;
  -webkit-tap-highlight-color:transparent;
  overflow-x:hidden;
  line-height:1.6;
}
img{max-width:100%;display:block;}
a{text-decoration:none;color:inherit;}

/* ─── NOISE TEXTURE OVERLAY ─── */
body::before{
  content:'';
  position:fixed;
  inset:0;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
  opacity:0.025;
  pointer-events:none;
  z-index:9999;
}

/* ─── TYPOGRAPHY ─── */
.f-display{font-family:'Playfair Display',serif;}
.f-ui{font-family:'Figtree',sans-serif;}
.f-gu{font-family:'Noto Sans Gujarati','Playfair Display',serif;}

/* ─── MARQUEE BAND ─── */
.marquee-band{
  background:var(--navy);
  overflow:hidden;
  padding:12px 0;
  border-top:3px solid var(--saffron);
  border-bottom:3px solid var(--saffron);
}
.marquee-track{
  display:flex;
  gap:0;
  animation:marquee 28s linear infinite;
  width:max-content;
}
.marquee-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:0 40px;
  font-family:'Figtree',sans-serif;
  font-size:0.8rem;
  font-weight:700;
  letter-spacing:0.12em;
  text-transform:uppercase;
  color:rgba(255,255,255,0.7);
  white-space:nowrap;
}
.marquee-item span{color:var(--saffron);}
.marquee-dot{width:5px;height:5px;border-radius:50%;background:var(--saffron);flex-shrink:0;}
@keyframes marquee{0%{transform:translateX(0);}100%{transform:translateX(-50%);}}

/* ─── NAVBAR ─── */
#navbar{
  position:fixed;
  top:0;left:0;right:0;
  z-index:1000;
  transition:all 0.35s ease;
}
.nav-inner{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:18px 40px;
  background:rgba(251,247,240,0.92);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  border-bottom:2px solid transparent;
  transition:all 0.35s ease;
}
#navbar.scrolled .nav-inner{
  padding:12px 40px;
  border-bottom-color:var(--border);
  box-shadow:0 4px 24px rgba(0,0,0,0.06);
}
.nav-logo{display:flex;align-items:center;gap:14px;}
.nav-logo-img{
  width:46px;height:46px;
  border-radius:50%;
  object-fit:cover;
  border:3px solid var(--saffron);
  flex-shrink:0;
}
.nav-logo h1{
  font-family:'Noto Sans Gujarati',serif;
  font-size:1rem;
  font-weight:700;
  color:var(--navy);
  line-height:1.2;
}
.nav-logo p{
  font-size:0.6rem;
  font-weight:800;
  letter-spacing:0.15em;
  text-transform:uppercase;
  color:var(--saffron);
}
.nav-links{display:none;align-items:center;gap:4px;}
@media(min-width:768px){.nav-links{display:flex;}}
.nav-links a{
  color:var(--ink-2);
  font-size:0.875rem;
  font-weight:600;
  padding:9px 18px;
  border-radius:8px;
  transition:all 0.2s;
}
.nav-links a:hover{background:var(--paper-2);color:var(--navy);}
.btn-nav-pay{
  display:inline-flex;
  align-items:center;
  gap:8px;
  background:var(--saffron);
  color:#fff!important;
  font-size:0.875rem;
  font-weight:700;
  padding:10px 22px;
  border-radius:10px;
  transition:all 0.25s!important;
  background:var(--saffron)!important;
}
.btn-nav-pay:hover{background:var(--saffron-2)!important;transform:translateY(-1px);box-shadow:0 6px 20px rgba(244,87,42,0.3);}

/* ─── HERO ─── */
#hero{
  min-height:100svh;
  display:grid;
  grid-template-columns:1fr;
  padding-top:80px;
}
@media(min-width:900px){
  #hero{grid-template-columns:1fr 1fr;min-height:100svh;}
}

/* Left panel */
.hero-left{
  background:var(--paper);
  padding:80px 48px 80px;
  display:flex;
  flex-direction:column;
  justify-content:center;
  position:relative;
  overflow:hidden;
}
@media(max-width:900px){.hero-left{padding:60px 28px;}}

/* Giant watermark */
.hero-watermark{
  position:absolute;
  bottom:-40px;right:-20px;
  font-family:'Noto Sans Gujarati',serif;
  font-size:clamp(8rem,18vw,16rem);
  font-weight:800;
  color:var(--saffron);
  opacity:0.06;
  line-height:1;
  pointer-events:none;
  user-select:none;
  letter-spacing:-0.05em;
}

.hero-eyebrow{
  display:inline-flex;
  align-items:center;
  gap:10px;
  background:var(--navy);
  color:#fff;
  font-size:0.7rem;
  font-weight:800;
  letter-spacing:0.15em;
  text-transform:uppercase;
  padding:8px 20px;
  border-radius:6px;
  margin-bottom:32px;
  width:fit-content;
}
.eyebrow-dot{
  width:7px;height:7px;
  border-radius:50%;
  background:var(--wa);
  animation:blink 1.5s ease-in-out infinite;
}
@keyframes blink{0%,100%{opacity:1;}50%{opacity:0.3;}}

.hero-headline{
  font-family:'Noto Sans Gujarati',serif;
  font-size:clamp(2.6rem,6vw,5rem);
  font-weight:800;
  line-height:1.1;
  letter-spacing:-0.02em;
  color:var(--navy);
  margin-bottom:8px;
  position:relative;
  z-index:1;
}
.hero-headline-en{
  font-family:'Playfair Display',serif;
  font-size:clamp(1.4rem,3vw,2.4rem);
  font-weight:700;
  font-style:italic;
  color:var(--saffron);
  margin-bottom:28px;
  display:block;
}
.hero-sub{
  font-size:1.0625rem;
  color:var(--muted);
  line-height:1.75;
  max-width:440px;
  margin-bottom:44px;
  position:relative;
  z-index:1;
}
.hero-ctas{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-bottom:52px;
  position:relative;
  z-index:1;
}
.btn-wa-hero{
  display:inline-flex;align-items:center;gap:10px;
  background:var(--wa);
  color:#fff;font-family:'Figtree',sans-serif;
  font-weight:700;font-size:1rem;
  padding:16px 32px;border-radius:12px;
  transition:all 0.3s;
  box-shadow:0 8px 28px rgba(37,211,102,0.28);
}
.btn-wa-hero:hover{background:var(--wa-dark);transform:translateY(-3px);box-shadow:0 14px 40px rgba(37,211,102,0.38);}
.btn-outline-hero{
  display:inline-flex;align-items:center;gap:10px;
  background:transparent;color:var(--navy);
  font-family:'Figtree',sans-serif;font-weight:700;font-size:1rem;
  padding:15px 28px;border-radius:12px;
  border:2px solid var(--border);
  transition:all 0.3s;
}
.btn-outline-hero:hover{border-color:var(--navy);background:var(--navy);color:#fff;}

.hero-badges{
  display:flex;flex-wrap:wrap;gap:12px;
  position:relative;z-index:1;
}
.hero-badge{
  display:flex;align-items:center;gap:8px;
  background:var(--paper-2);
  border:1.5px solid var(--border);
  border-radius:8px;
  padding:8px 16px;
  font-size:0.825rem;font-weight:600;
  color:var(--ink-2);
}
.hero-badge i{color:var(--saffron);}

/* Right panel */
.hero-right{
  background:var(--navy);
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
  padding:60px 40px;
  position:relative;
  overflow:hidden;
  min-height:500px;
}
/* Navy pattern */
.hero-right::before{
  content:'';
  position:absolute;inset:0;
  background-image:
    radial-gradient(circle at 20% 20%, rgba(244,87,42,0.12) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(37,211,102,0.08) 0%, transparent 50%);
}
.hero-right-decor{
  position:absolute;
  font-family:'Noto Sans Gujarati',serif;
  font-size:22rem;
  font-weight:800;
  color:rgba(255,255,255,0.03);
  line-height:1;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
  pointer-events:none;
  user-select:none;
  white-space:nowrap;
}

/* Receipt card design */
.receipt-card{
  position:relative;
  z-index:2;
  background:#fff;
  border-radius:24px;
  width:100%;
  max-width:340px;
  overflow:hidden;
  box-shadow:0 32px 80px rgba(0,0,0,0.4);
  animation:cardFloat 5s ease-in-out infinite;
}
@keyframes cardFloat{0%,100%{transform:translateY(0) rotate(-1deg);}50%{transform:translateY(-20px) rotate(1deg);}}
.receipt-top{
  background:var(--saffron);
  padding:24px 28px;
  position:relative;
  overflow:hidden;
}
.receipt-top::after{
  content:'';
  position:absolute;
  right:-30px;top:-30px;
  width:120px;height:120px;
  border-radius:50%;
  background:rgba(255,255,255,0.12);
}
.receipt-title{
  font-size:0.7rem;font-weight:800;
  letter-spacing:0.15em;text-transform:uppercase;
  color:rgba(255,255,255,0.8);margin-bottom:6px;
}
.receipt-village{
  font-family:'Noto Sans Gujarati',serif;
  font-size:1.2rem;font-weight:800;
  color:#fff;
  margin-bottom:2px;
}
.receipt-sub{font-size:0.75rem;color:rgba(255,255,255,0.75);font-weight:500;}
.receipt-body{padding:24px 28px;}
.receipt-row{
  display:flex;justify-content:space-between;align-items:center;
  padding:10px 0;
  border-bottom:1px dashed #EEE;
  font-size:0.875rem;
}
.receipt-row:last-of-type{border-bottom:none;}
.receipt-row label{color:var(--muted);font-weight:500;}
.receipt-row span{font-weight:700;color:var(--ink);}
.receipt-row span.amt{color:var(--saffron);font-size:1rem;}
.receipt-total{
  background:var(--paper);
  border-radius:12px;
  padding:16px 20px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-top:16px;
}
.receipt-total .t-label{font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);}
.receipt-total .t-amt{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--navy);}
.receipt-pay-btn{
  display:block;width:100%;text-align:center;
  background:var(--wa);
  color:#fff;font-weight:700;font-size:0.9rem;
  padding:14px;
  border-radius:10px;
  margin-top:16px;
  transition:all 0.3s;
}
.receipt-pay-btn:hover{background:var(--wa-dark);}
.receipt-pay-btn i{margin-right:8px;}

/* Floating badges on right panel */
.r-badge{
  position:absolute;
  z-index:3;
  background:#fff;
  border-radius:12px;
  padding:10px 16px;
  font-size:0.8rem;font-weight:700;
  box-shadow:0 8px 32px rgba(0,0,0,0.25);
  display:flex;align-items:center;gap:8px;
  white-space:nowrap;
}
.r-badge-1{top:12%;right:2%;animation:rb1 4s ease-in-out infinite;}
.r-badge-2{bottom:12%;left:2%;animation:rb2 5s ease-in-out 1s infinite;}
@keyframes rb1{0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);}}
@keyframes rb2{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}

/* ─── DIAGONAL DIVIDER ─── */
.diagonal-down{
  height:80px;background:inherit;
  clip-path:polygon(0 0,100% 0,100% 0%,0 100%);
  margin-bottom:-1px;
}
.diagonal-up{
  height:80px;background:inherit;
  clip-path:polygon(0 100%,100% 0%,100% 100%);
  margin-top:-1px;
}

/* ─── STATS BAR ─── */
#stats{background:var(--saffron);padding:0;}
.stats-inner{
  display:grid;
  grid-template-columns:repeat(2,1fr);
}
@media(min-width:640px){.stats-inner{grid-template-columns:repeat(4,1fr);}}
.stat-block{
  padding:44px 32px;
  text-align:center;
  border-right:1px solid rgba(255,255,255,0.2);
  border-bottom:1px solid rgba(255,255,255,0.2);
  transition:background 0.3s;
}
.stat-block:hover{background:rgba(255,255,255,0.08);}
@media(min-width:640px){
  .stat-block{border-bottom:none;}
  .stat-block:last-child{border-right:none;}
}
@media(max-width:639px){
  .stat-block:nth-child(2n){border-right:none;}
  .stat-block:nth-child(3),.stat-block:nth-child(4){border-bottom:none;}
}
.stat-num{
  font-family:'Playfair Display',serif;
  font-size:clamp(2.2rem,5vw,3.2rem);
  font-weight:900;
  color:#fff;
  line-height:1;
  margin-bottom:8px;
}
.stat-num sup{font-size:0.55em;vertical-align:super;}
.stat-lbl{
  font-size:0.7rem;font-weight:800;
  letter-spacing:0.12em;text-transform:uppercase;
  color:rgba(255,255,255,0.72);
}

/* ─── SECTION BASE ─── */
.section-pad{padding:100px 28px;}
.container{max-width:1240px;margin:0 auto;}
@media(max-width:640px){.section-pad{padding:72px 20px;}}

.section-pill{
  display:inline-flex;align-items:center;gap:8px;
  background:var(--navy);
  color:#fff;
  font-size:0.68rem;font-weight:800;
  letter-spacing:0.14em;text-transform:uppercase;
  padding:7px 18px;border-radius:6px;
  margin-bottom:20px;
}
.section-pill i{color:var(--saffron);}
.section-title{
  font-family:'Playfair Display',serif;
  font-size:clamp(2rem,4.5vw,3.5rem);
  font-weight:900;
  line-height:1.1;
  letter-spacing:-0.02em;
  color:var(--navy);
  margin-bottom:18px;
}
.section-title.gu{font-family:'Noto Sans Gujarati',serif;}
.section-body{
  font-size:1.0625rem;
  color:var(--muted);
  line-height:1.75;
  max-width:540px;
}

/* ─── HOW IT WORKS ─── */
#how{background:var(--paper-2);}
.steps-wrap{
  display:grid;
  grid-template-columns:1fr;
  gap:0;
  margin-top:64px;
  border:2px solid var(--border);
  border-radius:24px;
  overflow:hidden;
}
@media(min-width:768px){.steps-wrap{grid-template-columns:repeat(3,1fr);}}

.step-block{
  padding:48px 40px;
  border-right:2px solid var(--border);
  border-bottom:2px solid var(--border);
  transition:all 0.35s;
  position:relative;
  overflow:hidden;
  background:var(--paper);
}
.step-block:last-child{border-right:none;}
@media(max-width:767px){
  .step-block{border-right:none;}
  .step-block:last-child{border-bottom:none;}
}
.step-block:hover{background:var(--navy);transform:scale(1.01);z-index:2;}
.step-block:hover .step-n{color:var(--saffron);}
.step-block:hover .step-title-b{color:#fff;}
.step-block:hover .step-desc-b{color:rgba(255,255,255,0.65);}
.step-block:hover .step-icon-wrap{background:var(--saffron);color:#fff;border-color:var(--saffron);}
.step-block:hover .step-arrow{color:var(--saffron);}

.step-n{
  font-family:'Playfair Display',serif;
  font-size:4.5rem;font-weight:900;
  color:var(--paper-3);
  line-height:1;margin-bottom:16px;
  transition:color 0.35s;
}
.step-icon-wrap{
  width:52px;height:52px;border-radius:14px;
  border:2px solid var(--border);
  display:flex;align-items:center;justify-content:center;
  font-size:22px;color:var(--saffron);
  margin-bottom:20px;
  transition:all 0.35s;
}
.step-title-b{
  font-family:'Playfair Display',serif;
  font-size:1.4rem;font-weight:700;
  color:var(--navy);margin-bottom:12px;
  transition:color 0.35s;
}
.step-desc-b{
  font-size:0.9375rem;color:var(--muted);
  line-height:1.7;transition:color 0.35s;
}
.step-arrow{
  position:absolute;bottom:32px;right:32px;
  font-size:20px;color:var(--border);
  transition:all 0.35s;
}

/* ─── SERVICES ─── */
#services{background:var(--paper);}
.services-grid{
  display:grid;
  grid-template-columns:1fr;
  gap:20px;
  margin-top:64px;
}
@media(min-width:600px){.services-grid{grid-template-columns:repeat(2,1fr);}}
@media(min-width:1024px){.services-grid{grid-template-columns:repeat(4,1fr);}}

.svc-card{
  border:2px solid var(--border);
  border-radius:20px;
  padding:36px 28px;
  background:var(--paper);
  position:relative;
  overflow:hidden;
  transition:all 0.35s;
  cursor:pointer;
}
.svc-card::before{
  content:'';
  position:absolute;
  bottom:-40px;right:-40px;
  width:120px;height:120px;
  border-radius:50%;
  background:var(--saffron);
  opacity:0;
  transform:scale(0.5);
  transition:all 0.5s;
}
.svc-card:hover{border-color:var(--saffron);transform:translateY(-6px);}
.svc-card:hover::before{opacity:0.06;transform:scale(2);}
.svc-card.locked{opacity:0.65;cursor:default;}
.svc-card.locked:hover{transform:none;border-color:var(--border);}
.svc-card.locked:hover::before{opacity:0;}

.svc-icon{
  width:52px;height:52px;
  border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:22px;
  margin-bottom:22px;
  transition:all 0.3s;
}
.svc-icon.orange{background:rgba(244,87,42,0.1);color:var(--saffron);}
.svc-icon.blue{background:rgba(59,130,246,0.1);color:#3B82F6;}
.svc-icon.amber{background:rgba(232,165,0,0.1);color:var(--turmeric);}
.svc-icon.gray{background:rgba(120,113,108,0.1);color:#78716C;}
.svc-card:hover .svc-icon.orange{background:var(--saffron);color:#fff;}
.svc-card:hover .svc-icon.blue{background:#3B82F6;color:#fff;}
.svc-card:hover .svc-icon.amber{background:var(--turmeric);color:#fff;}
.svc-card.locked:hover .svc-icon{background:rgba(120,113,108,0.1);color:#78716C;}

.svc-title{
  font-family:'Noto Sans Gujarati',serif;
  font-size:1.1rem;font-weight:700;
  color:var(--navy);margin-bottom:10px;
}
.svc-desc{font-size:0.875rem;color:var(--muted);line-height:1.65;margin-bottom:22px;}
.svc-cta{
  display:flex;align-items:center;gap:6px;
  font-size:0.875rem;font-weight:700;
  transition:gap 0.2s;
}
.svc-cta.orange{color:var(--saffron);}
.svc-cta.blue{color:#3B82F6;}
.svc-cta.amber{color:var(--turmeric);}
.svc-card:hover .svc-cta{gap:12px;}
.svc-locked-tag{
  display:inline-flex;align-items:center;gap:6px;
  font-size:0.72rem;font-weight:700;
  letter-spacing:0.1em;text-transform:uppercase;
  color:var(--faint);
  background:var(--paper-2);
  border:1.5px solid var(--border);
  border-radius:100px;
  padding:5px 14px;
}

/* ─── ABOUT / PARTNER STRIP ─── */
#about{
  background:var(--navy);
  padding:80px 28px;
  position:relative;
  overflow:hidden;
}
#about::before{
  content:'';
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 30% 50%, rgba(244,87,42,0.12) 0%, transparent 60%),
             radial-gradient(ellipse at 70% 30%, rgba(37,211,102,0.07) 0%, transparent 60%);
  pointer-events:none;
}
.about-inner{
  display:grid;
  grid-template-columns:1fr;
  gap:60px;
  align-items:center;
  position:relative;z-index:1;
}
@media(min-width:900px){.about-inner{grid-template-columns:1fr 1fr;gap:80px;}}
.about-tag{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(244,87,42,0.15);
  color:var(--saffron-2);
  border:1px solid rgba(244,87,42,0.25);
  font-size:0.68rem;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;
  padding:7px 18px;border-radius:6px;margin-bottom:20px;
}
.about-headline{
  font-family:'Playfair Display',serif;
  font-size:clamp(2rem,4vw,3rem);
  font-weight:900;color:#fff;
  line-height:1.15;margin-bottom:20px;
}
.about-headline em{color:var(--saffron);font-style:normal;}
.about-para{color:rgba(255,255,255,0.6);font-size:1rem;line-height:1.8;margin-bottom:36px;}

.feature-list{display:flex;flex-direction:column;gap:16px;}
.feature-item{
  display:flex;align-items:flex-start;gap:16px;
  padding:18px 22px;
  background:rgba(255,255,255,0.04);
  border:1px solid rgba(255,255,255,0.08);
  border-radius:14px;
  transition:border-color 0.3s;
}
.feature-item:hover{border-color:rgba(244,87,42,0.3);}
.feature-ico{
  width:38px;height:38px;flex-shrink:0;
  border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:16px;
}
.feature-ico.a{background:rgba(244,87,42,0.15);color:var(--saffron);}
.feature-ico.b{background:rgba(37,211,102,0.15);color:var(--wa);}
.feature-ico.c{background:rgba(232,165,0,0.15);color:var(--turmeric-2);}
.feature-text h6{font-size:0.9rem;font-weight:700;color:#fff;margin-bottom:3px;}
.feature-text p{font-size:0.825rem;color:rgba(255,255,255,0.5);line-height:1.5;}

/* Right CTA block */
.cta-block{
  background:var(--paper);
  border-radius:28px;
  padding:52px 44px;
  text-align:center;
}
.cta-wa-icon{
  width:72px;height:72px;border-radius:24px;
  background:rgba(37,211,102,0.1);
  border:2px solid rgba(37,211,102,0.25);
  display:flex;align-items:center;justify-content:center;
  font-size:32px;color:var(--wa);
  margin:0 auto 24px;
}
.cta-block h3{
  font-family:'Playfair Display',serif;
  font-size:1.8rem;font-weight:900;
  color:var(--navy);margin-bottom:14px;
  line-height:1.25;
}
.cta-block p{font-size:0.9375rem;color:var(--muted);line-height:1.7;margin-bottom:32px;}
.btn-cta-wa{
  display:flex;align-items:center;justify-content:center;gap:12px;
  background:var(--wa);color:#fff;
  font-weight:700;font-size:1rem;
  padding:18px 36px;border-radius:14px;
  width:100%;max-width:300px;margin:0 auto 20px;
  transition:all 0.3s;
  box-shadow:0 8px 28px rgba(37,211,102,0.28);
}
.btn-cta-wa:hover{background:var(--wa-dark);transform:translateY(-3px);}
.cta-or{font-size:0.75rem;color:var(--faint);font-weight:600;margin-bottom:16px;}
.btn-cta-saffron{
  display:flex;align-items:center;justify-content:center;gap:10px;
  background:var(--saffron);color:#fff;
  font-weight:700;font-size:0.9rem;
  padding:14px 28px;border-radius:12px;
  width:100%;max-width:300px;margin:0 auto;
  transition:all 0.3s;
}
.btn-cta-saffron:hover{background:var(--saffron-2);transform:translateY(-2px);}
.cta-trust{
  display:flex;flex-wrap:wrap;justify-content:center;gap:16px;
  margin-top:28px;padding-top:24px;
  border-top:1.5px dashed var(--border);
}
.cta-trust-item{
  display:flex;align-items:center;gap:6px;
  font-size:0.78rem;font-weight:600;color:var(--muted);
}
.cta-trust-item i{color:var(--saffron);}

/* ─── FOOTER ─── */
footer{
  background:var(--navy-2);
  padding:80px 28px 120px;
  border-top:4px solid var(--saffron);
}
@media(min-width:768px){footer{padding:80px 28px 80px;}}
.footer-grid{
  display:grid;
  grid-template-columns:1fr;
  gap:48px;
  margin-bottom:56px;
}
@media(min-width:640px){.footer-grid{grid-template-columns:repeat(2,1fr);}}
@media(min-width:900px){.footer-grid{grid-template-columns:2fr 1fr 1fr;}}
.footer-logo-img{width:48px;height:48px;border-radius:50%;border:2px solid rgba(244,87,42,0.4);object-fit:cover;margin-bottom:16px;}
.footer-brand-name{font-family:'Noto Sans Gujarati',serif;font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:4px;}
.footer-brand-tag{font-size:0.6rem;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:var(--saffron);margin-bottom:16px;}
.footer-desc{font-size:0.9rem;color:rgba(255,255,255,0.45);line-height:1.8;max-width:300px;margin-bottom:24px;}
.footer-socials{display:flex;gap:10px;}
.f-social{
  width:38px;height:38px;border-radius:8px;
  background:rgba(255,255,255,0.05);
  border:1px solid rgba(255,255,255,0.08);
  display:flex;align-items:center;justify-content:center;
  color:rgba(255,255,255,0.5);font-size:14px;
  transition:all 0.25s;
}
.f-social:hover{background:var(--saffron);color:#fff;border-color:var(--saffron);}
.footer-col-title{font-size:0.7rem;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:22px;}
.footer-links-list{list-style:none;display:flex;flex-direction:column;gap:12px;}
.footer-links-list a{
  color:rgba(255,255,255,0.5);font-size:0.9rem;font-weight:500;
  transition:color 0.2s;
  display:flex;align-items:center;gap:10px;
}
.footer-links-list a:hover{color:var(--saffron);}
.footer-links-list a::before{content:'';width:4px;height:4px;border-radius:50%;background:var(--saffron);opacity:0.4;flex-shrink:0;}
.contact-li{
  display:flex;align-items:flex-start;gap:12px;
  font-size:0.875rem;color:rgba(255,255,255,0.5);
  margin-bottom:14px;
}
.contact-li i{color:var(--saffron);font-size:14px;margin-top:3px;flex-shrink:0;}
.footer-contact a{color:rgba(255,255,255,0.5);font-weight:500;transition:color 0.2s;}
.footer-contact a:hover{color:var(--saffron);}
.footer-bottom{
  padding-top:28px;border-top:1px solid rgba(255,255,255,0.08);
  display:flex;flex-direction:column;gap:12px;align-items:center;text-align:center;
}
@media(min-width:640px){.footer-bottom{flex-direction:row;justify-content:space-between;text-align:left;}}
.footer-bottom p{font-size:0.8rem;color:rgba(255,255,255,0.25);}
.powered{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(255,255,255,0.04);
  border:1px solid rgba(255,255,255,0.08);
  border-radius:100px;padding:6px 18px;
  font-size:0.75rem;font-weight:600;
  color:rgba(255,255,255,0.35);
}
.powered strong{color:var(--saffron);}

/* ─── MOBILE BOTTOM NAV ─── */
.mob-bar{
  position:fixed;bottom:12px;left:12px;right:12px;
  background:var(--navy);
  border:2px solid rgba(255,255,255,0.08);
  border-radius:24px;
  padding:10px 20px;
  display:flex;align-items:center;justify-content:space-between;
  z-index:1000;
  box-shadow:0 8px 40px rgba(0,0,0,0.35);
}
@media(min-width:768px){.mob-bar{display:none;}}
.mob-item{
  display:flex;flex-direction:column;align-items:center;gap:3px;
  color:rgba(255,255,255,0.35);
  font-size:0.58rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
  padding:4px 10px;transition:color 0.2s;
}
.mob-item i{font-size:18px;}
.mob-item:hover,.mob-item.active{color:var(--saffron);}
.mob-wa-btn{
  width:58px;height:58px;border-radius:50%;
  background:var(--wa);
  display:flex;align-items:center;justify-content:center;
  font-size:26px;color:#fff;
  margin-top:-26px;
  border:3px solid var(--navy);
  box-shadow:0 6px 24px rgba(37,211,102,0.4);
  transition:all 0.3s;flex-shrink:0;
}
.mob-wa-btn:hover{background:var(--wa-dark);transform:scale(1.08);}

/* ─── SCROLL REVEALS ─── */
.reveal{opacity:0;transform:translateY(28px);transition:opacity 0.65s ease,transform 0.65s ease;}
.reveal.visible{opacity:1;transform:translateY(0);}
.d1{transition-delay:0.08s;}.d2{transition-delay:0.16s;}.d3{transition-delay:0.24s;}.d4{transition-delay:0.32s;}

@media(max-width:479px){
  .hero-headline{font-size:2.2rem;}
  .section-pad{padding:64px 18px;}
  .receipt-card{max-width:90vw;}
}
</style>
</head>
<body>

<!-- ─── MARQUEE TOP BAND ─── -->
<div class="marquee-band" style="margin-top:0;position:relative;z-index:999;">
  <div class="marquee-track">
    <!-- duplicated for seamless loop -->
    <div class="marquee-item"><div class="marquee-dot"></div> ગ્રામ પંચાયત ગ્રામ પંચાયત {{ $village->name_local }} DigitalPay <span>Live</span></div>
    <div class="marquee-item"><div class="marquee-dot"></div> 24/7 Online Tax Payment</div>
    <div class="marquee-item"><div class="marquee-dot"></div> Instant Digital Receipt <span>✓</span></div>
    <div class="marquee-item"><div class="marquee-dot"></div> Zero Convenience Fee</div>
    <div class="marquee-item"><div class="marquee-dot"></div> Pay via GPay · PhonePe · UPI</div>
    <div class="marquee-item"><div class="marquee-dot"></div> WhatsApp Based Payment</div>
    <div class="marquee-item"><div class="marquee-dot"></div> ગ્રામ પંચાયત ગ્રામ પંચાયત {{ $village->name_local }} DigitalPay <span>Live</span></div>
    <div class="marquee-item"><div class="marquee-dot"></div> 24/7 Online Tax Payment</div>
    <div class="marquee-item"><div class="marquee-dot"></div> Instant Digital Receipt <span>✓</span></div>
    <div class="marquee-item"><div class="marquee-dot"></div> Zero Convenience Fee</div>
    <div class="marquee-item"><div class="marquee-dot"></div> Pay via GPay · PhonePe · UPI</div>
    <div class="marquee-item"><div class="marquee-dot"></div> WhatsApp Based Payment</div>
  </div>
</div>

<!-- ─── NAVBAR ─── -->
<nav id="navbar" style="top:40px;">
  <div class="nav-inner">
    <div class="nav-logo">
      <img src="{{ $village->logo ? Storage::url($village->logo) : asset('assets/images/logo.jpeg') }}" alt="GP Logo" class="nav-logo-img" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
      <div>
        <h1>ગ્રામ પંચાયત {{ $village->name_local }}</h1>
        <p>Smart Village Portal</p>
      </div>
    </div>
    <div class="nav-links">
      <a href="#hero">Home</a>
      <a href="#how">How to Pay</a>
      <a href="#services">Services</a>
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-nav-pay">
        <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
      </a>
    </div>
  </div>
</nav>

<!-- ─── HERO ─── -->
<section id="hero">
  <!-- LEFT: Content -->
  <div class="hero-left">
    <div class="hero-watermark">{{ substr($village->name_en, 0, 2) }}</div>

    <div class="hero-eyebrow reveal">
      <div class="eyebrow-dot"></div>
      Official · Secure · Digital
    </div>

    <h2 class="hero-headline reveal d1">
      {{ $village->name_local }} પ્રોપર્ટી<br>
      વેરો ભરો
      <span class="hero-headline-en">From WhatsApp.</span>
    </h2>

    <p class="hero-sub reveal d2">
      No queues. No paperwork. The official {{ $village->name_en }} Gram Panchayat WhatsApp bot helps you view and pay your property tax instantly — with an official signed PDF receipt delivered in seconds.
    </p>

    <div class="hero-ctas reveal d3">
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-wa-hero">
        <i class="fa-brands fa-whatsapp" style="font-size:22px;"></i>
        Start on WhatsApp
      </a>
      <a href="#how" class="btn-outline-hero">
        <i class="fa-solid fa-circle-play"></i>
        How it Works
      </a>
    </div>

    <div class="hero-badges reveal d4">
      <div class="hero-badge"><i class="fa-solid fa-shield-check"></i> 100% Secure</div>
      <div class="hero-badge"><i class="fa-solid fa-bolt"></i> Instant Receipt</div>
      <div class="hero-badge"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Fee</div>
    </div>
  </div>

  <!-- RIGHT: Receipt Card -->
  <div class="hero-right">
    <div class="hero-right-decor">₹</div>

    <!-- Floating badge 1 -->
    <div class="r-badge r-badge-1">
      <i class="fa-solid fa-circle-check" style="color:var(--wa);"></i>
      Payment Verified
    </div>

    <!-- Receipt card -->
    <div class="receipt-card reveal">
      <div class="receipt-top">
        <div class="receipt-title">Tax Invoice · 2024–25</div>
        <div class="receipt-village">{{ $village->name_local }}</div>
        <div class="receipt-sub">Official Digital Receipt</div>
      </div>
      <div class="receipt-body">
        <div class="receipt-row">
          <label>House No.</label>
          <span>A–42</span>
        </div>
        <div class="receipt-row">
          <label>Owner</label>
          <span>Rambhai Patel</span>
        </div>
        <div class="receipt-row">
          <label>Property Tax</label>
          <span class="amt">₹ 950</span>
        </div>
        <div class="receipt-row">
          <label>Water Tax</label>
          <span class="amt">₹ 300</span>
        </div>
        <div class="receipt-total">
          <div>
            <div class="t-label">Total Due</div>
          </div>
          <div class="t-amt">₹1,250</div>
        </div>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="receipt-pay-btn">
          <i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp Now
        </a>
      </div>
    </div>

    <!-- Floating badge 2 -->
    <div class="r-badge r-badge-2">
      <i class="fa-solid fa-file-pdf" style="color:var(--saffron);"></i>
      PDF Receipt Sent!
    </div>
  </div>
</section>

<!-- ─── STATS BAR ─── -->
<section id="stats">
  <div class="stats-inner container" style="max-width:100%;">
    <div class="stat-block">
      <div class="stat-num">2,500<sup>+</sup></div>
      <div class="stat-lbl">Homes Connected</div>
    </div>
    <div class="stat-block">
      <div class="stat-num">24<sup>/7</sup></div>
      <div class="stat-lbl">System Uptime</div>
    </div>
    <div class="stat-block">
      <div class="stat-num">0%</div>
      <div class="stat-lbl">Convenience Fee</div>
    </div>
    <div class="stat-block">
      <div class="stat-num">100%</div>
      <div class="stat-lbl">Digital Accuracy</div>
    </div>
  </div>
</section>

<!-- ─── HOW IT WORKS ─── -->
<section id="how" class="section-pad">
  <div class="container">
    <div class="reveal">
      <div class="section-pill"><i class="fa-solid fa-circle-dot"></i> Simple Process</div>
      <h3 class="section-title gu">કરવેરો ભરવાની<br>સૌથી સરળ રીત</h3>
      <p class="section-body">Three simple steps — no app installation required. Works on any WhatsApp-enabled phone.</p>
    </div>

    <div class="steps-wrap reveal d1">
      <div class="step-block">
        <div class="step-n">01</div>
        <div class="step-icon-wrap"><i class="fa-brands fa-whatsapp"></i></div>
        <h5 class="step-title-b">Send a Message</h5>
        <p class="step-desc-b">Simply send "Hi" to our official Panchayat WhatsApp number. The intelligent system responds within seconds.</p>
        <i class="fa-solid fa-arrow-right step-arrow"></i>
      </div>
      <div class="step-block">
        <div class="step-n">02</div>
        <div class="step-icon-wrap"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <h5 class="step-title-b">Verify Details</h5>
        <p class="step-desc-b">Enter your house number or registered phone number. Your outstanding property and water tax appear instantly.</p>
        <i class="fa-solid fa-arrow-right step-arrow"></i>
      </div>
      <div class="step-block" style="border-right:none;">
        <div class="step-n">03</div>
        <div class="step-icon-wrap"><i class="fa-solid fa-receipt"></i></div>
        <h5 class="step-title-b">Pay &amp; Get Receipt</h5>
        <p class="step-desc-b">Pay securely via GPay, PhonePe, or any UPI app. Official signed PDF receipt delivered to WhatsApp instantly.</p>
        <i class="fa-solid fa-arrow-right step-arrow"></i>
      </div>
    </div>

    <div style="text-align:center;margin-top:52px;" class="reveal d2">
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-wa-hero" style="display:inline-flex;max-width:320px;">
        <i class="fa-brands fa-whatsapp" style="font-size:20px;"></i>
        Try the Live Bot Now
        <i class="fa-solid fa-arrow-right" style="margin-left:auto;"></i>
      </a>
    </div>
  </div>
</section>

<!-- ─── SERVICES ─── -->
<section id="services" class="section-pad" style="background:var(--paper-2);">
  <div class="container">
    <div class="reveal">
      <div class="section-pill"><i class="fa-solid fa-layer-group"></i> Digital Governance</div>
      <h3 class="section-title gu">ઓનલાઇન સેવાઓ</h3>
      <p class="section-body">All Panchayat services accessible directly from your smartphone — transparent, fast, and always available.</p>
    </div>

    <div class="services-grid">
      <div class="svc-card reveal d1">
        <div class="svc-icon orange"><i class="fa-solid fa-house-chimney"></i></div>
        <h4 class="svc-title">મિલકત વેરો</h4>
        <p class="svc-desc">View and pay yearly property taxes for residential and commercial spaces with a single tap.</p>
        <div class="svc-cta orange">Pay Now <i class="fa-solid fa-arrow-right"></i></div>
      </div>

      <div class="svc-card reveal d2">
        <div class="svc-icon blue"><i class="fa-solid fa-droplet"></i></div>
        <h4 class="svc-title">પાણી વેરો</h4>
        <p class="svc-desc">Clear your water connection bills seamlessly without a single visit to the office.</p>
        <div class="svc-cta blue">Pay Now <i class="fa-solid fa-arrow-right"></i></div>
      </div>

      <div class="svc-card reveal d3">
        <div class="svc-icon amber"><i class="fa-solid fa-bullhorn"></i></div>
        <h4 class="svc-title">ગ્રામ પંચાયત નોટિસ</h4>
        <p class="svc-desc">Receive important announcements and Gram Sabha schedules directly to your WhatsApp.</p>
        <div class="svc-cta amber">Subscribe <i class="fa-solid fa-arrow-right"></i></div>
      </div>

      <div class="svc-card locked reveal d4">
        <div class="svc-icon gray"><i class="fa-solid fa-file-signature"></i></div>
        <h4 class="svc-title">પ્રમાણપત્રો</h4>
        <p class="svc-desc">Apply for and download official certificates. Coming soon in the next platform phase.</p>
        <div class="svc-locked-tag"><i class="fa-solid fa-lock"></i> Coming Soon</div>
      </div>
    </div>
  </div>
</section>

<!-- ─── ABOUT / CTA ─── -->
<section id="about">
  <div class="container about-inner">

    <!-- Left: Info -->
    <div class="reveal">
      <div class="about-tag"><i class="fa-solid fa-microchip"></i> Empowering Digital India</div>
      <h3 class="about-headline">Smart Village,<br><em>Smarter</em> Governance.</h3>
      <p class="about-para">
        This Gram Panchayat is a pioneer in digital village governance. Built on the secure <strong style="color:#fff;">CISETU Platform</strong> by Clonza Infotech, our system ensures transparent, accessible, and efficient citizen services 24/7.
      </p>
      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-ico a"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="feature-text">
            <h6>Bank-Grade Security</h6>
            <p>All payments encrypted end-to-end via RBI-certified payment gateways</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-ico b"><i class="fa-solid fa-file-circle-check"></i></div>
          <div class="feature-text">
            <h6>Official Digital Receipts</h6>
            <p>Government-signed PDF receipts accepted for all official and legal purposes</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-ico c"><i class="fa-brands fa-whatsapp"></i></div>
          <div class="feature-text">
            <h6>WhatsApp Native — No App Needed</h6>
            <p>Works on any phone with WhatsApp. No installation, no signup required</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: CTA block -->
    <div class="reveal d2">
      <div class="cta-block">
        <div class="cta-wa-icon"><i class="fa-brands fa-whatsapp"></i></div>
        <h3>Pay Your Tax<br>in 3 Minutes</h3>
        <p>Join thousands of households that have switched to the smart, paperless way of paying Panchayat taxes.</p>

        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-cta-wa">
          <i class="fa-brands fa-whatsapp" style="font-size:22px;"></i>
          Start on WhatsApp
        </a>
        <div class="cta-or">or</div>
        <a href="tel:+{{ $village->whatsapp_number }}" class="btn-cta-saffron">
          <i class="fa-solid fa-phone"></i>
          Call Us: +91 {{ $village->whatsapp_number }}
        </a>

        <div class="cta-trust">
          <div class="cta-trust-item"><i class="fa-solid fa-shield-check"></i> 100% Secure</div>
          <div class="cta-trust-item"><i class="fa-solid fa-bolt"></i> Instant Receipt</div>
          <div class="cta-trust-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Fee</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ─── FOOTER ─── -->
<footer>
  <div class="container">
    <div class="footer-grid">

      <div class="reveal">
        <img src="{{ $village->logo ? Storage::url($village->logo) : asset('assets/images/logo.jpeg') }}" alt="Logo" class="footer-logo-img" onerror="this.src='{{ asset('assets/images/logo.jpeg') }}'">
        <div class="footer-brand-name">ગ્રામ પંચાયત  {{ strtoupper($village->name_en) }}</div>
        <div class="footer-brand-tag" style="color:var(--saffron); font-weight:900;">DIGITAL CITIZEN PORTAL</div>
        <p class="footer-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
        <div class="footer-socials">
          <a href="#" class="f-social"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="f-social"><i class="fa-brands fa-twitter"></i></a>
          <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="f-social" style="color:var(--wa);border-color:rgba(37,211,102,0.2);"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="reveal d1">
        <div class="footer-col-title">Quick Links</div>
        <ul class="footer-links-list">
          <li><a href="#hero">Home</a></li>
          <li><a href="#how">How to Pay Tax</a></li>
          <li><a href="#services">Other Services</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>

      <div class="reveal d2 footer-contact">
        <div class="footer-col-title">સંપર્ક માહિતી</div>
        <div class="contact-li">
          <i class="fa-solid fa-location-dot"></i>
          <span style="font-family:'Noto Sans Gujarati',serif;">ગ્રામ પંચાયત ગ્રામ પંચાયત {{ $village->name_local }} કચેરી, ગુજરાત, ભારત</span>
        </div>
        <div class="contact-li">
          <i class="fa-solid fa-envelope"></i>
          <a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a>
        </div>
        <div class="contact-li">
          <i class="fa-solid fa-phone"></i>
          <a href="tel:+{{ $village->whatsapp_number }}" style="font-weight:600;">+91 {{ $village->whatsapp_number }}</a>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} CISETU. All Rights Reserved. | {{ $village->name_en }} Gram Panchayat Digital Portal</p>
      <div class="powered">Powered by <strong>CISETU · Clonza Infotech</strong></div>
    </div>
  </div>
</footer>

<!-- ─── MOBILE BOTTOM BAR ─── -->
<div class="mob-bar">
  <a href="#hero" class="mob-item active"><i class="fa-solid fa-house"></i>Home</a>
  <a href="#how" class="mob-item"><i class="fa-solid fa-circle-info"></i>Guide</a>
  <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="mob-wa-btn">
    <i class="fa-brands fa-whatsapp"></i>
  </a>
  <a href="#services" class="mob-item"><i class="fa-solid fa-layer-group"></i>Services</a>
  <a href="#about" class="mob-item"><i class="fa-solid fa-building-columns"></i>About</a>
</div>

<script>
  // Adjust navbar top to account for marquee band
  const band = document.querySelector('.marquee-band');
  const navbar = document.getElementById('navbar');
  function setNavTop(){
    const bh = band ? band.offsetHeight : 40;
    navbar.style.top = bh + 'px';
  }
  setNavTop();
  window.addEventListener('resize', setNavTop);

  // Navbar scroll
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
  }, {passive:true});

  // Scroll reveal
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }
    });
  }, {threshold:0.1, rootMargin:'0px 0px -40px 0px'});
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

  // Mobile nav active
  const sections = document.querySelectorAll('section[id]');
  const mobItems = document.querySelectorAll('.mob-item');
  const sObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if(e.isIntersecting){
        const id = e.target.id;
        mobItems.forEach(m => m.classList.toggle('active', m.getAttribute('href') === `#${id}`));
      }
    });
  }, {threshold:0.4});
  sections.forEach(s => sObs.observe(s));

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const t = document.querySelector(a.getAttribute('href'));
      if(t){ e.preventDefault(); t.scrollIntoView({behavior:'smooth',block:'start'}); }
    });
  });
</script>
</body>
</html>