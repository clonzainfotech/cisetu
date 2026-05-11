<!DOCTYPE html>
<html lang="gu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>{{ $village->name_en }} | Digital Property Tax Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700;1,800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}

:root{
  --saffron:#E8621A;
  --saffron-2:#D4521000;
  --saffron-lt:#FFF0E6;
  --terra:#C4421200;
  --cream:#FBF6EF;
  --cream-2:#F5EDE0;
  --ink:#1A0E05;
  --ink-2:#3D2010;
  --ink-3:#7A4828;
  --ink-4:#B8896A;
  --wa:#25D366;
  --white:#ffffff;
  --rule:rgba(26,14,5,0.08);
  --rule-2:rgba(26,14,5,0.05);
  --r-sm:8px;
  --r-md:16px;
  --r-lg:24px;
  --r-xl:40px;
}

body{
  background:var(--cream);
  color:var(--ink);
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:16px;
  line-height:1.65;
  overflow-x:hidden;
  -webkit-tap-highlight-color:transparent;
}

/* Subtle dot grid */
body::before{
  content:'';
  position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:radial-gradient(circle,rgba(232,98,26,0.08) 1px,transparent 1px);
  background-size:32px 32px;
}

.font-gu{font-family:'Noto Sans Gujarati',sans-serif}
.font-serif{font-family:'Playfair Display',serif}

/* ─── NAV ─── */
#nav{
  position:fixed;top:0;left:0;right:0;z-index:100;
  background:var(--cream);
  border-bottom:1.5px solid var(--rule);
  transition:box-shadow 0.3s;
}
#nav.scrolled{box-shadow:0 2px 20px rgba(232,98,26,0.1)}

.nav-inner{
  max-width:1320px;margin:0 auto;
  display:flex;align-items:center;justify-content:space-between;
  height:72px;padding:0 32px;
}

.nav-brand{display:flex;align-items:center;gap:14px;text-decoration:none}
.brand-emblem{
  width:46px;height:46px;border-radius:12px;
  background:var(--saffron);
  display:flex;align-items:center;justify-content:center;
  position:relative;overflow:hidden;
}
.brand-emblem::before{
  content:'';
  position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,0.25) 0%,transparent 60%);
}
.brand-emblem span{
  font-family:'Playfair Display',serif;
  font-size:18px;font-weight:900;color:#fff;
  position:relative;z-index:1;letter-spacing:-0.02em;
}
.brand-text-main{
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;font-weight:700;color:var(--ink);letter-spacing:0.01em;
}
.brand-text-sub{
  font-size:10px;font-weight:500;color:var(--ink-4);
  letter-spacing:0.12em;text-transform:uppercase;margin-top:1px;
}

.nav-links{display:none;gap:36px}
@media(min-width:768px){.nav-links{display:flex}}
.nav-links a{
  font-size:13px;font-weight:600;color:var(--ink-3);
  text-decoration:none;letter-spacing:0.03em;
  transition:color 0.2s;
}
.nav-links a:hover{color:var(--saffron)}

.btn-nav-wa{
  display:inline-flex;align-items:center;gap:9px;
  padding:11px 24px;border-radius:50px;
  background:var(--saffron);color:#fff;
  font-size:13px;font-weight:700;
  text-decoration:none;border:none;cursor:pointer;
  box-shadow:0 4px 20px rgba(232,98,26,0.35);
  transition:transform 0.2s,box-shadow 0.2s,background 0.2s;
}
.btn-nav-wa:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(232,98,26,0.45);background:#D4521A}

/* ─── HERO ─── */
#home{
  position:relative;z-index:1;
  padding-top:72px;
}

/* Big orange band at top */
.hero-band{
  background:var(--saffron);
  padding:0 32px;
  min-height:calc(100vh - 72px);
  display:grid;
  grid-template-columns:1fr;
  position:relative;overflow:hidden;
}
@media(min-width:980px){
  .hero-band{grid-template-columns:1fr 420px}
}

/* Decorative arcs */
.hero-band::before{
  content:'';
  position:absolute;
  bottom:-120px;right:-120px;
  width:500px;height:500px;
  border-radius:50%;
  border:60px solid rgba(255,255,255,0.06);
  pointer-events:none;
}
.hero-band::after{
  content:'';
  position:absolute;
  top:-200px;left:40%;
  width:600px;height:600px;
  border-radius:50%;
  border:1px solid rgba(255,255,255,0.1);
  pointer-events:none;
}

/* LEFT */
.hero-left{
  padding:72px 48px 72px 0;
  display:flex;flex-direction:column;justify-content:center;
  position:relative;z-index:2;
}
@media(max-width:979px){.hero-left{padding:52px 0}}

.hero-kicker{
  display:inline-flex;align-items:center;gap:10px;
  background:rgba(255,255,255,0.15);
  border:1px solid rgba(255,255,255,0.25);
  border-radius:50px;padding:6px 16px;
  font-size:11px;font-weight:600;color:rgba(255,255,255,0.9);
  letter-spacing:0.1em;text-transform:uppercase;
  margin-bottom:32px;width:fit-content;
  backdrop-filter:blur(8px);
}
.kicker-dot{
  width:6px;height:6px;border-radius:50%;
  background:#fff;
  box-shadow:0 0 8px #fff;
  animation:kpulse 2s infinite;
}
@keyframes kpulse{0%,100%{opacity:1}50%{opacity:0.5}}

.hero-h1{
  font-family:'Playfair Display',serif;
  font-size:clamp(52px,7.5vw,100px);
  font-weight:900;
  line-height:0.97;
  letter-spacing:-0.02em;
  color:#fff;
  margin-bottom:28px;
}
.hero-h1 em{
  font-style:italic;
  color:rgba(255,255,255,0.75);
}

.hero-gu{
  font-family:'Noto Sans Gujarati',sans-serif;
  font-size:clamp(16px,2.2vw,22px);
  font-weight:600;
  color:rgba(255,255,255,0.6);
  margin-bottom:36px;
}

.hero-p{
  font-size:15px;
  color:rgba(255,255,255,0.7);
  line-height:1.85;
  max-width:480px;
  margin-bottom:44px;
}

.hero-btns{display:flex;flex-wrap:wrap;gap:14px;margin-bottom:56px}

.btn-white{
  display:inline-flex;align-items:center;gap:10px;
  padding:15px 30px;border-radius:50px;
  background:#fff;color:var(--saffron);
  font-size:15px;font-weight:700;
  text-decoration:none;border:none;cursor:pointer;
  box-shadow:0 6px 24px rgba(0,0,0,0.15);
  transition:transform 0.2s,box-shadow 0.2s;
}
.btn-white:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,0.2)}

.btn-ghost-w{
  display:inline-flex;align-items:center;gap:10px;
  padding:14px 28px;border-radius:50px;
  background:transparent;
  border:1.5px solid rgba(255,255,255,0.4);
  color:#fff;font-size:15px;font-weight:600;
  text-decoration:none;cursor:pointer;
  transition:background 0.2s,border-color 0.2s;
}
.btn-ghost-w:hover{background:rgba(255,255,255,0.12);border-color:rgba(255,255,255,0.6)}

.hero-trust{
  display:flex;flex-wrap:wrap;gap:24px;
  padding-top:36px;
  border-top:1px solid rgba(255,255,255,0.15);
}
.ht-item{
  display:flex;align-items:center;gap:8px;
  font-size:12px;font-weight:600;color:rgba(255,255,255,0.55);
  letter-spacing:0.04em;
}
.ht-item i{font-size:14px;color:rgba(255,255,255,0.9)}

/* RIGHT — cream card column */
.hero-right{
  background:var(--cream);
  display:flex;flex-direction:column;justify-content:center;align-items:center;
  padding:60px 36px;
  position:relative;z-index:2;
  border-left:none;
}

/* Diagonal cut — only on desktop */
@media(min-width:980px){
  .hero-right{
    clip-path:polygon(40px 0,100% 0,100% 100%,0 100%);
    padding-left:72px;
  }
}

/* Chat card */
.chat-card{
  width:100%;max-width:320px;
  background:#fff;
  border-radius:var(--r-xl);
  overflow:hidden;
  box-shadow:0 24px 60px rgba(26,14,5,0.12),0 4px 16px rgba(26,14,5,0.06);
  position:relative;
}

.chat-top{
  background:var(--ink);
  padding:14px 18px;
  display:flex;align-items:center;gap:12px;
}
.chat-av{
  width:34px;height:34px;border-radius:50%;
  background:var(--saffron);
  display:flex;align-items:center;justify-content:center;
  font-family:'Playfair Display',serif;font-size:12px;font-weight:700;color:#fff;
}
.chat-name{font-size:12px;font-weight:700;color:#fff;letter-spacing:0.03em}
.chat-status{
  font-size:10px;color:var(--wa);
  display:flex;align-items:center;gap:4px;
}
.s-dot{width:5px;height:5px;border-radius:50%;background:var(--wa);animation:kpulse 2s infinite}

.chat-body{padding:18px 14px;display:flex;flex-direction:column;gap:10px;min-height:260px;background:var(--cream)}

.cb-in{
  max-width:80%;align-self:flex-start;
  background:#fff;border-radius:4px 16px 16px 16px;
  padding:10px 13px;font-size:11px;line-height:1.55;color:var(--ink-2);
  box-shadow:0 2px 8px rgba(26,14,5,0.06);
  border-left:3px solid var(--saffron);
}
.cb-out{
  max-width:80%;align-self:flex-end;
  background:var(--saffron);border-radius:16px 4px 16px 16px;
  padding:10px 13px;font-size:11px;line-height:1.55;color:#fff;
}

.tax-mini{
  align-self:flex-start;width:90%;
  background:#fff;border-radius:16px;
  padding:14px;
  box-shadow:0 2px 12px rgba(26,14,5,0.08);
  border:1px solid var(--rule);
}
.tax-mini .tlbl{font-size:9px;font-weight:700;color:var(--ink-4);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:6px}
.tax-mini .tamt{
  font-family:'Playfair Display',serif;
  font-size:30px;font-weight:900;color:var(--saffron);line-height:1;margin-bottom:4px;
}
.tax-mini .tsub{font-size:9px;color:var(--ink-4);margin-bottom:12px}
.tax-mini button{
  display:block;width:100%;padding:9px;border:none;
  background:var(--saffron);color:#fff;border-radius:10px;
  font-size:11px;font-weight:700;cursor:pointer;letter-spacing:0.03em;
}

/* Floating labels */
.float-tag{
  position:absolute;
  background:#fff;border-radius:12px;
  padding:10px 16px;
  box-shadow:0 8px 28px rgba(26,14,5,0.12);
  font-family:'Plus Jakarta Sans',sans-serif;
  border:1.5px solid var(--cream-2);
}
.ft-a{top:24px;right:-20px;animation:tagfloat 6s ease-in-out 1s infinite}
.ft-b{bottom:40px;left:-24px;animation:tagfloat 6s ease-in-out 3.5s infinite;z-index: 1;}
@keyframes tagfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.ft-val{font-size:18px;font-weight:800;color:var(--saffron);line-height:1}
.ft-lbl{font-size:9px;font-weight:600;color:var(--ink-4);text-transform:uppercase;letter-spacing:0.1em;margin-top:2px}

/* ─── MARQUEE BAND ─── */
.marquee-band{
  background:var(--ink);
  padding:14px 0;overflow:hidden;
  position:relative;z-index:1;
}
.marquee-track{
  display:flex;gap:0;
  animation:marquee 30s linear infinite;
  width:max-content;
}
@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.mq-item{
  display:flex;align-items:center;gap:10px;
  padding:0 32px;
  font-size:12px;font-weight:600;color:rgba(255,255,255,0.5);
  letter-spacing:0.08em;text-transform:uppercase;
  border-right:1px solid rgba(255,255,255,0.08);
  white-space:nowrap;
}
.mq-item i{color:var(--saffron);font-size:13px}

/* ─── STATS GRID ─── */
#stats{
  position:relative;z-index:1;
  background:var(--cream-2);
  border-bottom:1.5px solid var(--rule);
}
.stats-inner{max-width:1320px;margin:0 auto}
.stats-row{
  display:grid;grid-template-columns:repeat(2,1fr);
}
@media(min-width:700px){.stats-row{grid-template-columns:repeat(4,1fr)}}
.stat-c{
  padding:40px 28px;text-align:center;
  border-right:1.5px solid var(--rule);
  transition:background 0.2s;
  cursor:default;
}
.stat-c:last-child{border-right:none}
.stat-c:hover{background:var(--saffron-lt)}
.stat-num{
  font-family:'Playfair Display',serif;
  font-size:48px;font-weight:900;color:var(--saffron);line-height:1;margin-bottom:8px;
}
.stat-lbl{
  font-size:11px;font-weight:600;color:var(--ink-4);
  text-transform:uppercase;letter-spacing:0.14em;
}

/* ─── SECTION HELPERS ─── */
.sec-eyebrow{
  display:inline-flex;align-items:center;gap:10px;
  font-size:11px;font-weight:700;color:var(--saffron);
  text-transform:uppercase;letter-spacing:0.18em;
  margin-bottom:18px;
}
.sec-eyebrow::after{
  content:'';display:inline-block;
  width:40px;height:1.5px;background:var(--saffron);opacity:0.4;
}

.sec-h2{
  font-family:'Playfair Display',serif;
  font-size:clamp(34px,5vw,62px);
  font-weight:900;line-height:1.05;
  letter-spacing:-0.02em;
  color:var(--ink);
  margin-bottom:18px;
}
.sec-h2 em{font-style:italic;color:var(--saffron)}
.sec-p{font-size:15px;color:var(--ink-3);line-height:1.8}

/* ─── HOW IT WORKS ─── */
#how-it-works{
  padding:110px 32px;
  position:relative;z-index:1;
  background:var(--cream);
}
.hiw-inner{max-width:1320px;margin:0 auto}
.hiw-head{text-align:center;max-width:560px;margin:0 auto 68px}

.steps-list{
  display:grid;
  grid-template-columns:1fr;
  gap:24px;
}
@media(min-width:720px){.steps-list{grid-template-columns:repeat(3,1fr)}}

.step-box{
  background:#fff;
  border-radius:var(--r-xl);
  padding:44px 36px;
  position:relative;overflow:hidden;
  border:1.5px solid var(--rule);
  transition:transform 0.3s,box-shadow 0.3s,border-color 0.3s;
  cursor:default;
}
.step-box:hover{
  transform:translateY(-6px);
  box-shadow:0 20px 50px rgba(232,98,26,0.1);
  border-color:rgba(232,98,26,0.25);
}
/* Big background number */
.step-box::before{
  content:attr(data-n);
  position:absolute;bottom:-10px;right:16px;
  font-family:'Playfair Display',serif;
  font-size:120px;font-weight:900;line-height:1;
  color:rgba(232,98,26,0.05);
  pointer-events:none;user-select:none;
  transition:color 0.3s;
}
.step-box:hover::before{color:rgba(232,98,26,0.1)}

.step-num-tag{
  display:inline-block;
  padding:4px 12px;border-radius:50px;
  background:var(--saffron-lt);
  font-size:10px;font-weight:700;color:var(--saffron);
  letter-spacing:0.12em;text-transform:uppercase;
  margin-bottom:22px;
}

.step-icon-wrap{
  width:60px;height:60px;border-radius:18px;
  display:flex;align-items:center;justify-content:center;
  font-size:26px;margin-bottom:22px;
}
.sw-saffron{background:var(--saffron);color:#fff;box-shadow:0 8px 20px rgba(232,98,26,0.3)}
.sw-ink{background:var(--ink);color:#fff;box-shadow:0 8px 20px rgba(26,14,5,0.2)}

.step-h{
  font-family:'Playfair Display',serif;
  font-size:22px;font-weight:800;color:var(--ink);margin-bottom:12px;
}
.step-p{font-size:14px;color:var(--ink-3);line-height:1.8}

.hiw-cta{text-align:center;margin-top:52px}
.btn-saffron{
  display:inline-flex;align-items:center;gap:10px;
  padding:16px 36px;border-radius:50px;
  background:var(--saffron);color:#fff;
  font-size:15px;font-weight:700;
  text-decoration:none;border:none;cursor:pointer;
  box-shadow:0 6px 28px rgba(232,98,26,0.35);
  transition:transform 0.2s,box-shadow 0.2s,background 0.2s;
}
.btn-saffron:hover{transform:translateY(-3px);box-shadow:0 12px 36px rgba(232,98,26,0.45);background:#D4521A}

/* ─── SERVICES ─── */
#services{
  padding:110px 32px;
  background:var(--ink);
  position:relative;z-index:1;overflow:hidden;
}
/* Gujarati watermark */
#services::before{
  content:'સેવા';
  font-family:'Noto Sans Gujarati',sans-serif;
  font-size:320px;font-weight:800;
  color:rgba(255,255,255,0.025);
  position:absolute;right:-40px;bottom:-80px;
  line-height:1;pointer-events:none;user-select:none;
}
.svc-inner{max-width:1320px;margin:0 auto;position:relative;z-index:1}
.svc-head{
  display:flex;flex-wrap:wrap;
  justify-content:space-between;align-items:flex-end;
  gap:24px;margin-bottom:56px;
}
.sec-eyebrow-lt{
  display:inline-flex;align-items:center;gap:10px;
  font-size:11px;font-weight:700;color:var(--saffron);
  text-transform:uppercase;letter-spacing:0.18em;margin-bottom:18px;
}
.sec-eyebrow-lt::after{content:'';display:inline-block;width:40px;height:1.5px;background:var(--saffron);opacity:0.4}
.sec-h2-lt{
  font-family:'Playfair Display',serif;
  font-size:clamp(34px,5vw,62px);font-weight:900;
  line-height:1.05;color:#fff;letter-spacing:-0.02em;margin-bottom:14px;
}
.sec-h2-lt em{font-style:italic;color:var(--saffron)}
.sec-p-lt{font-size:15px;color:rgba(255,255,255,0.4);line-height:1.8}

.certified-badge{
  display:flex;align-items:center;gap:10px;
  padding:12px 20px;border-radius:var(--r-md);
  border:1px solid rgba(232,98,26,0.3);
  background:rgba(232,98,26,0.08);
  font-size:12px;font-weight:600;color:var(--saffron);
  flex-shrink:0;
}

.svc-cards{
  display:grid;grid-template-columns:1fr;gap:16px;
}
@media(min-width:600px){.svc-cards{grid-template-columns:repeat(2,1fr)}}
@media(min-width:1000px){.svc-cards{grid-template-columns:repeat(4,1fr)}}

.svc-c{
  background:rgba(255,255,255,0.04);
  border:1px solid rgba(255,255,255,0.07);
  border-radius:var(--r-xl);
  padding:36px 28px;
  position:relative;overflow:hidden;
  transition:background 0.3s,transform 0.3s,border-color 0.3s;
  cursor:pointer;
}
.svc-c:hover{
  background:rgba(232,98,26,0.08);
  border-color:rgba(232,98,26,0.25);
  transform:translateY(-4px);
}
.svc-c.featured{
  background:rgba(232,98,26,0.07);
  border-color:rgba(232,98,26,0.2);
}
.svc-c.featured:hover{background:rgba(232,98,26,0.14)}

.svc-ico-wrap{
  width:52px;height:52px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:22px;margin-bottom:20px;
}
.si-orange{background:rgba(232,98,26,0.15);color:var(--saffron)}
.si-sky{background:rgba(56,192,252,0.1);color:#7dd3fc}
.si-amber{background:rgba(251,191,36,0.12);color:#fbbf24}
.si-muted{background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.2)}

.svc-seq{font-size:10px;font-weight:700;color:rgba(255,255,255,0.2);text-transform:uppercase;letter-spacing:0.14em;margin-bottom:18px}
.svc-h{
  font-family:'Playfair Display',serif;
  font-size:20px;font-weight:800;color:#fff;margin-bottom:10px;
}
.svc-p{font-size:13px;color:rgba(255,255,255,0.4);line-height:1.7;margin-bottom:26px}
.svc-link{
  display:inline-flex;align-items:center;gap:6px;
  font-size:13px;font-weight:700;text-decoration:none;
  transition:gap 0.2s;
}
.svc-link:hover{gap:10px}
.lk-orange{color:var(--saffron)}
.lk-sky{color:#7dd3fc}
.lk-amber{color:#fbbf24}
.svc-soon{
  display:inline-flex;align-items:center;gap:6px;
  font-size:11px;font-weight:600;color:rgba(255,255,255,0.2);
  border:1px solid rgba(255,255,255,0.08);
  padding:5px 14px;border-radius:50px;
}

/* ─── INITIATIVE ─── */
#initiative{
  padding:110px 32px;
  position:relative;z-index:1;
  background:var(--cream-2);
  border-top:1.5px solid var(--rule);
}
.init-inner{max-width:1320px;margin:0 auto}
.init-grid{
  display:grid;grid-template-columns:1fr;gap:52px;align-items:center;
}
@media(min-width:820px){.init-grid{grid-template-columns:1fr 1fr;gap:88px}}

.init-p{font-size:16px;color:var(--ink-3);line-height:1.85;margin-bottom:30px}
.tech-pill{
  display:inline-flex;align-items:center;gap:8px;
  padding:8px 18px;border-radius:50px;
  background:#fff;border:1.5px solid var(--rule);
  font-size:12px;font-weight:600;color:var(--ink-3);
}
.tech-pill strong{color:var(--saffron)}

.init-panel{
  background:var(--ink);
  border-radius:var(--r-xl);
  padding:42px 38px;
  position:relative;overflow:hidden;
}
.init-panel::before{
  content:'';
  position:absolute;top:0;left:0;right:0;height:4px;
  background:linear-gradient(90deg,var(--saffron),#f97316,#fbbf24);
}
.init-stat{
  display:flex;align-items:center;gap:18px;
  padding:18px 0;border-bottom:1px solid rgba(255,255,255,0.06);
}
.init-stat:last-child{border-bottom:none;padding-bottom:0}
.is-ico{
  width:48px;height:48px;border-radius:14px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:20px;
}
.iso-1{background:rgba(232,98,26,0.15);color:var(--saffron)}
.iso-2{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.5)}
.iso-3{background:rgba(251,191,36,0.12);color:#fbbf24}
.is-val{
  font-family:'Playfair Display',serif;
  font-size:32px;font-weight:900;color:#fff;line-height:1;
}
.is-lbl{font-size:11px;color:rgba(255,255,255,0.35);margin-top:4px}

/* ─── FOOTER ─── */
footer{
  background:var(--ink-2);
  padding:84px 32px 60px;
  position:relative;z-index:1;
  border-top:4px solid var(--saffron);
}
.foot-inner{max-width:1320px;margin:0 auto}
.foot-grid{
  display:grid;grid-template-columns:1fr;gap:52px;margin-bottom:64px;
}
@media(min-width:900px){.foot-grid{grid-template-columns:2fr 1fr 1.4fr;gap:72px}}

.foot-brand-row{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.foot-emblem{
  width:46px;height:46px;border-radius:12px;
  background:var(--saffron);
  display:flex;align-items:center;justify-content:center;
}
.foot-emblem span{font-family:'Playfair Display',serif;font-size:16px;font-weight:900;color:#fff}
.foot-brand-name{font-size:15px;font-weight:700;color:#fff}
.foot-brand-sub{font-size:10px;font-weight:600;color:var(--saffron);letter-spacing:0.12em;text-transform:uppercase;margin-top:2px}
.foot-desc{font-size:13px;color:rgba(255,255,255,0.3);line-height:1.8;max-width:300px;margin-bottom:28px}

.soc-links{display:flex;gap:8px}
.soc-a{
  width:38px;height:38px;border-radius:10px;
  border:1px solid rgba(255,255,255,0.08);
  display:flex;align-items:center;justify-content:center;
  color:rgba(255,255,255,0.3);font-size:14px;text-decoration:none;
  transition:background 0.2s,color 0.2s,border-color 0.2s;
}
.soc-a:hover{background:rgba(232,98,26,0.15);color:var(--saffron);border-color:rgba(232,98,26,0.3)}

.foot-col-h{font-size:10px;font-weight:700;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.18em;margin-bottom:24px}
.foot-nav{list-style:none;display:flex;flex-direction:column;gap:14px}
.foot-nav a{
  font-size:14px;color:rgba(255,255,255,0.3);text-decoration:none;
  display:flex;align-items:center;gap:8px;transition:color 0.2s;
}
.foot-nav a::before{content:'';display:inline-block;width:12px;height:1.5px;background:var(--saffron);opacity:0.5}
.foot-nav a:hover{color:rgba(255,255,255,0.75)}

.cinfo-block{
  display:flex;align-items:flex-start;gap:12px;
  padding:14px 16px;border-radius:var(--r-md);
  background:rgba(255,255,255,0.03);
  border:1px solid rgba(255,255,255,0.06);
  margin-bottom:10px;transition:border-color 0.2s;
}
.cinfo-block:hover{border-color:rgba(232,98,26,0.25)}
.ci-icon{
  width:34px;height:34px;border-radius:10px;flex-shrink:0;
  background:rgba(232,98,26,0.12);color:var(--saffron);
  display:flex;align-items:center;justify-content:center;font-size:13px;
}
.ci-text{font-size:13px;color:rgba(255,255,255,0.3);line-height:1.65}
.ci-text a{color:rgba(255,255,255,0.3);text-decoration:none}
.ci-text a:hover{color:rgba(255,255,255,0.7)}

.foot-bottom{
  padding-top:28px;border-top:1px solid rgba(255,255,255,0.06);
  display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:14px;
}
.foot-copy{font-size:12px;color:rgba(255,255,255,0.18)}
.foot-pw{
  display:flex;align-items:center;gap:6px;
  font-size:12px;color:rgba(255,255,255,0.18);
}
.foot-pw strong{color:var(--saffron);font-weight:700}

/* ─── MOBILE NAV ─── */
#mob-nav{
  display:flex;position:fixed;bottom:10px;left:10px;right:10px;z-index:100;
  background:var(--ink);
  border:1px solid rgba(255,255,255,0.08);
  border-radius:22px;
  padding:6px 4px;
  justify-content:space-around;align-items:center;
  box-shadow:0 8px 40px rgba(0,0,0,0.3);
}
@media(min-width:768px){#mob-nav{display:none}}
.mob-a{
  display:flex;flex-direction:column;align-items:center;gap:3px;
  color:rgba(255,255,255,0.3);text-decoration:none;
  font-size:9px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;
  width:56px;padding:8px 0;border-radius:14px;
  transition:color 0.2s,background 0.2s;
}
.mob-a i{font-size:18px}
.mob-a.active,.mob-a:hover{color:var(--saffron);background:rgba(232,98,26,0.1)}
.mob-fab{position:relative;top:-12px}
.mob-fab a{
  width:56px;height:56px;border-radius:50%;
  background:var(--saffron);
  display:flex;align-items:center;justify-content:center;
  font-size:26px;color:#fff;text-decoration:none;
  box-shadow:0 6px 24px rgba(232,98,26,0.5);
  transition:transform 0.2s;
}
.mob-fab a:active{transform:scale(0.92)}

.pb-m{padding-bottom:96px}
@media(min-width:768px){.pb-m{padding-bottom:0}}

/* ─── REVEAL ─── */
.reveal{opacity:0;transform:translateY(26px);transition:opacity 0.7s ease,transform 0.7s ease}
.reveal.vis{opacity:1;transform:none}
.d1{transition-delay:0.1s}.d2{transition-delay:0.22s}.d3{transition-delay:0.36s}
</style>
</head>
<body class="pb-m">

<!-- NAV -->
<nav id="nav" role="navigation" aria-label="Main navigation">
  <div class="nav-inner">
    <a class="nav-brand" href="#home">
      <div class="brand-emblem">
          @if($village->logo)
              <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
          @else
              <span class="font-serif">{{ substr($village->name_en, 0, 1) }}</span>
          @endif
      </div>
      <div>
        <div class="brand-text-main">{{ $village->name_en }}</div>
        <div class="brand-text-sub">Smart Village Portal</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#home">Home</a>
      <a href="#how-it-works">How to Pay</a>
      <a href="#services">Services</a>
    </div>
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-nav-wa" rel="noopener">
      <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
    </a>
  </div>
</nav>

<!-- HERO -->
<section id="home" aria-labelledby="hero-title">
  <div class="hero-band">

    <!-- LEFT -->
    <div class="hero-left reveal" style="padding-left:max(32px, calc((100vw - 1320px)/2 + 32px))">
      <div class="hero-kicker">
        <span class="kicker-dot"></span>
        Official Portal &nbsp;·&nbsp; Live &amp; Secure
      </div>

      <h1 id="hero-title" class="hero-h1 font-serif">
        Pay Your<br>
        <em>Property</em><br>
        Tax. Now.
      </h1>

      <div class="hero-gu font-gu">
        {{ $village->name_local }} · ડિજિટલ નાગરિક પોર્ટલ
      </div>

      <p class="hero-p">
        A fully automated, secure WhatsApp system — view and pay your property tax from any phone, anytime. No app needed. Zero convenience fee.
      </p>

      <div class="hero-btns">
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi&type=phone_number&app_absent=0" target="_blank" class="btn-white" rel="noopener">
          <i class="fa-brands fa-whatsapp" style="font-size:18px;color:#25D366"></i>
          Start on WhatsApp
        </a>
        <a href="#how-it-works" class="btn-ghost-w">
          How It Works <i class="fa-solid fa-arrow-down" style="font-size:12px"></i>
        </a>
      </div>

      <div class="hero-trust">
        <div class="ht-item"><i class="fa-solid fa-shield-halved"></i> 100% Secure</div>
        <div class="ht-item"><i class="fa-solid fa-receipt"></i> Instant Receipt</div>
        <div class="ht-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Fee</div>
        <div class="ht-item"><i class="fa-solid fa-clock"></i> 24 × 7</div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="hero-right reveal d2">
      <div style="position:relative">
        <div class="float-tag ft-a" aria-hidden="true">
          <div class="ft-val">24/7</div>
          <div class="ft-lbl">Always On</div>
        </div>
        <div class="float-tag ft-b" aria-hidden="true">
          <div class="ft-val">₹ 0</div>
          <div class="ft-lbl">Extra Fee</div>
        </div>

        <div class="chat-card">
          <div class="chat-top">
            <div class="chat-av font-serif">
                @if($village->logo)
                    <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                @else
                    {{ substr($village->name_en, 0, 1) }}
                @endif
            </div>
            <div>
              <div class="chat-name">{{ $village->name_en }}</div>
              <div class="chat-status"><span class="s-dot"></span> Online Now</div>
            </div>
          </div>
          <div class="chat-body">
            <div class="cb-out">Hi Panchayat 👋</div>
            <div class="cb-in">Welcome! Enter your house number to check your outstanding tax.</div>
            <div class="cb-out">House No. 142</div>
            <div class="tax-mini">
              <div class="tlbl">Outstanding Tax — 2024–25</div>
              <div class="tamt font-serif">₹ 1,250</div>
              <div class="tsub">House #142 · Gram Panchayat</div>
              <button><i class="fa-solid fa-lock-open" style="font-size:10px;margin-right:4px"></i>Pay Now via UPI</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- SCROLLING MARQUEE -->
<div class="marquee-band" aria-hidden="true">
  <div class="marquee-track">
    <span class="mq-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span>
    <span class="mq-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span>
    <span class="mq-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span>
    <span class="mq-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span>
    <span class="mq-item"><i class="fa-solid fa-clock"></i> Available 24/7</span>
    <span class="mq-item"><i class="fa-solid fa-certificate"></i> ISO Certified</span>
    <!-- Duplicate for loop -->
    <span class="mq-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span>
    <span class="mq-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span>
    <span class="mq-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span>
    <span class="mq-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span>
    <span class="mq-item"><i class="fa-solid fa-clock"></i> Available 24/7</span>
    <span class="mq-item"><i class="fa-solid fa-certificate"></i> ISO Certified</span>
  </div>
</div>

<!-- STATS -->
<div id="stats" class="reveal">
  <div class="stats-inner">
    <div class="stats-row">
      <div class="stat-c">
        <div class="stat-num font-serif">2,500+</div>
        <div class="stat-lbl">Homes Connected</div>
      </div>
      <div class="stat-c">
        <div class="stat-num font-serif">24/7</div>
        <div class="stat-lbl">System Uptime</div>
      </div>
      <div class="stat-c">
        <div class="stat-num font-serif">0%</div>
        <div class="stat-lbl">Convenience Fee</div>
      </div>
      <div class="stat-c">
        <div class="stat-num font-serif">100%</div>
        <div class="stat-lbl">Digital Accuracy</div>
      </div>
    </div>
  </div>
</div>

<!-- HOW IT WORKS -->
<section id="how-it-works" aria-labelledby="hiw-title">
  <div class="hiw-inner">
    <div class="hiw-head reveal">
      <div class="sec-eyebrow">Simple Process</div>
      <h2 id="hiw-title" class="sec-h2 font-serif">
        3 Steps to Pay<br><em>Your Tax</em>
      </h2>
      <p class="sec-p">Directly from your phone. No app download needed.</p>
    </div>

    <div class="steps-list">
      <div class="step-box reveal d1" data-n="01">
        <div class="step-num-tag">Step 01</div>
        <div class="step-icon-wrap sw-saffron"><i class="fa-brands fa-whatsapp"></i></div>
        <h3 class="step-h font-serif">Send a Message</h3>
        <p class="step-p">Send "Hi" to our official Panchayat WhatsApp number to launch the automated bot instantly. Works on any phone.</p>
      </div>
      <div class="step-box reveal d2" data-n="02">
        <div class="step-num-tag">Step 02</div>
        <div class="step-icon-wrap sw-ink"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <h3 class="step-h font-serif">Verify Details</h3>
        <p class="step-p">Enter your house number or registered phone number to instantly view your outstanding property &amp; water tax.</p>
      </div>
      <div class="step-box reveal d3" data-n="03">
        <div class="step-num-tag">Step 03</div>
        <div class="step-icon-wrap sw-saffron"><i class="fa-solid fa-receipt"></i></div>
        <h3 class="step-h font-serif">Pay &amp; Get Receipt</h3>
        <p class="step-p">Pay via UPI — GPay, PhonePe, or any UPI app. Your official signed PDF receipt arrives on WhatsApp instantly.</p>
      </div>
    </div>

    <div class="hiw-cta reveal">
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="btn-saffron" rel="noopener">
        Try the Live Bot &nbsp;<i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section id="services" aria-labelledby="svc-title">
  <div class="svc-inner">
    <div class="svc-head reveal">
      <div>
        <div class="sec-eyebrow-lt">Digital Governance</div>
        <h2 id="svc-title" class="sec-h2-lt font-serif">
          Online<br><em>Services</em>
        </h2>
        <p class="sec-p-lt">Panchayat services, directly on your phone.</p>
      </div>
      <div class="certified-badge"><i class="fa-solid fa-certificate"></i> ISO Certified</div>
    </div>

    <div class="svc-cards">
      <div class="svc-c featured reveal d1">
        <div class="svc-seq">01 · Property</div>
        <div class="svc-ico-wrap si-orange"><i class="fa-solid fa-house-chimney"></i></div>
        <h3 class="svc-h font-serif font-gu" style="font-family:'Noto Sans Gujarati',sans-serif;">મિલકત વેરો</h3>
        <p class="svc-p">View and pay yearly property taxes for residential and commercial spaces without visiting the office.</p>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-link lk-orange" rel="noopener">
          Pay Now <i class="fa-solid fa-arrow-right" style="font-size:11px"></i>
        </a>
      </div>
      <div class="svc-c reveal d2">
        <div class="svc-seq">02 · Water</div>
        <div class="svc-ico-wrap si-sky"><i class="fa-solid fa-droplet"></i></div>
        <h3 class="svc-h font-serif" style="font-family:'Noto Sans Gujarati',sans-serif;">પાણી વેરો</h3>
        <p class="svc-p">Clear your water connection bills seamlessly without visiting the office. Quick, easy, fully digital.</p>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" class="svc-link lk-sky" rel="noopener">
          Pay Now <i class="fa-solid fa-arrow-right" style="font-size:11px"></i>
        </a>
      </div>
      <div class="svc-c reveal d3">
        <div class="svc-seq">03 · Notices</div>
        <div class="svc-ico-wrap si-amber"><i class="fa-solid fa-bullhorn"></i></div>
        <h3 class="svc-h font-serif" style="font-family:'Noto Sans Gujarati',sans-serif;">ગ્રામ નોટિસ</h3>
        <p class="svc-p">Receive important announcements and Gram Sabha schedules directly on your phone via WhatsApp.</p>
        <a href="#" class="svc-link lk-amber">Subscribe <i class="fa-solid fa-arrow-right" style="font-size:11px"></i></a>
      </div>
      <div class="svc-c reveal" style="pointer-events:none;opacity:0.3">
        <div class="svc-seq">04 · Certificates</div>
        <div class="svc-ico-wrap si-muted"><i class="fa-solid fa-file-signature"></i></div>
        <h3 class="svc-h font-serif" style="font-family:'Noto Sans Gujarati',sans-serif;">પ્રમાણપત્રો</h3>
        <p class="svc-p">Apply for and download official certificates — arriving in Phase 2 of the portal rollout.</p>
        <span class="svc-soon"><i class="fa-solid fa-lock" style="font-size:10px"></i> Coming Soon</span>
      </div>
    </div>
  </div>
</section>

<!-- INITIATIVE -->
<section id="initiative" aria-labelledby="init-title">
  <div class="init-inner">
    <div class="init-grid reveal">
      <div>
        <div class="sec-eyebrow">Empowering Digital India</div>
        <h2 id="init-title" class="sec-h2 font-serif">
          Transparent<br>Governance.<br><em>Powered by Tech.</em>
        </h2>
        <p class="init-p">
          This Gram Panchayat pioneers smart village governance. The secure, scalable digital framework is powered by the <strong>CISETU Platform</strong> built by Clonza Infotech — built for Bharat, built to last.
        </p>
        <div class="tech-pill">
          <i class="fa-solid fa-microchip" style="color:var(--saffron)"></i>
          Technology Partner: <strong>Clonza Infotech</strong>
        </div>
      </div>

      <div class="init-panel">
        <div class="init-stat">
          <div class="is-ico iso-1"><i class="fa-solid fa-house-user"></i></div>
          <div>
            <div class="is-val font-serif">2,500+</div>
            <div class="is-lbl">Homes Connected</div>
          </div>
        </div>
        <div class="init-stat">
          <div class="is-ico iso-2"><i class="fa-solid fa-clock"></i></div>
          <div>
            <div class="is-val font-serif">24/7</div>
            <div class="is-lbl">Always Available</div>
          </div>
        </div>
        <div class="init-stat">
          <div class="is-ico iso-3"><i class="fa-solid fa-indian-rupee-sign"></i></div>
          <div>
            <div class="is-val font-serif">₹ 0</div>
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
        <div class="foot-brand-row">
          <div class="foot-emblem">
              @if($village->logo)
                  <img src="{{ Storage::url($village->logo) }}" alt="{{ $village->name_en }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
              @else
                  <span class="font-serif">{{ substr($village->name_en, 0, 1) }}</span>
              @endif
          </div>
          <div>
            <div class="foot-brand-name">{{ $village->name_en }}</div>
            <div class="foot-brand-sub">Digital Citizen Portal</div>
          </div>
        </div>
        <p class="foot-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
        <div class="soc-links">
          <a href="#" class="soc-a" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="soc-a" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
          <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="soc-a" aria-label="WhatsApp" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>
      <div>
        <div class="foot-col-h">Quick Links</div>
        <ul class="foot-nav">
          <li><a href="#home">Home</a></li>
          <li><a href="#how-it-works">How to Pay Tax</a></li>
          <li><a href="#services">Other Services</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div>
        <div class="foot-col-h">Contact Info</div>
        <div class="cinfo-block">
          <div class="ci-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div class="ci-text font-gu">{{ $village->name_local }} કચેરી,<br>ગુજરાત, ભારત</div>
        </div>
        <div class="cinfo-block">
          <div class="ci-icon"><i class="fa-solid fa-envelope"></i></div>
          <div class="ci-text"><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
        </div>
        <div class="cinfo-block">
          <div class="ci-icon"><i class="fa-solid fa-phone"></i></div>
          <div class="ci-text"><a href="tel:+{{ $village->whatsapp_number }}">+91 {{ $village->whatsapp_number }}</a></div>
        </div>
      </div>
    </div>
    <div class="foot-bottom">
      <p class="foot-copy">&copy; 2025 CISETU. All Rights Reserved.</p>
      <div class="foot-pw">Powered by <strong>&nbsp;CISETU / Clonza Infotech</strong></div>
    </div>
  </div>
</footer>

<!-- MOBILE NAV -->
<nav id="mob-nav" aria-label="Mobile navigation">
  <a href="#home" class="mob-a active" aria-current="page">
    <i class="fa-solid fa-house" aria-hidden="true"></i> Home
  </a>
  <a href="#how-it-works" class="mob-a">
    <i class="fa-solid fa-circle-info" aria-hidden="true"></i> Guide
  </a>
  <div class="mob-fab">
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text&type=phone_number&app_absent=0" target="_blank" aria-label="Pay Tax via WhatsApp" rel="noopener">
      <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
  </div>
  <a href="#services" class="mob-a">
    <i class="fa-solid fa-layer-group" aria-hidden="true"></i> Services
  </a>
  <a href="#" class="mob-a">
    <i class="fa-solid fa-user" aria-hidden="true"></i> Profile
  </a>
</nav>

<script>
const revs=document.querySelectorAll('.reveal');
const ro=new IntersectionObserver(es=>{es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('vis')})},{threshold:0.06,rootMargin:'0px 0px -30px 0px'});
revs.forEach(r=>ro.observe(r));

const nav=document.getElementById('nav');
window.addEventListener('scroll',()=>{nav.classList.toggle('scrolled',scrollY>20)},{passive:true});

const secs=document.querySelectorAll('section[id]');
const mlinks=document.querySelectorAll('#mob-nav .mob-a[href^="#"]');
const so=new IntersectionObserver(es=>{es.forEach(e=>{if(e.isIntersecting){const id=e.target.id;mlinks.forEach(l=>{const a=l.getAttribute('href')==='#'+id;l.classList.toggle('active',a);a?l.setAttribute('aria-current','page'):l.removeAttribute('aria-current')})}})},{threshold:0.4,rootMargin:'-15% 0px -15% 0px'});
secs.forEach(s=>so.observe(s));
</script>
</body>
</html>