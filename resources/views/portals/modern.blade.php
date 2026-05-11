<!DOCTYPE html>
<html lang="gu">
<head>
<title>{{ $village->name_local }} | Digital Property Tax Portal</title>
<meta name="description" content="Official Digital Portal for {{ $village->name_en }} Gram Panchayat. Pay your property tax instantly and securely via WhatsApp.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&family=Instrument+Sans:wght@400;500;600;700&family=Noto+Sans+Gujarati:wght@400;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
--bg:#f5f0e8;--bg2:#ede8df;--bg3:#e4ddd0;
--ink:#1a1510;--ink2:#2e2720;--ink3:#6b5e50;--ink4:#a8998a;
--rule:rgba(26,21,16,0.1);--rule2:rgba(26,21,16,0.06);
--gold:#b8892a;--gold2:#d4a843;--gold3:#f0c97a;--gold-lt:#fdf3e0;--gold-lt2:rgba(184,137,42,0.08);
--wa:#25D366;--wa2:#1da851;
--white:#ffffff;
--r4:4px;--r8:8px;--r12:12px;--r16:16px;--r24:24px;--r32:32px;
}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:'Instrument Sans',sans-serif;font-size:16px;line-height:1.65;overflow-x:hidden;-webkit-tap-highlight-color:transparent}

body::before{content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='0.5' fill='%23b8892a' opacity='0.15'/%3E%3C/svg%3E");
background-size:60px 60px}

.r-gu{font-family:'Noto Sans Gujarati',sans-serif}
.r-disp{font-family:'Cormorant Garamond',serif}
.r-mono{font-family:'JetBrains Mono',monospace}

nav{position:sticky;top:0;z-index:200;
background:rgba(245,240,232,0.9);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
border-bottom:1px solid var(--rule);transition:box-shadow .3s}
nav.scrolled{box-shadow:0 4px 32px rgba(26,21,16,0.08)}
.nav-i{max-width:1240px;margin:0 auto;padding:0 40px;height:70px;display:flex;align-items:center;justify-content:space-between}
.nav-brand{display:flex;align-items:center;gap:14px;text-decoration:none}
.nav-seal{width:42px;height:42px;border-radius:50%;border:1.5px solid var(--gold);background:var(--gold-lt);
display:flex;align-items:center;justify-content:center;flex-shrink:0}
.nav-seal svg{width:22px;height:22px}
.nav-title{font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;color:var(--ink);line-height:1.1;letter-spacing:0.01em}
.nav-subtitle{font-family:'JetBrains Mono',monospace;font-size:9px;color:var(--gold);letter-spacing:0.2em;text-transform:uppercase;margin-top:2px}
.nav-links{display:flex;gap:36px}
@media(max-width:900px){.nav-links{display:none}}
.nav-links a{font-size:13px;font-weight:500;color:var(--ink3);text-decoration:none;letter-spacing:0.02em;transition:color .2s}
.nav-links a:hover{color:var(--ink)}
.nav-cta{display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:var(--r8);
background:var(--ink);color:var(--bg);font-size:13px;font-weight:600;text-decoration:none;letter-spacing:0.04em;
border:none;cursor:pointer;transition:background .2s,transform .15s}
.nav-cta:hover{background:var(--ink2);transform:translateY(-1px)}
.nav-cta i{color:var(--wa);font-size:15px}

.hero{position:relative;z-index:1;overflow:hidden;padding-top:70px}

.hero-marquee{background:var(--ink);overflow:hidden;height:38px;display:flex;align-items:center}
.marquee-track{display:flex;animation:mq 35s linear infinite;white-space:nowrap}
@keyframes mq{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.mq-item{display:inline-flex;align-items:center;gap:12px;padding:0 36px;
font-family:'JetBrains Mono',monospace;font-size:10px;color:rgba(245,240,232,0.5);letter-spacing:0.14em;text-transform:uppercase}
.mq-item i{color:var(--gold2);font-size:10px}
.mq-sep{width:3px;height:3px;border-radius:50%;background:rgba(245,240,232,0.15);flex-shrink:0}

.hero-body{max-width:1240px;margin:0 auto;padding:0 40px}
.hero-grid{display:grid;grid-template-columns:1.15fr 0.85fr;gap:0;align-items:stretch;min-height:calc(100vh - 108px)}
@media(max-width:960px){.hero-grid{grid-template-columns:1fr;min-height:auto}}

.hero-left{padding:80px 60px 80px 0;display:flex;flex-direction:column;justify-content:center;position:relative}
.hero-left::after{content:'';position:absolute;top:20%;right:0;bottom:20%;width:1px;background:var(--rule)}
@media(max-width:960px){.hero-left::after{display:none};.hero-left{padding:60px 0 0}}

.hero-eyebrow{display:flex;align-items:center;gap:14px;margin-bottom:36px}
.eyebrow-num{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--gold);letter-spacing:0.2em;
border:1px solid rgba(184,137,42,0.3);padding:5px 12px;border-radius:var(--r4);background:var(--gold-lt2)}
.eyebrow-line{flex:1;height:1px;background:var(--rule)}
.live-badge{display:flex;align-items:center;gap:6px;font-family:'JetBrains Mono',monospace;
font-size:10px;color:var(--wa2);letter-spacing:0.12em;text-transform:uppercase}
.live-dot{width:6px;height:6px;border-radius:50%;background:var(--wa);animation:ldot 2s infinite}
@keyframes ldot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.5;transform:scale(1.5)}}

.hero-h1{font-family:'Cormorant Garamond',serif;font-size:clamp(56px,7.5vw,108px);
font-weight:300;line-height:0.92;letter-spacing:-0.02em;color:var(--ink);margin-bottom:10px}
.hero-h1 em{font-style:italic;color:var(--gold);display:block}
.hero-h1 strong{font-weight:700;display:block}

.hero-gu{font-family:'Noto Sans Gujarati',sans-serif;font-size:clamp(18px,2.2vw,28px);
color:var(--ink4);line-height:1.4;margin:20px 0 32px;font-weight:400}

.hero-desc{font-size:15px;color:var(--ink3);line-height:1.85;max-width:480px;margin-bottom:44px;font-weight:400}

.hero-actions{display:flex;flex-wrap:wrap;gap:14px;margin-bottom:52px}
.btn-gold{display:inline-flex;align-items:center;gap:10px;padding:15px 30px;border-radius:var(--r8);
background:var(--gold);color:var(--white);font-size:14px;font-weight:600;letter-spacing:0.04em;
text-decoration:none;border:none;cursor:pointer;transition:all .2s}
.btn-gold:hover{background:var(--gold2);transform:translateY(-2px);box-shadow:0 12px 36px rgba(184,137,42,0.28)}
.btn-wa{display:inline-flex;align-items:center;gap:10px;padding:15px 30px;border-radius:var(--r8);
background:var(--wa);color:var(--white);font-size:14px;font-weight:600;letter-spacing:0.04em;
text-decoration:none;border:none;cursor:pointer;transition:all .2s}
.btn-wa:hover{background:var(--wa2);transform:translateY(-2px);box-shadow:0 12px 36px rgba(37,211,102,0.25)}
.btn-outline{display:inline-flex;align-items:center;gap:10px;padding:14px 28px;border-radius:var(--r8);
background:transparent;border:1px solid var(--rule);color:var(--ink3);font-size:14px;font-weight:500;
text-decoration:none;cursor:pointer;transition:all .2s;letter-spacing:0.02em}
.btn-outline:hover{border-color:var(--ink3);color:var(--ink);background:rgba(26,21,16,0.03)}

.trust-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--rule);
border:1px solid var(--rule);border-radius:var(--r12);overflow:hidden;max-width:460px}
.trust-cell{background:var(--white);padding:14px 12px;text-align:center}
.trust-cell i{font-size:16px;margin-bottom:6px;display:block;color:var(--gold)}
.trust-cell .tv{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:500;color:var(--ink);display:block}
.trust-cell .tl{font-size:10px;color:var(--ink4);display:block;margin-top:1px}

.hero-right{padding:60px 0 60px 60px;display:flex;flex-direction:column;justify-content:center;align-items:center;position:relative}
@media(max-width:960px){.hero-right{padding:40px 0 60px}}

.receipt-outer{position:relative;width:320px}
.receipt-shadow{position:absolute;inset:20px 0 -20px 20px;background:var(--gold-lt);
border-radius:var(--r24);border:1px solid rgba(184,137,42,0.2);z-index:0;transform:rotate(2.5deg)}
.receipt-shadow2{position:absolute;inset:10px 0 -10px 10px;background:var(--bg2);
border-radius:var(--r24);border:1px solid var(--rule);z-index:1;transform:rotate(1.2deg)}
.receipt-main{position:relative;z-index:2;background:var(--white);border:1px solid var(--rule2);
border-radius:var(--r24);overflow:hidden;
box-shadow:0 32px 80px rgba(26,21,16,0.12),0 8px 24px rgba(26,21,16,0.06);
animation:rfloat 8s ease-in-out infinite}
@keyframes rfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-16px)}}

.r-hdr{background:var(--ink);padding:22px 24px 18px;display:flex;align-items:center;gap:14px}
.r-av{width:38px;height:38px;border-radius:50%;background:rgba(184,137,42,0.15);border:1.5px solid var(--gold);
display:flex;align-items:center;justify-content:center;flex-shrink:0}
.r-av svg{width:18px;height:18px}
.r-title{font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:600;color:var(--white);letter-spacing:0.02em;line-height:1.2}
.r-subtitle{font-family:'JetBrains Mono',monospace;font-size:9px;color:rgba(245,240,232,0.4);letter-spacing:0.14em;text-transform:uppercase;margin-top:2px}

.r-body{padding:22px 24px}
.r-row{display:flex;justify-content:space-between;align-items:baseline;padding:9px 0;border-bottom:1px solid var(--rule2)}
.r-row:last-of-type{border:none}
.r-lbl{font-size:12px;color:var(--ink4);font-weight:400}
.r-val{font-size:13px;font-weight:600;color:var(--ink)}
.r-big{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:700;color:var(--gold);letter-spacing:-0.01em}
.r-confirm{margin-top:16px;padding:14px 16px;background:rgba(37,211,102,0.08);
border:1px solid rgba(37,211,102,0.2);border-radius:var(--r12);display:flex;align-items:center;gap:12px}
.r-confirm i{color:var(--wa);font-size:18px;flex-shrink:0}
.r-ct{font-size:13px;font-weight:600;color:var(--ink);line-height:1.2}
.r-cs{font-size:11px;color:var(--ink3);margin-top:2px}

.float-tag{position:absolute;top:-14px;right:-14px;z-index:10;
background:var(--gold);color:var(--white);
border-radius:var(--r12) var(--r12) var(--r12) var(--r4);
padding:11px 16px;text-align:center;
box-shadow:0 10px 30px rgba(184,137,42,0.35);
animation:ftag 8s ease-in-out 4s infinite}
@keyframes ftag{0%,100%{transform:translateY(0) rotate(-1.5deg)}50%{transform:translateY(-10px) rotate(-1.5deg)}}
.ft-big{font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:700;line-height:1;color:var(--white);display:block}
.ft-sm{font-family:'JetBrains Mono',monospace;font-size:9px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.75);margin-top:2px;display:block}

.stats-bar{position:relative;z-index:1;border-top:1px solid var(--rule);border-bottom:1px solid var(--rule)}
.stats-grid{max-width:1240px;margin:0 auto;padding:0 40px;
display:grid;grid-template-columns:repeat(4,1fr)}
@media(max-width:700px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
.stat-c{padding:36px 20px;text-align:center;border-right:1px solid var(--rule);background:var(--white);transition:background .2s}
.stat-c:last-child{border-right:none}
.stat-c:hover{background:var(--gold-lt)}
.sn{font-family:'Cormorant Garamond',serif;font-size:clamp(36px,4vw,56px);font-weight:700;color:var(--ink);line-height:1;margin-bottom:6px}
.sl{font-family:'JetBrains Mono',monospace;font-size:9px;color:var(--ink4);letter-spacing:0.18em;text-transform:uppercase}

.section{padding:120px 0;position:relative;z-index:1}
.section-inner{max-width:1240px;margin:0 auto;padding:0 40px}
.sec-eyebrow{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.sec-num{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--gold);letter-spacing:0.2em;
border:1px solid rgba(184,137,42,0.25);padding:4px 12px;border-radius:var(--r4)}
.sec-rule{flex:0 0 32px;height:1px;background:var(--gold);opacity:0.4}
.sec-label{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--ink4);letter-spacing:0.18em;text-transform:uppercase}
.sec-h{font-family:'Cormorant Garamond',serif;font-size:clamp(38px,5vw,68px);font-weight:300;
line-height:0.95;letter-spacing:-0.02em;color:var(--ink);margin-bottom:20px}
.sec-h em{font-style:italic;color:var(--gold)}
.sec-p{font-size:15px;color:var(--ink3);line-height:1.8;font-weight:400}

.steps-wrap{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--rule);
border:1px solid var(--rule);border-radius:var(--r24);overflow:hidden;margin-top:60px}
@media(max-width:760px){.steps-wrap{grid-template-columns:1fr}}
.step-c{background:var(--white);padding:48px 36px;position:relative;transition:background .3s}
.step-c:hover{background:var(--gold-lt)}
.step-index{position:absolute;top:24px;right:28px;font-family:'Cormorant Garamond',serif;
font-size:96px;font-weight:300;color:rgba(184,137,42,0.08);line-height:1;pointer-events:none;transition:color .3s}
.step-c:hover .step-index{color:rgba(184,137,42,0.15)}
.step-ico{width:54px;height:54px;border-radius:var(--r12);display:flex;align-items:center;justify-content:center;
font-size:22px;margin-bottom:28px;border:1px solid var(--rule);transition:all .3s}
.ico-wa{background:rgba(37,211,102,0.08);color:var(--wa);border-color:rgba(37,211,102,0.2)}
.ico-ink{background:rgba(26,21,16,0.04);color:var(--ink3)}
.ico-gold{background:var(--gold-lt2);color:var(--gold);border-color:rgba(184,137,42,0.2)}
.step-c:hover .step-ico{border-color:var(--gold);background:var(--gold-lt)}
.step-h{font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;color:var(--ink);margin-bottom:12px;letter-spacing:0.01em}
.step-p{font-size:14px;color:var(--ink3);line-height:1.75}

.hiw-cta{text-align:center;margin-top:48px}

.dark-section{padding:120px 0;background:var(--ink);position:relative;z-index:1;overflow:hidden}
.dark-section::before{content:'સેવા';font-family:'Noto Sans Gujarati',sans-serif;
font-size:320px;font-weight:800;color:rgba(245,240,232,0.02);
position:absolute;right:-20px;bottom:-60px;line-height:1;pointer-events:none;user-select:none}
.dark-section::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;
background:linear-gradient(90deg,transparent,var(--gold),transparent)}
.ds-inner{max-width:1240px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.ds-top{display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:24px;margin-bottom:56px}
.ds-h{font-family:'Cormorant Garamond',serif;font-size:clamp(36px,5vw,68px);
font-weight:300;line-height:0.95;letter-spacing:-0.02em;color:var(--white);margin-bottom:14px}
.ds-h em{font-style:italic;color:var(--gold3)}
.ds-p{font-size:15px;color:rgba(245,240,232,0.4);line-height:1.75}
.iso-pill{display:flex;align-items:center;gap:8px;padding:10px 18px;border-radius:var(--r8);
border:1px solid rgba(184,137,42,0.25);background:rgba(184,137,42,0.06);
font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--gold2);letter-spacing:0.12em;text-transform:uppercase;flex-shrink:0}

.svc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:rgba(245,240,232,0.06);
border:1px solid rgba(245,240,232,0.08);border-radius:var(--r24);overflow:hidden}
@media(max-width:900px){.svc-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:540px){.svc-grid{grid-template-columns:1fr}}
.svc-c{background:rgba(245,240,232,0.02);padding:36px 28px;transition:background .3s;cursor:pointer;position:relative}
.svc-c:hover{background:rgba(184,137,42,0.06)}
.svc-c.feat{background:rgba(184,137,42,0.05);border-bottom:2px solid rgba(184,137,42,0.3)}
.svc-c.locked{opacity:0.35;pointer-events:none}
.svc-idx{font-family:'JetBrains Mono',monospace;font-size:9px;color:rgba(245,240,232,0.2);letter-spacing:0.16em;text-transform:uppercase;margin-bottom:24px}
.svc-ico{width:48px;height:48px;border-radius:var(--r8);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:18px;border:1px solid rgba(245,240,232,0.08)}
.si-g{background:rgba(184,137,42,0.1);color:var(--gold2);border-color:rgba(184,137,42,0.2)}
.si-b{background:rgba(56,189,248,0.08);color:#7dd3fc}
.si-a{background:rgba(251,191,36,0.08);color:#fde68a}
.si-m{background:rgba(245,240,232,0.03);color:rgba(245,240,232,0.15)}
.svc-h{font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:var(--white);margin-bottom:10px;letter-spacing:0.01em}
.svc-p{font-size:12px;color:rgba(245,240,232,0.35);line-height:1.7;margin-bottom:24px}
.svc-lnk{display:inline-flex;align-items:center;gap:6px;font-family:'JetBrains Mono',monospace;
font-size:10px;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;transition:gap .2s}
.svc-lnk:hover{gap:10px}
.lk-g{color:var(--gold2)}
.lk-b{color:#7dd3fc}
.lk-a{color:#fde68a}
.svc-soon{display:inline-flex;align-items:center;gap:6px;
font-family:'JetBrains Mono',monospace;font-size:9px;color:rgba(245,240,232,0.2);
border:1px solid rgba(245,240,232,0.08);padding:5px 12px;border-radius:var(--r4);letter-spacing:0.1em;text-transform:uppercase}

.cream-section{padding:120px 0;background:var(--bg2);border-top:1px solid var(--rule);position:relative;z-index:1}
.cs-inner{max-width:1240px;margin:0 auto;padding:0 40px}
.cs-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
@media(max-width:860px){.cs-grid{grid-template-columns:1fr}}
.init-card{background:var(--white);border:1px solid var(--rule);border-radius:var(--r24);padding:40px;
box-shadow:0 24px 64px rgba(26,21,16,0.06)}
.init-row{display:flex;align-items:center;gap:18px;padding:18px 0;border-bottom:1px solid var(--rule2)}
.init-row:last-child{border:none;padding-bottom:0}
.ir-ico{width:48px;height:48px;border-radius:var(--r8);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;border:1px solid var(--rule)}
.ir1{background:var(--gold-lt2);color:var(--gold);border-color:rgba(184,137,42,0.2)}
.ir2{background:rgba(37,211,102,0.06);color:var(--wa2)}
.ir3{background:rgba(245,158,11,0.06);color:#d97706}
.ir-val{font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:700;color:var(--ink);line-height:1}
.ir-lbl{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--ink4);letter-spacing:0.12em;text-transform:uppercase;margin-top:4px}

footer{background:var(--ink2);padding:80px 0 48px;position:relative;z-index:1;border-top:3px solid var(--gold)}
.ft-inner{max-width:1240px;margin:0 auto;padding:0 40px}
.ft-grid{display:grid;grid-template-columns:2fr 1fr 1.5fr;gap:64px;margin-bottom:60px}
@media(max-width:900px){.ft-grid{grid-template-columns:1fr;gap:40px}}
.ft-logo{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.ft-seal{width:44px;height:44px;border-radius:50%;background:rgba(184,137,42,0.12);border:1px solid rgba(184,137,42,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ft-seal svg{width:20px;height:20px}
.ft-name{font-family:'Cormorant Garamond',serif;font-size:17px;font-weight:600;color:var(--white);line-height:1.1;letter-spacing:0.01em}
.ft-sub{font-family:'JetBrains Mono',monospace;font-size:9px;color:var(--gold2);letter-spacing:0.2em;text-transform:uppercase;margin-top:3px}
.ft-desc{font-size:14px;color:rgba(245,240,232,0.3);line-height:1.8;max-width:290px;margin-bottom:28px}
.soc-row{display:flex;gap:8px}
.soc-b{width:36px;height:36px;border-radius:var(--r8);border:1px solid rgba(245,240,232,0.08);
display:flex;align-items:center;justify-content:center;color:rgba(245,240,232,0.3);font-size:13px;text-decoration:none;transition:all .2s}
.soc-b:hover{border-color:rgba(184,137,42,0.3);color:var(--gold2);background:rgba(184,137,42,0.06)}
.ft-h{font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:600;color:rgba(245,240,232,0.5);margin-bottom:22px;letter-spacing:0.03em}
.ft-lnks{list-style:none;display:flex;flex-direction:column;gap:12px}
.ft-lnks a{font-size:13px;color:rgba(245,240,232,0.3);text-decoration:none;display:flex;align-items:center;gap:8px;transition:color .2s;font-weight:400}
.ft-lnks a::before{content:'→';font-size:11px;color:var(--gold);opacity:0.5}
.ft-lnks a:hover{color:rgba(245,240,232,0.75)}
.ci-row{display:flex;align-items:flex-start;gap:12px;padding:12px 14px;border-radius:var(--r8);
border:1px solid rgba(245,240,232,0.06);background:rgba(245,240,232,0.02);margin-bottom:8px;transition:border-color .2s}
.ci-row:hover{border-color:rgba(184,137,42,0.2)}
.ci-ico{width:30px;height:30px;border-radius:var(--r8);background:rgba(184,137,42,0.1);color:var(--gold2);
display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0}
.ci-txt{font-size:12px;color:rgba(245,240,232,0.3);line-height:1.6}
.ci-txt a{color:rgba(245,240,232,0.3);text-decoration:none;transition:color .2s}
.ci-txt a:hover{color:var(--gold2)}
.ft-bot{padding-top:28px;border-top:1px solid rgba(245,240,232,0.06);
display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px}
.ft-copy{font-family:'JetBrains Mono',monospace;font-size:10px;color:rgba(245,240,232,0.2);letter-spacing:0.1em;text-transform:uppercase}
.ft-pwr{display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:10px;color:rgba(245,240,232,0.2);letter-spacing:0.08em;text-transform:uppercase}
.pwr-badge{padding:3px 10px;border-radius:var(--r4);background:rgba(184,137,42,0.1);border:1px solid rgba(184,137,42,0.2);color:var(--gold2);font-weight:700;font-size:9px;letter-spacing:0.1em}

.mob-nav{display:none;position:sticky;bottom:0;z-index:200;
background:rgba(245,240,232,0.95);backdrop-filter:blur(20px);
border-top:1px solid var(--rule);padding:6px 8px;
justify-content:space-around;align-items:center}
@media(max-width:767px){.mob-nav{display:flex};body{padding-bottom:70px}}
.mob-lnk{display:flex;flex-direction:column;align-items:center;gap:3px;
color:var(--ink4);text-decoration:none;font-family:'JetBrains Mono',monospace;
font-size:8px;letter-spacing:0.08em;text-transform:uppercase;
width:56px;padding:8px 0;border-radius:var(--r8);transition:all .2s}
.mob-lnk i{font-size:16px}
.mob-lnk.act{color:var(--gold);background:var(--gold-lt2)}
.mob-lnk:hover{color:var(--ink)}
.mob-fab{position:relative;top:-12px}
.mob-fab a{width:54px;height:54px;border-radius:50%;background:var(--wa);
display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--white);
text-decoration:none;border:2px solid var(--bg);
box-shadow:0 6px 24px rgba(37,211,102,0.3);transition:all .2s}
.mob-fab a:hover{background:var(--wa2)}

.rv{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s ease}
.rv.vis{opacity:1;transform:none}
.rv.d1{transition-delay:.1s}.rv.d2{transition-delay:.2s}.rv.d3{transition-delay:.3s}
</style>
</head>
<body>

<nav id="site-nav">
  <div class="nav-i">
    <a class="nav-brand" href="#home">
      <div class="nav-seal">
        @if($village->logo)
          <img src="{{ Storage::url($village->logo) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
        @else
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2L14.5 9H22L16 13.5L18.5 20.5L12 16L5.5 20.5L8 13.5L2 9H9.5L12 2Z" stroke="#b8892a" stroke-width="1.5" stroke-linejoin="round" fill="rgba(184,137,42,0.12)"/>
          </svg>
        @endif
      </div>
      <div>
        <div class="nav-title">Gram Panchayat {{ $village->name_en }}</div>
        <div class="nav-subtitle">Official Digital Portal</div>
      </div>
    </a>
    <div class="nav-links">
      <a href="#home">Home</a>
      <a href="#how">How to Pay</a>
      <a href="#services">Services</a>
      <a href="#initiative">About</a>
    </div>
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="nav-cta" rel="noopener">
      <i class="fa-brands fa-whatsapp"></i> Pay Tax Now
    </a>
  </div>
</nav>

<section id="home" class="hero">
  <div class="hero-marquee">
    <div class="marquee-track">
      <span class="mq-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-clock"></i> Available 24 / 7</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-shield-check"></i> 100% Secure Payments</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-bolt"></i> Instant PDF Receipt</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-brands fa-whatsapp"></i> Pay via WhatsApp</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-indian-rupee-sign"></i> Zero Convenience Fee</span><span class="mq-sep"></span>
      <span class="mq-item"><i class="fa-solid fa-clock"></i> Available 24 / 7</span><span class="mq-sep"></span>
    </div>
  </div>

  <div class="hero-body">
    <div class="hero-grid">
      <div class="hero-left rv">
        <div class="hero-eyebrow">
          <div class="eyebrow-num">— 2024–25</div>
          <div class="eyebrow-line"></div>
          <div class="live-badge"><span class="live-dot"></span> Portal Active</div>
        </div>

        <h1 class="hero-h1">
          Pay Your
          <strong>Property Tax</strong>
          <em>Instantly.</em>
        </h1>

        <div class="hero-gu r-gu">{{ $village->name_local }} · ગ્રામ પંચાયત</div>

        <p class="hero-desc">No office visits. No queues. {{ $village->name_en }} Gram Panchayat's fully automated WhatsApp system lets you view, verify, and pay your property tax from any phone. Official receipt delivered in seconds.</p>

        <div class="hero-actions">
          <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}&text=Hi" target="_blank" class="btn-wa" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i> Start on WhatsApp
          </a>
          <a href="#how" class="btn-outline">How It Works <i class="fa-solid fa-arrow-right" style="font-size:12px"></i></a>
        </div>

        <div class="trust-grid">
          <div class="trust-cell">
            <i class="fa-solid fa-shield-halved"></i>
            <span class="tv">Secure</span>
            <span class="tl">Encrypted</span>
          </div>
          <div class="trust-cell">
            <i class="fa-solid fa-file-pdf"></i>
            <span class="tv">Instant</span>
            <span class="tl">PDF Receipt</span>
          </div>
          <div class="trust-cell">
            <i class="fa-solid fa-indian-rupee-sign"></i>
            <span class="tv">₹ 0</span>
            <span class="tl">Extra Fee</span>
          </div>
          <div class="trust-cell">
            <i class="fa-solid fa-clock"></i>
            <span class="tv">24/7</span>
            <span class="tl">Uptime</span>
          </div>
        </div>
      </div>

      <div class="hero-right rv d2">
        <div class="receipt-outer">
          <div class="receipt-shadow"></div>
          <div class="receipt-shadow2"></div>
          <div class="receipt-main">
            <div class="r-hdr">
              <div class="r-av">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2L14.5 9H22L16 13.5L18.5 20.5L12 16L5.5 20.5L8 13.5L2 9H9.5L12 2Z" stroke="#b8892a" stroke-width="1.5" stroke-linejoin="round" fill="rgba(184,137,42,0.15)"/>
                </svg>
              </div>
              <div>
                <div class="r-title">{{ $village->name_en }} Gram Panchayat</div>
                <div class="r-subtitle">Official Property Tax Receipt</div>
              </div>
            </div>
            <div class="r-body">
              <div class="r-row"><span class="r-lbl">Receipt No.</span><span class="r-val">#GP-2025-4891</span></div>
              <div class="r-row"><span class="r-lbl">House No.</span><span class="r-val">Village: {{ $village->name_en }}</span></div>
              <div class="r-row"><span class="r-lbl">Tax Year</span><span class="r-val">2024 – 2025</span></div>
              <div class="r-row"><span class="r-lbl">Payment Mode</span><span class="r-val">UPI / GPay</span></div>
              <div class="r-row"><span class="r-lbl">Amount Paid</span><span class="r-val r-big">₹ 1,250</span></div>
              <div class="r-confirm">
                <i class="fa-solid fa-circle-check"></i>
                <div>
                  <div class="r-ct">Payment Confirmed</div>
                  <div class="r-cs">PDF sent to your WhatsApp instantly</div>
                </div>
              </div>
            </div>
          </div>
          <div class="float-tag">
            <span class="ft-big">₹0</span>
            <span class="ft-sm">Convenience Fee</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="stats-bar rv">
  <div class="stats-grid">
    <div class="stat-c"><div class="sn">2,500+</div><div class="sl">Homes Connected</div></div>
    <div class="stat-c"><div class="sn">24/7</div><div class="sl">System Uptime</div></div>
    <div class="stat-c"><div class="sn">₹ 0</div><div class="sl">Convenience Fee</div></div>
    <div class="stat-c"><div class="sn">100%</div><div class="sl">Digital Accuracy</div></div>
  </div>
</div>

<section id="how" class="section">
  <div class="section-inner">
    <div class="rv" style="text-align:center;max-width:560px;margin:0 auto">
      <div class="sec-eyebrow" style="justify-content:center">
        <div class="sec-num">01</div>
        <div class="sec-rule"></div>
        <div class="sec-label">Simple Process</div>
      </div>
      <h2 class="sec-h">3 Steps to <em>Pay Your Tax</em></h2>
      <p class="sec-p">Direct from your phone. No app installation required. Works on any device with WhatsApp.</p>
    </div>

    <div class="steps-wrap rv">
      <div class="step-c">
        <div class="step-index" aria-hidden="true">01</div>
        <div class="step-ico ico-wa"><i class="fa-brands fa-whatsapp"></i></div>
        <h3 class="step-h">Send a Message</h3>
        <p class="step-p">Send "Hi" to our official Panchayat WhatsApp number. The automated bot responds instantly — no waiting, no queue.</p>
      </div>
      <div class="step-c">
        <div class="step-index" aria-hidden="true">02</div>
        <div class="step-ico ico-ink"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <h3 class="step-h">Verify Your Details</h3>
        <p class="step-p">Enter your house number or registered phone. View your outstanding property & water tax details in under 2 seconds.</p>
      </div>
      <div class="step-c">
        <div class="step-index" aria-hidden="true">03</div>
        <div class="step-ico ico-gold"><i class="fa-solid fa-receipt"></i></div>
        <h3 class="step-h">Pay & Get Receipt</h3>
        <p class="step-p">Pay via any UPI app — GPay, PhonePe, or Paytm. Official signed PDF receipt delivered to WhatsApp instantly.</p>
      </div>
    </div>

    <div class="hiw-cta rv">
      <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="btn-wa" style="display:inline-flex" rel="noopener">
        Try the Live Bot <i class="fa-solid fa-arrow-right" style="font-size:12px"></i>
      </a>
    </div>
  </div>
</section>

<section id="services" class="dark-section">
  <div class="ds-inner">
    <div class="ds-top rv">
      <div>
        <div class="sec-eyebrow">
          <div class="sec-num" style="color:var(--gold2);border-color:rgba(184,137,42,0.3)">02</div>
          <div class="sec-rule"></div>
          <div class="sec-label" style="color:rgba(245,240,232,0.3)">Digital Governance</div>
        </div>
        <h2 class="ds-h">Online <em>Services</em></h2>
        <p class="ds-p">Panchayat services, directly on your smartphone.</p>
      </div>
      <div class="iso-pill rv"><i class="fa-solid fa-certificate"></i> ISO Certified System</div>
    </div>

    <div class="svc-grid">
      <div class="svc-c feat rv d1">
        <div class="svc-idx">01 · Property</div>
        <div class="svc-ico si-g"><i class="fa-solid fa-house-chimney"></i></div>
        <h3 class="svc-h r-gu">મિલકત વેરો</h3>
        <p class="svc-p">View and pay yearly property taxes for residential and commercial spaces without visiting the office.</p>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="svc-lnk lk-g" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-c rv d2">
        <div class="svc-idx">02 · Water</div>
        <div class="svc-ico si-b"><i class="fa-solid fa-droplet"></i></div>
        <h3 class="svc-h r-gu">પાણી વેરો</h3>
        <p class="svc-p">Clear your water connection bills seamlessly. No office visit needed. Quick, simple, and instant.</p>
        <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="svc-lnk lk-b" rel="noopener">Pay Now <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-c rv d3">
        <div class="svc-idx">03 · Notice</div>
        <div class="svc-ico si-a"><i class="fa-solid fa-bullhorn"></i></div>
        <h3 class="svc-h r-gu">ગ્રામ નોટિસ</h3>
        <p class="svc-p">Receive important announcements and Gram Sabha schedules directly on your phone.</p>
        <a href="#" class="svc-lnk lk-a">Subscribe <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="svc-c locked rv">
        <div class="svc-idx">04 · Certificates</div>
        <div class="svc-ico si-m"><i class="fa-solid fa-file-signature"></i></div>
        <h3 class="svc-h r-gu">પ્રમાણપત્રો</h3>
        <p class="svc-p">Apply and download official certificates — arriving in Phase 2 of this portal rollout.</p>
        <span class="svc-soon"><i class="fa-solid fa-lock" style="font-size:9px"></i> Coming Soon</span>
      </div>
    </div>
  </div>
</section>

<section id="initiative" class="cream-section">
  <div class="cs-inner">
    <div class="cs-grid">
      <div class="rv">
        <div class="sec-eyebrow">
          <div class="sec-num">03</div>
          <div class="sec-rule"></div>
          <div class="sec-label">Empowering Digital India</div>
        </div>
        <h2 class="sec-h">Transparent Governance, <em>Powered by Technology.</em></h2>
        <p class="sec-p" style="margin-top:20px;margin-bottom:32px">{{ $village->name_en }} Gram Panchayat is proud to pioneer smart village governance. This secure, scalable digital framework is powered by the <strong style="font-weight:600;color:var(--ink)">CISETU Platform</strong> built by Clonza Infotech — bringing institutional-grade infrastructure to every citizen.</p>
        <div style="display:flex;align-items:center;gap:12px;font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--ink4);letter-spacing:0.12em;text-transform:uppercase">
          <i class="fa-solid fa-microchip" style="color:var(--gold);font-size:16px"></i>
          Technology Partner: <strong style="color:var(--gold)">Clonza Infotech</strong>
        </div>
      </div>
      <div class="init-card rv d2">
        <div class="init-row">
          <div class="ir-ico ir1"><i class="fa-solid fa-house-user"></i></div>
          <div><div class="ir-val">2,500+</div><div class="ir-lbl">Homes Connected</div></div>
        </div>
        <div class="init-row">
          <div class="ir-ico ir2"><i class="fa-solid fa-clock"></i></div>
          <div><div class="ir-val">24 / 7</div><div class="ir-lbl">Always Available</div></div>
        </div>
        <div class="init-row">
          <div class="ir-ico ir3"><i class="fa-solid fa-indian-rupee-sign"></i></div>
          <div><div class="ir-val">₹ 0</div><div class="ir-lbl">Convenience Fee to Citizens</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="ft-inner">
    <div class="ft-grid">
      <div>
        <div class="ft-logo">
          <div class="ft-seal">
            @if($village->logo)
              <img src="{{ Storage::url($village->logo) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
            @else
              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L14.5 9H22L16 13.5L18.5 20.5L12 16L5.5 20.5L8 13.5L2 9H9.5L12 2Z" stroke="#b8892a" stroke-width="1.5" stroke-linejoin="round" fill="rgba(184,137,42,0.12)"/>
              </svg>
            @endif
          </div>
          <div>
            <div class="ft-name">Gram Panchayat {{ $village->name_en }}</div>
            <div class="ft-sub">Digital Citizen Portal</div>
          </div>
        </div>
        <p class="ft-desc">Committed to transparent governance and making citizen services completely accessible through modern digital solutions.</p>
        <div class="soc-row">
          <a href="#" class="soc-b" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="soc-b" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
          <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" class="soc-b" aria-label="WhatsApp" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>
      <div>
        <div class="ft-h">Quick Links</div>
        <ul class="ft-lnks">
          <li><a href="#home">Home</a></li>
          <li><a href="#how">How to Pay Tax</a></li>
          <li><a href="#services">Other Services</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div>
        <div class="ft-h r-gu">સંપર્ક માહિતી</div>
        <div class="ci-row">
          <div class="ci-ico"><i class="fa-solid fa-location-dot"></i></div>
          <div class="ci-txt r-gu">{{ $village->name_local }} ગ્રામ પંચાયત કચેરી, ગુજરાત</div>
        </div>
        <div class="ci-row">
          <div class="ci-ico"><i class="fa-solid fa-envelope"></i></div>
          <div class="ci-txt"><a href="mailto:info@gpt.cisetu.com">info@gpt.cisetu.com</a></div>
        </div>
        <div class="ci-row">
          <div class="ci-ico"><i class="fa-solid fa-phone"></i></div>
          <div class="ci-txt"><a href="tel:+{{ $village->whatsapp_number }}">+91 {{ $village->whatsapp_number }}</a></div>
        </div>
      </div>
    </div>
    <div class="ft-bot">
      <div class="ft-copy">© 2025 CISETU. All Rights Reserved.</div>
      <div class="ft-pwr">Powered by <span class="pwr-badge">CISETU / Clonza Infotech</span></div>
    </div>
  </div>
</footer>

<nav class="mob-nav" aria-label="Mobile navigation">
  <a href="#home" class="mob-lnk act"><i class="fa-solid fa-house"></i> Home</a>
  <a href="#how" class="mob-lnk"><i class="fa-solid fa-circle-info"></i> Guide</a>
  <div class="mob-fab">
    <a href="https://api.whatsapp.com/send/?phone={{ $village->whatsapp_number }}" target="_blank" rel="noopener" aria-label="Pay via WhatsApp">
      <i class="fa-brands fa-whatsapp"></i>
    </a>
  </div>
  <a href="#services" class="mob-lnk"><i class="fa-solid fa-layer-group"></i> Services</a>
  <a href="#initiative" class="mob-lnk"><i class="fa-solid fa-info"></i> About</a>
</nav>

<script>
const rv=document.querySelectorAll('.rv');
const ro=new IntersectionObserver(e=>{e.forEach(x=>{if(x.isIntersecting)x.target.classList.add('vis')})},{threshold:0.07,rootMargin:'0px 0px -30px 0px'});
rv.forEach(el=>ro.observe(el));
const sn=document.getElementById('site-nav');
window.addEventListener('scroll',()=>{sn.classList.toggle('scrolled',scrollY>20)},{passive:true});
const secs=document.querySelectorAll('section[id]');
const ml=document.querySelectorAll('.mob-nav .mob-lnk[href^="#"]');
const so=new IntersectionObserver(e=>{e.forEach(x=>{if(x.isIntersecting){const id=x.target.id;ml.forEach(l=>{l.classList.toggle('act',l.getAttribute('href')==='#'+id)})}})},{threshold:0.4});
secs.forEach(s=>so.observe(s));
</script>
</body>
</html>
